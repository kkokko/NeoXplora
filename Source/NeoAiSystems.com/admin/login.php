<?php
  /**
   * Login
   *
   * @package CMS Pro
   * @author wojoscripts.com
   * @copyright 2011
   * @version $Id: login.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  
  define("_VALID_PHP", true);
  require_once("init.php");
?>
<?php
  if ($user->is_Admin())
      redirect_to("index.php");
	  
  if (isset($_POST['submit']))
      : $result = $user->login($_POST['username'], $_POST['password']);
  //Login successful 
  if ($result)
      : $wojosec->writeLog(_USER . ' ' . $user->username. ' ' . _LG_LOGIN, "user", "no", "user");
	  redirect_to("index.php");
  endif;
  endif;

?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $core->site_name;?></title>
<link href="assets/login.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../assets/jquery.js"></script>
<script type="text/javascript" src="../assets/jquery-ui.js"></script>
<script type="text/javascript" src="../assets/global.js"></script>
</head>
<body>
<div id="loginform">
  <form id="admin_form" name="admin_form" method="post" action="login.php">
    <h1>admin panel</h1>
    <fieldset id="inputs">
      <input id="username" name="username" type="text" onclick="disAutoComplete(this);" />
      <input id="password" name="password" type="password" />
    </fieldset>
    <fieldset id="actions">
      <input type="submit" name="submit" id="submit" value="<?php echo _UA_LOGIN;?>" />
      <div>
        <p>&lsaquo; <a href="../index.php"><?php echo _LG_BACK;?></a></p>
        Copyright &copy; <?php echo date('Y').' '.$core->site_name;?></div>
    </fieldset>
  </form>
</div>
<div id="message-box"><?php print $core->showMsg;?></div>
</body>
</html>