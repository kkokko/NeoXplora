<?php
  /**
   * Language Data Delete
   *
   * @version $Id: lang-delete.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php
  $db->query('LOCK TABLES mod_gallery_config WRITE');
  $db->query("ALTER TABLE mod_gallery_config DROP COLUMN title_" . $flag_id);
  $db->query('UNLOCK TABLES');

  $db->query('LOCK TABLES mod_gallery_images WRITE');
  $db->query("ALTER TABLE mod_gallery_images DROP COLUMN title_" . $flag_id);
  $db->query("ALTER TABLE mod_gallery_images DROP COLUMN description_" . $flag_id);
  $db->query('UNLOCK TABLES');
?>