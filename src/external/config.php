<?php

$config = array(
  'ip' => '127.0.0.1',
  'port' => 4242,
);

$config['users'] = array(
  array(
    'id' => 'test',
    'md5pass' => md5('test'),
  ),
);

$r = file_put_contents('config.json', json_encode($config));
if ($r === false)
{
  exit('error saving config'."\n");
}
exit('successfully saved config'."\n");
