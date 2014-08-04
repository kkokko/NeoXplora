<?php
define("HOST1", "127.0.0.1");
define("DBU1", "userneo123dev");
define("DBPASS1", "y6ege6ere");
define("DB1", "zadmin_neo123dev");

/*define("HOST1", "localhost");
define("DBU1", "root");
define("DBPASS1", "");
define("DB1", "zadmin_neoxplora");*/

$link1 = mysql_connect(HOST1, DBU1, DBPASS1);
$db = mysql_select_db(DB1, $link1);

?>