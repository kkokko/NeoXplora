<?php
  /**
   * Elastic Slider Class
   *
   * @version $Id: class_admin.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
  
  class elasticSlider
  {
      
	  private $mTable = "plug_elastic";
	  private $cTable = "plug_elastic_config";
	  public $sliderid = null;


      /**
       * elasticSlider::__construct()
       * 
       * @return
       */
      function __construct()
      {
          $this->getSliderId();
      }

	  /**
	   * elasticSlider::getSliderId()
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
				  $core->error("You have selected an Invalid SliderId","elasticSlider::getSliderId()");
			  } else
				  return $this->sliderid = $sliderid;
		  }
	  }
	  
	  /**
	   * elasticSlider::getSliderImages()
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
	   * elasticSlider::getSliderImages()
	   * 
	   * @return
	   */
	  function processSliderImage()
	  {
		  global $db, $core, $wojosec;
		  
		  if (empty($_POST['title'.$core->dblang]))
			  $core->msgs['title'] = PLG_JQE_CAPTION_R;
		  
		  if (!$this->sliderid) {
			  if (empty($_FILES['filename']['name']))
				  $core->msgs['name'] = PLG_JQE_IMGFILE_R;
		  }
		  
		  if (empty($core->msgs)) {
			  $data['title'.$core->dblang] = sanitize($_POST['title'.$core->dblang]);
			  $data['description'.$core->dblang] = sanitize($_POST['description'.$core->dblang]);
			  
              // Procces Image
			  $file = getValue("filename",$this->mTable,"id = '".$this->sliderid."'");
			  if (!empty($_FILES['filename']['name'])) {
				  $filedir = WOJOLITE . "plugins/elastic/slides/";
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
			  $message = ($this->sliderid) ? PLG_JQE_UPDATED : PLG_JQE_ADDED;
			  
			  ($db->affected()) ? $wojosec->writeLog($message, "", "no", "plugin") . $core->msgOk($message) : $core->msgAlert(_SYSTEM_PROCCESS);
				 
		  } else
			  print $core->msgStatus();
	  }

	  /**
	   * elasticSlider::getConfiguration()
	   * 
	   * @return
	   */
	  public function getConfiguration()
	  {
		  global $db;
		  
		  $sql = "SELECT * FROM " . $this->cTable;
		  $row = $db->first($sql);
		  
		  return ($row) ? $row : 0;
	  }
	   
	  /**
	   * elasticSlider::getSliderImages()
	   * 
	   * @return
	   */
	  function updateConfiguration()
	  {
		  global $db, $core, $wojosec;
		  
		  if ($_POST['interval'] == "")
			  $core->msgs['interval'] = PLG_JQE_INTERVAL_R;
		  
		  if ($_POST['speed'] == "")
			  $core->msgs['speed'] = PLG_JQE_SPEED_R;
		  
		  if (empty($this->msgs)) {
              $data = array(
                  'animation' => sanitize($_POST['animation']),
                  'autoplay' => intval($_POST['autoplay']),
                  'interval' => intval($_POST['interval']),
                  'speed' => intval($_POST['speed']),
                  'titlespeed' => empty($_POST['titlespeed']) ? 800 : intval($_POST['titlespeed']),
				  'thumbMaxWidth' => empty($_POST['thumbMaxWidth']) ? 200 : intval($_POST['thumbMaxWidth']),
                  'height' => empty($_POST['height']) ? 350 : intval($_POST['height'])
				  );
				  
			  $db->update($this->cTable, $data);
			  
			  ($db->affected()) ? $wojosec->writeLog(PLG_JQE_CONF_UPDATED, "", "no", "plugin") . $core->msgOk(PLG_JQE_CONF_UPDATED) :  $core->msgAlert(_SYSTEM_PROCCESS);
		  } else
			  print $core->msgStatus();
	  }
	   
	  /**
	   * elasticSlider::updateOrder()
	   * 
	   * @return
	   */
	  public function updateOrder()
	  {
		  global $db, $core;
		  	  
		  foreach ($_POST['node'] as $k => $v) {
			  $p = $k + 1;
			  $data['position'] = intval($p);
			  $res = $db->update($this->mTable, $data, "id='" . (int)$v . "'");
		  }
		  
		  ($res) ? $core->msgOk(PLG_JQE_SUPDATED) : $core->msgAlert(_SYSTEM_PROCCESS);
	  }
  }
?>