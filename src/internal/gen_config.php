<?php

require_once('config.php');

$config = array();

$config['buffer_length'] = 64*1024; // ~udp max data size

$config['internal'] = array(
  'ip' => '127.0.0.1',
  'port' => 4242,
);

$config['database'] = array(
  'host' => '127.0.0.1',
  'user' => 'db_dd',
  'pass' => 'Passw0rd',
  'name' => 'dd',
);

if (save_config($config))
{
  exit('successfully saved config'."\n");
}

exit('error saving config'."\n");
