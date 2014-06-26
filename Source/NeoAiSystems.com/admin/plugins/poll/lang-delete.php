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
	$db->query('LOCK TABLES plug_poll_options WRITE');
	$db->query("ALTER TABLE plug_poll_options DROP COLUMN value_" . $flag_id);
	$db->query('UNLOCK TABLES');
  
	$db->query('LOCK TABLES plug_poll_questions WRITE');
	$db->query("ALTER TABLE plug_poll_questions DROP COLUMN question_" . $flag_id);
	$db->query('UNLOCK TABLES');
?>