<?php
  /**
   * Modules
   *
   * @version $Id: modules.php, v2.00 2011-10-15 10:12:05 gewa Exp $
   */
  define("_VALID_PHP", true);
  require_once("init.php");

  define("MODTHEMEURL", ($content->moduledata['theme']) ? SITEURL."/theme/".$content->moduledata['theme'] : THEMEURL);
  define("MODTHEMEDIR", ($content->moduledata['theme'] and is_file(WOJOLITE."theme/".$content->moduledata['theme'].'/mod_index.php')) ? WOJOLITE."theme/".$content->moduledata['theme'] : THEMEDIR);

  $plugtop = $content->getPluginLayout("top",true);
  $plugbot = $content->getPluginLayout("bottom",true);
  $plugleft = $content->getPluginLayout("left",true);
  $plugright = $content->getPluginLayout("right",true);
  $totalleft = count($plugleft);
  $totalright = count($plugright);
  $totaltop = count($plugtop);
  $totalbot = count($plugbot);
  
  if ($core->offline == 1 && $user->is_Admin())
      require_once(MODTHEMEDIR . "/mod_index.php");
  elseif ($core->offline == 1)
      require_once("maintenance.php");
  else
      require_once(MODTHEMEDIR . "/mod_index.php");
?>