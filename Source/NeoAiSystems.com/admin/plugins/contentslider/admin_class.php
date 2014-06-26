<?php
  /**
   * Slideout Class
   *
   * @version $Id: class_admin.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
  
  class ContentSlider
  {
      
	  private $mTable = "plug_content_slider";
	  public $sliderid = null;


      /**
       * ContentSlider::__construct()
       * 
       * @return
       */
      function __construct()
      {
          $this->getSliderId();
      }

	  /**
	   * ContentSlider::getSliderId()
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
				  $core->error("You have selected an Invalid SliderId","Slideout::getSliderId()");
			  } else
				  return $this->sliderid = $sliderid;
		  }
	  }
	  
	  /**
	   * ContentSlider::getSliderImages()
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
	   * ContentSlider::getSliderImages()
	   * 
	   * @return
	   */
	  public function processSliderImage()
	  {
		  global $db, $core, $wojosec;
		  
		  if (empty($_POST['title'.$core->dblang]))
			  $core->msgs['title'] = PLG_CS_CAPTION_R;
		  
		  if (empty($core->msgs)) {
			  $data = array(
					'title'.$core->dblang => sanitize($_POST['title'.$core->dblang]), 
					'description'.$core->dblang => $core->in_url($_POST['description'.$core->dblang]),
					'align' => intval($_POST['align'])
			  );
			  
              // Procces Image
			  $file = getValue("filename",$this->mTable,"id = '".$this->sliderid."'");
			  if (!empty($_FILES['filename']['name'])) {
				  $filedir = WOJOLITE . "plugins/contentslider/slides/";
				  $newName = "FILE_" . randName();
				  $ext = substr($_FILES['filename']['name'], strrpos($_FILES['filename']['name'], '.') + 1);
				  $fullname = $filedir . $newName.".".strtolower($ext);				  
				  
				  if ($file)
					  @unlink($filedir . $file);
				  $res = move_uploaded_file($_FILES['filename']['tmp_name'], $fullname);
				  $data['filename'] = $newName.".".strtolower($ext);	
			  } else {
				  $data['filename'] = $file;
			  }
			  
			  ($this->sliderid) ? $db->update($this->mTable, $data, "id='" . (int)$this->sliderid . "'") : $db->insert($this->mTable, $data);
			  $message = ($this->sliderid) ? PLG_CS_UPDATED : PLG_CS_ADDED;
			  
			  ($db->affected()) ? $wojosec->writeLog($message, "", "no", "plugin") . $core->msgOk($message) : $core->msgAlert(_SYSTEM_PROCCESS);
				 
		  } else
			  print $core->msgStatus();
	  }
	   
	  /**
	   * ContentSlider::updateOrder()
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
		  
		  ($db->affected()) ? $core->msgOk(PLG_CS_SUPDATED) : $core->msgAlert(_SYSTEM_PROCCESS);
	  }
  }
?>