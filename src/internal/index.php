<?php

require_once('database.php');

function html_response($title, $body)
{
  header('Content-Type: text/html; charset=utf-8');
  //header('X-Powered-By: Data-Diode');
  header_remove('X-Powered-By');
  
  print('<!DOCTYPE html><html lang="en"><head>');
  print('<meta name="viewport" content="width=device-width, initial-scale=1">');
  print('<title>'.htmlspecialchars($title).'</title>');
  print('
    <style type="text/css">
      table {
        border-collapse: collapse;
      }
      th,
      td {
        border: 1px solid black;
        line-height: 1.5;
        padding: 20px;
      }
      a {
        color: black;
        text-decoration: none;
      }
      a:hover {
        text-decoration: underline;
      }
      body {
        background-color: #D8D8D8;
        text-align: center;
        margin: 50px;
      }
      a:hover fieldset {
        background-color: #bababa;
      }
      pre {
        background-color: white;
        padding: 10px;
        text-align: left;
      }
    </style>');
  print('</head><body>');
  print('<h1><a href="?">'.htmlspecialchars($title).'</a></h1><hr>');
  print($body);
  print('<hr><p style="font-size: 80%;">'.htmlspecialchars($title).'</p>');
  print('</body></html>');
  exit();
}

function error($message)
{
  html_response('Error', $message);
}

function get($key)
{
  if (isset($_GET[$key]))
  {
    return $_GET[$key];
  }
  return null;
}

function data_table($title, $data)
{
  $body = '<table>';
  
  // table header
  $head = '<tr>';
    $head .= '<th>Date/Time</th>';
    $head .= '<th>User</th>';
    $head .= '<th>ID</th>';
  $head .= '</tr>';
  
  $body .= $head;
  // existing users
  foreach ($data as $d)
  {
    $body .= '<tr>';
    $body .= '<td>'.date('D j M Y H:m:s', $d['time']).'</td>';
    $body .= '<td><a href="?user='.urlencode($d['user']).'">'.$d['user'].'</a></td>';
    $body .= '<td><a href="?id='.urlencode($d['id']).'">'.$d['id'].'</a></td>';
    
    $body .= '</tr>';
  }
  $body .= $head;
  $body .= '</table>';
  html_response($title, $body);
}

function pretty_data($title, $json)
{
  $body = '<pre>';
  
  $data = json_decode($json, true);
  if ($data === null)
  {
    $body .= 'Data decode error.';
  }
  else
  {
    $body .= json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
  }
  
  $body .= '</pre>';
  html_response($title, $body);
}

// case ID
$id = get('id');
if (!empty($id))
{
  $data = get_data($id);
  if ($data === false)
  {
    error('BDD Error (1).');
  }
  if (get('format') === 'json')
  {
    exit($data);
  }
  pretty_data('Data '.$id, $data);
}

// case user
$user = get('user');
if (empty($user))
{
  $data = list_datas();
}
else // case all
{
  $data = list_user_datas($user);
}

if ($data === false)
{
  error('BDD Error (2).');
}

if (get('format') === 'json')
{
  exit(json_encode($data));
}
data_table('Data from '.(empty($user) ? 'everybody' : $user), $data);
