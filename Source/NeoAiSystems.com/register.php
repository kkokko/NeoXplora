<?php
  /**
   * User Register
   *
   * @package CMS Pro
   * @author wojoscripts.com
   * @copyright 2011
   * @version $Id: register.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  define("_VALID_PHP", true);
  require_once("init.php");

  if ($user->logged_in)
      redirect_to("account.php");
?>
<?php include_once(THEMEDIR."/header.php");?>
<?php
  $numusers = countEntries("users");
  require_once(THEMEDIR."/register.tpl.php");	  
?>
<?php include_once(THEMEDIR."/footer.php");?>