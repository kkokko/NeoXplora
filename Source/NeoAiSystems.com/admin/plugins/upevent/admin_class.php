<?php
  /**
   * upEvent Class
   *
   * @version $Id: class_admin.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
  
  class upEvent
  {
      
	  private $mTable = "plug_upevent_config";


      /**
       * upEvent::__construct()
       * 
       * @return
       */
      function __construct()
      {
		  $this->getconfig();
      }

	  
	  /**
	   * upEvent::getEvent()
	   * 
	   * @return
	   */
	  public function getEvent()
	  {
		  global $db;
		  
		  $sql = "SELECT * FROM mod_events WHERE id = " . $this->event_id;
		  $row = $db->first($sql);
		  
		  return ($row) ? $row : 0;
	  }

	  /**
	   * upEvent::getconfig()
	   * 
	   * @return
	   */
	  private function getconfig()
	  {
		  global $db;
		  
		  $sql = "SELECT * FROM " . $this->mTable . "";
		  $row = $db->first($sql);
		  
		  $this->event_id = $row['event_id'];
	  }

	  /**
	   * upEvent::getconfig()
	   * 
	   * @return
	   */
	  function get_time_difference($start, $end)
	  {
		  global $core;
		  
		  $uts['start'] = strtotime($start);
		  $uts['end'] = strtotime($end);
		  if ($uts['start'] !== -1 && $uts['end'] !== -1) {
			  if ($uts['end'] >= $uts['start']) {
				  $diff = $uts['end'] - $uts['start'];
				  if ($days = intval((floor($diff / 86400)))) {
					  $diff = $diff % 86400;
				  }
				  if ($hours = intval((floor($diff / 3600)))) {
					  $h = $diff % 3600;
					  $diff = ($h >10) ? '0'.$h:$h;
				  }
				  if ($minutes = intval((floor($diff / 60)))) {
					  $diff = $diff % 60;
				  }
				  $diff = intval($diff);
				  
				  $days = ($days < 10) ? '0'.$days : $days;
				  $minutes = ($minutes < 10) ? '0'.$minutes : $minutes;
				  $diff = ($diff < 10) ? '0'.$diff : $diff;
				  $hours = ($hours < 10) ? '0'.$hours : $hours;
				  
				  return (array(
					  'days' => $days,
					  'hours' => $hours,
					  'minutes' => $minutes,
					  'seconds' => $diff));
			  } else {
				  return false;
				  $core->msgError("Ending date/time is earlier than the start date/time.");
			  }
		  } else {
			  return false;
			  $core->msgError("Invalid date/time data detected");
		  }
		  return (false);
	  }


	  /**
	   * upEvent::processConfig()
	   * 
	   * @return
	   */
	  function processConfig()
	  {
		  global $db, $core, $wojosec;
		  
		  if ($_POST['event_id'] == "")
			  $core->msgs['event_id'] = PLG_UE_SELECT_R;
		  
		  if (empty($core->msgs)) {
			  $data = array(
					'event_id' => intval($_POST['event_id'])
			  );

			  $db->update($this->mTable, $data);
			  ($db->affected()) ? $wojosec->writeLog(PLG_UE_UPDATED, "", "no", "plugin") . $core->msgOk(PLG_UE_UPDATED) : $core->msgAlert(_SYSTEM_PROCCESS);

		  } else
			  print $core->msgStatus();
	  }
  }
?>