<?php
  /**
   * Gallery Class
   *
   * @version $Id: class_admin.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
  
  class Gallery
  {
	  
	  private $mTable = "mod_gallery_images";
	  private $cTable = "mod_gallery_config";
	  public $galid = null;
	  public $galpath = "modules/gallery/galleries/";
	  const maxFile = 3145728;
	  private static $fileTypes = array("jpg","jpeg","png");


      /**
       * Gallery::__construct()
       * 
       * @param bool $galid
       * @return
       */
      function __construct($galid = false)
      {
          $this->getGalleryId();
		  $this->getConfig($galid);
      }

	  /**
	   * Gallery::getGalleryId()
	   * 
	   * @return
	   */
	  private function getGalleryId()
	  {
	  	  global $core;
		  if (isset($_GET['galid'])) {
			  $galid = (is_numeric($_GET['galid']) && $_GET['galid'] > -1) ? intval($_GET['galid']) : false;
			  $galid = sanitize($galid);
			  
			  if ($galid == false) {
				  $core->error("You have selected an Invalid CommentId","newsSlider::getCommentId()");
			  } else
				  return $this->galid = $galid;
		  }
	  }
	  
	  /**
	   * Gallery::getConfig()
	   * 
	   * @param bool $galid
	   * @return
	   */
	  private function getConfig($galid = false)
	  {
		  global $db, $core;
		  $id = ($galid) ? $galid : $this->galid;
		  $sql = "SELECT * FROM " . $this->cTable . " WHERE id = '" . $id . "'";
          $row = $db->first($sql);
          
          $this->title = $row['title'.$core->dblang];
		  $this->folder = $row['folder'];
		  $this->image_w = $row['image_w'];
		  $this->image_h = $row['image_h'];
		  $this->watermark = $row['watermark'];
		  $this->method = $row['method'];
		  $this->created = $row['created'];
	  }

	  /**
	   * Gallery::getGalleries()
	   * 
	   * @return
	   */
	  public function getGalleries()
	  {
		  global $db, $core;
		  
		  $sql = "SELECT *, DATE_FORMAT(created, '" . $core->long_date . "') as date,"
		  . "\n (SELECT COUNT(" . $this->mTable . ".gallery_id) FROM " . $this->mTable . " WHERE " . $this->mTable . ".gallery_id = " . $this->cTable . ".id) as totalpics"
		  . "\n FROM " . $this->cTable
		  . "\n ORDER BY title".$core->dblang;
          $row = $db->fetch_all($sql);
          
		  return ($row) ? $row : 0;
	  }	

	  /**
	   * Gallery::updateConfig()
	   * 
	   * @return
	   */
	  public function updateConfig()
	  {
		  global $db, $core, $wojosec;
		  
		  if (empty($_POST['title'.$core->dblang]))
			  $core->msgs['title'] = MOD_GA_NAME_R;

		  if (empty($_POST['image_h']))
			  $core->msgs['image_h'] = MOD_GA_IMG_H_R;	
			  
		  if(!$this->galid) {
			  if (empty($_POST['folder']))
				  $core->msgs['folder'] = MOD_GA_FOLDER_R;
		  }
			  			  			  		  
		  if (empty($core->msgs)) {
			  $data = array(
					  'title'.$core->dblang => sanitize($_POST['title'.$core->dblang]), 
					  'image_w' => intval($_POST['image_w']),
					  'image_h' => intval($_POST['image_h']),
					  'method' => intval($_POST['method']),
					  'watermark' => intval($_POST['watermark'])
			  );
			  if(!$this->galid) {
				  $data['folder'] = paranoia($_POST['folder']);
				  $data['created'] = "NOW()";
			  }

			  ($this->galid) ? $res = $db->update($this->cTable, $data, "id='" . (int)$this->galid . "'") : $res = $db->insert($this->cTable, $data);
			  $message = ($this->galid) ? MOD_GA_UPDATED : MOD_GA_ADDED;

			  if(!$this->galid) {
				  if(!is_dir(WOJOLITE . $this->galpath . $data['folder'])){
					  mkdir(WOJOLITE . $this->galpath . $data['folder'], 0755);
					  chmod(WOJOLITE . $this->galpath . $data['folder'], 0755);
				  }
				  /*
				  if(!is_dir(WOJOLITE . $this->galpath . $data['folder'] . "/thumbs")){
					  mkdir(WOJOLITE . $this->galpath . $data['folder'] . "/thumbs", 0755);
					  chmod(WOJOLITE . $this->galpath . $data['folder'] . "/thumbs", 0755);
				  }
				  */
			  }
		
			  ($db->affected()) ? $wojosec->writeLog($message, "", "no", "module") . $core->msgOk($message) :  $core->msgAlert(_SYSTEM_PROCCESS);

		  } else
			  print $core->msgStatus();
	  }

	  /**
	   * Gallery::getGalleryImages()
	   * 
	   * @param bool $galid
	   * @return
	   */
	  public function getGalleryImages($galid = false)
	  {
		  global $db;
		  
		  $id = ($galid) ? $galid : $this->galid;
		  
		  $sql = "SELECT * FROM " . $this->mTable
		  . "\n WHERE gallery_id = '".(int)$id."'"
		  . "\n ORDER BY sorting";
          $row = $db->fetch_all($sql);
          
		  return ($row) ? $row : 0;
	  }	
	  
	  /**
	   * Gallery::loadPhotos()
	   * 
	   * @return
	   */

	  public function loadPhotos($gid, $gfolder)
	  {
		  global $core;
		  
		  if ($picrow = $this->getGalleryImages($gid)) {
			  foreach ($picrow as $row) {
				  print '
					<div class="gallview" id="gid_' .$row['id'] . '">
					  <div class="gal-inner">';
					  print '
					  <figure>
						<img src="' . SITEURL . '/modules/gallery/thumbmaker.php?src=' . SITEURL.'/'.$this->galpath . $gfolder.'/'.$row['thumb'] . '&amp;w=280&amp;h=160" alt="" class="galimg" />                      </figure>';
					  print '
					  <div class="title">
					  <a href="javascript:void(0);" data-title="' . $row['title'.$core->dblang] . '"  class="delete" id="item_' . $row['id'].'::' . $gfolder . '">
					  <img src="images/trash.png" alt="" title="' .  _DELETE . '" class="tooltip"/></a>
					  <a href="javascript:void(0);" data-title="' . $row['title'.$core->dblang] . '" data-desc="' . $row['description'.$core->dblang] . '" class="edit" id="list_' . $row['id'] . '">
					  <img src="images/pencil.png" alt="" title="' . _EDIT . '" class="tooltip"/></a>' . character_limiter($row['title'.$core->dblang],50) . '
					  </div>
					</div>
				</div>';
			  }
		  }
	  }
	  
	  
	  /**
	   * Gallery::doUpload()
	   * 
	   * @return
	   */
	  public function doUpload($gid, $gfolder)
	  {
		  global $db, $core;
		  
		  if (self::validateUpload($gfolder) == true) {
              $filedir = WOJOLITE . $this->galpath . '/' . $gfolder . '/';
			  $newName = "IMG_" . randName();
			  $ext = substr($_FILES['filedata']['name'], strrpos($_FILES['filedata']['name'], '.') + 1);
			  $fullname = $filedir . $newName . "." . strtolower($ext);
			  move_uploaded_file($_FILES['filedata']['tmp_name'], $fullname);

			  $data = array(
					'gallery_id' => $gid, 
					'thumb' => $newName . "." . strtolower($ext), 
					'title' . $core->dblang => "-/-", 
					'description' . $core->dblang => "-/-"
					);
			  
			  $db->insert($this->mTable, $data);
			  
			  self::doJason(array(
				  "success" => true,
				  "id" => $_POST["fileid"],
				  "instanceid" => self::isXhrMethod() ? "" : $_POST["instanceid"],
				  "file" => array(
					  "name" => $_FILES["filedata"]["name"],
					  "mime" => $_FILES["filedata"]["type"],
					  "size" => $_FILES["filedata"]["size"],
					  "id" => $_POST["fileid"])));
		  }
	  }
	  
	  /**
	   * Gallery::doJason()
	   * 
	   * @param mixed $result
	   * @return
	   */
	  public static function doJason($result)
	  {
		  $json = json_encode($result);
	
		  if (self::isXhrMethod()) {
			  header("Content-Type: application/json");
			  echo $json;
		  } else {
			  $instanceid = $result['instanceid'];
            echo "
			<script type=\"text/javascript\">
				parent.jQuery.fn.FileUploader.Instances['" . $instanceid."'].onComplete(eval('(".$json.")'));
			</script>";
            }
	  }

	  /**
	   * Gallery::getFileExt()
	   * 
	   * @return
	   */
	  private static function getFileExt()
	  {
		  $name = $_FILES["filedata"]["name"];
		  $parts = explode(".", $name);
		  $last = sizeof($parts) - 1;
	
		  return (sizeof($parts) < 2) ? "" : strtolower($parts[$last]);
	  }

	  /**
	   * Gallery::isXhrMethod()
	   * 
	   * @return
	   */
	  private static function isXhrMethod()
	  {
		  return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
	  }

	  /**
	   * Gallery::isPostMethod()
	   * 
	   * @return
	   */
	  private static function isPostMethod()
	  {
		  return ($_SERVER["REQUEST_METHOD"] == "POST" or self::isXhrMethod());
	  }

	  /**
	   * Gallery::validateUpload()
	   * 
	   * @return
	   */
	  private static function validateUpload($gfolder)
	  {
		  if (!self::isPostMethod()) {
			  self::doJason(array(
				  "success" => false, 
				  "message" => "This request type is not supported"));
			  return false;
		  }
	
		  if (isset($_POST["fileid"]) == false) {
			  self::doJason(array(
				  "success" => false, 
				  "message" => "No file was identified"));
			  return false;
		  }
	
		  if (sizeof($_FILES) == 0) {
			  self::doJason(array(
				  "success" => false,
				  "message" => "No file can be detected",
				  "instanceid" => $_POST["instanceid"],
				  "id" => $_POST["fileid"]));
			  return false;
		  }
	
		  if (self::maxFile != null && self::maxFile < $_FILES["filedata"]["size"]) {
			  self::doJason(array(
				  "success" => false,
				  "message" => str_replace("[LIMIT]", getSize($this->maxFile), MOD_GA_ERRFILESIZE_T),
				  "id" => $_POST["fileid"],
				  "instanceid" => self::isXhrMethod() ? "" : $_POST["instanceid"],
				  "file" => array(
					  "name" => $_FILES["filedata"]["name"],
					  "mime" => $_FILES["filedata"]["type"],
					  "size" => $_FILES["filedata"]["size"],
					  "id" => $_POST["fileid"])));
	
			  return false;
		  }
	
		  if (self::$fileTypes != null && in_array(self::getFileExt(), self::$fileTypes) == false) {
			  self::doJason(array(
				  "success" => false,
				  "message" => MOD_GA_ERRFILETYPE_T,
				  "id" => $_POST["fileid"],
				  "instanceid" => self::isXhrMethod() ? "" : $_POST["instanceid"],
				  "file" => array(
					  "name" => $_FILES["filedata"]["name"],
					  "mime" => $_FILES["filedata"]["type"],
					  "size" => $_FILES["filedata"]["size"],
					  "id" => $_POST["fileid"])));
	
			  return false;
		  }
	
		  if (!is_dir(WOJOLITE . 'modules/gallery/galleries/' . $gfolder . '/')) {
			  self::doJason(array(
				  "success" => false, 
				  "message" => MOD_GA_UPLDIR,
				  "id" => $_POST["fileid"],
				  "instanceid" => self::isXhrMethod() ? "" : $_POST["instanceid"],
				  "file" => array(
					  "name" => $_FILES["filedata"]["name"],
					  "mime" => $_FILES["filedata"]["type"],
					  "size" => $_FILES["filedata"]["size"],
					  "id" => $_POST["fileid"])));
	
			  return false;
		  }
	
		  if (!is_writeable(WOJOLITE . 'modules/gallery/galleries/' . $gfolder . '/')) {
			  self::doJason(array(
				  "success" => false, 
				  "message" => str_replace("[DIRNAME]", 'modules/gallery/galleries/' . $gfolder . '/', MOD_GA_DIRNW),
				  "id" => $_POST["fileid"],
				  "instanceid" => self::isXhrMethod() ? "" : $_POST["instanceid"],
				  "file" => array(
					  "name" => $_FILES["filedata"]["name"],
					  "mime" => $_FILES["filedata"]["type"],
					  "size" => $_FILES["filedata"]["size"],
					  "id" => $_POST["fileid"])));
	
			  return false;
		  }
		  
		  return true;
	  }
  }
?>