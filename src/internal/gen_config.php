<?php

require_once('config.php');

$config = array();

$config['buffer_length'] = 64*1024; // ~udp max data size

$config['internal'] = array(
  'ip' => '192.168.0.2',
  'port' => 4242,
);

$config['database'] = array(
  'host' => '127.0.0.1',
  'user' => 'root',
  'pass' => 'toto',
  'name' => 'dd',
);

if (save_config($config))
{
  exit('successfully saved config'."\n");
}

exit('error saving config'."\n");
