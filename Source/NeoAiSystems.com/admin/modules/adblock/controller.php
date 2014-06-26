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

  require_once ("../../init.php");
  if (!$user->is_Admin())
      redirect_to("../../login.php");

  require_once ("lang/" . $core->language . ".lang.php");
  require_once ("admin_class.php");

  $adblock = new AdBlock();
?>
<?php
  /* Proccess AdBlock */
  if (isset($_POST['processAdBlock'])):
      $adblock->adblockid = (isset($_POST['adblockid'])) ? $_POST['adblockid'] : 0;
      $adblock->processAdBlock();
  endif;
?>
<?php
  /* Delete AdBlock  */
  if (isset($_POST['deleteAdBlock'])):
      $id = sanitize($_POST['deleteAdBlock']);
      $img = getValue("banner_image", "mod_adblock", "id='" . $id . "'");
      $block_assignment = getValue("block_assignment", "mod_adblock", "id='" . $id . "'");

      if ($block_assignment) {
          $pluginid = getValue("id", "plugins", "plugalias='" . $block_assignment . "'");

          $block_assignment_clean = str_replace('adblock/', '', $block_assignment);
          $plugin_file_current = WOJOLITE . $adblock->pluginspath . $block_assignment_clean . '/main.php';
          unlink($plugin_file_current);
          rmdir(str_replace('/main.php', '', $plugin_file_current));

          $db->delete("plugins", "id=" . $pluginid);
          $db->delete("layout", "plug_id=" . $pluginid);
      }

      if ($img)
          @unlink(WOJOLITE . $adblock->imagepath . $img);
      $db->delete("mod_adblock", "id='" . (int)$id . "'");

      $title = sanitize($_POST['title']);
      print ($db->affected()) ? $wojosec->writeLog(MOD_AB_ADBLOCK . ' <strong>' . $title . '</strong> ' . _DELETED, "", "no", "module") . $core->msgOk(MOD_AB_ADBLOCK . ' <strong>' . $title . '</strong> ' . _DELETED) : $core->msgAlert(_SYSTEM_PROCCESS);
  endif;
?>