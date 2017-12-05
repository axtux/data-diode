<?php

require_once('config.php');

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
    </style>');
  print('</head><body>');
  print('<h1><a href="">'.htmlspecialchars($title).'</a></h1><hr>');
  print($body);
  print('<hr><p style="font-size: 80%;">'.htmlspecialchars($title).'</p>');
  print('</body></html>');
  exit();
}

function error($message)
{
  html_response('Error', $message);
}

function login_form()
{
  $form = '';
  $form .= '<fieldset><legend>Connection</legend>';
  $form .= '<form method="POST">';
  $form .= '<input name="id" type="text" placeholder="Login"/><br>';
  $form .= '<input name="pass" type="password" placeholder="Password"/><br>';
  $form .= '<input type="submit"/>';
  $form .= '</form></fieldset>';
  html_response('Connection', $form);
}

function get($key)
{
  if (isset($_GET[$key]))
  {
    return $_GET[$key];
  }
  return null;
}

function post($key)
{
  if (isset($_POST[$key]))
  {
    return $_POST[$key];
  }
  return null;
}

function check_login($admins)
{
  $id = post('id');
  $pass = post('pass');
  
  if (isset($admins[$id]) && md5($pass) === $admins[$id])
  {
    $_SESSION['admin'] = true;
  }
}

function check_auth()
{
  if (empty($_SESSION['admin']))
  {
    login_form();
  }
}

function add_id(&$users, $id, $pass)
{
  if (empty($id))
  {
    error('Empty login.');
  }
  
  // do not allow empty password
  $hash = empty($pass) ? null : md5($pass);
  $users[$id] = $hash;
  return true;
}

function del_id(&$users, $id)
{
  unset($users[$id]);
  return true;
}

/*
 * return true if a modification has been done
 */
function check_users(&$users)
{
  $id = post('id');
  if (empty($id))
  {
    return false;
  }
  
  switch(post('action'))
  {
    case 'add':
      return add_id($users, $id, post('pass'));
    case 'del':
      return del_id($users, $id);
  }
  return false;
}

function users_table($type, $users)
{
  $title = $type;
  $title[0] = strtoupper($title[0]);
  $body = '<h2>'.$title.'</h2>';
  
  $body .= '<table>';
  // new user row
  $body .= '<tr>';
  
  $body .= '<td colspan="3"><form method="POST">';
  $body .= '<input type="hidden" name="action" value="add">';
  $body .= '<input type="hidden" name="type" value="'.$type.'">';
  $body .= '<input type="text" name="id" placeholder="New user">';
  $body .= '<input type="password" name="pass" placeholder="New password">';
  $body .= '<input type="submit" value="Create">';
  $body .= '</form></td>';
  
  $body .= '</tr>';
  // existing users
  foreach ($users as $id => $pass)
  {
    $body .= '<tr>';
    // ID
    $body .= '<td>'.$id.'</td>';
    // update password cell
    $body .= '<td><form method="POST">';
    $body .= '<input type="hidden" name="action" value="add">';
    $body .= '<input type="hidden" name="type" value="'.$type.'">';
    $body .= '<input type="hidden" name="id" value="'.htmlspecialchars($id).'">';
    $body .= '<input type="password" name="pass" placeholder="New password">';
    $body .= '<input type="submit" value="Update">';
    $body .= '</form></td>';
    // delete cell
    $body .= '<td><form method="POST">';
    $body .= '<input type="hidden" name="action" value="del">';
    $body .= '<input type="hidden" name="type" value="'.$type.'">';
    $body .= '<input type="hidden" name="id" value="'.htmlspecialchars($id).'">';
    $body .= '<input type="submit" value="Delete">';
    $body .= '</form></td>';
    
    $body .= '</tr>';
  }
  $body .= '</table>';
  return $body;
}

$config = load_config();
if ($config === false)
{
  error('Bad configuration.');
}
session_start();
check_login($config['admins']);
check_auth();

$types = array('admins', 'users');
$type = post('type');
if (in_array($type, $types))
{
  if (check_users($config[$type]))
  {
    save_config($config);
  }
}

$body = '';
foreach($types as $type)
{
  $body .= users_table($type, $config[$type]);
}
html_response('Manager', $body);
