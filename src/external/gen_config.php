<?php

require_once('config.php');

$config = array();

$config['internal'] = array(
  'ip' => '192.168.0.2',
  'port' => 4242,
);

$config['admins'] = array(
  // 'ID' => 'salt|md5pwd'
  'test' => '|'.md5('test'),
);

$config['users'] = array(
  // 'ID' => 'salt|md5pwd'
  'test' => '|'.md5('test'),
);

if (save_config($config))
{
  exit('successfully saved config'."\n");
}

exit('error saving config'."\n");
