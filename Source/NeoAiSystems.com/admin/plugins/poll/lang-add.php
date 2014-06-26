<?php
  /**
   * Language Data Add
   *
   * @version $Id: lang-add.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php
	$db->query('LOCK TABLES plug_poll_options WRITE');
	$db->query("ALTER TABLE plug_poll_options ADD value_$flag_id VARCHAR(250) NOT NULL AFTER value_en");
	$db->query('UNLOCK TABLES');

	if($plug_poll_options = $db->fetch_all("SELECT * FROM plug_poll_options")) {
		foreach ($plug_poll_options as $row) {
			$data['value_' . $flag_id] = $row['value_en'];
			$db->update("plug_poll_options", $data, "id = '".$row['id']."'");
		}
		unset($data, $row);
	}

	$db->query('LOCK TABLES plug_poll_questions WRITE');
	$db->query("ALTER TABLE plug_poll_questions ADD question_$flag_id VARCHAR(250) NOT NULL AFTER question_en");
	$db->query('UNLOCK TABLES');

	if($plug_poll_questions = $db->fetch_all("SELECT * FROM plug_poll_questions")) {
		foreach ($plug_poll_questions as $row) {
			$data['question_' . $flag_id] = $row['question_en'];
			$db->update("plug_poll_questions", $data, "id = '".$row['id']."'");
		}
		unset($data, $row);
	}
?>