<?php
session_start();
if (stripos($_SERVER["PHP_SELF"], "/blog_config.php") > 0)
    die("Restricted access");

define("HOST", "127.0.0.1");
define("DBU", "userneo123");
define("DBPASS", "edu3uvy4e");
define("DB", "zadmin_neo123");

$link = mysql_connect(HOST, DBU, DBPASS);
mysql_select_db(DB);

define('MAILER_NAME', 'NeoXplora');
define('MAILER_EMAIL', 'support@neoxplora.com');
define('MAILER_REPLY_TO', 'noreply@neoxplora.com');


define('ROOT', '/home/179668/domains/neoxplora.com/html/');
define('FULLBASE', 'http://neoxplora.com/');
define('DEBUG', false);
$today = time();
define('RIGHT_BOX', false);

include ROOT . 'classes/UserSessionManager.php';
include ROOT . 'classes/EmailAddressValidator.php';
include ROOT . 'classes/UserUtils.php';

UserSessionManager::StartSession();

?>