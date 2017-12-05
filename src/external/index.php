<?php

function api_response($code, $success, $message)
{
  http_response_code($code);
  header('Content-Type: application/json');
  //header('X-Powered-By: Data-Diode');
  header_remove('X-Powered-By');
  $data = array(
    'success' => $success,
    'message' => $message,
  );
  exit(json_encode($data));
}

function success($message)
{
  api_response(202, true, $message); // accepted
}

function error($message)
{
  api_response(400, false, $message); // bad request
}

function read_config()
{
  $json = @file_get_contents('config.json');
  if ($json === false)
  {
    error('Bad configuration (1).');
  }
  
  $data = json_decode($json, true);
  if ($data === null)
  {
    error('Bad configuration (2).');
  }
  
  return $data;
}

function check_auth($users)
{
  if (empty($_SERVER['HTTP_AUTHORIZATION']))
  {
    error('Missing authorization header.');
  }
  
  $auth = preg_split('/ /', $_SERVER['HTTP_AUTHORIZATION'], null, PREG_SPLIT_NO_EMPTY);
  if (count($auth) !== 2)
  {
    error('Bad authorisation header, expected basic (1).');
  }
  
  if (strtolower($auth[0]) !== 'basic')
  {
    error('Bad authorisation header, expected basic (2).');
  }
  
  $auth = base64_decode($auth[1]);
  if ($auth === false)
  {
    error('Expected base 64 encoded authorization.');
  }
  
  $sep = strpos($auth, ':');
  if ($sep === false)
  {
    error('Missing separator ":" in base 64 encoded authorization header.');
  }
  
  $id = substr($auth, 0, $sep);
  $pass = substr($auth, $sep+1);
  
  foreach($users as $user)
  {
    if ($user['id'] !== $id)
    {
      continue;
    }
    
    if ($user['md5pass'] === md5($pass))
    {
      return true;
    }
    
    error('Unauthorized user.');
  }
  
  error('Unauthorized user.');
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

function udp_socket()
{
  $s = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
  if ($s === false)
  {
    error('Error processing data (1).');
  }
  return $s;
}

function random_id($length=30)
{
  $bytes = random_bytes($length);
  return base64_encode($bytes);
}

function send_data($data, $ip, $port)
{
  $wrapper = array(
    'time' => time(),
    'id' => random_id(),
    'data' => $data,
  );
  $json = json_encode($wrapper);
  $len = strlen($json);
  
  $sock = udp_socket();
  for ($i = 0; $i < 3; $i++)
  {
    $r = socket_sendto($sock, $json, $len, null, $ip, $port);
    if ($r !== $len)
    {
      error('Error processing data (2).');
    }
  }
  
  success('Data processed.');
}

$config = read_config();
check_auth($config['users']);
$data = json_data();
send_data($data, $config['ip'], $config['port']);
