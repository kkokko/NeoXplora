<?php
  /**
   * Language Data Delete
   *
   * @package CMS Pro
   * @author wojoscripts.com
   * @copyright 2012
   * @version $Id: lang-delete.php, v1.00 2012-12-24 12:12:12 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php
  $db->query('LOCK TABLES mod_adblock WRITE');
  $db->query("ALTER TABLE mod_adblock DROP COLUMN title_" . $flag_id);
  $db->query('UNLOCK TABLES');
?>