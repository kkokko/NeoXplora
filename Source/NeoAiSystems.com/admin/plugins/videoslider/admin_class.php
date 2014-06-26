<?php
  /**
   * videoSlider Class
   *
   * @version $Id: class_admin.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
  
  class videoSlider
  {
      
	  private $mTable = "plug_videoslider";
	  public $sliderid = null;


      /**
       * videoSlider::__construct()
       * 
       * @return
       */
      function __construct()
      {
          $this->getSliderId();
      }

	  /**
	   * videoSlider::getSliderId()
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
				  $core->error("You have selected an Invalid SliderId","jQuerySlider::getSliderId()");
			  } else
				  return $this->sliderid = $sliderid;
		  }
	  }
	  
	  /**
	   * videoSlider::getSliderImages()
	   * 
	   * @return
	   */
	  public function getSliderImages()
	  {
		  global $db;
		  
		  $sql = "SELECT * FROM " . $this->mTable . " ORDER BY position";
		  $row = $db->fetch_all($sql);
		  
		  return ($row) ? $row : 0;
	  }
	  
	  /**
	   * videoSlider::getSliderImages()
	   * 
	   * @return
	   */
	  function processSliderImage()
	  {
		  global $db, $core, $wojosec;
		  
		  if (empty($_POST['title'.$core->dblang]))
			  $core->msgs['title'] = PLG_VS_CAPTION_R;

		  if (empty($_POST['vidurl']))
			  $core->msgs['vidurl'] = PLG_VS_URL_R;
			  
		  if (empty($core->msgs)) {
			  $data = array(
					'title'.$core->dblang => sanitize($_POST['title'.$core->dblang]), 
					'description'.$core->dblang => sanitize($_POST['description'.$core->dblang]),
					'vidurl' => sanitize($_POST['vidurl'])
			  );
			  
			  ($this->sliderid) ? $db->update($this->mTable, $data, "id='" . (int)$this->sliderid . "'") : $db->insert($this->mTable, $data);
			  $message = ($this->sliderid) ? PLG_VS_UPDATED : PLG_VS_ADDED;
			  
			  ($db->affected()) ? $wojosec->writeLog($message, "", "no", "plugin") . $core->msgOk($message) : $core->msgAlert(_SYSTEM_PROCCESS);
				 
		  } else
			  print $core->msgStatus();
	  }
	   

	  /**
	   * videoSlider::updateOrder()
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
		  
		  ($db->affected()) ? $core->msgOk(PLG_VS_SUPDATED) : $core->msgAlert(_SYSTEM_PROCCESS);
	  }
	  
	   
	  /**
	   * videoSlider::doUrl()
	   * 
	   * @return
	   */
	  public function doUrl($url)
	  {
		  $data = explode("?", $url);
		  
		  if(count($data) > 1) {
			  $yurl = $url.'&amp;wmode=opaque';
		  } else {
			  $yurl = $url.'?wmode=opaque';
		  }
		  
		  return $yurl;
	  }
  }
?>