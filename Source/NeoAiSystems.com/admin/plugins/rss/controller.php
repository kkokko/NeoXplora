<?php
  /**
   * Controller
   *
   * @version $Id: controller.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  define("_VALID_PHP", true);
  
  require_once("../../init.php");
  if (!$user->is_Admin())
      redirect_to("../../login.php");

  require_once("lang/" . $core->language . ".lang.php");
  require_once("admin_class.php");
  
  $rss = new Rss();
?>
<?php
  /* Proccess Configuration */
  if (isset($_POST['processConfig'])): 
  	$rss->processConfig();
  endif;
?>