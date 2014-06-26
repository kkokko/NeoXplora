<?php
  /**
   * User Account
   *
   * @package CMS Pro
   * @author wojoscripts.com
   * @copyright 2011
   * @version $Id: account.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  define("_VALID_PHP", true);
  require_once("init.php");
  
  if (!$user->logged_in)
      redirect_to("login.php");
?>
<?php include(THEMEDIR."/header.php");?>
<?php
  $listpackrow  = $member->getMembershipListFrontEnd();
  $mrow = $user->getUserMembership();
  $gatelist = $member->getGateways(true);
  $row = $user->getUserData();
  
  require_once(THEMEDIR . "/account.tpl.php");
?>
<?php include(THEMEDIR."/footer.php");?>