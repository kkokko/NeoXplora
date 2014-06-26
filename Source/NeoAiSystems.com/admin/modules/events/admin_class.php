<?php
  /**
   * EventManager Class
   *
   * @version $Id: class_admin.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
  
  class eventManager
  {

	  private $mTable = "mod_events";
	  private $dTable = "mod_events_data";
	  public $eventid = null;
	  
      public $weekDayNameLength;
	  public $monthNameLength;
      private $arrWeekDays = array();
      private $arrMonths = array();
      private $pars = array();
      private $today = array();
      private $prevYear = array();
      private $nextYear = array();
      private $prevMonth = array();
      private $nextMonth = array();
	  public $eventMonth;
	  public $daterange;


      /**
       * eventManager::__construct()
       * 
       * @return
       */
      function __construct()
      {
		  $this->getEventId();
		  $this->weekStartedDay = $this->setWeekStart();
		  $this->weekDayNameLength = "long";
		  $this->monthNameLength = "long";
		  $this->init();
          $this->eventMonth = $this->getCalDataMonth();
      }

	  /**
	   * eventManager::init()
	   * 
	   * @return
	   */
	  private function init()
	  {
          $year = (isset($_POST['year']) && $this->checkYear($_POST['year'])) ? intval($_POST['year']) : date("Y");
          $month = (isset($_POST['month']) && $this->checkMonth($_POST['month'])) ? intval($_POST['month']) : date("m");
          $day = (isset($_POST['day']) && $this->checkDay($_POST['day'])) ? intval($_POST['day']) : date("d");
		  $ldim = $this->calcDays($month, $day);
		  
		  if($day > $ldim) {
		  	$day = $ldim;
		  }
		  
          $cdate = getdate(mktime(0, 0, 0, $month, $day, $year));

          $this->pars["year"] = $cdate['year'];
          $this->pars["month"] = $this->toDecimal($cdate['mon']);
          $this->pars["nmonth"] = $cdate['mon'];
          $this->pars["month_full_name"] = $cdate['month'];
          $this->pars["day"] = $day;
          $this->today = getdate();

          $this->prevYear = getdate(mktime(0, 0, 0, $this->pars['month'], $this->pars["day"], $this->pars['year'] - 1));
          $this->nextYear = getdate(mktime(0, 0, 0, $this->pars['month'], $this->pars["day"], $this->pars['year'] + 1));
          $this->prevMonth = getdate(mktime(0, 0, 0, $this->pars['month'] - 1, $this->calcDays($this->pars['month']-1,$this->pars["day"]), $this->pars['year']));
          $this->nextMonth = getdate(mktime(0, 0, 0, $this->pars['month'] + 1, $this->calcDays($this->pars['month']+1,$this->pars["day"]), $this->pars['year']));

          $this->arrWeekDays[0] = array("mini" => _SU, "short" => _SUN, "long" => _SUNDAY);
          $this->arrWeekDays[1] = array("mini" => _MO, "short" => _MON, "long" => _MONDAY);
          $this->arrWeekDays[2] = array("mini" => _TU, "short" => _TUE, "long" => _TUESDAY);
          $this->arrWeekDays[3] = array("mini" => _WE, "short" => _WED, "long" => _WEDNESDAY);
          $this->arrWeekDays[4] = array("mini" => _TH, "short" => _THU, "long" => _THURSDAY);
          $this->arrWeekDays[5] = array("mini" => _FR, "short" => _FRI, "long" => _FRIDAY);
          $this->arrWeekDays[6] = array("mini" => _SA, "short" => _SAT, "long" => _SATURDAY);
		  
		  $this->arrMonths[1] = array("short" => _JA_, "long" => _JAN);
		  $this->arrMonths[2] = array("short" => _FE_, "long" => _FEB);
		  $this->arrMonths[3] = array("short" => _MA_, "long" => _MAR);
		  $this->arrMonths[4] = array("short" => _AP_, "long" => _APR);
		  $this->arrMonths[5] = array("short" => _MY_, "long" => _MAY);
		  $this->arrMonths[6] = array("short" => _JU_, "long" => _JUN);
		  $this->arrMonths[7] = array("short" => _JU_, "long" => _JUL);
		  $this->arrMonths[8] = array("short" => _AU_, "long" => _AUG);
		  $this->arrMonths[9] = array("short" => _SE_, "long" => _SEP);
		  $this->arrMonths[10] = array("short" => _OC_, "long" => _OCT);
		  $this->arrMonths[11] = array("short" => _NO_, "long" => _NOV);
		  $this->arrMonths[12] = array("short" => _DE_, "long" => _DEC);
	  }
	  
	  /**
	   * eventManager::getSliderId()
	   * 
	   * @return
	   */
	  private function getEventId()
	  {
	  	  global $core;
		  if (isset($_GET['eventid'])) {
			  $eventid = (is_numeric($_GET['eventid']) && $_GET['eventid'] > -1) ? intval($_GET['eventid']) : false;
			  $eventid = sanitize($eventid);
			  
			  if ($eventid == false) {
				  $core->error("You have selected an Invalid EventId","eventManager::getEventId()");
			  } else
				  return $this->eventid = $eventid;
		  }
	  }

	  /**
	   * eventManager::processEvent()
	   * 
	   * @return
	   */
	  public function processEvent()
	  {
		  global $db, $core, $wojosec;
		  
		  if ($_POST['title'.$core->dblang] == "")
			  $core->msgs['title'] = PLG_EM_TITLE_R;
		  
		  if ($_POST['date_start'] == "")
			  $core->msgs['date_start'] = PLG_EM_DATE_S_R;
		  
		  if ($_POST['date_end'] == "")
			  $core->msgs['date_end'] = PLG_EM_TIME_S_R;
		  
		  if ($_POST['body'.$core->dblang] == "")
			  $core->msgs['body'] = PLG_EM_BODY_R;
			  
		  if (empty($core->msgs)) {
			  
			  list($date_start, $time_start) = explode(" ", $_POST['date_start']);
			  list($date_end, $time_end) = explode(" ", $_POST['date_end']);
			  $data = array(
					'title'.$core->dblang => sanitize($_POST['title'.$core->dblang]), 
					'venue'.$core->dblang => sanitize($_POST['venue'.$core->dblang]),
					'date_start' => sanitize($date_start),
					'date_end' => sanitize($date_end),
					'time_start' => sanitize($time_start),
					'time_end' => sanitize($time_end),
					'contact_person' => sanitize($_POST['contact_person']),
					'user_id' => intval($_POST['user_id']),
					'contact_email' => sanitize($_POST['contact_email']),
					'contact_phone' => sanitize($_POST['contact_phone']),
					'color' => str_replace("#","",sanitize($_POST['color'])),
					'body'.$core->dblang => $core->in_url($_POST['body'.$core->dblang]),
					'active' => intval($_POST['active'])
			  );
			  
			  ($this->eventid) ? $db->update($this->mTable, $data, "id='" . (int)$this->eventid . "'") : $lastid = $db->insert($this->mTable, $data);
			  $message = ($this->eventid) ? PLG_EM_UPDATED : PLG_EM_ADDED;
			  
			  $dstart = explode("-",$date_start);
			  $dend = explode("-",$date_end);
			  $days_data = $this->createDateRangeArray($dstart[0],$dstart[1],$dstart[2],$dend[0],$dend[1],$dend[2]);
			  
			  $edata['event_id'] = ($this->eventid) ? $this->eventid : $lastid;
			  $db->delete($this->dTable, "event_id='" . $this->eventid . "'");
			  foreach($days_data as $event) {
				  $edata['event_date'] = $event;
				  $edata['color'] = $data['color'];
				  $db->insert($this->dTable, $edata);
			  }
			  
			  ($db->affected()) ? $wojosec->writeLog($message, "", "no", "module") . $core->msgOk($message) :  $core->msgAlert(_SYSTEM_PROCCESS);
		  } else
			  print $core->msgStatus();
	  }
	  
      /**
       * eventManager::renderCalendar()
       * 
	   * @param mixed $type
       * @return
       */
      public function renderCalendar($type)
      {
		  switch($type) {
			  case "responsive" :
			  return $this->drawMonthResponsive();
			  break;

			  case "small" :
			  return $this->drawMonthSmall();
			  break;
			  
			  default :
			  return $this->drawMonth();
			  break;
			   
		  }
		  //($type == 'large') ? $this->drawMonthResponsive() : $this->drawMonthSmall();
      }


      /**
       * eventManager::checkEventsMonths()
       * 
       * @param mixed $day
       * @return
       */
      private function checkEventsMonths($day)
      {
          if ($this->eventMonth) {
              foreach ($this->eventMonth as $v) {
                  if ($day == $v['sday']) {
                      return true;
                  }
              }

              return false;
          }
      }

      /**
       * eventManager::getEvent()
       * 
       * @return
       */
      public function getEvent($id)
      {
		  global $db, $core;
		  
		  $sql = "SELECT *,"
		  . "\n DATE_FORMAT(time_start,'%H:%i') AS stime,"
		  . "\n DATE_FORMAT(time_end,'%H:%i') AS etime"
		  . "\n FROM " . $this->mTable
		  . "\n WHERE id = " . (int)$id
		  . "\n AND active = 1";
		  $row = $db->first($sql);
		  
		  return ($row) ? $row : 0;

      }
	  

      /**
       * eventManager::getAllEvents()
       * 
       * @return
       */
      public function getAllEvents($day, $month, $year)
      {
		  global $db, $core;
		  
		  $sql = "SELECT e.*, e.id as event_id, ed.id as eid, DAY(event_date) as sday, title{$core->dblang} as etitle, DAY(date_end) as eday, ed.color,"
		  . "\n DATE_FORMAT(time_start,'%H:%i') AS stime,"
		  . "\n DATE_FORMAT(time_end,'%H:%i') AS etime"
		  . "\n FROM " . $this->mTable . " as e"
		  . "\n LEFT JOIN " . $this->dTable . " as ed ON ed.event_id = e.id" 
		  . "\n WHERE YEAR(event_date) = " . (int)$year
		  . "\n AND MONTH(event_date) = " . (int)$month
		  . "\n AND DAY(event_date) = " . (int)$day
		  . "\n AND active = 1"
		  . "\n ORDER BY time_start ASC";
		  $row = $db->fetch_all($sql);
		  
		  return ($row) ? $row : 0;

      }
	  
      /**
       * eventManager::getCalDataMonth()
       * 
       * @return
       */
      private function getCalDataMonth()
      {
		  global $db, $core;
		  
		  $sql = "SELECT e.*, e.id as event_id, ed.id as eid, DAY(event_date) as sday, title{$core->dblang} as etitle, DAY(date_end) as eday, ed.color,"
		  . "\n DATE_FORMAT(time_start,'%H:%i') AS stime,"
		  . "\n DATE_FORMAT(time_end,'%H:%i') AS etime"
		  . "\n FROM " . $this->mTable . " as e"
		  . "\n LEFT JOIN " . $this->dTable . " as ed ON ed.event_id = e.id" 
		  . "\n WHERE YEAR(event_date) = " . $this->pars['year']
		  . "\n AND MONTH(event_date) = " . $this->pars['month']
		  . "\n AND active = 1"
		  . "\n ORDER BY time_start ASC";
		  $row = $db->fetch_all($sql);
		  
		  return ($row) ? $row : 0;

      }

	  /**
	   * eventManager::createDateRangeArray()
	   * 
	   * @param mixed $fromYear
	   * @param mixed $fromMonth
	   * @param mixed $fromDay
	   * @param mixed $toYear
	   * @param mixed $toMonth
	   * @param mixed $toDay
	   * @return
	   */
	  private function createDateRangeArray($fromYear, $fromMonth, $fromDay, $toYear, $toMonth, $toDay) {
	  
		  $fromTime = mktime(0,0,0,$fromMonth,$fromDay,$fromYear);
		  $toTime = mktime(0,0,0,$toMonth,$toDay,$toYear);
		  $howManyDays = ceil(($toTime-$fromTime)/60/60/24);
		  $listdays = array();
		  
		  for ($day = 0; $day <= $howManyDays; $day++) {
			  $dateYear = date("Y", mktime(0, 0, 0, $fromMonth, ($fromDay + $day), $fromYear));
			  $dateMonth = date("m", mktime(0, 0, 0, $fromMonth, ($fromDay + $day), $fromYear));
			  $dateDay = date("d", mktime(0, 0, 0, $fromMonth, ($fromDay + $day), $fromYear));
			  $listdays[$day] = $dateYear . "-" . $dateMonth . "-" . $dateDay;
		  }
		  
		  return $listdays;
	  }

	  /**
	   * eventManager::getEvents()
	   * 
	   * @return
	   */
	  public function getEvents()
	  {
		  global $db, $core;
		  
		  $sql = "SELECT * FROM " . $this->mTable
		  . "\n ORDER BY date_start ASC";
		  $row = $db->fetch_all($sql);
		  
		  return ($row) ? $row : 0;
	  }

      /**
       * eventManager::drawMonthResponsive()
       * 
       * @return
       */
	  private function drawMonthResponsive()
	  {
		  global $db, $core;
	
		  $is_day = 0;
		  $first_day = getdate(mktime(0, 0, 0, $this->pars['month'], 1, $this->pars['year']));
		  $last_day = getdate(mktime(0, 0, 0, $this->pars['month'] + 1, 0, $this->pars['year']));

		  echo "<div class=\"nav clearfix\">";
		  echo "<h3><span class=\"month\">" . $this->arrMonths[$this->pars['nmonth']][$this->monthNameLength] . "</span><span class=\"year\">" . $this->pars['year'] . "</span></h3>";
		  echo "<nav>";
		  echo "<a href=\"javascript:void(0);\" id=\"item_" . $this->toDecimal($this->prevMonth['mon']) . ":" . $this->prevMonth['year'] . "\" class=\"changedate prev\"></a>";
		  echo "<a href=\"javascript:void(0);\" id=\"item_" . $this->toDecimal($this->nextMonth['mon']) . ":" . $this->nextMonth['year'] . "\" class=\"changedate next\"></a>";
		  echo "</nav>";
		  echo "</div>";
		  
		  echo "<header class=\"header clearfix\">";
		  for ($i = $this->weekStartedDay - 1; $i < $this->weekStartedDay + 6; $i++) {
			  echo "<div>" . $this->arrWeekDays[($i % 7)][$this->weekDayNameLength] . "</div>";
		  }
		  echo "</header>";
		  echo "<div class=\"body clearfix\">";

		  if ($first_day['wday'] == 0) {
			  $first_day['wday'] = 7;
		  }
		  
		 $max_days = $first_day['wday'] - ($this->weekStartedDay - 1);
		 
		  if ($max_days < 7) {
			  echo "<section class=\"section clearfix\">";
			  for ($j = 1; $j <= $max_days; $j++) {
				  echo "<div class=\"empty\">&nbsp;</div>";
			  }
			  $is_day = 0;
			  for ($k = $max_days + 1; $k <= 7; $k++) {
				  $is_day++;
				  $class = '';
				  $tclass = '';
				  $align = '';
				  if (($is_day == $this->today['mday']) && ($this->today['mon'] == $this->pars["month"])) {
					  $tclass = " today";
					  $display = $is_day;
				  }
				  $res = '';
				  if ($this->checkEventsMonths($is_day)) {
					  $data = '';
					  foreach ($this->eventMonth as $row) {
						  if ($row['sday'] == $is_day) {
							  $res .= "<div><a href=\"" . SITEURL . "/modules/events/controller.php?eventid=" . $row['event_id'] . "\" class=\"ajax\" style=\"color:#" . $row['color'] . "\">" . character_limiter($row['etitle'],15) . "</a></div>";
						  }
					  }
					  $display = $data . $is_day;
					  $class = " content";
				  } else {
					  $display = $is_day;
				  }
				  $curweek = $this->arrWeekDays[$k-1][$this->weekDayNameLength];
				  echo "<div class=\"caldata" . $class . $tclass . "\"><span class=\"date\">" . $display ."</span><span class=\"weekday\">" . $curweek . "</span>$res</div>";

				  
			  }
			  echo "</section>";
		  }
	
		  $fullWeeks = floor(($last_day['mday'] - $is_day) / 7);
	
		  for ($i = 0; $i < $fullWeeks; $i++) {
			  echo "<section class=\"section clearfix\">";
			  for ($j = 0; $j < 7; $j++) {
				  $is_day++;
				  $class = '';
				  $tclass = '';
				  $align = '';
				  if (($is_day == $this->today['mday']) && ($this->today['mon'] == $this->pars["month"])) {
					  $tclass = " today";
					  $display = $is_day;
				  }
				  $res = '';
				  if ($this->checkEventsMonths($is_day)) {
					  $data = '';
					  foreach ($this->eventMonth as $row) {
						  if ($row['sday'] == $is_day) {
							  $res .= "<div><a href=\"" . SITEURL . "/modules/events/controller.php?eventid=" . $row['event_id'] . "\" style=\"color:#" . $row['color'] . "\" class=\"ajax\">" . character_limiter($row['etitle'],15) . "</a></div>";
						  }
					  }
					  $display = $data . $is_day;
					  $class = " content";
				  } else {
					  $display = $is_day;
				  }
				  $curweek = $this->arrWeekDays[($j)][$this->weekDayNameLength];
				  echo "<div class=\"caldata" . $class . $tclass . "\"><span class=\"date\">" . $display ."</span><span class=\"weekday\">" . $curweek . "</span>$res</div>";
			  }
			  echo "</section>";
		  }


		  if ($is_day < $last_day['mday']) {
			  echo "<section class=\"section clearfix\">";
			  for ($i = 0; $i < 7; $i++) {
				  $is_day++;
				  $class = '';
				  $tclass = '';
				  $align = '';
				  if (($is_day == $this->today['mday']) && ($this->today['mon'] == $this->pars["month"])) {
					  $tclass = " today";
					  $display = $is_day;
				  }
				  
				  $res = '';
				  if ($this->checkEventsMonths($is_day)) {
					  $data = '';
					  foreach ($this->eventMonth as $row) {
						  if ($row['sday'] == $is_day) {
							  $res .= "<div><a href=\"" . SITEURL . "/modules/events/controller.php?eventid=" . $row['event_id'] . "\" class=\"ajax\" style=\"color:#" . $row['color'] . "\">" . character_limiter($row['etitle'],15) . "</a></div>";
						  }

					  }
					  $display = $data . $is_day;
					  $class = " content";
				  } else {
					  $display = $is_day;
				  }
				$curweek = $this->arrWeekDays[$i][$this->weekDayNameLength]; 
				echo ($is_day <= $last_day['mday']) ? "<div class=\"caldata" . $class . $tclass . "\"><span class=\"date\">" . $display . "</span><span class=\"weekday\">$curweek</span>$res</div>" : "<div class=\"empty\">&nbsp;</div>";  
			  }
			  echo "</section>";
		  }

		  echo "</div>";
	
	  }
	  
      /**
       * eventManager::drawMonth()
       * 
       * @return
       */
	  private function drawMonth()
	  {
		  global $db, $core;
	
		  $is_day = 0;
		  $first_day = getdate(mktime(0, 0, 0, $this->pars['month'], 1, $this->pars['year']));
		  $last_day = getdate(mktime(0, 0, 0, $this->pars['month'] + 1, 0, $this->pars['year']));
	
		  echo "<table class=\"month\">";
		  echo "<thead>";
		  echo "<tr>";
		  echo " <td><a href=\"javascript:void(0);\" id=\"item_" . $this->toDecimal($this->prevMonth['mon']) . ":" . $this->prevMonth['year'] . "\" class=\"changedate prev\"></a></td>";
		  echo "<td colspan=\"5\">" . $this->arrMonths[$this->pars['nmonth']][$this->monthNameLength] . " - " . $this->pars['year'] . "</td>";
		  echo "<td><a href=\"javascript:void(0);\" id=\"item_" . $this->toDecimal($this->nextMonth['mon']) . ":" . $this->nextMonth['year'] . "\" class=\"changedate next\"></a></td>";
		  echo "</tr>";
		  echo "<tr>";
		  for ($i = $this->weekStartedDay - 1; $i < $this->weekStartedDay + 6; $i++) {
			  echo "<th>" . $this->arrWeekDays[($i % 7)][$this->weekDayNameLength] . "</th>";
		  }
		  echo "</tr>";
		  echo "</thead>";
		  echo "<tbody>";
	
		  if ($first_day['wday'] == 0) {
			  $first_day['wday'] = 7;
		  }
		  $max_days = $first_day['wday'] - ($this->weekStartedDay - 1);
		  if ($max_days < 7) {
			  echo "<tr>";
			  for ($i = 1; $i <= $max_days; $i++) {
				  echo "<td class=\"empty\">&nbsp;</td>";
			  }
			  $is_day = 0;
			  for ($i = $max_days + 1; $i <= 7; $i++) {
				  $is_day++;
				  $class = '';
				  $tclass = '';
				  $align = '';
				  if (($is_day == $this->today['mday']) && ($this->today['mon'] == $this->pars["month"])) {
					  $tclass = " today";
					  $display = $is_day;
				  }
				  
				  if ($this->checkEventsMonths($is_day)) {
					  $res = '';
					  $data = '';
					  foreach ($this->eventMonth as $row) {
						  if ($row['sday'] == $is_day) {
							  $res .= "<small><span style=\"background-color:#" . $row['color'] . "\"></span><a href=\"javascript:void(0);\" class=\"loadevent\" id=\"eventid_" . $row['eid'] . "\">" . sanitize($row['etitle'], 25) . "</a></small>\n";

							  $data .= '<div class="event-wrapper" id="eid_' . $row['eid'] . '" style="display:none" title="' . $row['title' . $core->dblang] . '">';
							  $data .= '<div class="event-list">';
							  $data .= '<h3 class="event-title"><span>' . PLG_EM_TSE . ': ' . $row['stime'] . '/' . $row['etime'] . '</span>' . $row['title' . $core->dblang] . '</h3>';
							  if ($row['venue' . $core->dblang])
								  $data .= '<h6 class="event-venue">' . $row['venue' . $core->dblang] . '</h6>';
							  $data .= "<hr />";
							  $data .= '<div class="event-desc">' . cleanOut($core->out_url($row['body' . $core->dblang])) . '</div>';

							  $data .= '<span class="contact-info-toggle">' . PLG_EM_CONTACT . '</span>';
							  $data .= '<div class="event-contact">';
							  if ($row['venue' . $core->dblang])
								  $data .= '<div>' . $row['contact_person'] . '</div>';
							  if ($row['venue' . $core->dblang])
								  $data .= '<div>' . $row['contact_email'] . '</div>';
							  if ($row['venue' . $core->dblang])
								  $data .= '<div>' . $row['contact_phone'] . '</div>';
							  $data .= '</div>';
							  $data .= '</div>';
							  $data .= '</div>';
						  }

					  }
					  $display = $data . "<div><span>" . $is_day . "</span>" . $res . "</div>";
					  $class = " events";
					  $align = " valign=\"top\"";
				  } else {
					  $display = $is_day;
				  }
				  echo "<td class=\"caldata" . $class . $tclass . "\"" . $align . ">" . $display . "</td>";
			  }
			  echo "</tr>";
		  }
	
		  $fullWeeks = floor(($last_day['mday'] - $is_day) / 7);
	
		  for ($i = 0; $i < $fullWeeks; $i++) {
			  echo "<tr>";
			  for ($j = 0; $j < 7; $j++) {
				  $is_day++;
				  $class = '';
				  $tclass = '';
				  $align = '';
				  if (($is_day == $this->today['mday']) && ($this->today['mon'] == $this->pars["month"])) {
					  $tclass = " today";
					  $display = $is_day;
				  }
				  
				  if ($this->checkEventsMonths($is_day)) {
					  $res = '';
					  $data = '';
					  foreach ($this->eventMonth as $row) {
						  if ($row['sday'] == $is_day) {
							  $res .= "<small><span style=\"background-color:#" . $row['color'] . "\"></span><a href=\"javascript:void(0);\" class=\"loadevent\" id=\"eventid_" . $row['eid'] . "\">" . sanitize($row['etitle'], 25) . "</a></small>\n";

							  $data .= '<div class="event-wrapper" id="eid_' . $row['eid'] . '" style="display:none" title="' . $row['title' . $core->dblang] . '">';
							  $data .= '<div class="event-list">';
							  $data .= '<h3 class="event-title"><span>' . PLG_EM_TSE . ': ' . $row['stime'] . '/' . $row['etime'] . '</span>' . $row['title' . $core->dblang] . '</h3>';
							  if ($row['venue' . $core->dblang])
								  $data .= '<h6 class="event-venue">' . $row['venue' . $core->dblang] . '</h6>';
							  $data .= "<hr />";
							  $data .= '<div class="event-desc">' . cleanOut($core->out_url($row['body' . $core->dblang])) . '</div>';

							  $data .= '<span class="contact-info-toggle">' . PLG_EM_CONTACT . '</span>';
							  $data .= '<div class="event-contact">';
							  if ($row['venue' . $core->dblang])
								  $data .= '<div>' . $row['contact_person'] . '</div>';
							  if ($row['venue' . $core->dblang])
								  $data .= '<div>' . $row['contact_email'] . '</div>';
							  if ($row['venue' . $core->dblang])
								  $data .= '<div>' . $row['contact_phone'] . '</div>';
							  $data .= '</div>';
							  $data .= '</div>';
							  $data .= '</div>';
						  }

					  }
					  $display = $data . "<div><span>" . $is_day . "</span>" . $res . "</div>";
					  $class = " events";
					  $align = " valign=\"top\"";
				  } else {
					  $display = $is_day;
				  }
				  echo "<td class=\"caldata" . $class . $tclass . "\"" . $align . ">" . $display . "</td>";
			  }
			  echo "</tr>";
		  }
	
		  if ($is_day < $last_day['mday']) {
			  echo "<tr>";
			  for ($i = 0; $i < 7; $i++) {
				  $is_day++;
				  $class = '';
				  $tclass = '';
				  $align = '';
				  if (($is_day == $this->today['mday']) && ($this->today['mon'] == $this->pars["month"])) {
					  $tclass = " today";
					  $display = $is_day;
				  }
				  if ($this->checkEventsMonths($is_day)) {
					  $res = '';
					  $data = '';
					  foreach ($this->eventMonth as $row) {
						  if ($row['sday'] == $is_day) {
							  $res .= "<small><span style=\"background-color:#" . $row['color'] . "\"></span><a href=\"javascript:void(0);\" class=\"loadevent\" id=\"eventid_" . $row['eid'] . "\">" . sanitize($row['etitle'], 25) . "</a></small>\n";

							  $data .= '<div class="event-wrapper" id="eid_' . $row['eid'] . '" style="display:none" title="' . $row['title' . $core->dblang] . '">';
							  $data .= '<div class="event-list">';
							  $data .= '<h3 class="event-title"><span>' . PLG_EM_TSE . ': ' . $row['stime'] . '/' . $row['etime'] . '</span>' . $row['title' . $core->dblang] . '</h3>';
							  if ($row['venue' . $core->dblang])
								  $data .= '<h6 class="event-venue">' . $row['venue' . $core->dblang] . '</h6>';
							  $data .= "<hr />";
							  $data .= '<div class="event-desc">' . cleanOut($core->out_url($row['body' . $core->dblang])) . '</div>';

							  $data .= '<span class="contact-info-toggle">' . PLG_EM_CONTACT . '</span>';
							  $data .= '<div class="event-contact">';
							  if ($row['venue' . $core->dblang])
								  $data .= '<div>' . $row['contact_person'] . '</div>';
							  if ($row['venue' . $core->dblang])
								  $data .= '<div>' . $row['contact_email'] . '</div>';
							  if ($row['venue' . $core->dblang])
								  $data .= '<div>' . $row['contact_phone'] . '</div>';
							  $data .= '</div>';
							  $data .= '</div>';
							  $data .= '</div>';
						  }

					  }
					  $display = $data . "<div><span>" . $is_day . "</span>" . $res . "</div>";
					  $class = " events";
					  $align = " valign=\"top\"";
				  } else {
					  $display = $is_day;
				  }
				  
				echo ($is_day <= $last_day['mday']) ? "<td class=\"caldata" . $class . $tclass . "\"" . $align . ">" . $display . "</td>" : "<td class=\"empty\">&nbsp;</td>";  
			  }
			  echo "</tr>";
		  }
		  echo "</tbody>";
		  echo "</table>";
	
	  }

      /**
       * eventManager::DrawMonthSmall()
       * 
       * @return
       */
	  private function drawMonthSmall()
	  {
		  global $core;
	
		  $is_day = 0;
		  $first_day = getdate(mktime(0, 0, 0, $this->pars['month'], 1, $this->pars['year']));
		  $last_day = getdate(mktime(0, 0, 0, $this->pars['month'] + 1, 0, $this->pars['year']));
	
		  echo "<table class=\"month-small\">";
		  echo "<thead>";
		  echo "<tr>";
		  echo " <td><a href=\"javascript:void(0);\" id=\"item_" . $this->toDecimal($this->prevMonth['mon']) . ":" . $this->prevMonth['year'] . "\" class=\"changedate prev\"></a></td>";
		  echo "<td colspan=\"5\"><span class=\"year\">" . $this->pars['year'] . "</span><span class=\"month\">" . $this->arrMonths[$this->pars['nmonth']][$this->monthNameLength] . "</span></td>";
		  echo "<td><a href=\"javascript:void(0);\" id=\"item_" . $this->toDecimal($this->nextMonth['mon']) . ":" . $this->nextMonth['year'] . "\" class=\"changedate next\"></a></td>";
		  echo "</tr>";
		  echo "<tr>";
		  for ($i = $this->weekStartedDay - 1; $i < $this->weekStartedDay + 6; $i++) {
			  echo "<th>" . $this->arrWeekDays[($i % 7)][$this->weekDayNameLength] . "</th>";
		  }
		  echo "</tr>";
		  echo "</thead>";
		  echo "<tbody>";
	
		  if ($first_day['wday'] == 0) {
			  $first_day['wday'] = 7;
		  }
		  $max_days = $first_day['wday'] - ($this->weekStartedDay - 1);
		  if ($max_days < 7) {
			  echo "<tr>";
			  for ($i = 1; $i <= $max_days; $i++) {
				  echo "<td class=\"empty\">&nbsp;</td>";
			  }
			  $is_day = 0;
			  for ($i = $max_days + 1; $i <= 7; $i++) {
				  $is_day++;
				  $class = '';
				  $tclass = '';
				  $data = '';
				  if (($is_day == $this->today['mday']) && ($this->today['mon'] == $this->pars["month"])) {
					  $tclass = " today";
					  $display = $is_day;
				  }
				  
				  if ($this->checkEventsMonths($is_day)) {					  
					  $datamonth = $this->arrMonths[$this->pars['nmonth']][$this->monthNameLength];
					  $m = $this->pars["month"];
					  $datayear = $this->pars['year'];
					  $display = $data . "<a href=\"" . SITEURL . "/plugins/events/controller.php?d=$is_day&amp;m=$m&amp;y=$datayear\" class=\"ajax\" title=\"$datamonth $datayear\">" . $is_day . "</a>";
					  $class = " events";
				  } else {
					   $display = $is_day;
				  }
				  
				  echo "<td class=\"caldata" . $class . $tclass . "\"><span>" . $display . "</span></td>";
			  }
			  echo "</tr>";
		  }
	
		  $fullWeeks = floor(($last_day['mday'] - $is_day) / 7);
	
		  for ($i = 0; $i < $fullWeeks; $i++) {
			  echo "<tr>";
			  for ($j = 0; $j < 7; $j++) {
				  $is_day++;
				  $class = '';
				  $tclass = '';
				  $data = '';
				  if (($is_day == $this->today['mday']) && ($this->today['mon'] == $this->pars["month"])) {
					  $tclass = " today";
					  $display = $is_day;
				  }

				  if ($this->checkEventsMonths($is_day)) {
					  $datamonth = $this->arrMonths[$this->pars['nmonth']][$this->monthNameLength];
					  $m = $this->pars["month"];
					  $datayear = $this->pars['year'];
					  $display = $data . "<a href=\"" . SITEURL . "/plugins/events/controller.php?d=$is_day&amp;m=$m&amp;y=$datayear\" class=\"ajax\" title=\"$datamonth $datayear\">" . $is_day . "</a>";
					  $class = " events";
				  } else {
					   $display = $is_day;
				  }
				  
				  echo "<td class=\"caldata" . $class . $tclass . "\"><span>" . $display . "</span></td>";
			  }
			  echo "</tr>";
		  }
	
		  if ($is_day < $last_day['mday']) {
			  echo "<tr>";
			  for ($i = 0; $i < 7; $i++) {
				  $is_day++;
				  $class = '';
				  $tclass = '';
				  $align = '';
				  $data = '';
				  
				  if (($is_day == $this->today['mday']) && ($this->today['mon'] == $this->pars["month"])) {
					  $tclass = " today";
					  $display = $is_day;
				  }
				  
				  if ($this->checkEventsMonths($is_day)) {
					  $datamonth = $this->arrMonths[$this->pars['nmonth']][$this->monthNameLength];
					  $m = $this->pars["month"];
					  $datayear = $this->pars['year'];
					  $display = $data . "<a href=\"" . SITEURL . "/plugins/events/controller.php?d=$is_day&amp;m=$m&amp;y=$datayear\" class=\"ajax\" title=\"$datamonth $datayear\">" . $is_day . "</a>";
					  $class = " events";
				  } else {
					   $display = $is_day;
				  }
				  echo ($is_day <= $last_day['mday']) ? "<td class=\"caldata" . $class . $tclass . "\"><span>" . $display . "</span></td>" : "<td class=\"empty\">&nbsp;</td>";
	
			  }
			  echo "</tr>";
		  }
		  echo "</tbody>";
		  echo "</table>";
	
	  }

      /**
       * eventManager::getUserList()
       * 
       * @return
       */
      public function getUserList()
      {
		  global $db, $core;
		  
		  $sql = "SELECT id, CONCAT(fname,' ',lname) as name FROM users"
		  . "\n WHERE active = 'y'"
		  . "\n ORDER BY userlevel DESC";
		  $row = $db->fetch_all($sql);
		  
		  return ($row) ? $row : 0;

      }
	  
	  /**
	   * eventManager::getCalData()
	   *
	   * @return
	   */
	  public function getCalData()
	  {
		  global $core;
		  
		  $caldata = "dateFormat: 'yy-mm-dd',timeFormat: 'hh:mm:ss',";
		  $caldata .= "dayNames: ['"._SUNDAY."', '"._MONDAY."', '"._TUESDAY."', '"._WEDNESDAY."', '"._THURSDAY."', '"._FRIDAY."', '"._SATURDAY."'],";
		  $caldata .= "dayNamesMin: ['"._SU."','"._MO."', '"._TU."', '"._WE."', '"._TH."', '"._FR."', '"._SA."'],";
		  $caldata .= "dayNamesShort: ['"._SUN."', '"._MON."', '"._TUE."', '"._WED."', '"._THU."', '"._FRI."', '"._SAT."'],";
		  $caldata .= "monthNames: ['"._JAN."', '"._FEB."', '"._MAR."', '"._APR."', '"._MAY."', '"._JUN."', '"._JUL."', '"._AUG."', '"._SEP."', '"._OCT."', '"._NOV."', '"._DEC."'],";
		  $caldata .= "monthNamesShort: ['"._JA_."', '"._FE_."', '"._MA_."', '"._AP_."', '"._MY_."', '"._JU_."', '"._JL_."', '"._AU_."', '"._SE_."', '"._OC_."', '"._NO_."', '"._DE_."'],";
		  $caldata .= "prevText: '".PLG_EM_PREV."',";
		  $caldata .= "nextText: '".PLG_EM_NEXT."',";
		  $caldata .= "timeText: '".PLG_EM_TIME."',";
		  $caldata .= "hourText: '".PLG_EM_HOUR."',";
		  $caldata .= "minuteText: '".PLG_EM_MIN."',";
		  $caldata .= "secondText: '".PLG_EM_SEC."',";
		  $caldata .= "firstDay: " . ($core->weekstart - 1) . ",";
		  $caldata .= "hourGrid: 4,";
		  $caldata .= "minuteGrid: 10,";
		  $caldata .= "secondGrid: 10";
		  
		  return $caldata;
	  }

      /**
       * eventManager::setWeekStart()
       * 
       * @return
       */
      private function setWeekStart()
      {
		  global $core;
		  
		  return $core->weekstart;
      }

	/**
	 * eventManager::calcDays()
	 * 
	 * @param string $month
	 * @param string $day
	 * @return
	 */
	  private function calcDays($month, $day)
	  {
		  if ($day < 29) {
			  return $day;
		  } elseif ($day == 29) {
			  return ((int)$month == 2) ? 28 : 29;
		  } elseif ($day == 30) {
			  return ((int)$month != 2) ? 30 : 28;
		  } elseif ($day == 31) {
			  return ((int)$month == 2 ? 28 : ((int)$month == 4 || (int)$month == 6 || (int)$month == 9 || (int)$month == 11 ? 30 : 31));
		  } else {
			  return 30;
		  }
	
	  }
	  
      /**
       * eventManager::toDecimal()
       * 
       * @param mixed $number
       * @return
       */
      public function toDecimal($number)
      {
          return (($number < 10) ? "0" : "") . $number;
      }
	  
      /**
       * eventManager::checkYear()
       * 
       * @param string $year
       * @return
       */
      private function checkYear($year = "")
      {
          return (strlen($year) == 4 or ctype_digit($year)) ? true : false;
      }


      /**
       * eventManager::checkMonth()
       * 
       * @param string $month
       * @return
       */
      private function checkMonth($month = "")
      {
          return ((strlen($month) == 2) or ctype_digit($month) or ($month < 12)) ? true : false;
      }


      /**
       * eventManager::checkDay()
       * 
       * @param string $day
       * @return
       */
      private function checkDay($day = "")
      {
          return ((strlen($day) == 2) or ctype_digit($day) or ($day < 31)) ? true : false;
      }
  }
?>