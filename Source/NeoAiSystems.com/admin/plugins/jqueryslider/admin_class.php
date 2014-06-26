<?php
  /**
   * jQuerySlider Class
   *
   * @version $Id: class_admin.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
  
  class jQuerySlider
  {
      
	  private $mTable = "plug_slider";
	  private $cTable = "plug_slider_config";
	  public $sliderid = null;


      /**
       * jQuerySlider::__construct()
       * 
       * @return
       */
      function __construct()
      {
          $this->getSliderId();
      }

	  /**
	   * jQuerySlider::getSliderId()
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
	   * jQuerySlider::getSliderImages()
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
	   * jQuerySlider::getSliderImages()
	   * 
	   * @return
	   */
	  function processSliderImage()
	  {
		  global $db, $core, $wojosec;
		  
		  if (empty($_POST['title'.$core->dblang]))
			  $core->msgs['title'] = PLG_JQ_CAPTION_R;
		  
		  if (!$this->sliderid) {
			  if (empty($_FILES['filename']['name']))
				  $core->msgs['name'] = PLG_JQ_IMGFILE_R;
		  }
		  
		  if (empty($core->msgs)) {
			  $data['title'.$core->dblang] = sanitize($_POST['title'.$core->dblang]);
			  $data['description'.$core->dblang] = sanitize($_POST['description'.$core->dblang]);
			  
			  if (isset($_POST['urltype'])&& $_POST['urltype'] == "internal" && isset($_POST['page_id'])) {
				  $slug = getValue("slug","pages","id = '".(int)$_POST['page_id']."'");
				  $data['url'] = createPageLink($slug, true);
				  $data['urltype'] = "int";
				  $data['page_id'] = intval($_POST['page_id']);
			  } elseif (isset($_POST['urltype'])&& $_POST['urltype'] == "external" && isset($_POST['url'])) {
				  $data['url'] = sanitize($_POST['url']);
				  $data['urltype'] = "ext";
				  $data['page_id'] = "DEFAULT(page_id)";
			  } else {
				  $data['url'] = "#";
				  $data['urltype'] = "ext";
				  $data['page_id'] = "DEFAULT(page_id)";
			  }
			  
              // Procces Image
			  $file = getValue("filename",$this->mTable,"id = '".$this->sliderid."'");
			  if (!empty($_FILES['filename']['name'])) {
				  $filedir = WOJOLITE . "plugins/jqueryslider/slides/";
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
			  $message = ($this->sliderid) ? PLG_JQ_UPDATED : PLG_JQ_ADDED;
			  
			  ($db->affected()) ? $wojosec->writeLog($message, "", "no", "plugin") . $core->msgOk($message) : $core->msgAlert(_SYSTEM_PROCCESS);
				 
		  } else
			  print $core->msgStatus();
	  }

	  /**
	   * jQuerySlider::getConfiguration()
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
	   * jQuerySlider::getSliderImages()
	   * 
	   * @return
	   */
	  function updateConfiguration()
	  {
		  global $db, $core, $wojosec;
		  
		  if ($_POST['animation'] == "")
			  $core->msgs['animation'] = PLG_JQ_ANI_SPEED_R;
		  
		  if ($_POST['anispeed'] == "")
			  $core->msgs['anispeed'] = PLG_JQ_ANI_TIME_R;
		  
		  if (empty($this->msgs)) {
			  $data = array(
					'animation' => sanitize($_POST['animation']), 
					'anispeed' => intval($_POST['anispeed']),
					'anitime' => intval($_POST['anitime']),
					'shownav' => intval($_POST['shownav']),
					'shownavhide' => intval($_POST['shownavhide']),
					'controllnav' => intval($_POST['controllnav']),
					'hoverpause' => intval($_POST['hoverpause']),
					'showcaption' => intval($_POST['showcaption'])
			  );
			  $db->update($this->cTable, $data);
			  
			  ($db->affected()) ? $wojosec->writeLog(PLG_JQ_CONF_UPDATED, "", "no", "plugin") . $core->msgOk(PLG_JQ_CONF_UPDATED) :  $core->msgAlert(_SYSTEM_PROCCESS);
		  } else
			  print $core->msgStatus();
	  }
	   
	  /**
	   * jQuerySlider::updateOrder()
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
		  
		  ($db->affected()) ? $core->msgOk(PLG_JQ_SUPDATED) : $core->msgAlert(_SYSTEM_PROCCESS);
	  }
  }
?>