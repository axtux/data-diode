<?php

require_once('config.php');

$config = array(
  'internal_ip' => '127.0.0.1',
  'internal_port' => 4242,
  'admin_pass' => md5('test'),
);

$config['users'] = array(
  array(
    'id' => 'test',
    'md5pass' => md5('test'),
  ),
);

if (save_config($config))
{
  exit('successfully saved config'."\n");
}

exit('error saving config'."\n");
