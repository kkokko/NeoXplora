<?php
  /**
   * Rss Class
   *
   * @version $Id: class_admin.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
  
  class Rss
  {
	  private $mTable = "plug_rss_config";


      /**
       * Rss::__construct()
       * 
       * @return
       */
      function __construct()
      {
          $this->getConfig();
      }
	  
	  /**
	   * Rss::getConfig()
	   * 
	   * @return
	   */
	  private function getConfig()
	  {
		  global $db;
		  
		  $sql = "SELECT * FROM " . $this->mTable;
          $row = $db->first($sql);
          
          $this->url = $row['url'];
		  $this->title_trim = $row['title_trim'];
		  $this->show_body = $row['show_body'];
		  $this->body_trim = $row['body_trim'];
		  $this->show_date = $row['show_date'];
		  $this->dateformat = $row['dateformat'];
		  $this->perpage = $row['perpage'];
	  }
	  	  
	  /**
	   * Rss::processConfig()
	   * 
	   * @return
	   */
	  public function processConfig()
	  {
		  global $db, $core, $wojosec;

		  if (empty($_POST['url']))
			  $core->msgs['url'] = PLG_RS_URL_R;
			  		  
		  if (empty($_POST['dateformat']))
			  $core->msgs['dateformat'] = PLG_RS_DATE_R;

		  if (empty($_POST['perpage']))
			  $core->msgs['perpage'] = PLG_RS_PP_R;
			  		  
		  if (empty($core->msgs)) {
			  $data = array(
					'url' => sanitize($_POST['url']), 
					'title_trim' => intval($_POST['title_trim']),
					'show_body' => intval($_POST['show_body']),
					'body_trim' => intval($_POST['body_trim']),
					'show_date' => intval($_POST['show_date']),
					'dateformat' => sanitize($_POST['dateformat']),
					'perpage' => intval($_POST['perpage'])
			  );
			  
			  $db->update($this->mTable, $data);
			  ($db->affected()) ? $wojosec->writeLog(PLG_RS_UPDATED, "", "no", "plugin") . $core->msgOk(PLG_RS_UPDATED) :  $core->msgAlert(_SYSTEM_PROCCESS);
				 
		  } else
			  print $core->msgStatus();
	  }
	  
      /**
       * Rss::getDateFormat()
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
  }
?>