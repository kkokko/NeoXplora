<?php
  /**
   * Comments Class
   *
   * @version $Id: class_admin.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
  
  class Comments
  {
	  private $mTable = "mod_comments";
	  private $cTable = "mod_comments_config";
	  public $comid = null;


      /**
       * Comments::__construct()
       * 
       * @return
       */
      function __construct()
      {
          $this->getCommentId();
		  $this->getConfig();
      }

	  /**
	   * Comments::getSliderId()
	   * 
	   * @return
	   */
	  private function getCommentId()
	  {
	  	  global $core;
		  if (isset($_GET['comid'])) {
			  $comid = (is_numeric($_GET['comid']) && $_GET['comid'] > -1) ? intval($_GET['comid']) : false;
			  $comid = sanitize($comid);
			  
			  if ($comid == false) {
				  $core->error("You have selected an Invalid CommentId","newsSlider::getCommentId()");
			  } else
				  return $this->comid = $comid;
		  }
	  }
	  
	  /**
	   * Comments::getConfig()
	   * 
	   * @return
	   */
	  private function getConfig()
	  {
		  global $db;
		  
		  $sql = "SELECT * FROM " . $this->cTable;
          $row = $db->first($sql);
          
          $this->username_req = $row['username_req'];
		  $this->email_req = $row['email_req'];
		  $this->show_captcha = $row['show_captcha'];
		  $this->show_www = $row['show_www'];
		  $this->show_username = $row['show_username'];
		  $this->show_email = $row['show_email'];
		  $this->auto_approve = $row['auto_approve'];
		  $this->public_access = $row['public_access'];
		  $this->notify_new = $row['notify_new'];
		  $this->sorting = $row['sorting'];
		  $this->blacklist_words = $row['blacklist_words'];
		  $this->char_limit = $row['char_limit'];
		  $this->perpage = $row['perpage'];
		  $this->dateformat = $row['dateformat'];
	  }

	  /**
	   * Comments::updateConfig()
	   * 
	   * @return
	   */
	  public function updateConfig()
	  {
		  global $db, $core, $wojosec;
		  
		  if ($_POST['dateformat'] == "")
			  $core->msgs['dateformat'] = MOD_CM_DATE_R;
		  
		  if (empty($core->msgs)) {
			  $data = array(
					'username_req' => intval($_POST['username_req']), 
					'email_req' => intval($_POST['email_req']),
					'show_captcha' => intval($_POST['show_captcha']),
					'show_www' => intval($_POST['show_www']),
					'show_username' => intval($_POST['show_username']),
					'show_email' => intval($_POST['show_email']),
					'auto_approve' => intval($_POST['auto_approve']),
					'notify_new' => intval($_POST['notify_new']),
					'public_access' => intval($_POST['public_access']),
					'sorting' => sanitize($_POST['sorting'],4),
					'blacklist_words' => rtrim($_POST['blacklist_words']),
					'char_limit' => intval($_POST['char_limit']),
					'perpage' => intval($_POST['perpage']),
					'dateformat' => sanitize($_POST['dateformat'])
			  );
			   $db->update($this->cTable, $data);
			  ($db->affected()) ? $wojosec->writeLog(MOD_CM_UPDATED, "", "no", "module") . $core->msgOk(MOD_CM_UPDATED) :  $core->msgAlert(_SYSTEM_PROCCESS);
				 
		  } else
			  print $core->msgStatus();
	  }
	  	  
	  /**
	   * Comments::getComments()
	   * 
	   * @return
	   */
	  public function getComments($sort = false, $from = false)
	  {
		  global $db, $pager, $core;
		  
		  require_once(WOJOLITE . "lib/class_paginate.php");
          $pager = new Paginator();
		  
          $counter = countEntries($this->mTable);
          $pager->items_total = $counter;
          $pager->default_ipp = $this->perpage;
          $pager->paginate();
          
          if ($counter == 0) {
              $pager->limit = null;
          }

          ($sort) ? $order = sanitize($sort) : $order = "c.created DESC";
		  
          if (isset($_POST['fromdate']) && $_POST['fromdate'] <> "" || isset($from) && $from != '') {
              $enddate = date("Y-m-d");
              $fromdate = (empty($from)) ? $_POST['fromdate'] : $from;
              if (isset($_POST['enddate']) && $_POST['enddate'] <> "") {
                  $enddate = $_POST['enddate'];
              }
              $where = " WHERE c.created BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59'";
          } 
		  $where = (isset($where)) ? $where : null ;

          $sql = "SELECT c.*, c.id as cid, p.id as id, p.title{$core->dblang} as title,"
		  . "\n DATE_FORMAT(c.created, '" . $this->dateformat . "') as cdate"
		  . "\n FROM ".$this->mTable." as c" 
		  . "\n LEFT JOIN pages AS p ON p.id = c.page_id"
		  . "\n ".$where.""
		  . "\n ORDER BY " . $order . $pager->limit;
		  $row = $db->fetch_all($sql);
		  
		  return ($row) ? $row : 0;
	  }

      /**
       * Comments::getDateFormat()
       * 
       * @return
       */ 
      public function getDateFormat()
	  {
		  $arr = array(
				 '%m-%d-%Y' => '12-21-2009 (MM-DD-YYYY)',
				 '%e-%m-%Y' => '21-12-2009 (D-MM-YYYY)',
				 '%m-%e-%y' => '12-21-09 (MM-D-YY)',
				 '%e-%m-%y' => '21-12-09 (D-MM-YY)',
				 '%b %d %Y' => 'Dec 21 2009',
				'%B %d, %Y' => 'December 21, 2009',
				'%d %B %Y %H:%M' => '21 December 2009 19:00',
				'%B %d, %Y %I:%M %p' => 'December 21, 2009 4:00 am',
				'%A %d %B, %Y' => 'Monday 21 December, 2009',
				'%A %d %B, %Y %H:%M' => 'Monday 21 December 2009 07:00',
				'%a %d, %B' => 'Mon. 12, December'
		  );
		  
		  $dformat = '';
		  foreach ($arr as $key => $val) {
              if ($key == $this->dateformat) {
                  $dformat .= "<option selected=\"selected\" value=\"" . $key . "\">" . $val . "</option>\n";
              } else
                  $dformat .= "<option value=\"" . $key . "\">" . $val . "</option>\n";
          }
          unset($val);
          return $dformat;
      }

      /**
       * Comments::getCommentFilter()
       * 
       * @return
       */
      public function getCommentFilter()
	  {
		  $arr = array(
				 'username-ASC' => 'Username &uarr;',
				 'username-DESC' => 'Username &darr;',
				 'email-ASC' => 'User Email &uarr;',
				 'email-DESC' => 'User Email &darr;',
				 'created-ASC' => 'Comment Created &uarr;',
				 'created-DESC' => 'Comment Created &darr;'
		  );
		  
		  $filter = '';
		  foreach ($arr as $key => $val) {
				  if ($key == get('sort')) {
					  $filter .= "<option selected=\"selected\" value=\"$key\">$val</option>\n";
				  } else
					  $filter .= "<option value=\"$key\">$val</option>\n";
		  }
		  unset($val);
		  return $filter;
	  } 

	  /**
	   * Comments::censored()
	   *
	   * @param mixed $string
	   * @return
	   */
	  public function censored($string)
	  {
		  $array = explode("\r\n",$this->blacklist_words);
		  reset($array);
		  
		  foreach ($array as $row) {
			  $string = preg_replace("`$row`", "***", $string);
		  }
		  unset($row);
		  return $string;
	  }
  
	  /**
	   * Comments::keepTags()
	   *
	   * @param mixed $str
	   * @param mixed $tags
	   * @return
	   */
	  public function keepTags($string, $allowtags = null, $allowattributes = null)
	  {
		  $string = strip_tags($string, $allowtags);
		  if (!is_null($allowattributes)) {
			  if (!is_array($allowattributes))
				  $allowattributes = explode(",", $allowattributes);
			  if (is_array($allowattributes))
				  $allowattributes = implode(")(?<!", $allowattributes);
			  if (strlen($allowattributes) > 0)
				  $allowattributes = "(?<!" . $allowattributes . ")";
			  $string = preg_replace_callback("/<[^>]*>/i", create_function('$matches', 'return preg_replace("/ [^ =]*' . $allowattributes . '=(\"[^\"]*\"|\'[^\']*\')/i", "", $matches[0]);'), $string);
		  }
		  return $string;
	  }
  }
?>