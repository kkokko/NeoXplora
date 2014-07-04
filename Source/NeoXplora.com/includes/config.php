<?php
 
if (stripos($_SERVER["PHP_SELF"], "/config.php") > 0)
    die("Restricted access");

define("HOST", "127.0.0.1");
define("DBU", "userneo123");
define("DBPASS", "edu3uvy4e");
define("DB", "zadmin_neoxplora");

$link = @mysql_connect(HOST, DBU, DBPASS);
$db = mysql_select_db(DB, $link);


define('MAILER_NAME', 'NeoXplora');
define('MAILER_EMAIL', 'support@neoxplora.com');
define('MAILER_REPLY_TO', 'noreply@neoxplora.com');
define('COOKIEDOMAIN', 'http://neoxplora.com');
$requestedFile = explode("/", $_SERVER['PHP_SELF']);
$reqFile = '/' . end($requestedFile);


$base = substr($_SERVER['PHP_SELF'], 0, -strlen($reqFile));
$base = str_replace("/admin","",$base);
$fullbase = 'http://' . $_SERVER['HTTP_HOST'] . $base;

$root = $_SERVER['DOCUMENT_ROOT'] . $base . '/';
define('ROOT', '');
define('BASE', $base);
define('FULLBASE', $fullbase . '/');
define('DEBUG', false);
$today = time();
define('RIGHT_BOX', true);

include ROOT . 'classes/UserSessionManager.php';
include ROOT . 'classes/EmailAddressValidator.php';
include ROOT . 'classes/UserUtils.php';
include(ROOT .'includes/db/dbSQLFunctions.php'); 
require_once( ROOT . 'blog/wp-load.php' );
UserSessionManager::StartSession();

/*if (!UserSessionManager::LoggedIn() && isset($_COOKIE['UserPassword']) && isset($_COOKIE['UserEmail'])) {
    $isLogin = UserSessionManager::Login($_COOKIE['UserEmail'], base64_decode($_COOKIE['UserPassword']), 1);
    if ($isLogin === true) {
        header('location: ' . FULLBASE . 'index.php');
        die;
    }
}*/

define("ENV", "prod");
?>