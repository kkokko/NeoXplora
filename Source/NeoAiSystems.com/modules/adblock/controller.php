<?php
  /**
   * Controller
   *
   * @package CMS Pro
   * @author wojoscripts.com
   * @copyright 2012
   * @version $Id: controller.php, v1.00 2012-12-24 12:12:12 gewa Exp $
   */
  define("_VALID_PHP", true);
  
  require_once("../../init.php");
  
  require_once(WOJOLITE . "admin/modules/adblock/admin_class.php");
  $adblock = new AdBlock();
?>
<?php 
  /* Proccess AdClick */
  if (isset($_GET['adC'])):
      $fname = substr($_GET['f'],42);
  	  $adblock->adblockid = (isset($_GET['adC'])) ? $_GET['adC'] : 0; 
  	  if($fname != md5(sha1($adblock->adblockid))) die('err');
  	  $adblock->incrementClicksNumber();
  endif;
?>