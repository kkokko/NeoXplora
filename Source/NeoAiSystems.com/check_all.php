<?php
define("_VALID_PHP", true);
require_once ("init.php");
require_once "config_storydb.php"; 
mysql_connect($configuration['host'], $configuration['user'], $configuration['pass']);
mysql_select_db($configuration['db']);

$query = mysql_query("SELECT * FROM `page`");

while($story_data = mysql_fetch_array($query)) {
  $query2 = mysql_query("SELECT * FROM `sentence` WHERE `pageID` = '" . $story_data['pageID'] . "' AND TRIM(`representation`) = ''");
  $flag = true;
  if(mysql_num_rows($query2)) $flag = false;
  if($flag) {
    mysql_query("UPDATE `page` SET `is_checked` = '0' AND `has_reps` = '0' WHERE `pageID` = '" . $story_data['pageID'] . "'");
  }
  else {
    mysql_query("UPDATE `page` SET `is_checked` = '1' AND `has_reps` = '1' WHERE `pageID` = '" . $story_data['pageID'] . "'");
  }
}

include (THEMEDIR . "/header.php");
?>
  <style type="text/css">
    @import url("/assets/train-style.css");
  </style>
  <style type="text/css">
      .top { padding-bottom: 10px; }
  </style>
  <div id="container">
    <div class="top">
      All stories were succesfully checked.
    </div>
  </div>
  
<?php
include (THEMEDIR . "/footer.php");
?>