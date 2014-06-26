<?php
  /**
   * jQuery Tabs Class
   *
   * @version $Id: class_admin.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
  
  class jTabs
  {
      
	  private $mTable = "plug_tabs";
	  public $tabid = null;


      /**
       * jTabs::__construct()
       * 
       * @return
       */
      function __construct()
      {
          $this->getTabId();
      }

	  /**
	   * jTabs::getTabId()
	   * 
	   * @return
	   */
	  private function getTabId()
	  {
	  	  global $core;
		  if (isset($_GET['tabid'])) {
			  $tabid = (is_numeric($_GET['tabid']) && $_GET['tabid'] > -1) ? intval($_GET['tabid']) : false;
			  $tabid = sanitize($tabid);
			  
			  if ($tabid == false) {
				  $core->error("You have selected an Invalid TabId","newsSlider::getTabId()");
			  } else
				  return $this->tabid = $tabid;
		  }
	  }
	  
	  /**
	   * jTabs::getTabs()
	   * 
	   * @return
	   */
	  public function getTabs()
	  {
		  global $db;
		  
		  $sql = "SELECT * FROM " . $this->mTable . " ORDER BY position";
		  $row = $db->fetch_all($sql);
		  
		  return ($row) ? $row : 0;
	  }

	  /**
	   * jTabs::renderTabs()
	   * 
	   * @return
	   */
	  public function renderTabs()
	  {
		  global $db;
		  
		  $sql = "SELECT * FROM " . $this->mTable . ""
		  . "\n WHERE active = '1'"
		  . "\n ORDER BY position";
		  $row = $db->fetch_all($sql);
		  
		  return ($row) ? $row : 0;
	  }
	  	  
	  /**
	   * jTabs::processTabs()
	   * 
	   * @return
	   */
	  function processTabs()
	  {
		  global $db, $core, $wojosec;
		  
		  if ($_POST['title'.$core->dblang] == "")
			  $core->msgs['title'] = PLG_JT_TITLE_R;
		  
		  if ($_POST['body'.$core->dblang] == "")
			  $core->msgs['body'] = PLG_JT_BODY_R;
		  
		  if (empty($core->msgs)) {
			  $data = array(
					'title'.$core->dblang => sanitize($_POST['title'.$core->dblang]), 
					'body'.$core->dblang => $core->in_url($_POST['body'.$core->dblang]),
					'active' => intval($_POST['active'])
			  );
			  
			  ($this->tabid) ? $db->update($this->mTable, $data, "id='" . (int)$this->tabid . "'") : $db->insert($this->mTable, $data);
			  $message = ($this->tabid) ? PLG_JT_UPDATED : PLG_JT_ADDED;
			  
			  ($db->affected()) ? $wojosec->writeLog($message, "", "no", "module") . $core->msgOk($message) : $core->msgAlert(_SYSTEM_PROCCESS);
				 
		  } else
			  print $core->msgStatus();
	  }
 
	  /**
	   * jTabs::updateOrder()
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
		  
		  ($db->affected()) ? $core->msgOk(PLG_JT_SUPDATED) : $core->msgAlert(_SYSTEM_PROCCESS);
	  }
  }
?>