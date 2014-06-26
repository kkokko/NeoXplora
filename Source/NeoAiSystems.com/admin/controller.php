<?php
  /**
   * Controller
   *
   * @version $Id: controller.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  define("_VALID_PHP", true);
  
  require_once("init.php");
  if (!$user->is_Admin())
    redirect_to("login.php");
?>
<?php
  /* Proccess Menu */
  if (isset($_POST['processMenu']))
      : if (intval($_POST['processMenu']) == 0 || empty($_POST['processMenu']))
      : redirect_to("index.php?do=menus");
  endif;
  $content->id = (isset($_POST['id'])) ? $_POST['id'] : 0; 
  $content->processMenu();
  endif;
?>
<?php
  /* Proccess Page */
  if (isset($_POST['processPage']))
      : if (intval($_POST['processPage']) == 0 || empty($_POST['processPage']))
      : redirect_to("index.php?do=pages");
  endif;
  $content->pageid = (isset($_POST['pageid'])) ? $_POST['pageid'] : 0; 
  $content->processPage();
  endif;
?>
<?php
  /* Proccess Post */
  if (isset($_POST['processPost']))
      : if (intval($_POST['processPost']) == 0 || empty($_POST['processPost']))
      : redirect_to("index.php?do=posts");
  endif;
  $content->postid = (isset($_POST['postid'])) ? $_POST['postid'] : 0; 
  $content->processPost();
  endif;
?>
<?php
  /* Proccess Module */
  if (isset($_POST['processModule']))
      : if (intval($_POST['processModule']) == 0 || empty($_POST['processModule']))
      : redirect_to("index.php?do=modules");
  endif;
  $content->id = (isset($_POST['id'])) ? $_POST['id'] : 0; 
  $content->processModule();
  endif;
?>
<?php
  /* Proccess Plugin */
  if (isset($_POST['processPlugin']))
      : if (intval($_POST['processPlugin']) == 0 || empty($_POST['processPlugin']))
      : redirect_to("index.php?do=plugins");
  endif;
  $content->id = (isset($_POST['id'])) ? $_POST['id'] : 0; 
  $content->processPlugin();
  endif;
?>
<?php
  /* Proccess Membership */
  if (isset($_POST['processMembership']))
      : if (intval($_POST['processMembership']) == 0 || empty($_POST['processMembership']))
      : redirect_to("index.php?do=pages");
  endif;
  $content->id = (isset($_POST['id'])) ? $_POST['id'] : 0; 
  $member->processMembership();
  endif;
?>
<?php
  /* Proccess User */
  if (isset($_POST['processUser']))
      : if (intval($_POST['processUser']) == 0 || empty($_POST['processUser']))
      : redirect_to("index.php?do=users");
  endif;
  $user->userid = (isset($_POST['userid'])) ? $_POST['userid'] : 0; 
  $user->processUser();
  endif;
?>
<?php
  /* Proccess Configuration */
  if (isset($_POST['processConfig']))
      : $core->processConfig();
  endif;
?>
<?php
  /* Proccess Email Template */
  if (isset($_POST['processTemplate']))
      : if (intval($_POST['processTemplate']) == 0 || empty($_POST['processTemplate']))
      : redirect_to("index.php?do=templates");
  endif;
  $content->id = (isset($_POST['id'])) ? $_POST['id'] : 0; 
  $member->processEmailTemplate();
  endif;
?>
<?php
  /* Add New Language */
  if (isset($_POST['addLanguage']))
      : if (intval($_POST['addLanguage']) == 0 || empty($_POST['addLanguage']))
      : redirect_to("index.php?do=language");
  endif;
  $core->addLanguage();
  endif;
?>
<?php
  /* Update Language */
  if (isset($_POST['updateLanguage']))
      : if (intval($_POST['updateLanguage']) == 0 || empty($_POST['updateLanguage']))
      : redirect_to("index.php?do=language");
  endif;
  $content->id = (isset($_POST['id'])) ? $_POST['id'] : 0; 
  $core->updateLanguage();
  endif;
?>
<?php
  /* Proccess Newsletter */
  if (isset($_POST['processNewsletter']))
      : if (intval($_POST['processNewsletter']) == 0 || empty($_POST['processNewsletter']))
      : redirect_to("index.php?do=newsletter");
  endif;
  $member->emailUsers();
  endif;
?>
<?php
  /* Proccess Gateway */
  if (isset($_POST['processGateway']))
      : if (intval($_POST['processGateway']) == 0 || empty($_POST['processGateway']))
      : redirect_to("index.php?do=gateways");
  endif;
  $content->id = (isset($_POST['id'])) ? $_POST['id'] : 0; 
  $member->processGateway();
  endif;
?>
<?php
  /* Proccess Theme Switch */
  if (isset($_POST['themeoption'])):
      print $core->getThemeOptions(sanitize($_POST['themeoption']));
	  print '<script type="text/javascript">
	  $(\'select.custombox2\').selectbox();
	  </script>';
  endif;
?>
<?php
  /* == Site Maintenance == */
  if (isset($_POST['processMaintenance'])):
      if (isset($_POST['inactive'])):
          $now = date('Y-m-d H:i:s');
          $diff = intval($_POST['days']);
          $expire = date("Y-m-d H:i:s", strtotime($now . -$diff . " days"));
          $db->delete("users", "lastlogin < '" . $expire . "' AND active = 'y' AND userlevel !=9");
          print ($db->affected()) ? $core->msgOk(str_replace("[NUMBER]", $db->affected(), _SM_INACTIVEOK)) : $core->msgAlert(_SYSTEM_PROCCESS);
      elseif (isset($_POST['pending'])):
          $db->delete("users", "active = 't'");
          print ($db->affected()) ? $core->msgOk(str_replace("[NUMBER]", $db->affected(), _SM_PENDINGOK)) : $core->msgAlert(_SYSTEM_PROCCESS);
      elseif (isset($_POST['banned'])):
		  $db->delete("users", "active = 'b'");
          print ($db->affected()) ? $core->msgOk(_SM_BANNEDOK) : $core->msgAlert(_SYSTEM_PROCCESS);
      elseif (isset($_POST['sitemap'])):
          $content->writeSiteMap();
      endif;
  endif;
?>