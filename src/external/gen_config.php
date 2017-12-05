<?php

require_once('config.php');

$config = array();

$config['internal'] = array(
  'ip' => '127.0.0.1',
  'port' => 4242,
);

$config['admins'] = array(
  // 'ID' => 'md5pwd'
  'test' => md5('test'),
);

$config['users'] = array(
  // 'ID' => 'md5pwd'
  'test' => md5('test'),
);

if (save_config($config))
{
  exit('successfully saved config'."\n");
}

exit('error saving config'."\n");
