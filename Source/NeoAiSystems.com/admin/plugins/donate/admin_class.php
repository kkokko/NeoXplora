<?php
  /**
   * Donate Class
   *
   * @version $Id: class_admin.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
  
  class Donate
  {
      
	  private $cTable = "plug_donate_config";
	  private $mTable = "plug_donate";


      /**
       * Donate::__construct()
       * 
       * @return
       */
      function __construct()
      {
		  $this->getconfig();
      }

	  /**
	   * Donate::getconfig()
	   * 
	   * @return
	   */
	  private function getconfig()
	  {
		  global $db;
		  
		  $sql = "SELECT * FROM " . $this->cTable . "";
		  $row = $db->first($sql);
		  
		  $this->atarget = $row['atarget'];
		  $this->paypal = $row['paypal'];
		  $this->thankyou = $row['thankyou'];
	  }


	  /**
	   * Donate::processConfig()
	   * 
	   * @return
	   */
	  public function processConfig()
	  {
		  global $db, $core, $wojosec;
		  
		  if ($_POST['atarget'] == "")
			  $core->msgs['atarget'] = PLG_DP_TARGET_R;

		  if ($_POST['paypal'] == "")
			  $core->msgs['paypal'] = PLG_DP_PAYPAL_R;
			  
		  if (empty($core->msgs)) {
			  $data = array(
					'atarget' => floatval($_POST['atarget']),
					'paypal' => sanitize($_POST['paypal']),
					'thankyou' => sanitize($_POST['thankyou']),
			  );

			  $db->update($this->cTable, $data);
			  ($db->affected()) ? $wojosec->writeLog(PLG_DP_UPDATED, "", "no", "plugin") . $core->msgOk(PLG_DP_UPDATED) : $core->msgAlert(_SYSTEM_PROCCESS);

		  } else
			  print $core->msgStatus();
	  }
	  
	  /**
	   * Donate::getDonations()
	   * 
	   * @return
	   */
	  public function getDonations()
	  {
		  global $db, $core, $pager;

		  require_once(WOJOLITE . "lib/class_paginate.php");
          $pager = new Paginator();

          $counter = countEntries($this->mTable);
          $pager->items_total = $counter;
          $pager->default_ipp = $core->perpage;
          $pager->paginate();
          
          if ($counter == 0) {
              $pager->limit = null;
          }
		  
		  $sql = "SELECT * FROM " . $this->mTable . $pager->limit;
		  $row = $db->fetch_all($sql);
		  
		  return ($row) ? $row : 0;

	  } 

	  /**
	   * Donate::countDonations()
	   * 
	   * @return
	   */
	  public function countDonations()
	  {
		  global $db;
		  
		  $sql = "SELECT SUM(amount) as total FROM " . $this->mTable . "";
		  $row = $db->first($sql);
		  
		  return ($row) ? $row['total'] : 0;
	  }

	  /**
	   * Donate::donationPercentage()
	   * 
	   * @return
	   */
	  public function donationPercentage($paid, $total)
	  {
		  return ($paid > 0) ? number_format(($paid * 100) / $total) : 0;

	  }
	  
	  /**
	   * Donate::getPageList()
	   * 
	   * @return
	   */
	  public function getPageList()
	  {
		  global $db, $core;
		  
		  $sql = "SELECT slug, title{$core->dblang} FROM pages";
		  $result = $db->fetch_all($sql);
		  
		  $display = '';
		  if ($result) {
			  $display .= "<select name=\"thankyou\" class=\"custombox\" style=\"width:300px\">";
			  
				  foreach ($result as $row) {
					  $sel = ($row['slug'] == $this->thankyou) ? ' selected="selected"' : null;
				   $display .= "<option value=\"" . $row['slug'] . "\"" . $sel . ">" . $row['title'.$core->dblang] . "</option>\n";
				  }
			  
			  $display .= "</select>\n";
			  $display .= tooltip(PLG_DP_THANKYOU_T);
		  }
		  return $display;

	  }
  }
?>