<?php

require_once('config.php');

function db_connect() {
  // connect only once
  static $c = null;
  if(!is_null($c)) {
    return $c;
  }
  
  $config = load_config();
  if ($config === false)
  {
    error('Bad configuration.');
  }
  $db = $config['database'];
  
  try {
    $pdo = new PDO('mysql:host='.$db['host'].';charset=utf8', $db['user'], $db['pass']);
    $pdo->query("CREATE DATABASE IF NOT EXISTS ".$db['name']);
    $pdo->query("use ".$db['name']);

    //$c = new PDO('mysql:host='.$db['host'].';dbname='.$db['name'].';charset=utf8', $db['user'], $db['pass']);
  } catch(PDOException $e) {
    error('Error connecting to database : '.$e->getMessage());
  }
  
  // silent errors, check return values and handle error your way
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
  
  return $pdo;
}
/*
 * Log PDO error and return false
 */
function pdo_error($pdo_object, $type='') {
  $error = $pdo_object->errorInfo();
  
  // no matter PDO error handling setting,
  // execute statement throws a warning in case of parameters error
  // in this case, warning is already logged and $error[1] is null
  if(!is_null($error[1])) {
    error('SQLSTATE '.$error[0].', '.$type.' Error '.$error[1].' : '.$error[2]);
  }
}
/*
 * Execute an SQL request using PDO
 * $sql request to be passed to PDO prepare statement
 *      replace variables with "?" or ":paramname"
 * $param array of parameters to pass to PDO bindValue
 *        if names are used, must be associative array e.g. array('name' => 'value')
 *        if index is used, must be in right order e.g. array('param1', 'param2')
 *        the type of each parameter is automatically detected
 */
function sql_request($sql, $params=null, &$rows=null, &$time=null) {
  $db = db_connect();
  //$db->query('set profiling=1');
  
  // save prepared statements for reuse (faster)
  static $requests = array();
  
  // find unique key for this sql request
  $key = hash('md5', $sql);
  
  // if not found, prepare it
  if(!isset($requests[$key])) {
    $r = $db->prepare($sql);
    if($r === false) {
      return pdo_error($db, 'Prepare');
    }
    
    $requests[$key] = $r;
  }
  $req = $requests[$key];
  
  // bind parameters with type detection
  if(is_array($params)) {
    foreach($params as $i => $param) {
      // if index is used
      if(is_int($i)) {
        // PDO params start at 1 while PHP starts at 0
        ++$i;
      }
      
      // default to string type
      $type = PDO::PARAM_STR;
      if(is_bool($param)) $type = PDO::PARAM_BOOL;
      if(is_null($param)) $type = PDO::PARAM_NULL;
      if(is_int($param)) $type = PDO::PARAM_INT;
      // TODO add PDO::PARAM_LOB ?
      
      $r = $req->bindValue($i, $param, $type);
      if($r === false) {
        return pdo_error($req, 'Bind');
      }
    }
  }
  
  //$before = microtime(true);
  $r = $req->execute();
  //$after = microtime(true);
  if($r === false) {
    return pdo_error($req, 'Execute');
  }
  
  //request time
  //$profiles = $db->query('show profiles;')->fetchAll(PDO::FETCH_ASSOC);
  //$time = $profiles[count($profiles)-1]['Duration'];
  
  $rows = $req->rowCount();
  
  //$diff = intval(round(1e3*($after-$before)));
  //error_log($rows.' rows affected, time '.$time.'s, diff '.$diff.'ms');
  
  return $req->fetchAll(PDO::FETCH_ASSOC);
}

function drop_tables() {
  // get database
  $db = db_connect();
  
  // drop comments
  $r = $db->exec('DROP TABLE IF EXISTS data;');
  if($r === false) return pdo_error($db, 'DROP data');
  
  return true;
}

function create_tables() {
  // get database
  $db = db_connect();
  
  // table messages
  $sql =  'CREATE TABLE IF NOT EXISTS data(';
  // field time, using INT UNSIGNED instead of DATETIME for performance
  $sql .=   'time INT UNSIGNED NOT NULL,';
  $sql .=   'INDEX (time),';
  // field id
  $sql .=   'id VARCHAR(40) NOT NULL,';
  $sql .=   'INDEX (id),';
  $sql .=   'PRIMARY KEY (time, id),';
  // field count
  $sql .=   'count TINYINT UNSIGNED NOT NULL,';
  $sql .=   'INDEX (count),';
  // field user
  $sql .=   'user VARCHAR(40) NOT NULL,';
  $sql .=   'INDEX (user),';
  // field data
  $sql .=   'data LONGTEXT NOT NULL';
  // InnoDB engine
  $sql .= ') CHARACTER SET utf8 ENGINE=INNODB ;';
  // execute
  $r = $db->exec($sql);
  if($r === false) return pdo_error($db, 'CREATE data');
  
  return true;
}
/*
 * Insert data
 * @return number of rows affected or false on error
 */
function insert_data($data) {
  $sql = 'INSERT INTO data VALUES(?, ?, ?, ?, ?);';
  
  $params = array(
    $data['time'],
    $data['id'],
    1,
    $data['user'],
    json_encode($data['data']),
  );
  
  sql_request($sql, $params, $rows);
  
  return $rows;
}

/*
 * Get count
 * @return count or false on error
 */
function get_count($data) {
  $sql  = 'SELECT count FROM data WHERE time=? AND id=?;';
  
  $params = array(
    $data['time'],
    $data['id'],
  );
  
  $r = sql_request($sql, $params);
  if (empty($r))
  {
    return false;
  }
  
  return intval($r[0]['count']);
}

/*
 * Set count
 * @return count or false on error
 */
function set_count($data, $count) {
  $sql  = 'UPDATE data SET count=? WHERE time=? AND id=?;';
  
  $params = array(
    intval($count),
    $data['time'],
    $data['id'],
  );
  
  sql_request($sql, $params, $rows);
  
  return $rows;
}

function get_data($id) {
  $sql  = 'SELECT data FROM data WHERE id=?';
  
  $params = array(
    $id,
  );
  
  $r = sql_request($sql, $params);
  if (empty($r))
  {
    return false;
  }
  
  return $r[0]['data'];
}

function list_datas() {
  $sql  = 'SELECT time, user, id FROM data ORDER BY time DESC';
  
  return sql_request($sql);
}

function list_user_datas($user) {
  $sql  = 'SELECT time, user, id FROM data WHERE user=? ORDER BY time DESC';
  
  $params = array(
    $user,
  );
  
  return sql_request($sql, $params);
}

// if main script (not included)
if (get_included_files()[0] === __FILE__)
{
  function error($message)
  {
    exit($message."\n");
  }
  
  if ($argc !== 2)
  {
    error('usage: php '.$argv[0].' create|drop');
  }
  switch($argv[1])
  {
    case 'create':
      create_tables();
      error('tables created successfully');
    case 'drop':
      drop_tables();
      error('tables dropped successfully');
    default:
      error('usage: php '.$argv[0].' create|drop');
  }
}
