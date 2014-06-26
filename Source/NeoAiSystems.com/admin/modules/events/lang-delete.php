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
	$db->query('LOCK TABLES mod_events WRITE');
	$db->query("ALTER TABLE mod_events DROP COLUMN title_" . $flag_id);
	$db->query("ALTER TABLE mod_events DROP COLUMN venue_" . $flag_id);
	$db->query("ALTER TABLE mod_events DROP COLUMN body_" . $flag_id);
	$db->query('UNLOCK TABLES');
?>