<?php
  /**
   * NewsSlider Class
   *
   * @version $Id: class_admin.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
  
  class newsSlider
  {
      
	  private $mTable = "plug_newsslider";
	  public $sliderid = null;


      /**
       * newsSlider::__construct()
       * 
       * @return
       */
      function __construct()
      {
          $this->getSliderId();
      }

	  /**
	   * newsSlider::getSliderId()
	   * 
	   * @return
	   */
	  private function getSliderId()
	  {
	  	  global $core;
		  if (isset($_GET['sliderid'])) {
			  $sliderid = (is_numeric($_GET['sliderid']) && $_GET['sliderid'] > -1) ? intval($_GET['sliderid']) : false;
			  $sliderid = sanitize($sliderid);
			  
			  if ($sliderid == false) {
				  $core->error("You have selected an Invalid SliderId","newsSlider::getSliderId()");
			  } else
				  return $this->sliderid = $sliderid;
		  }
	  }
	  
	  /**
	   * newsSlider::getNewsItems()
	   * 
	   * @return
	   */
	  public function getNewsItems()
	  {
		  global $db;
		  
		  $sql = "SELECT * FROM " . $this->mTable . " ORDER BY position";
		  $row = $db->fetch_all($sql);
		  
		  return ($row) ? $row : 0;
	  }

	  /**
	   * newsSlider::renderNewsItems()
	   * 
	   * @return
	   */
	  public function renderNewsItems()
	  {
		  global $db, $core;
		  
		  $sql = "SELECT *, DATE_FORMAT(created, '" . $core->short_date . "') as started"
		  . "\n FROM " . $this->mTable . ""
		  . "\n WHERE active = '1'"
		  . "\n ORDER BY position";
		  $row = $db->fetch_all($sql);
		  
		  return ($row) ? $row : 0;
	  }
	  	  
	  /**
	   * newsSlider::processNews()
	   * 
	   * @return
	   */
	  public function processNews()
	  {
		  global $db, $core, $wojosec;
		  
		  if ($_POST['title'.$core->dblang] == "")
			  $core->msgs['title'] = PLG_NS_TITLE_R;
		  
		  if ($_POST['body'.$core->dblang] == "")
			  $core->msgs['body'] = PLG_NS_BODY_R;
		  
		  if (empty($core->msgs)) {
			  $data = array(
					'title'.$core->dblang => sanitize($_POST['title'.$core->dblang]), 
					'body'.$core->dblang => $core->in_url($_POST['body'.$core->dblang]),
					'show_title' => intval($_POST['show_title']),
					'show_created' => intval($_POST['show_created']),
					'active' => intval($_POST['active'])
			  );
			  if(!$this->sliderid)
			  	$data['created'] = "NOW()";

			  
			  ($this->sliderid) ? $db->update($this->mTable, $data, "id='" . (int)$this->sliderid . "'") : $db->insert($this->mTable, $data);
			  $message = ($this->sliderid) ? PLG_NS_UPDATED : PLG_NS_ADDED;
			  
			  ($db->affected()) ? $wojosec->writeLog($message, "", "no", "plugin") . $core->msgOk($message) :  $core->msgAlert(_SYSTEM_PROCCESS);
				 
		  } else
			  print $core->msgStatus();
	  }
 
	  /**
	   * newsSlider::updateOrder()
	   * 
	   * @return
	   */
	  public function updateOrder()
	  {
		  global $db, $core;
			  
		  foreach ($_POST['node'] as $k => $v) {
			  $p = $k + 1;
			  $data['position'] = intval($p);
			  $db->update($this->mTable, $data, "id='" . (int)$v . "'");
		  }
		  
		  ($db->affected()) ? $core->msgOk(PLG_NS_SUPDATED) : $core->msgAlert(_SYSTEM_PROCCESS);
	  }
	  	  	  	  	  	  	  	  	  	  	  	  	  
  }
?>