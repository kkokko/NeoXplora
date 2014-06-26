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
	  $db->query('LOCK TABLES plug_newsslider WRITE');
	  $db->query("ALTER TABLE plug_newsslider ADD title_$flag_id VARCHAR(150) NOT NULL AFTER title_en");
	  $db->query("ALTER TABLE plug_newsslider ADD body_$flag_id TEXT AFTER body_en");
	  $db->query('UNLOCK TABLES');
  
	  if($plug_newsslider = $db->fetch_all("SELECT * FROM plug_newsslider")) {
		  foreach ($plug_newsslider as $row) {
			  $data = array(
			  'title_' . $flag_id => $row['title_en'],
			  'body_' . $flag_id => $row['body_en']
			  );
			  
			  $db->update("plug_newsslider", $data, "id = '".$row['id']."'");
		  }
		  unset($data, $row);
	  }
?>