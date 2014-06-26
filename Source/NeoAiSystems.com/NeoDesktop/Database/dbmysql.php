<?php
  function connect_db() {
    require_once str_replace('\\', '/', __DIR__)."/../../config_storydb.php";
    mysql_connect($configuration['host'], $configuration['user'], $configuration['pass']);
    mysql_select_db($configuration['db']);
  }
  
  function checkuser($username, $password) {
    mysql_connect('127.0.0.1', 'userneoai', 'login141');
    mysql_select_db('neoaisystems.sql');
    
    $sql = "SELECT password, active, userlevel FROM users WHERE username = '".$username."'";
    $row = mysql_fetch_array(mysql_query($sql));
    
    if($row['active'] == 'y' && sha1($password) == $row['password']) {
      if(($row['userlevel'] != 8) && ($row['userlevel'] != 9)) {
        mysql_close();
        return false;
      } else {
        mysql_close();
        return true;
      }
    } else {
      mysql_close();
      return false;
    }
  }
  
?>