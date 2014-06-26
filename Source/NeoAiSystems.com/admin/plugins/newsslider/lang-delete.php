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
	$db->query('LOCK TABLES plug_newsslider WRITE');
	$db->query("ALTER TABLE plug_newsslider DROP COLUMN title_" . $flag_id);
	$db->query("ALTER TABLE plug_newsslider DROP COLUMN body_" . $flag_id);
	$db->query('UNLOCK TABLES');
?>