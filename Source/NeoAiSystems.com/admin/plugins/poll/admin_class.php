<?php
  /**
   * Poll Class
   *
   * @version $Id: class_admin.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
  
  class poll
  {

	  public $pollid = null;
	  private $oTable = "plug_poll_options";
	  private $qTable = "plug_poll_questions";
	  private $vTable = "plug_poll_votes";


      /**
       * poll::__construct()
       * 
       * @return
       */
      function __construct()
      {
          $this->getPollId();
      }

	  /**
	   * poll::getPollId()
	   * 
	   * @return
	   */
	  private function getPollId()
	  {
	  	  global $core;
		  if (isset($_GET['pollid'])) {
			  $pollid = (is_numeric($_GET['pollid']) && $_GET['pollid'] > -1) ? intval($_GET['pollid']) : false;
			  $pollid = sanitize($pollid);
			  
			  if ($pollid == false) {
				  $core->error("You have selected an Invalid PollId","newsSlider::getPollId()");
			  } else
				  return $this->pollid = $pollid;
		  }
	  }
	  
	  /**
	   * poll::getNewsItems()
	   * 
	   * @return
	   */
	  public function getPolls()
	  {
		  global $db, $core;
		  
		  $sql = "SELECT * , DATE_FORMAT(created, '" . $core->short_date . "') as added FROM " . $this->qTable. " ORDER BY created DESC";
		  $row = $db->fetch_all($sql);
		  
		  return ($row) ? $row : 0;
	  }

	  /**
	   * poll::getPollOptions()
	   * 
	   * @return
	   */
	  public function getPollOptions()
	  {
		  global $db, $core;
		  
		  $sql = "SELECT * FROM " . $this->oTable. " WHERE question_id = '" . $this->pollid . "' ORDER BY position";
		  $row = $db->fetch_all($sql);
		  
		  return ($row) ? $row : 0;
	  }
	  	  
	  /**
	   * poll::addPoll()
	   * 
	   * @return
	   */
	  public function addPoll()
	  {
		  global $db, $core, $wojosec;
		  
		  if ($_POST['question'.$core->dblang] == "")
			  $core->msgs['question'] = PLG_PL_QUESTION_R;
		  
		  if (empty($core->msgs)) {
			  $qdata = array(
					'question'.$core->dblang => sanitize($_POST['question'.$core->dblang]), 
					'created' => "NOW()",
					'status' => intval($_POST['status'])
			  );

			  if ($qdata['status'] == 1) {
				  $status['status'] = "DEFAULT(status)";
				  $db->update($this->qTable, $status);
			  }
			  
			  $db->insert($this->qTable, $qdata);
			  $lastID = $db->insertid();

              if ($_POST['value'.$core->dblang] != "") {
                  foreach ($_POST['value'.$core->dblang] as $key => $val) {
                      $data['value'.$core->dblang] = trim($val);
                      $data['question_id'] = $lastID;
					  $data['position'] = $key;
                      $db->insert($this->oTable, $data);
                  }
              }
			  ($db->affected()) ? $wojosec->writeLog(PLG_PL_ADDED, "", "no", "module") . $core->msgOk(PLG_PL_ADDED) :  $core->msgAlert(_SYSTEM_PROCCESS);
				 
		  } else
			  print $core->msgStatus();
	  }

	  /**
	   * poll::addPoll()
	   * 
	   * @return
	   */
	  public function updatePoll()
	  {
		  global $db, $core, $wojosec;
		  
		  if ($_POST['question'.$core->dblang] == "")
			  $core->msgs['question'] = PLG_PL_QUESTION_R;
		  
		  if (empty($core->msgs)) {
			  $qdata = array(
					'question'.$core->dblang => sanitize($_POST['question'.$core->dblang]), 
					'status' => intval($_POST['status'])
			  );

			  if ($qdata['status'] == 1) {
				  $status['status'] = "DEFAULT(status)";
				  $db->update($this->qTable, $status);
			  }
			  
			  $db->update($this->qTable, $qdata, "id='" . $this->pollid . "'");
			  
              if ($_POST['value'.$core->dblang] != "") {
                  foreach ($_POST['value'.$core->dblang] as $key => $val) {
                      $data['value'.$core->dblang] = sanitize($val);
                      $data['question_id'] = $this->pollid;
                      $res = $db->update($this->oTable, $data,"id = '".(int)$key."'");
                  }
              }
			  
			  ($res) ? $wojosec->writeLog(PLG_PL_UPDATED, "", "no", "module") . $core->msgOk(PLG_PL_UPDATED) :  $core->msgAlert(_SYSTEM_PROCCESS);
				 
		  } else
			  print $core->msgStatus();
	  }
	   
	  /**
	   * poll::showPollResults()
	   * 
	   * @return
	   */
	  public function showPollResults($poll_id)
	  {
		  global $db, $core;
		  $sql = $db->query("SELECT COUNT(*) as totalvotes" 
				. "\n FROM {$this->vTable}" 
				. "\n WHERE option_id" 
				. "\n IN(SELECT id FROM " . $this->oTable . " WHERE question_id='" . (int)$poll_id . "')");
		  while ($row = $db->fetch($sql))
			  $total = $row['totalvotes'];
		  $query = $db->query("SELECT {$this->oTable}.id, {$this->oTable}.value".$core->dblang.", COUNT(*) as votes" 
				   . "\n FROM {$this->vTable}, {$this->oTable}" 
				   . "\n WHERE {$this->vTable}.option_id = {$this->oTable}.id" 
				   . "\n AND {$this->vTable}.option_id" 
				   . "\n IN(SELECT id FROM {$this->oTable} WHERE question_id='" . (int)$poll_id . "')" 
				   . "\n GROUP BY {$this->vTable}.option_id");
		  $display = '';
		  while ($row = $db->fetch($query)) {
			  $percent = round(($row['votes'] * 100) / $total);
			  $display .= "<div class=\"option\">" . $row['value'.$core->dblang] . " (<em>" . $percent . "%, " . $row['votes'] . PLG_NS_VOTES."</em> )\n";
			  $display .= "<div class=\"option-bar-out\"><div class=\"optionbar\" style=\"width:" . $percent . "%;\"></div></div></div>\n";
		  }
		  $display .= "<p>".PLG_NS_TOTAL . $total . "</p>\n";
		  
		  return $display;
	  }

	  /**
	   * poll::showPollQuestion()
	   * 
	   * @return
	   */
	  function showPollQuestion()
	  {
		  global $db, $core;
		  
		  $sql = "SELECT id, question{$core->dblang} FROM {$this->qTable} WHERE status = '1'";
		  $row = $db->first($sql);

		  print "<div class=\"question\" >" . $row['question'.$core->dblang] . "</div>\n";
		  $id = intval($row['id']);
		  
		  if (isset($_GET["result"]) == 1 || isset($_COOKIE["voted" . $id]) == 'yes') {
			  print $this->getPollResults($id);
			  exit;
		  } else {
			  $sql = $db->query("SELECT id, value{$core->dblang} FROM {$this->oTable} WHERE question_id='" . $id . "' ORDER BY position");
			  if ($db->numrows($sql)) {
				  print "<div id=\"formcontainer\">\n";
				  print "<form method=\"post\" id=\"pollform\" action=\"\" >\n";
				  print "<input type=\"hidden\" name=\"pollid\" value=\"" . $id . "\" />\n";
				  while ($row = $db->fetch($sql)) {
					  print "<div class=\"option-bar\"><input type=\"radio\" name=\"poll\" value=\"" . $row['id'] . "\" id=\"option-" . $row['id'] . "\"/>\n";
					  print "<label for=\"option-" . $row['id'] . "\" >" . $row['value'.$core->dblang] . "</label></div>\n";
				  }
				  print "<div class=\"poll-buttons\"><a href=\"javascript:void(0);\" class=\"votenow\">".PLG_NS_NOW."</a>\n";
				  print "<a href=\"" .  SITEURL . "/plugins/poll/get_poll.php?result=1\" id=\"viewresult\" class=\"view-poll\">".PLG_NS_RESULT."</a></div>\n";
				  print "</form>\n";
				  print "</div>";
			  }
		  }
	  }

	  /**
	   * poll::countTotalVotes()
	   * 
	   * @return
	   */
	  function countTotalVotes($id)
	  {
		  global $db;
		  
		  $sql = "SELECT COUNT(*) as totalvotes FROM {$this->vTable}" 
		  . " \n WHERE option_id IN(SELECT id FROM {$this->oTable} WHERE question_id='" . (int)$id . "')";
		  
		  $row = $db->first($sql);
		  return $row['totalvotes'];
	  }

	  /**
	   * poll::getPollResults()
	   * 
	   * @return
	   */
	  function getPollResults($id)
	  {
		  global $db, $core;
		  
		  $query = "SELECT {$this->oTable}.id, {$this->oTable}.value{$core->dblang}, COUNT(*) as votes FROM {$this->vTable}, {$this->oTable}" 
		  . " \n WHERE {$this->vTable}.option_id={$this->oTable}.id" 
		  . " \n AND {$this->vTable}.option_id IN(SELECT id FROM {$this->oTable} WHERE question_id='" . (int)$id . "')" 
		  . " \n GROUP BY {$this->vTable}.option_id";
		  
		  $display = '';
		  $showall = $db->fetch_all($query);
		  $total = $this->countTotalVotes($id);
		  
		  foreach ($showall as $row) {
			  $percent = round(($row['votes'] * 98) / $total);
			  $display .= "<div class=\"option\"><div>" . $row['value'.$core->dblang] . " (<em>" . $percent . "%, " . $row['votes'] . PLG_NS_VOTES."</em> )</div>\n";
			  $display .= "<div class=\"option-bar-out\"><div class=\"optionbar\" style=\"width:" . $percent . "%;\">" . $percent . "%</div></div></div>\n";
		  }
		  $display .= "<div class=\"totalvote\">".PLG_NS_TOTAL . $total . "</div>\n";
		  
		  return $display;
	  }  

	  /**
	   * poll::updatePollResult()
	   * 
	   * @return
	   */	  
	  function updatePollResult()
	  {
		  global $db;
		  
		  $sql = $db->query("SELECT * FROM {$this->oTable} WHERE id='" . intval($db->escape($_POST["poll"])) . "'");
		  if ($db->numrows($sql)) {
			  $data['option_id'] = intval($_POST["poll"]);
			  $data['voted_on'] = "NOW()";
			  $data['ip'] = sanitize($_SERVER['REMOTE_ADDR']);
			  
			  $db->insert($this->vTable, $data);
			  if ($db->affected()) {
				  setcookie("voted" . intval($_POST['pollid']), 'yes', time() + 86400 * 300);
				  print "<span class=\"voteok\">".PLG_NS_THANKS."</span>";
			  }
		  }
	  }
  }
?>