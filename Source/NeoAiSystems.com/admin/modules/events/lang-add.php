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
	$db->query('LOCK TABLES mod_events WRITE');
	$db->query("ALTER TABLE mod_events ADD title_$flag_id VARCHAR(150) NOT NULL AFTER title_en");
	$db->query("ALTER TABLE mod_events ADD venue_$flag_id VARCHAR(150) NOT NULL AFTER venue_en");
	$db->query("ALTER TABLE mod_events ADD body_$flag_id TEXT AFTER body_en");
	$db->query('UNLOCK TABLES');

	if($mod_events = $db->fetch_all("SELECT * FROM mod_events")) {
		foreach ($mod_events as $row) {
			$data = array(
			'title_' . $flag_id => $row['title_en'],
			'venue_' . $flag_id => $row['venue_en'],
			'body_' . $flag_id => $row['body_en']
			);
			
			$db->update("mod_events", $data, "id = '".$row['id']."'");
		}
		unset($data, $row);
	}
?>