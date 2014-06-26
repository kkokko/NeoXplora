<?php
  /**
   * Language Data Add
   *
   * @package CMS Pro
   * @author wojoscripts.com
   * @copyright 2012
   * @version $Id: lang-add.php, v1.00 2012-12-24 12:12:12 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php
	$db->query('LOCK TABLES mod_adblock WRITE');
	$db->query("ALTER TABLE mod_adblock ADD COLUMN title_$flag_id VARCHAR(100) NOT NULL AFTER title_en");
	$db->query('UNLOCK TABLES');

	if($mod_adblock = $db->fetch_all("SELECT * FROM mod_adblock")) {
		foreach ($mod_adblock as $row) {
			$data['title_' . $flag_id] = $row['title_en'];
			$db->update("mod_adblock", $data, "id = '".$row['id']."'");
		}
		unset($data, $row);
	}
?>