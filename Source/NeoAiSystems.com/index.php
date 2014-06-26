<?php
  /**
   * Index
   *
   * @version $Id: index.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  define("_VALID_PHP", true);
  require_once("init.php");
  
  $plugtop = $content->getPluginLayout("top");
  $plugbot = $content->getPluginLayout("bottom");
  $plugleft = $content->getPluginLayout("left");
  $plugright = $content->getPluginLayout("right");
  $totalleft = count($plugleft);
  $totalright = count($plugright);
  $totaltop = count($plugtop);
  $totalbot = count($plugbot);
  
  $core->getVisitors(); // visitor counter
  if ($core->offline == 1 && $user->is_Admin())
      require_once(THEMEDIR . "/index.php");
  elseif ($core->offline == 1)
      require_once("maintenance.php");
  else
      require_once(THEMEDIR . "/index.php");
?>