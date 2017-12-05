<?php

function save_config($config)
{
  $r = file_put_contents('config.json', json_encode($config));
  if ($r === false)
  {
    return false;
  }
  return true;
}

function load_config()
{
  $json = @file_get_contents('config.json');
  if ($json === false)
  {
    return false;
  }
  
  $data = json_decode($json, true);
  if ($data === null)
  {
    return false;
  }
  
  return $data;
}
