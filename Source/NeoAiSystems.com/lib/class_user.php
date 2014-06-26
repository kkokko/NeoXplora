<?php
  /**
   * User Class
   *
   * @version $Id: class_user.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');

  class Users
  {
	  private $uTable = "users";
	  public $logged_in = null;
	  public $uid = 0;
	  public $fbid = 0;
	  public $userid = 0;
      public $username;
	  public $sesid;
	  public $email;
	  public $name;
      public $membership_id = 0;
	  public $memused = 0;
	  public $access = null;
	  public $fb_token = null;
      public $userlevel;
	  private $lastlogin = "NOW()";
      

      /**
       * Users::__construct()
       * 
       * @return
       */
      function __construct()
      {
		  $this->getUserId();
		  $this->startSession();
      }

	  /**
	   * Users::getUserId()
	   * 
	   * @return
	   */
	  private function getUserId()
	  {
	  	  global $core, $DEBUG;
		  if (isset($_GET['userid'])) {
			  $userid = (is_numeric($_GET['userid']) && $_GET['userid'] > -1) ? intval($_GET['userid']) : false;
			  $userid = sanitize($userid);
			  
			  if ($userid == false) {
				  $DEBUG == true ? $core->error("You have selected an Invalid Id", "Users::getUserId()") : $core->ooops();
			  } else
				  return $this->userid = $userid;
		  }
	  }  

      /**
       * Users::startSession()
       * 
       * @return
       */
      private function startSession()
      {
		if (strlen(session_id()) < 1)
			session_start();
	  
		$this->logged_in = $this->loginCheck();
		
		if (!$this->logged_in) {
			$this->username = $_SESSION['username'] = "Guest";
			$this->sesid = sha1(session_id());
			$this->userlevel = 0;
		}
      }

	  /**
	   * Users::loginCheck()
	   * 
	   * @return
	   */
	  private function loginCheck()
	  {
          if (isset($_SESSION['username']) && $_SESSION['username'] != "Guest") {
              
              $row = isset($_SESSION['fb_token']) ? $this->getUserFBInfo($_SESSION['fbid']) : $this->getUserInfo($_SESSION['username']);
			  $this->uid = $row['id'];
			  $this->fbid = $row['fbid'];
              $this->username = $row['username'];
			  $this->email = $row['email'];
			  $this->name = $row['fname'].' '.$row['lname'];
              $this->userlevel = $row['userlevel'];
			  $this->membership_id = $row['membership_id'];
			  $this->memused = $row['memused'];
			  $this->access = $row['access'];
			  $this->fb_token = isset($_SESSION['fb_token']) ? $_SESSION['fb_token'] : 0;
			  $this->sesid = sha1(session_id());
              return true;
          } else {
              return false;
          }  
	  }

	  /**
	   * Users::is_Admin()
	   * 
	   * @return
	   */
	  public function is_Admin()
	  {
		  return($this->userlevel == 9 or $this->userlevel == 8);
	  
	  }	

	  /**
	   * Users::login()
	   * 
	   * @param mixed $username
	   * @param mixed $password
	   * @return
	   */
	  public function login($username, $password)
	  {
		  global $db, $core, $wojosec;
		  
		  $timeleft = null;
		  if (!$wojosec->loginAgain($timeleft)) {
			  $minutes = ceil($timeleft / 60);
			  $core->msgs['username'] = str_replace("%MINUTES%", $minutes, _LG_BRUTE_RERR);
		  } elseif ($username == "" && $password == "") {
			  $core->msgs['username'] = _LG_ERROR1;
		  } else {
			  $status = $this->checkStatus($username, $password);
			  
			  switch ($status) {
				  case 0:
					  $core->msgs['username'] = _LG_ERROR2;
					  $wojosec->setFailedLogin();
					  break;
					  
				  case 1:
					  $core->msgs['username'] = _LG_ERROR3;
					  $wojosec->setFailedLogin();
					  break;
					  
				  case 2:
					  $core->msgs['username'] = _LG_ERROR4;
					  $wojosec->setFailedLogin();
					  break;
					  
				  case 3:
					  $core->msgs['username'] = _LG_ERROR5;
					  $wojosec->setFailedLogin();
					  break;
			  }
		  }
		  if (empty($core->msgs) && $status == 5) {
			  $row = $this->getUserInfo($username);
			  $this->uid = $_SESSION['uid'] = $row['id'];
			  $this->fbid = $_SESSION['fbid'] = $row['fbid'];
			  $this->username = $_SESSION['username'] = $row['username'];
			  $this->email = $_SESSION['email'] = $row['email'];
			  $this->userlevel = $_SESSION['userlevel'] = $row['userlevel'];
			  $this->membership_id = $_SESSION['membership_id'] = $row['membership_id'];
			  $this->memused = $_SESSION['memused'] = $row['memused'];
			  $this->access = $_SESSION['access'] = $row['access'];

			  $data = array(
					'lastlogin' => $this->lastlogin, 
					'lastip' => sanitize($_SERVER['REMOTE_ADDR'])
			  );
			  $db->update($this->uTable, $data, "username='" . $this->username . "'");
			  if(!$this->validateMembership()) {
				$data = array(
					  'membership_id' => 0, 
					  'mem_expire' => "0000-00-00 00:00:00"
				);
				$db->update($this->uTable, $data, "username='" . $this->username . "'");
			  }
				  
			  return true;
		  } else
			  $core->msgStatus();
	  }

      /**
       * Users::logout()
       * 
       * @return
       */
      public function logout()
      {
          unset($_SESSION['username']);
		  unset($_SESSION['email']);
		  unset($_SESSION['name']);
          unset($_SESSION['membership_id']);
		  unset($_SESSION['memused']);
		  unset($_SESSION['access']);
          unset($_SESSION['uid']);
		  unset($_SESSION['fbid']);
          session_destroy();
		  session_regenerate_id();
          
          $this->logged_in = false;
          $this->username = "Guest";
          $this->userlevel = 0;
      }

	  /**
	   * Users::fbLogin()
	   * 
	   * @return
	   */
	  public function fbLogin($me)
	  {
		  global $db, $core, $facebook;
		  
		  if (!empty($me)) {
			  $result = $db->first("SELECT fbid FROM users WHERE fbid = " . $me['id']);
			  if (!$result) {
		
				  $data = array(
					  'fbid' => $me['id'],
					  'username' => sanitize($me['username']),
					  'email' => sanitize($me['email']),
					  'lname' => sanitize($me['last_name']),
					  'fname' => sanitize($me['first_name']),
					  'created' => "NOW()",
					  'lastlogin' => "NOW()",
					  'lastip' => sanitize($_SERVER['REMOTE_ADDR']),
					  'active' => 'y');
				  $db->insert($this->uTable, $data);
		
		
			  } else {
		
				  $data = array('lastlogin' => "NOW()", 'lastip' => sanitize($_SERVER['REMOTE_ADDR']));
				  
				  $db->update($this->uTable, $data, "fbid='" . $me['id'] . "'");
				  if (!$this->validateMembership()) {
					  $data = array('membership_id' => 0, 'mem_expire' => "0000-00-00 00:00:00");
					  $db->update($this->uTable, $data, "fbid='" . $me['id'] . "'");
				  }
			  }
		
		
			  $row = $db->first("SELECT * FROM " . $this->uTable . " WHERE fbid = " . $me['id']);
		
			  $this->uid = $_SESSION['uid'] = $row['id'];
			  $this->fbid = $_SESSION['fbid'] = $row['fbid'];
			  $this->username = $_SESSION['username'] = $row['username'];
			  $this->email = $_SESSION['email'] = $row['email'];
			  $this->userlevel = $_SESSION['userlevel'] = $row['userlevel'];
			  $this->userlevel = $_SESSION['membership_id'] = $row['membership_id'];
			  $this->access = $_SESSION['access'] = $row['access'];
			  $this->fb_token = $_SESSION['fb_token'] = $facebook->getAccessToken();
		
			  redirect_to(SITEURL);
		
		
		  }

	  }
	  
	  /**
	   * Users::getUserInfo()
	   * 
	   * @param mixed $username
	   * @return
	   */
	  private function getUserInfo($username)
	  {
		  global $db;
		  $username = sanitize($username);
		  $username = $db->escape($username);
		  
		  $sql = "SELECT * FROM " . $this->uTable . " WHERE username = '" . $username . "' AND fbid = 0";
		  $row = $db->first($sql);
		  if (!$username)
			  return false;
		  
		  return ($row) ? $row : 0;
	  }

	  /**
	   * Users::getUserFBInfo()
	   * 
	   * @param mixed $fbid
	   * @return
	   */
	  private function getUserFBInfo($fbid)
	  {
		  global $db;
		  $fbid = sanitize($fbid);
		  $fbid = $db->escape($fbid);
		  
		  $sql = "SELECT * FROM " . $this->uTable . " WHERE fbid = '" . $fbid . "'";
		  $row = $db->first($sql);
		  if (!$fbid)
			  return false;
		  
		  return ($row) ? $row : 0;
	  }
	  
	  /**
	   * Users::checkStatus()
	   * 
	   * @param mixed $username
	   * @param mixed $password
	   * @return
	   */
	  public function checkStatus($username, $password)
	  {
		  global $db;
		  
		  $username = sanitize($username);
		  $username = $db->escape($username);
		  $password = sanitize($password);
		  
          $sql = "SELECT password, active FROM " . $this->uTable
		  . "\n WHERE username = '".$username."'";
          $result = $db->query($sql);
          
		  if ($db->numrows($result) == 0)
			  return 0;
			  
		  $row = $db->fetch($result);
		  $entered_pass = sha1($password);
		  
		  switch ($row['active']) {
			  case "b":
				  return 1;
				  break;
				  
			  case "n":
				  return 2;
				  break;
				  
			  case "t":
				  return 3;
				  break;
				  
			  case "y" && $entered_pass == $row['password']:
				  return 5;
				  break;
		  }
	  }

	  /**
	   * Users::getUsers()
	   * 
	   * @param bool $from
	   * @return
	   */
	  public function getUsers($from = false)
	  {
		  global $db, $pager, $core;
		  
		  require_once(WOJOLITE . "lib/class_paginate.php");
          $pager = new Paginator();
		  
          $counter = countEntries($this->uTable);
          $pager->items_total = $counter;
          $pager->default_ipp = $core->perpage;
          $pager->paginate();
          
          if ($counter == 0) {
              $pager->limit = null;
          }

		  if (isset($_GET['sort'])) {
			  list($sort, $order) = explode("-", $_GET['sort']);
			  $sort = sanitize($sort);
			  $order = sanitize($order);
			  if (in_array($sort, array("username", "fname", "lname", "email","membership_id","created"))) {
				  $ord = ($order == 'DESC') ? " DESC" : " ASC";
				  $sorting = " u." . $sort . $ord;
			  } else {
				  $sorting = " u.created DESC";
			  }
		  } else {
			  $sorting = " u.created DESC";
		  }
		  
		  $clause = (isset($clause)) ? $clause : null;
		  
          if (isset($_POST['fromdate']) && $_POST['fromdate'] <> "" || isset($from) && $from != '') {
              $enddate = date("Y-m-d");
              $fromdate = (empty($from)) ? $_POST['fromdate'] : $from;
              if (isset($_POST['enddate']) && $_POST['enddate'] <> "") {
                  $enddate = $_POST['enddate'];
              }
              $clause .= " WHERE u.created BETWEEN '" . trim($fromdate) . "' AND '" . trim($enddate) . " 23:59:59'";
          } 
		  
          $sql = "SELECT u.*, CONCAT(u.fname,' ',u.lname) as name, m.title{$core->dblang}, m.id as mid,"
		  . "\n DATE_FORMAT(u.created, '" . $core->short_date . "') as cdate,"
		  . "\n DATE_FORMAT(u.lastlogin, '" . $core->short_date . "') as adate"
		  . "\n FROM " . $this->uTable . " as u"
		  . "\n LEFT JOIN memberships as m ON m.id = u.membership_id" 
		  . "\n " . $clause
		  . "\n ORDER BY " . $sorting . $pager->limit;
          $row = $db->fetch_all($sql);
          
		  return ($row) ? $row : 0;
	  }

	  /**
	   * Users::processUser()
	   * 
	   * @return
	   */
	  public function processUser()
	  {
		  global $db, $core, $wojosec;

		  if (!$this->userid) {
			  if (empty($_POST['username']))
				  $core->msgs['username'] = _UR_USERNAME_R;
			  
			  if ($value = $this->usernameExists($_POST['username'])) {
				  if ($value == 1)
					  $core->msgs['username'] = _UR_USERNAME_R1;
				  if ($value == 2)
					  $core->msgs['username'] = _UR_USERNAME_R2;
				  if ($value == 3)
					  $core->msgs['username'] = _UR_USERNAME_R3;
			  }
		  }

		  if (empty($_POST['fname']))
			  $core->msgs['fname'] = _UR_FNAME_R;
			  
		  if (empty($_POST['lname']))
			  $core->msgs['lname'] = _UR_LNAME_R;
			  
		  if (!$this->userid) {
			  if (empty($_POST['password']))
				  $core->msgs['password'] = _UR_PASSWORD_R;
		  }

		  if (empty($_POST['email']))
			  $core->msgs['email'] = _UR_EMAIL_R;
		  if (!$this->userid) {
			  if ($this->emailExists($_POST['email']))
				  $core->msgs['email'] = _UR_EMAIL_R1;
		  }
		  if (!$this->isValidEmail($_POST['email']))
			  $core->msgs['email'] = _UR_EMAIL_R2;

		  if (!empty($_FILES['avatar']['name'])) {
			  if (!preg_match("/(\.jpg|\.png|\.gif)$/i", $_FILES['avatar']['name'])) {
				  $core->msgs['avatar'] = _CG_LOGO_R;
			  }
			  $file_info = getimagesize($_FILES['avatar']['tmp_name']);
			  if(empty($file_info))
				  $core->msgs['avatar'] = _CG_LOGO_R;
		  }
		  
		  if (empty($core->msgs)) {
			  
			  $data = array(
				  'username' => sanitize($_POST['username']), 
				  'email' => sanitize($_POST['email']), 
				  'lname' => sanitize($_POST['lname']), 
				  'fname' => sanitize($_POST['fname']), 
				  'membership_id' => intval($_POST['membership_id']),
				  'mem_expire' => $this->calculateDays($_POST['membership_id']),
				  'newsletter' => intval($_POST['newsletter']),
				  'userlevel' => intval($_POST['userlevel']), 
				  'active' => sanitize($_POST['active'])
			  );

			  if (isset($_POST['access'])) {
				  $accs = $_POST['access'];
				  $total = count($accs);
				  $i = 1;
				  if (is_array($accs)) {
					  $adata = '';
					  foreach ($accs as $acc) {
						  if ($i == $total) {
							  $adata .= $acc;
						  } else
							  $adata .= $acc . ",";
						  $i++;
					  }
				  }
				  $data['access'] = $adata;
			  } else
				  $data['access'] = "NULL";
				  
			  if (!$this->userid)
				  $data['created'] = "NOW()";
				   
			  if ($this->userid)
				  $userrow = $core->getRowById($this->uTable, $this->userid);
			  
			  if ($_POST['password'] != "") {
				  $data['password'] = sha1($_POST['password']);
			  } else
				  $data['password'] = $userrow['password'];

			  // Start Avatar Upload
			  include(WOJOLITE . "lib/class_imageUpload.php");
			  include(WOJOLITE . "lib/class_imageResize.php");

			  $newName = "IMG_" . randName();
			  $ext = substr($_FILES['avatar']['name'], strrpos($_FILES['avatar']['name'], '.') + 1);
			  $name = $newName.".".strtolower($ext);
		
			  $als = new Upload();
			  $als->File = $_FILES['avatar'];
			  $als->method = 1;
			  $als->SavePath = UPLOADS.'/avatars/';
			  $als->NewWidth = $core->avatar_w;
			  $als->NewHeight = $core->avatar_h;
			  $als->NewName  = $newName;
			  $als->OverWrite = true;
			  $err = $als->UploadFile();

			  if ($this->userid) {
				  $avatar = getValue("avatar",$this->uTable,"id = '".$this->userid."'");
				  if (!empty($_FILES['avatar']['name'])) {
					  if ($avatar) {
						  @unlink($als->SavePath . $avatar);
					  }
					  $data['avatar'] = $name;
				  } else {
					  $data['avatar'] = $avatar;
				  }
			  } else {
				  if (!empty($_FILES['avatar']['name'])) 
				  $data['avatar'] = $name;
			  }
			  
			  if (count($err) > 0 and is_array($err)) {
				  foreach ($err as $key => $val) {
					  $core->msgError($val, false);
				  }
			  }
			  
			  ($this->userid) ? $db->update($this->uTable, $data, "id='" . (int)$this->userid . "'") : $db->insert($this->uTable, $data);
			  $message = ($this->userid) ? _UR_UPDATED : _UR_ADDED;

			  if ($db->affected()) {
				  $core->msgOk($message);
				  $wojosec->writeLog($message, "", "no", "content");
				  
				  if (isset($_POST['notify']) && intval($_POST['notify']) == 1) {
					  
					  require_once(WOJOLITE . "lib/class_mailer.php");
					  $mailer = $mail->sendMail();	
								  
					  $row = $core->getRowById("email_templates", 3);
					  
					  $body = str_replace(array('[USERNAME]', '[PASSWORD]', '[NAME]', '[SITE_NAME]', '[URL]'), 
					  array($data['username'], $_POST['password'], $data['fname'].' '.$data['lname'], $core->site_name, $core->site_url), $row['body'.$core->dblang]);
			
					  $message = Swift_Message::newInstance()
								->setSubject($row['subject'.$core->dblang])
								->setTo(array($data['email'] => $data['fname'].' '.$data['lname']))
								->setFrom(array($core->site_email => $core->site_name))
								->setBody(cleanOut($body), 'text/html');
								
					   $mailer->send($message);
				  }
			  } else
				  $core->msgAlert(_SYSTEM_PROCCESS);
		  } else
			  print $core->msgStatus();
	  } 

	  /**
	   * Users::updateProfile()
	   * 
	   * @return
	   */
	  public function updateProfile()
	  {
		  global $db, $core, $wojosec;

		  if (empty($_POST['fname']))
			  $core->msgs['fname'] = _UR_FNAME_R;
			  
		  if (empty($_POST['lname']))
			  $core->msgs['lname'] = _UR_LNAME_R;

		  if (empty($_POST['email']))
			  $core->msgs['email'] = _UR_EMAIL_R;

		  if (!$this->isValidEmail($_POST['email']))
			  $core->msgs['email'] = _UR_EMAIL_R2;

		  if (!empty($_FILES['avatar']['name'])) {
			  if (!preg_match("/(\.jpg|\.png|\.gif)$/i", $_FILES['avatar']['name'])) {
				  $core->msgs['avatar'] = _CG_LOGO_R;
			  }
			  if ($_FILES["avatar"]["size"] > 307200) {
				  $core->msgs['avatar'] = _UA_AVATAR_SIZE;
			  }
			  $file_info = getimagesize($_FILES['avatar']['tmp_name']);
			  if(empty($file_info))
				  $core->msgs['avatar'] = _CG_LOGO_R;
		  }

		  if (empty($core->msgs)) {
			  
			  $data = array(
				  'email' => sanitize($_POST['email']), 
				  'lname' => sanitize($_POST['lname']), 
				  'fname' => sanitize($_POST['fname']), 
				  'newsletter' => intval($_POST['newsletter'])
			  );
				   
			  $userpass = getValue("password", $this->uTable, "id = '".$this->uid."'");
			  
			  if ($_POST['password'] != "") {
				  $data['password'] = sha1($_POST['password']);
			  } else
				  $data['password'] = $userpass;

			  // Start Avatar Upload
			  include(WOJOLITE . "lib/class_imageUpload.php");
			  include(WOJOLITE . "lib/class_imageResize.php");

			  $newName = "IMG_" . randName();
			  $ext = substr($_FILES['avatar']['name'], strrpos($_FILES['avatar']['name'], '.') + 1);
			  $name = $newName.".".strtolower($ext);
		
			  $als = new Upload();
			  $als->File = $_FILES['avatar'];
			  $als->method = 1;
			  $als->SavePath = UPLOADS.'/avatars/';
			  $als->NewWidth = $core->avatar_w;
			  $als->NewHeight = $core->avatar_h;
			  $als->NewName  = $newName;
			  $als->OverWrite = true;
			  $err = $als->UploadFile();

			  $avatar = getValue("avatar",$this->uTable,"id = '".$this->uid."'");
			  if (!empty($_FILES['avatar']['name'])) {
				  if ($avatar) {
					  @unlink($als->SavePath . $avatar);
				  }
				  $data['avatar'] = $name;
			  } else {
				  $data['avatar'] = $avatar;
			  }

			  if (count($err) > 0 and is_array($err)) {
				  foreach ($err as $key => $val) {
					  $core->msgError($val, false);
				  }
			  }
			  
			  $db->update($this->uTable, $data, "id='" . (int)$this->uid . "'");

			  ($db->affected()) ? $wojosec->writeLog(_USER . ' ' . $this->username. ' ' . _LG_PROFILE_UPDATED, "user", "no", "user") . $core->msgOk(_UA_UPDATEOK) : $core->msgAlert(_SYSTEM_PROCCESS);
		  } else
			  print $core->msgStatus();
	  } 

      /**
       * User::register()
       * 
       * @return
       */
	  public function register()
	  {
		  global $db, $core, $wojosec;
		  
		  if (empty($_POST['username']))
			  $core->msgs['username'] = _UR_USERNAME_R;
		  
		  if ($value = $this->usernameExists($_POST['username'])) {
			  if ($value == 1)
				  $core->msgs['username'] = _UR_USERNAME_R1;
			  if ($value == 2)
				  $core->msgs['username'] = _UR_USERNAME_R2;
			  if ($value == 3)
				  $core->msgs['username'] = _UR_USERNAME_R3;
		  }

		  if (empty($_POST['fname']))
			  $core->msgs['fname'] = _UR_FNAME_R;
			  
		  if (empty($_POST['lname']))
			  $core->msgs['lname'] = _UR_LNAME_R;
			  
		  if (empty($_POST['pass']))
			  $this->msgs['pass'] = _UR_PASSWORD_R;
		  
		  if (strlen($_POST['pass']) < 6)
			  $core->msgs['pass'] = _UR_PASSWORD_R1;
		  elseif (!preg_match("/^([0-9a-z])+$/i", ($_POST['pass'] = trim($_POST['pass']))))
			  $core->msgs['pass'] = _UR_PASSWORD_R2;
		  elseif ($_POST['pass'] != $_POST['pass2'])
			  $core->msgs['pass'] = _UR_PASSWORD_R3;
		  
		  if (empty($_POST['email']))
			  $core->msgs['email'] = _UR_EMAIL_R;
		  
		  if ($this->emailExists($_POST['email']))
			  $core->msgs['email'] = _UR_EMAIL_R1;
		  
		  if (!$this->isValidEmail($_POST['email']))
			  $core->msgs['email'] = _UR_EMAIL_R2;
			  		  
		  if (empty($_POST['captcha']))
			  $core->msgs['captcha'] = _UA_REG_RTOTAL_R;

		  if ($_SESSION['captchacode'] != $_POST['captcha'])
			  $core->msgs['captcha'] = _UA_REG_RTOTAL_R1;
		  
		  if (empty($core->msgs)) {

			  $token = ($core->reg_verify == 1) ? $this->generateRandID() : 0;
			  $pass = sanitize($_POST['pass']);
			  
			  if($core->reg_verify == 1) {
				  $active = "t";
			  } elseif($core->auto_verify == 0) {
				  $active = "n";
			  } else {
				  $active = "y";
			  }
				  
			  $data = array(
					  'username' => sanitize($_POST['username']), 
					  'password' => sha1($_POST['pass']),
					  'email' => sanitize($_POST['email']), 
					  'fname' => sanitize($_POST['fname']),
					  'lname' => sanitize($_POST['lname']),
					  'token' => $token,
					  'active' => $active, 
					  'created' => "NOW()"
			  );
			  
			  $db->insert($this->uTable, $data);
		
			  require_once(WOJOLITE . "lib/class_mailer.php");
			  
			  if ($core->reg_verify == 1) {
				  $actlink = $core->site_url . "/login.php?action=activate";
				  $row = $core->getRowById("email_templates", 1);
				  
				  $body = str_replace(
						array('[NAME]', '[USERNAME]', '[PASSWORD]', '[TOKEN]', '[EMAIL]', '[URL]', '[LINK]', '[SITE_NAME]'), 
						array($data['fname'].' '.$data['lname'], $data['username'], $_POST['pass'], $token, $data['email'], $core->site_url, $actlink, $core->site_name), $row['body'.$core->dblang]
						);
						
				 $newbody = cleanOut($body);	
					 
				  $mailer = $mail->sendMail();
				  $message = Swift_Message::newInstance()
							->setSubject($row['subject'.$core->dblang])
							->setTo(array($data['email'] => $data['username']))
							->setFrom(array($core->site_email => $core->site_name))
							->setBody($newbody, 'text/html');
							
				 $mailer->send($message);
				 
			  } elseif ($core->auto_verify == 0) {
				  $row = $core->getRowById("email_templates", 14);
				  
				  $body = str_replace(
						array('[NAME]', '[USERNAME]', '[PASSWORD]', '[URL]', '[SITE_NAME]'), 
						array($data['fname'].' '.$data['lname'], $data['username'], $_POST['pass'], $core->site_url, $core->site_name), $row['body'.$core->dblang]
						);
						
				 $newbody = cleanOut($body);	

				  $mailer = $mail->sendMail();
				  $message = Swift_Message::newInstance()
							->setSubject($row['subject'.$core->dblang])
							->setTo(array($data['email'] => $data['username']))
							->setFrom(array($core->site_email => $core->site_name))
							->setBody($newbody, 'text/html');
							
				 $mailer->send($message); 
				  
			  } else {
				  $row = $core->getRowById("email_templates", 7);
				  
				  $body = str_replace(
						array('[NAME]', '[USERNAME]', '[PASSWORD]', '[URL]', '[SITE_NAME]'), 
						array($data['fname'].' '.$data['lname'], $data['username'], $_POST['pass'], $core->site_url, $core->site_name), $row['body'.$core->dblang]
						);
						
				 $newbody = cleanOut($body);	

				  $mailer = $mail->sendMail();
				  $message = Swift_Message::newInstance()
							->setSubject($row['subject'.$core->dblang])
							->setTo(array($data['email'] => $data['username']))
							->setFrom(array($core->site_email => $core->site_name))
							->setBody($newbody, 'text/html');
							
				 $mailer->send($message);

			  }
			  if($core->notify_admin) {
				$arow = $core->getRowById("email_templates", 13);
  
					$abody = str_replace(
						  array('[USERNAME]', '[EMAIL]', '[NAME]', '[IP]'), 
						  array($data['username'], $data['email'], $data['fname'].' '.$data['lname'], $_SERVER['REMOTE_ADDR']), $arow['body'.$core->dblang]
						  );
						  
				   $anewbody = cleanOut($abody);	
  
					$amailer = $mail->sendMail();
					$amessage = Swift_Message::newInstance()
							  ->setSubject($arow['subject'.$core->dblang])
							  ->setTo(array($core->site_email => $core->site_name))
							  ->setFrom(array($core->site_email => $core->site_name))
							  ->setBody($anewbody, 'text/html');
							  
				   $amailer->send($amessage);
			  }
			  
			  ($db->affected() && $mailer) ? $wojosec->writeLog(_USER . ' ' . $data['username']. ' ' . _LG_USER_REGGED, "user", "no", "user") . print "OK" : $core->msgError(_UA_REG_ERR,false);
		  } else
			  print $core->msgStatus();
	  } 
	  
      /**
       * User::passReset()
       * 
       * @return
       */
	  public function passReset()
	  {
		  global $db, $core, $wojosec;
		  
		  if (empty($_POST['uname']))
			  $core->msgs['uname'] = _UR_USERNAME_R;
		  
		  $uname = $this->usernameExists($_POST['uname']);
		  if (strlen($_POST['uname']) < 4 || strlen($_POST['uname']) > 30 || !preg_match("/^([0-9a-z])+$/i", $_POST['uname']) || $uname != 3)
			  $core->msgs['uname'] = _UR_USERNAME_R0;

		  if (empty($_POST['email']))
			  $core->msgs['email'] = _UR_EMAIL_R;

		  if (!$this->emailExists($_POST['email']))
			  $core->msgs['uname'] = _UR_EMAIL_R3;
			    
		  if (empty($_POST['captcha']))
			  $core->msgs['captcha'] = _UA_PASS_RTOTAL_R;

		  if ($_SESSION['captchacode'] != $_POST['captcha'])
			  $core->msgs['captcha'] = _UA_PASS_RTOTAL_R1;
		  
		  if (empty($core->msgs)) {
			  
              $user = $this->getUserInfo($_POST['uname']);
			  $randpass = $this->getUniqueCode(12);
			  $newpass = sha1($randpass);
			  
			  $data['password'] = $newpass;
			  
			  $db->update($this->uTable, $data, "username = '" . $user['username'] . "'");
		  
			  require_once(WOJOLITE . "lib/class_mailer.php");
			  $row = $core->getRowById("email_templates", 2);
			  
			  $body = str_replace(
					array('[USERNAME]', '[PASSWORD]', '[URL]', '[LINK]', '[IP]', '[SITE_NAME]'), 
					array($user['username'], $randpass, $core->site_url, $core->site_url, $_SERVER['REMOTE_ADDR'], $core->site_name), $row['body'.$core->dblang]
					);
					
			  $newbody = cleanOut($body);

			  $mailer = $mail->sendMail();
			  $message = Swift_Message::newInstance()
						->setSubject($row['subject'.$core->dblang])
						->setTo(array($user['email'] => $user['username']))
						->setFrom(array($core->site_email => $core->site_name))
						->setBody($newbody, 'text/html');
						
			  ($db->affected() && $mailer->send($message)) ? $wojosec->writeLog(_USER . ' ' . $user['username']. ' ' . _LG_PASS_RESET, "user", "no", "user") . $core->msgOk(_UA_PASS_R_OK,false) : $core->msgError(_UA_PASS_R_ERR,false);

		  } else
			  print $core->msgStatus();
	  }
	  
      /**
       * User::activateUser()
       * 
       * @return
       */
	  public function activateUser()
	  {
		  global $db, $core, $wojosec;
		  
		  if (empty($_POST['email']))
			  $core->msgs['email'] = _UR_EMAIL_R;
		  
		  if (!$this->emailExists($_POST['email']))
			  $core->msgs['email'] = _UR_EMAIL_R3;
		  
		  if (empty($_POST['token']))
			  $core->msgs['token'] = _UA_TOKEN_R1;
		  
		  if (!$this->validateToken($_POST['token']))
			  $core->msgs['token'] = _UA_TOKEN_R;
		  
		  if (empty($core->msgs)) {
			  $email = sanitize($_POST['email']);
			  $token = sanitize($_POST['token']);
			  $message = ($core->auto_verify == 1) ? _UA_TOKEN_OK1 : _UA_TOKEN_OK2;
			  
			  $data = array(
					'token' => 0, 
					'active' => ($core->auto_verify) ? "y" : "n"
			  );
			  
			  $db->update($this->uTable, $data, "email = '" . $email . "' AND token = '" . $token . "'");
			  ($db->affected()) ? $core->msgOk($message,false) : $core->msgError(_UA_TOKEN_R_ERR,false);
		  } else
			  print $core->msgStatus();
	  }

	  /**
	   * Users::getUserData()
	   * 
	   * @return
	   */
	  public function getUserData()
	  {
		  global $db, $core;
		  
		  $sql = "SELECT *, DATE_FORMAT(created, '" . $core->long_date . "') as cdate,"
		  . "\n DATE_FORMAT(lastlogin, '" . $core->long_date . "') as ldate"
		  . "\n FROM " . $this->uTable
		  . "\n WHERE id = '" . $this->uid . "'";
		  $row = $db->first($sql);

		  return ($row) ? $row : 0;
	  }
	  
	  /**
	   * Users::getUserMembership()
	   * 
	   * @return
	   */
	  public function getUserMembership()
	  {
		  global $db, $core;
		  		  
          $sql = "SELECT u.*, m.title{$core->dblang},"
		  . "\n DATE_FORMAT(u.mem_expire, '" . $core->long_date . "') as expiry"
		  . "\n FROM " . $this->uTable . " as u"
		  . "\n LEFT JOIN memberships as m ON m.id = u.membership_id" 
		  . "\n WHERE u.id = '" . $this->uid . "'";
          $row = $db->first($sql);
          
		  return ($row) ? $row : 0;
	  }

	  /**
	   * Users::calculateDays()
	   * 
	   * @return
	   */
	  public function calculateDays($membership_id)
	  {
		  global $db;
		  
		  $now = date('Y-m-d H:i:s');
		  $row = $db->first("SELECT days, period FROM memberships WHERE id = '" . (int)$membership_id . "'");
		  if($row) {
			  switch($row['period']) {
				  case "D" :
				  $diff = $row['days'];
				  break;
				  case "W" :
				  $diff = $row['days'] * 7;
				  break; 
				  case "M" :
				  $diff = $row['days'] * 30;
				  break;
				  case "Y" :
				  $diff = $row['days'] * 365;
				  break;
			  }
			$expire = date("Y-m-d H:i:s", strtotime($now . + $diff . " days"));
		  } else {
			$expire = "0000-00-00 00:00:00";
		  }
		  return $expire;
	  }

      /**
       * User::trialUsed()
       * 
       * @return
       */
     public function trialUsed()
      {
          global $db;

          $sql = "SELECT trial_used" 
		  . "\n FROM ".$this->uTable 
		  . "\n WHERE id ='" . $this->uid . "'" 
		  . "\n LIMIT 1";
          $row = $db->first($sql);
		  
		  return ($row['trial_used'] == 1) ? true : false;
      }
	  
	  /**
	   * Users::validateMembership()
	   * 
	   * @return
	   */
	  public function validateMembership()
	  {
		  global $db;
		  
		  $sql = "SELECT mem_expire" 
		  . "\n FROM " . $this->uTable
		  . "\n WHERE id = '" . $this->uid . "'" 
		  . "\n AND TO_DAYS(mem_expire) > TO_DAYS(NOW())";
		  $row = $db->first($sql);
		  
		  return ($row) ? $row : 0;
	  }
	  	  	  	  
	  /**
	   * Users::usernameExists()
	   * 
	   * @param mixed $username
	   * @return
	   */
	  private function usernameExists($username)
	  {
		  global $db;
		  
		  $username = sanitize($username);
		  if (strlen($db->escape($username)) < 4)
			  return 1;
		  
		  $alpha_num = str_replace(" ", "", $username);
		  if (!ctype_alnum($alpha_num))
			  return 2;
		  
		  $sql = $db->query("SELECT username" 
		  . "\n FROM users" 
		  . "\n WHERE username = '" . $username . "'" 
		  . "\n LIMIT 1");
		  
		  $count = $db->numrows($sql);
		  
		  return ($count > 0) ? 3 : false;
	  }  	
	  
	  /**
	   * User::emailExists()
	   * 
	   * @param mixed $email
	   * @return
	   */
	  private function emailExists($email)
	  {
		  global $db;
		  
		  $sql = $db->query("SELECT email" 
		  . "\n FROM users" 
		  . "\n WHERE email = '" . sanitize($email) . "'" 
		  . "\n LIMIT 1");
		  
		  if ($db->numrows($sql) == 1) {
			  return true;
		  } else
			  return false;
	  }
	  
	  /**
	   * User::isValidEmail()
	   * 
	   * @param mixed $email
	   * @return
	   */
	  private function isValidEmail($email)
	  {
		  if (function_exists('filter_var')) {
			  if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
				  return true;
			  } else
				  return false;
		  } else
			  return preg_match('/^[a-zA-Z0-9._+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/', $email);
	  } 	

      /**
       * User::validateToken()
       * 
       * @param mixed $token
       * @return
       */
     private function validateToken($token)
      {
          global $db;
          $token = sanitize($token,40);
          $sql = "SELECT token" 
		  . "\n FROM ".$this->uTable 
		  . "\n WHERE token ='" . $db->escape($token) . "'" 
		  . "\n LIMIT 1";
          $result = $db->query($sql);
          
          if ($db->numrows($result))
              return true;
      }
	  
	  /**
	   * Users::getUniqueCode()
	   * 
	   * @param string $length
	   * @return
	   */
	  private function getUniqueCode($length = "")
	  {
		  $code = md5(uniqid(rand(), true));
		  if ($length != "") {
			  return substr($code, 0, $length);
		  } else
			  return $code;
	  }

	  /**
	   * Users::generateRandID()
	   * 
	   * @return
	   */
	  private function generateRandID()
	  {
		  return sha1($this->getUniqueCode(24));
	  }

      /**
       * Users::getPermissionList()
       * 
       * @param bool $access
       * @return
       */
	  public function getPermissionList($access = false)
	  {
		  global $db, $core;
		  
		  $moddata = $db->fetch_all("SELECT title{$core->dblang}, modalias FROM modules WHERE hasconfig = '1'");
		  $plugdata = $db->fetch_all("SELECT title{$core->dblang}, plugalias  FROM plugins WHERE hasconfig = '1'");
		  
		  $data = '';
		  
		  if ($access) {
			  $arr = explode(",", $access);
			  reset($arr);
		  }
		  
		  $data .= '<select name="access[]" size="10" multiple="multiple" class="select" style="width:250px">';
		  foreach ($this->getPermissionValues() as $key => $val) {
			  if ($access && $arr) {
				  $selected = (in_array($key, $arr)) ? " selected=\"selected\"" : "";
			  } else 
				  $selected = null;
			  $data .= "<option $selected value=\"" . $key . "\">" . $val . "</option>\n";
		  }
		  unset($val);
		  
          if($moddata) {
			  $data .= "<optgroup label=\""._N_MODS."\">\n";
			foreach ($moddata as $mval) {
				if ($access && $arr) {
					$selected = (in_array($mval['modalias'], $arr)) ? " selected=\"selected\"" : "";
				} else 
					$selected = null;
				$data .= "<option $selected value=\"" . $mval['modalias'] . "\">-- " . $mval['title'.$core->dblang] . "</option>\n";
			}
			  $data .= "</optgroup>\n";
			unset($mval);
		  }

          if($plugdata) {
			  $data .= "<optgroup label=\""._N_PLUGS."\">\n";
			foreach ($plugdata as $pval) {
				if ($access && $arr) {
					$selected = (in_array($pval['plugalias'], $arr)) ? " selected=\"selected\"" : "";
				} else 
					$selected = null;
				$data .= "<option $selected value=\"" . $pval['plugalias'] . "\">-- " . $pval['title'.$core->dblang] . "</option>\n";
			}
			  $data .= "</optgroup>\n";
			unset($pval);
		  }
		  		  
		  $data .= "</select>";
		  $data .= "&nbsp;&nbsp;";
		  $data .= tooltip(_UR_PERM_T);
		  
		  return $data;
	  } 

	  /**
	   * Users::getAcl()
	   * 
	   * @param string $content
	   * @return
	   */
	  public function getAcl($content)
	  {
		  if ($this->userlevel == 8) {
			  $arr = explode(",", $this->access);
			  reset($arr);
			  
			  if (in_array($content, $arr)) {
				  return true;
			  } else
				  return false;
		  } else
			  return true;
	  }
	  	  
      /**
       * Users::getPermissionValues()
       * 
       * @return
       */
      private function getPermissionValues()
	  {
		  $arr = array(
				 'Menus' => _N_MENUS,
				 'Pages' => _N_PAGES,
				 'Posts' =>  _N_POSTS,
				 'Memberships' =>  _N_MEMBS,
				 'Gateways' =>  '-- '._N_GATES,
				 'Transactions' =>  '-- '._N_TRANS,
				 'Layout' =>  _N_LAYS,
				 'Users' =>  _N_USERS,
				 'Configuration' =>  _N_CONF,
				 'Templates' =>  '-- '._N_EMAILS,
				 'Newsletter' =>  '-- '._N_NEWSL,
				 'Language' =>  '-- '._N_LANGS,
				 'Logs' =>  '-- '._N_LOGS,
				 'FM' =>  _FM_TITLE,
				 'Backup' =>  _UP_DBACKUP,
				 'Modules' =>  _N_MODS,
				 'Plugins' =>  _N_PLUGS
		  );

		  return $arr;
	  } 
	  	    	  	  
      /**
       * Users::getUserFilter()
       * 
       * @return
       */
      public function getUserFilter()
	  {
		  $arr = array(
				 'username-ASC' => _USERNAME . ' &uarr;',
				 'username-DESC' => _USERNAME . ' &darr;',
				 'fname-ASC' => _UR_FNAME . ' &uarr;',
				 'fname-DESC' => _UR_FNAME . ' &darr;',
				 'lname-ASC' => _UR_LNAME . ' &uarr;',
				 'lname-DESC' => _UR_LNAME . ' &darr;',
				 'email-ASC' => _UR_EMAIL . ' &uarr;',
				 'email-DESC' => _UR_EMAIL . ' &darr;',
				 'membership_id-ASC' => _MEMBERSHIP . ' &uarr;',
				 'membership_id-DESC' => _MEMBERSHIP . ' &darr;',
				 'created-ASC' => _UR_DATE_REGGED . ' &uarr;',
				 'created-DESC' => _UR_DATE_REGGED . ' &darr;',
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
  }
?>