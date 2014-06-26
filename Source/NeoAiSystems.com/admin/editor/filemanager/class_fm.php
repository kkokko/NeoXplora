<?php
  /**
   * Filemanager Class
   *
   * @version $Id: class_fm.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
  
  class Filemanager
  {
      private $base_dir;
      private $rel_dir;
      private $show_dir;
      private $cur_dir;
      private $dir_list = array();
      private $file_list = array();
      private $dir_count = 0;
      private $file_count = 0;
	  private $cdirs = 0;
	  private $cfiles = 0;
	  
	  protected $color;
	  protected $ext = array();
	  private $fsize = 0;
	  private $fileext = array(".gif", ".jpg", ".jpeg", ".bmp", ".png", ".txt", ".nfo", ".doc", ".docx", ".xls", ".xlsx", ".htm", ".html", ".zip", ".rar", ".tar", ".css", ".pdf", ".swf", ".avi", ".mp4", ".ogv", ".webm", ".mp3");
      
      
      
      /**
       * Filemanager::__construct()
       * 
       * @return
       */
      function __construct()
      {
          global $core;
          $this->base_dir = str_replace("\\", "/", UPLOADS);
          
          
          if (isset($_REQUEST['rel_dir'])) {
              $this->rel_dir = str_replace("../", "", $_REQUEST['rel_dir']);
              $this->rel_dir = str_replace(".", "", $_REQUEST['rel_dir']);
          } else
              $this->rel_dir = '';
          
          if (isset($_REQUEST['rel_dir'])) {
              if ($_REQUEST['rel_dir'] == $this->base_dir) {
                  $this->show_dir = $this->base_dir;
                  $_REQUEST['rel_dir'] = "";
              } else
                  $this->rel_dir = urldecode($_REQUEST['rel_dir']);
                  $this->show_dir = $this->base_dir . $_REQUEST['rel_dir'];
          } else
              $this->show_dir = $this->base_dir;
              $_REQUEST['rel_dir'] = "";
          
          $this->cur_dir = $this->base_dir . $this->rel_dir;
          $this->getDir();
          
          if (!is_dir($this->show_dir))
              die($core->msgError(_FM_NO_DIR1 . $this->show_dir . _FM_NO_DIR2, false));
			  
          if (!($dir = opendir($this->show_dir)))
              die($core->msgError(_FM_ACCESS1 . $this->show_dir . _FM_ACCESS2, false));
      }
	  
	  /**
	   * Filemanager::topNav()
	   * 
	   * @return
	   */
	  private function topNav()
	  {
		 $data = '
			  <div class="box">
				<div style="float:left"><img src="filemanager/images/folder.png" alt="" title="' . _FM_CURDIR . '" class="tooltip" style="margin-right:8px"/>
				<strong>' . _FM_CURDIR . ':</strong>&nbsp;&nbsp;' . $this->rel_dir . '</div>
				<div style="float:right"><a href="filemanager.php"><img src="filemanager/images/icons/home.png" alt="" title="Home" class="tooltip" style="margin-right:8px"/></a> 
				<a href="javascript:void(0);" id="create-dir" data-path="' . $this->rel_dir . '"><img src="filemanager/images/new-folder.png" title="' . _FM_NEWDIR . '" style="margin-right:8px"/></a>
				<a href="javascript:void(0);" id="' . $this->rel_dir . '" class="dirchange">
				<img src="filemanager/images/icons/refresh.png" alt="" title="' . _REFRESH . '" class="tooltip"/></a></div>
				<div class="clear"></div>
			  </div>';
			  
			  return $data;
	  }
	  
	  /**
	   * Filemanager::getNav()
	   * 
	   * @return
	   */
	  private function getNav()
	  {
		  $data = '<a href="';
		  $p_ar = explode("/", $this->rel_dir, strlen($this->rel_dir));
		  $p_dir = "";
		  
		  for ($i = 0; $i < count($p_ar) - 2; $i++) {
			  $p_dir = $p_dir . $p_ar[$i];
			  if ($i != count($p_ar) - 2)
				  $p_dir = $p_dir . "/";
		  }
		  
		  if ($p_dir == "") {
			  $data .= "filemanager.php";
			  $p_dir = "/";
		  } 
		  
		  $data .= '" class="dirchange" id="' . $p_dir . '" style="text-decoration: none;"><img src="filemanager/images/category.png" alt="" /><strong>...</strong>
		  </a>';
		  return $data;
	  }
	  
	  /**
	   * Filemanager::renderAll()
	   * 
	   * @return
	   */
	  public function renderAll()
	  {
		 print $this->topNav();
		 print '
			<form action="" method="post" name="admin_form" id="admin_form">
			<div style="height:390px;overflow:auto">
			  <table cellpadding="0" cellspacing="0" class="display" id="dataholder">
			  <thead>
				  <tr style="background-color:transparent">
					<td>' . $this->getNav() . '</td>
				  </tr>
				  </thead>
				<tbody>
				' . $this->renderDirectories() . '
				' . $this->renderFiles() . '
				  </tbody>
			  </table></div>
			   <div class="box">
				  <div class="fileuploader">
					<input type="text" class="filename" readonly="readonly"/>
					<input type="button" name="file" class="filebutton" value="'._BROWSE.'"/>
					<input type="file" name="newfile" size="30"/>
				  </div>
				  <div style="position:relative">
				  <input style="position:absolute;top:-28px;left:300px" name="dofile" id="fileupload" data-path="' . $this->rel_dir . '" type="submit" value="' . _FM_UPLOAD . '" class="button-sml"/>
				  </div>

			   <input name="filepath" type="hidden" value="' . $this->rel_dir . '" />
			   </div>
			</form>';
	  }
		
      /**
       * Filemanager::getDir()
       * 
       * @return
       */
      private function getDir()
      {
          if ($handle = opendir($this->cur_dir)) {
              while (false !== ($name = readdir($handle))) {
                  if ($name == ".." || $name == "." || $name == "index.php" || $name == "index.html" || $name == "Thumbs.db" || $name == ".htaccess")
                      continue;
                  
                  if (is_dir($this->show_dir . $name))
                      $this->dir_list[$this->dir_count++] = $name;
                  if (is_file($this->show_dir . $name))
                      $this->file_list[$this->file_count++] = $name;
              }
              closedir($handle);
          }
      }

	  /**
	   * Filemanager::renderDirectories()
	   * 
	   * @return
	   */
	  private function renderDirectories()
	  {
		  $data = '';

		  $data .= '<tr style="background-color:transparent"><td>';
		  for ($i = 0; $i < $this->dir_count; $i++) {
		  $data .= '<div class="thumbview"><div class="inner">';
		  $data .= '<a href="javascript:void(0);"';
		  if ($this->rel_dir == "") {
			  $path = $this->rel_dir . $this->dir_list[$i];
		  } else
			  $path = $this->rel_dir . $this->dir_list[$i];
		  
		  $data .= 'id="' . $path . '/" class="dirchange">';
		  $data .= '<img src="filemanager/images/icons/_Close.png" alt="folder" /></a></div>';
		  $data .= '<span>'.sanitize($this->dir_list[$i],15).'</span>';
		  $data .= '<p class="control"><a href="javascript:void(0);" id="' . $this->rel_dir . '" data-path="' . $this->dir_list[$i] . '" class="del-single">
		  <img src="filemanager/images/delete.png" alt="" title="'._DELETE.'"/></a></p>';	
		  $data .= '</div>';
		 }  
		$data .= '</td></tr>';
		$this->cdirs++;	

		  return $data;
	  }
	  
	  /**
	   * Filemanager::renderFiles()
	   * 
	   * @return
	   */
	  private function renderFiles()
	  {
		  sort($this->file_list);
		  $data = '';

		  $data .= '<tr style="background-color:transparent"><td>';
		  for ($i = 0; $i < $this->file_count; $i++) {
			$this->ext = explode(".", $this->file_list[$i], strlen($this->file_list[$i]));
			$extn = $this->ext[count($this->ext) - 1];
		  $data .= '<div class="thumbview"><div class="inner">';
		  $data .= '<a href="javascript:void(0);" class="getfile" data-path="' . $this->rel_dir . $this->file_list[$i] . '">';
		  if ($this->isimage($extn)) {
			$data .= '<img src="' . SITEURL . '/uploads/' . $this->rel_dir . $this->file_list[$i] . '" alt="" />';
		  } else {
			$data .= '<img src="filemanager/images/mime/large/' . $this->getFileType($extn) . '" alt="" />';
		  }
		  $data .='</a></div>';
		  $data .= '<span>'.sanitize($this->file_list[$i],13).'</span>';
		  $data .= '<p class="control"><a href="javascript:void(0);" id="' . $this->rel_dir . '" data-path="' . $this->file_list[$i] . '" class="del-single">
		  <img src="filemanager/images/delete.png" alt="" title="'._DELETE.'"/></a></p>';			
		  $data .= '</div>';
			}		 
		$this->cfiles++;
			$data .= ' </td>
			</tr>';
		  return $data;
	  }


	  /**
	   * Filemanager::isimage()
	   * 
	   * @param mixed $str
	   * @return
	   */
	  private function isimage($str)
      {
          $image_file = array("gif", "jpg", "jpeg", "png", "GIF", "JPG", "JPEG", "PNG");
          for ($f = 0; $f < count($image_file); $f++) {
              if ($str == $image_file[$f])
                  return true;
          }
          return false;
      }

      /**
       * Filemanager::delete()
       * 
	   * @param mixed $path
	   * @param bool $name
	   * @return
       */
	  public function delete($path, $name = '')
	  {
		  global $core;
		  if (is_dir($this->base_dir . $path)) {
			  if ($this->purge($this->base_dir . $path)) {
				  if ($name) {
					  $core->msgOK(_FM_DIR_DEL_OK1 . '<strong> ' . $name . ' </strong>' . _FM_DIR_DEL_OK2);
				  } else
					  return true;
			  } else
				  $core->msgOK(_FM_DIR_DEL_ERR . '<strong> ' . $name . ' </strong>');
		  } elseif (file_exists($this->base_dir . $path)) {
			  if (unlink($this->base_dir . $path)) {
				  if ($name) {
					  $core->msgOK(_FM_FILE_OK1 . '<strong> ' . $name . ' </strong>' . _FM_FILE_OK2);
				  } else
					  return true;
			  } else
				  $core->msgOK(_FM_FILE_ERR . '<strong> ' . $name . ' </strong>');
		  } else
			  $core->msgError(_FM_DEL_ERR2);
	  }

      /**
       * Filemanager::purge()
       * 
       * @param mixed $dir
       * @param bool $delroot
       * @return
       */
      private function purge($dir, $delroot = true)
      {
          if (!$dh = @opendir($dir))
              return;

          while (false !== ($obj = readdir($dh))) {
              if ($obj == '.' || $obj == '..' || $obj == 'index.php' || $obj == 'index.html')
                  continue;
              
              if (!@unlink($dir . '/' . $obj))
                  $this->purge($dir . '/' . $obj, true);
          }
          
          closedir($dh);
          
          if ($delroot)
              @rmdir($dir);
          return true;
      }

	  /**
	   * Filemanager::makeDirectory()
	   * 
	   * @param mixed $path
	   * @param mixed $name
	   * @param bool $multi
	   * @return
	   */
	  public function makeDirectory($path, $name, $multi = false)
      {
		  global $core;

		  if (mkdir($this->base_dir . $path . $name)) {
			  if (!$multi)
			     $core->msgOK(_FM_DIR_OK1 . '<strong> ' . $name . ' </strong>' . _FM_DIR_OK2);
		  } else
		      if (!$multi)
			  $core->msgError(_FM_DIR_ERR . '<strong> ' . $name . ' </strong>');
      }


	  /**
	   * Filemanager::uploadFile()
	   * 
	   * @param mixed $path
	   * @param mixed $name
	   * @return
	   */
	  public function uploadFile($path)
	  {
		  global $core;
		  
		  if (!is_dir($this->base_dir . $path))
			  die($core->msgError(_FM_UPLOAD_ERR1));
		  
		  if (!is_writeable($this->base_dir . $path))
			  die($core->msgError(_FM_UPLOAD_ERR2));
		  
		  $upldir = $this->base_dir . htmlspecialchars($path, ENT_QUOTES);
		  $newfile = $_FILES['newfile'];
		  $filename = $newfile['name'];
		  
		  $filename = str_replace(' ', '_', $filename);
		  $filetmp = $newfile['tmp_name'];
		  $filesize = $newfile['size'];
		  
		  $ext = strrchr($filename, '.');
		  if (!in_array(strtolower($ext), $this->fileext)) {
			  die($core->msgError(_FM_FILE . ' <strong> ' . $filename . ' </strong> ' . _FM_UPLOAD_ERR4));
		  }
		  
		  if (getimagesize($filetmp)) {
			  include(WOJOLITE . "lib/class_imageUpload.php");
			  include(WOJOLITE . "lib/class_imageResize.php");
			  
			  $filedir = $this->base_dir . $path;
			  $fileinfo = pathinfo($filedir . $_FILES['newfile']['name']);
			  $newName = paranoia($fileinfo['filename']);
			  
			  $bdp = new Upload();
			  $bdp->File = $_FILES['newfile'];
			  $bdp->method = 0;
			  $bdp->SavePath = $filedir;
			  $bdp->ThumbPath = $filedir;
			  $bdp->TWidth = $core->thumb_w;
			  $bdp->THeight = $core->thumb_h;
			  $bdp->NewWidth = $core->img_w;
			  $bdp->NewHeight = $core->img_h;
			  $bdp->ThumbPrefix = "thumb_";
			  $bdp->NewName = $newName;
			  $bdp->OverWrite = true;
			  $err = $bdp->UploadFile();
			  
			  if (count($err) > 0 and is_array($err)) {
				  foreach ($err as $key => $val)
					  $core->msgOk($val, false);
			  } else {
				  $core->msgOk(_FM_FILE . ' <strong> ' . $newName . ' </strong> ' . _FM_UPLOAD_ERR7);
			  }
		  }
		  
		  if (!is_uploaded_file($filetmp)) {
			  $core->msgs['file'] = _FM_FILE . ' <strong> ' . $filename . ' </strong> ' . _FM_UPLOAD_ERR3;
		  }
		  
		  if (file_exists($upldir . $filename)) {
			  $core->msgs['file'] = _FM_FILE . ' <strong> ' . $filename . ' </strong> ' . _FM_UPLOAD_ERR6;
		  }
		  
		  if (empty($core->msgs)) {
			  if(!getimagesize($filetmp)) {
				  if (move_uploaded_file($filetmp, $upldir . $filename)) {
					  $core->msgOk(_FM_FILE . ' <strong> ' . $filename . ' </strong> ' . _FM_UPLOAD_ERR7);
				  } else
					  $core->msgs['file'] = _FM_FILE . ' <strong> ' . $filename . ' </strong> ' . _FM_UPLOAD_ERR8;
			  }
		  } else
			  $core->msgStatus();
	  }

      
	  /**
	   * Filemanager::getSize()
	   * 
	   * @param mixed $size
	   * @param integer $precision
	   * @param bool $long_name
	   * @param bool $real_size
	   * @return
	   */
	  private function getSize($size, $precision = 2, $long_name = false, $real_size = true)
      {
          $base = $real_size ? 1024 : 1000;
          $pos = 0;
          while ($size > $base) {
              $size /= $base;
              $pos++;
          }
          $prefix = $this->_getSizePrefix($pos);
          @$size_name = ($long_name) ? $prefix . "bytes" : $prefix[0] . "B";
          return round($size, $precision) . ' ' . ucfirst($size_name);
      }
      
	  /**
	   * Filemanager::_getSizePrefix()
	   * 
	   * @param mixed $pos
	   * @return
	   */
	  private function _getSizePrefix($pos)
      {
          switch ($pos) {
              case 00:
                  return "";
              case 01:
                  return "kilo";
              case 02:
                  return "mega";
              case 03:
                  return "giga";
              default:
                  return "?-";
          }
      }
	  
	  
	  /**
	   * Filemanager::getFileType()
	   * 
	   * @param mixed $extn
	   * @return
	   */
	  private function getFileType($extn) {
		  
		switch ($extn) {
			case "css":
				return "css.png";
				break;
				
			case "csv":
				return "csv.png";
				break;
				
			case "fla":
			case "swf":
				return "fla.png";
				break;
				
			case "mp3":
			case "wav":
				return "mp3.png";
				break;

			case "jpg":
			case "JPG":
			case "jpeg":
				return "jpg.png";
				break;
				
			case "png":
				return "png.png";
				break;
				
			case "gif":
				return "gif.png";
				break;
				
			case "bmp":
			case "dib":
				return "bmp.png";
				break;
				
			case "txt":
			case "log":
				return "txt.png";
				break;
				
			case "sql":
				return "sql.png";
				break;
								
			case "js":
				echo "js.png";
				break;
				
			case "pdf":
				return "pdf.png";
				break;
				
			case "zip":
			case "rar":
			case "tgz":
			case "gz":
				return "zip.png";
				break;
				
			case "doc":
			case "docx":
			case "rtf":
				return "doc.png";
				break;
				
			case "asp":
			case "jsp":
				echo "asp.png";
				break;
				
			case "php":
				return "php.png";
				break;
				
			case "htm":
			case "html":
				return "htm.png";
				break;
				
			case "ppt":
				return "ppt.png";
				break;
				
			case "exe":
			case "bat":
			case "com":
				return "exe.png";
				break;
				
			case "wmv":
			case "mpg":
			case "mpeg":
			case "wma":
			case "asf":
				return "wmv.png";
				break;
				
			case "midi":
			case "mid":
				return "midi.png";
				break;
				
			case "mov":
				return "mov.png";
				break;
				
			case "psd":
				return "psd.png";
				break;
				
			case "ram":
			case "rm":
				return "rm.png";
				break;
				
			case "xml":
				return "xml.png";
				break;

			case "xls":
				return "xls.png";
				break;
				
			default:
				return "default.png";
				break;
		}	

	  }
  }
?>