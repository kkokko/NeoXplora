<?php
  /**
   * latestTwitts Class
   *
   * @version $Id: class_admin.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
  
  class latestTwitts
  {
      
	  private $mTable = "plug_twitter_config";


      /**
       * newsSlider::__construct()
       * 
       * @return
       */
      function __construct()
      {
		  $this->getconfig();
      }

	  
	  /**
	   * latestTwitts::getconfig()
	   * 
	   * @return
	   */
	  private function getconfig()
	  {
		  global $db;
		  
		  $sql = "SELECT * FROM " . $this->mTable . "";
		  $row = $db->first($sql);
		  
		  $this->username = $row['username'];
		  $this->counter = $row['counter'];
		  $this->speed = $row['speed'];
		  $this->show_image = $row['show_image'];
		  $this->timeout = $row['timeout'];
	  }
	  	  
	  /**
	   * latestTwitts::processConfig()
	   * 
	   * @return
	   */
	  function processConfig()
	  {
		  global $db, $core, $wojosec;
		  
		  if ($_POST['username'] == "")
			  $core->msgs['username'] = PLG_TW_USER_R;
		  
		  if (empty($core->msgs)) {
			  $data = array(
					'username' => sanitize($_POST['username']),
					'counter' => intval($_POST['counter']),
					'speed' => intval($_POST['speed']),
					'show_image' => intval($_POST['show_image']),
					'timeout' => intval($_POST['timeout'])
			  );

			  $db->update($this->mTable, $data);
			  ($db->affected()) ? $wojosec->writeLog(PLG_TW_UPDATED, "", "no", "plugin") . $core->msgOk(PLG_TW_UPDATED) : $core->msgAlert(_SYSTEM_PROCCESS);

		  } else
			  print $core->msgStatus();
	  }
  }
?>