<?php
  /**
   * Functions
   *
   * @version $Id: functions.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
  
  /**
   * redirect_to()
   * 
   * @param mixed $location
   * @return
   */
  function redirect_to($location)
  {
      if (!headers_sent()) {
          header('Location: ' . $location);
		  exit;
	  } else
          echo '<script type="text/javascript">';
          echo 'window.location.href="' . $location . '";';
          echo '</script>';
          echo '<noscript>';
          echo '<meta http-equiv="refresh" content="0;url=' . $location . '" />';
          echo '</noscript>';
  }
  
  /**
   * countEntries()
   * 
   * @param mixed $table
   * @param string $where
   * @param string $what
   * @return
   */
  function countEntries($table, $where = '', $what = '')
  {
      global $db;
      if (!empty($where) && isset($what)) {
          $q = "SELECT COUNT(*) FROM " . $table . "  WHERE " . $where . " = '" . $what . "' LIMIT 1";
      } else
          $q = "SELECT COUNT(*) FROM " . $table . " LIMIT 1";
      
      $record = $db->query($q);
      $total = $db->fetchrow($record);
      return $total[0];
  }
  
  /**
   * getChecked()
   * 
   * @param mixed $row
   * @param mixed $status
   * @return
   */
  function getChecked($row, $status)
  {
      if ($row == $status) {
          echo "checked=\"checked\"";
      }
  }
  
  /**
   * post()
   * 
   * @param mixed $var
   * @return
   */
  function post($var)
  {
      if (isset($_POST[$var]))
          return $_POST[$var];
  }
  
  /**
   * get()
   * 
   * @param mixed $var
   * @return
   */
  function get($var)
  {
      if (isset($_GET[$var]))
          return $_GET[$var];
  }
  
  /**
   * sanitize()
   * 
   * @param mixed $string
   * @param bool $trim
   * @return
   */
  function sanitize($string, $trim = false, $int = false, $str = false)
  {
      $string = filter_var($string, FILTER_SANITIZE_STRING);
      $string = trim($string);
      $string = stripslashes($string);
      $string = strip_tags($string);
      $string = str_replace(array('‘', '’', '“', '”'), array("'", "'", '"', '"'), $string);
      
	  if ($trim)
          $string = substr($string, 0, $trim);
      if ($int)
		  $string = preg_replace("/[^0-9\s]/", "", $string);
      if ($str)
		  $string = preg_replace("/[^a-zA-Z\s]/", "", $string);
		  
      return $string;
  }

  /**
   * cleanSanitize()
   * 
   * @param mixed $string
   * @param bool $trim
   * @return
   */
  function cleanSanitize($string, $trim = false,  $end_char = '&#8230;')
  {
	  $string = cleanOut($string);
      $string = filter_var($string, FILTER_SANITIZE_STRING);
      $string = trim($string);
      $string = stripslashes($string);
      $string = strip_tags($string);
      $string = str_replace(array('‘', '’', '“', '”'), array("'", "'", '"', '"'), $string);
      
	  if ($trim) {
        if (strlen($string) < $trim)
        {
            return $string;
        }

        $string = preg_replace("/\s+/", ' ', str_replace(array("\r\n", "\r", "\n"), ' ', $string));

        if (strlen($string) <= $trim)
        {
            return $string;
        }

        $out = "";
        foreach (explode(' ', trim($string)) as $val)
        {
            $out .= $val.' ';

            if (strlen($out) >= $trim)
            {
                $out = trim($out);
                return (strlen($out) == strlen($string)) ? $out : $out.$end_char;
            }       
        }
	  }
      return $string;
  }

  /**
   * character_limiter()
   * 
   * @param mixed $str
   * @param int $n
   * @param mixed $end_char
   * @return
   */
  function character_limiter($str, $n = 100, $end_char = '&#8230;')
  {
	  if (strlen($str) < $n)
	  {
		  return $str;
	  }

	  $str = preg_replace("/\s+/", ' ', str_replace(array("\r\n", "\r", "\n"), ' ', $str));

	  if (strlen($str) <= $n)
	  {
		  return $str;
	  }

	  $out = "";
	  foreach (explode(' ', trim($str)) as $val)
	  {
		  $out .= $val.' ';

		  if (strlen($out) >= $n)
		  {
			  $out = trim($out);
			  return (strlen($out) == strlen($str)) ? $out : $out.$end_char;
		  }       
	  }
  }
 
  /**
   * getValue()
   * 
   * @param mixed $stwhatring
   * @param mixed $table
   * @param mixed $where
   * @return
   */
  function getValue($what, $table, $where)
  {
      global $db;
      $sql = "SELECT $what FROM $table WHERE $where";
      $row = $db->first($sql);
      return $row[$what];
  } 

  /**
   * getValueById()
   * 
   * @param mixed $what
   * @param mixed $table
   * @param mixed $id
   * @return
   */
  function getValueById($what, $table, $id)
  {
      global $db;
	  
      $sql = "SELECT $what FROM $table WHERE id = $id";
      $row = $db->first($sql);
      return ($row) ? $row[$what] : '';
  }
  
  /**
   * self()
   * 
   * @return
   */
  function self()
  {
      return htmlspecialchars($_SERVER['PHP_SELF']);
  }
  
  /**
   * tooltip()
   * 
   * @param mixed $tip
   * @return
   */
  function tooltip($tip, $front = false)
  {
	  $url = ($front) ? THEMEURL : ADMINURL;
	  
      return '<img src="' . $url . '/images/info2.png" alt="Tip" class="tooltip" title="' . $tip . '" />';
  }
  
  /**
   * required()
   * 
   * @return
   */
  function required($front = false)
  {
	  $url = ($front) ? THEMEURL : ADMINURL;
      return '<img src="' . $url . '/images/required.png" alt="'._REQ_FIELD.'" class="tooltip" title="'._REQ_FIELD.'" />';
  }

  /**
   * createPageLink()
   * 
   * @param mixed $slug
   * @return
   */
  function createPageLink($slug, $nourl = false)
  {
      global $db, $core;
	  
      $sql = "SELECT slug FROM pages WHERE slug = '".sanitize($slug,100)."'";
      $row = $db->first($sql);
      
      if ($core->seo == 1) {
		  $display = ($nourl) ? $row['slug'].'.html' : SITEURL . '/' . $row['slug'].'.html';
      } else {
		  $display = ($nourl) ? 'content.php?pagename=' . $row['slug'] : SITEURL . '/content.php?pagename=' . $row['slug'];
      }
      return $display;
  }
  
  /**
   * stripTags()
   * 
   * @param mixed $start
   * @param mixed $end
   * @param mixed $string
   * @return
   */
  function stripTags($start, $end, $string)
  {
	  $string = stristr($string, $start);
	  $doend = stristr($string, $end);
	  return substr($string, strlen($start), -strlen($doend));
  }
  
  /**
   * getTemplates()
   * 
   * @param mixed $dir
   * @param mixed $site
   * @return
   */
  function getTemplates($dir, $site)
  {
      $getDir = dir($dir);
      while (false !== ($templDir = $getDir->read())) {
          if ($templDir != "." && $templDir != ".." && $templDir != "index.php") {
              $selected = ($site == $templDir) ? " selected=\"selected\"" : "";
              echo "<option value=\"{$templDir}\"{$selected}>{$templDir}</option>\n";
          }
      }
      $getDir->close();
  }
  
  /**
   * stripExt()
   * 
   * @param mixed $filename
   * @return
   */ 
  function stripExt($filename)
  {
      if (strpos($filename, ".") === false) {
          return ucwords($filename);
      } else
          return substr(ucwords($filename), 0, strrpos($filename, "."));
  }
  
  /**
   * loadEditor()
   * 
   * @param mixed $field
   * @param mixed $value
   * @param mixed $width
   * @param mixed $height
   * @param mixed $toolbar
   * @param mixed $var
   * @return
   */
  function loadEditor($field, $width = "100%", $height = "450", $var = "oEdit1")
  {
	  print '
		  <script type="text/javascript">
		    // <![CDATA[
			var '.$var.' = new InnovaEditor("'.$var.'");
			'.$var.'.width="'.$width.'";
			'.$var.'.height='.$height.';
			'.$var.'.enableFlickr = false;
			'.$var.'.enableCssButtons = false;
			'.$var.'.flickrUser = "";
			'.$var.'.returnKeyMode = 2;
			'.$var.'.arrCustomButtons = [
			["CustomName1","modalDialog(\'editor/scripts/common/paypal.htm\',350,270)","PayPal Button","btnPayPal.gif"],
			["HTML5Video", "modalDialog(\'editor/scripts/common/webvideo.htm\',750,550,\'HTML5 Video\');", "HTML5 Video", "btnVideo.png"]
			];
			'.$var.'.toolbarMode = 2;
			'.$var.'.groups=[
			["grpEdit", "", ["SourceDialog", "FullScreen", "SearchDialog", "RemoveFormat", "BRK", "Undo", "Redo", "Cut", "Copy", "Paste"]],
			["grpFont", "", ["FontName", "FontSize", "Strikethrough", "Superscript", "BRK", "Bold", "Italic", "Underline", "ForeColor", "BackColor"]],
			["grpPara", "", ["CompleteTextDialog", "Quote", "Indent", "Outdent", "Styles", "StyleAndFormatting", "Absolute", "BRK", "JustifyLeft", "JustifyCenter", "JustifyRight", "JustifyFull", "Numbering", "Bullets"]],
			["grpInsert", "", ["LinkDialog", "BRK", "ImageDialog", "Form"]],
			["grpTables", "", ["TableDialog", "BRK", "Guidelines", "Guidelines", "CustomName1"]],
			["grpMedia", "", ["Media", "FlashDialog", "YoutubeDialog", "HTML5Video", "BRK", "CustomTag", "CharsDialog", "Line"]]
			];
			
			'.$var.'.css="'.THEMEURL.'/css/custom.css";
			'.$var.'.fileBrowser = "../../filemanager.php";
			'.$var.'.arrCustomTag=[
			["First Last Name","[NAME]"],
			["Username","[USERNAME]"],
			["Site Name","[SITE_NAME]"],
			["Site Url","[URL]"]
			];
			'.$var.'.customColors=["#ff4500","#ffa500","#808000","#4682b4","#1e90ff","#9400d3","#ff1493","#a9a9a9"];
			'.$var.'.mode="XHTMLBody";
			'.$var.'.REPLACE("'.$field.'");
			// ]]>
		  </script>
		  ';
  }

  /**
   * cleanOut()
   * 
   * @param mixed $text
   * @return
   */
  function cleanOut($text) {
	 $text =  strtr($text, array('\r\n' => "", '\r' => "", '\n' => ""));
	 $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
	 $text = str_replace('<br>', '<br />', $text);
	 return stripslashes($text);
  }
    
  /**
   * isActive()
   * 
   * @param mixed $id
   * @return
   */
  function isActive($id)
  {
	  if ($id == 1) {
		  $display = '<img src="images/yes.png" alt="" class="tooltip" title="'._PUBLISHED.'"/>';
	  } else {
		  $display = '<img src="images/no.png" alt="" class="tooltip" title="'._NOTPUBLISHED.'"/>';
	  }

      return $display;;
  }

  /**
   * isAdmin()
   * 
   * @param mixed $id
   * @return
   */
  function isAdmin($userlevel)
  {
	  if ($userlevel == 9) {
		  $display = '<img src="images/superadmin.png" alt="" class="tooltip" title="Super Admin"/>';
	  } elseif ($userlevel == 8) {
		  $display = '<img src="images/admin.png" alt="" class="tooltip" title="Admin"/>';
	  } else {
		  $display = '<img src="images/user.png" alt="" class="tooltip" title="User"/>';
	  }

      return $display;;
  }

  /**
   * userStatus()
   * 
   * @param mixed $id
   * @return
   */
  function userStatus($status)
  {
	  switch ($status) {
		  case "y":
			  $display = '<img src="images/u_active.png" alt="" class="tooltip" title="'._USER_A.'"/>';
			  break;
			  
		  case "n":
			  $display = '<img src="images/u_inactive.png" alt="" class="tooltip" title="'._USER_I.'"/>';
			  break;
			  
		  case "t":
			  $display = '<img src="images/u_pending.png" alt="" class="tooltip" title="'._USER_P.'"/>';
			  break;
			  
		  case "b":
			  $display = '<img src="images/u_banned.png" alt="" class="tooltip" title="'._USER_B.'"/>';
			  break;
	  }
	  
      return $display;;
  }
  
  /**
   * delete_directory()
   * 
   * @param mixed $dirname
   * @return
   */ 
	function delete_directory($dirname) {
	   if (is_dir($dirname))
		  $dir_handle = opendir($dirname);
	   if (!$dir_handle)
		  return false;
	   while($file = readdir($dir_handle)) {
		  if ($file != "." && $file != "..") {
			 if (!is_dir($dirname."/".$file))
				@unlink($dirname."/".$file);
			 else
				delete_directory($dirname.'/'.$file);    
		  }
	   }
	   closedir($dir_handle);
	   @rmdir($dirname);
	   return true;
	}

  /**
   * randName()
   * 
   * @return
   */ 
  function randName() {
	  $code = '';
	  for($x = 0; $x<6; $x++) {
		  $code .= '-'.substr(strtoupper(sha1(rand(0,999999999999999))),2,6);
	  }
	  $code = substr($code,1);
	  return $code;
  }
        
  /**
   * checkDir()
   * 
   * @param mixed $dir
   * @return
   */ 
  function checkDir($dir)
  {
      if (!is_dir($dir)) {
          echo "path does not exist<br/>";
          $dirs = explode('/', $dir);
          $tempDir = $dirs[0];
          $check = false;
          
          for ($i = 1; $i < count($dirs); $i++) {
              echo " Checking " . $tempDir . "<br/>";
              if (is_writeable($tempDir)) {
                  $check = true;
              } else {
                  $error = $tempDir;
              }
              
              $tempDir .= '/' . $dirs[$i];
              if (!is_dir($tempDir)) {
                  if ($check) {
                      echo " Creating " . $tempDir . "<br/>";
                      @mkdir($tempDir, 0755);
                      @chmod($tempDir, 0755);
                  }
                  else
                      echo " Not enough permissions";
              }
          }
      }
  }

  /**
   * getSize()
   * 
   * @param mixed $size
   * @param integer $precision
   * @param bool $long_name
   * @param bool $real_size
   * @return
   */
  function getSize($size, $precision = 2, $long_name = false, $real_size = true)
  {
      $base = $real_size ? 1024 : 1000;
      $pos = 0;
      while ($size > $base) {
          $size /= $base;
          $pos++;
      }
      $prefix = _getSizePrefix($pos);
      $size_name = $long_name ? $prefix . "bytes" : $prefix[0] . 'B';
      return round($size, $precision) . ' ' . ucfirst($size_name);
  }

  /**
   * _getSizePrefix()
   * 
   * @param mixed $pos
   * @return
   */  
  function _getSizePrefix($pos)
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
          case 04:
              return "tera";
          default:
              return "?-";
      }
  }
  
  /**
   * dodate()
   * 
   * @param mixed $format
   * @param mixed $date
   * @return
   */  
  function dodate($format, $date) {
	  
	return strftime($format, strtotime($date));
  } 
  
  /**
   * getTime()
   * 
   * @return
   */ 
  function getTime() {
	  $timer = explode( ' ', microtime() );
	  $timer = $timer[1] + $timer[0];
	  return $timer;
  }
?>