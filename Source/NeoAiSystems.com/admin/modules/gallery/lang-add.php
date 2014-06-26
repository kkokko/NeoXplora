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
	$db->query('LOCK TABLES mod_gallery_config WRITE');
	$db->query("ALTER TABLE mod_gallery_config ADD title_$flag_id VARCHAR(100) NOT NULL AFTER title_en");
	$db->query('UNLOCK TABLES');

	if($mod_gallery_config = $db->fetch_all("SELECT * FROM mod_gallery_config")) {
		foreach ($mod_gallery_config as $row) {
			$data['title_' . $flag_id] = $row['title_en'];
			$db->update("mod_gallery_config", $data, "id = '".$row['id']."'");
		}
		unset($data, $row);
	}

	$db->query('LOCK TABLES mod_gallery_images WRITE');
	$db->query("ALTER TABLE mod_gallery_images ADD title_$flag_id VARCHAR(100) NOT NULL AFTER title_en");
	$db->query("ALTER TABLE mod_gallery_images ADD description_$flag_id VARCHAR(250) NOT NULL AFTER description_en");
	$db->query('UNLOCK TABLES');

	if($mod_gallery_images = $db->fetch_all("SELECT * FROM mod_gallery_images")) {
		foreach ($mod_gallery_images as $row) {
			$data = array(
			'title_' . $flag_id => $row['title_en'],
			'description_' . $flag_id => $row['description_en']
			);
			
			$db->update("mod_gallery_images", $data, "id = '".$row['id']."'");
		}
		unset($data, $row);
	}
?>