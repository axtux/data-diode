<?php

require_once('config.php');
require_once('database.php');

function error($message)
{
  exit($message."\n");
}

function status($message)
{
  print($message."\n");
}

function json_data()
{
  $json = @file_get_contents('php://input');
  if (empty($json))
  {
    error('Empty body.');
  }
  
  $data = json_decode($json, true);
  if ($data === null)
  {
    error('Invalid JSON.');
  }
  
  return $data;
}

function udp_socket($internal)
{
  $s = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
  if ($s === false)
  {
    error('Error creating socket.');
  }
  
  $r = socket_bind($s, $internal['ip'], $internal['port']);
  if ($r === false)
  {
    error('Error binding socket.');
  }
  
  status('Listening for UDP packets on '.$internal['ip'].':'.$internal['port']);
  return $s;
}

function save_data($data)
{
  $c = get_count($data);
  if ($c === false)
  {
    insert_data($data);
    status('inserted data');
  }
  else
  {
    set_count($data, $c+1);
    status('updated count to '.strval($c+1));
  }
}

function server_loop($socket, $buff_len)
{
  while (true)
  {
    $size = socket_recv($socket, $buffer, $buff_len, null);
    if ($size === false)
    {
      error('Error reading socket.');
    }
    
    $data = json_decode($buffer, true);
    if ($data === null)
    {
      status('Got invalid JSON.');
      continue;
    }
    
    save_data($data);
  }
}


$config = load_config();
if ($config === false)
{
  error('Bad configuration.');
}
$sock = udp_socket($config['internal']);
server_loop($sock, $config['buffer_length']);
