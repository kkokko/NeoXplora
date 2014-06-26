<?php
  /**
   * processComment
   *
   * @version $Id: processComment.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  define("_VALID_PHP", true);
  require_once("../../init.php");
  
  require_once(WOJOLITE . "admin/modules/comments/admin_class.php");
  require_once(WOJOLITE . "admin/modules/comments/lang/" . $core->language . ".lang.php");
  $com = new Comments();
?>
<?php
  $post = (!empty($_POST)) ? true : false;
  
  if ($post) {
	  
      if ($_POST['username'] == "" && $com->username_req)
          $core->msgs['username'] = MOD_CM_E_NAME;
		  
      if ($com->show_captcha) {
		  if ($_POST['captcha'] == "")
			  $core->msgs['captcha'] = MOD_CM_E_CAPTCHA;

	  if ($_SESSION['captchacode'] != $_POST['captcha'])
          $core->msgs['captcha'] = MOD_CM_E_CAPTCHA2;
	  }
      
      if ($_POST['email'] == "" && $com->email_req)
          $core->msgs['email'] = MOD_CM_E_EMAIL;
      
      if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/", $_POST['email']))
          $core->msgs['email'] = MOD_CM_E_EMAIL2;
		  
      if (isset($_POST['www']) && !empty($_POST['www'])) {
		if (!preg_match("#^http://*#", $_POST["www"]))
			$core->msgs['www'] = MOD_CM_E_WWW;
	  }
	  
      if ($_POST['body'] == "")
          $core->msgs['body'] = MOD_CM_E_COMMENT;
      
      if (empty($core->msgs)) {
		  
		$text = cleanOut($_POST['body']);
		$string = $com->keepTags($text,'<strong><em><i><b><br><p><pre><code>','');
		$filtered = $com->censored($string);
		
		$data = array(
			  'parent_id' => (isset($_POST['parent_id'])) ? intval($_POST['parent_id']) : 0, 
			  'page_id' => intval($_POST['page_id']),
			  'user_id' => intval($user->uid),
			  'username' => sanitize($_POST['username']),
			  'email' => sanitize($_POST['email']),
			  'body' => $filtered,
			  'www' => sanitize($_POST['www']),
			  'created' => "NOW()",
			  'ip' => sanitize($_SERVER['REMOTE_ADDR']),
			  'active' => ($com->auto_approve) ? 1:0
		);
		$pageslug = getValue("slug", "pages","id = '".$data['page_id']."'");
		
		$db->insert("mod_comments", $data);
		  
		  if($com->notify_new) { 
			  $sender_email = $data['email'];
			  $username = $data['username'];
			  $message = $filtered;
			  $www = $data['www'];
			  $ip = sanitize($_SERVER['REMOTE_ADDR']);
			  //$page_id = $data['page_id'];
			  
			  require_once(WOJOLITE . "lib/class_mailer.php");
			  $mailer = $mail->sendMail();	
						  
			  $row = $core->getRowById("email_templates", 11);
			  
			  $body = str_replace(array('[MESSAGE]', '[SENDER]', '[NAME]', '[WWW]', '[PAGEURL]', '[IP]'), 
			  array($message, $sender_email, $username, $www, createPageLink($pageslug), $ip), $row['body'.$core->dblang]);
	
			  $message = Swift_Message::newInstance()
						->setSubject($row['subject'.$core->dblang])
						->setTo(array($core->site_email => $core->site_name))
						->setFrom(array($sender_email => $username))
						->setBody(cleanOut($body), 'text/html');

			  $mailer->send($message);
		  }
		  
              $res =  ($com->auto_approve) ? 'OK1' : 'OK2';
			  if($db->affected()) {
			     print $res;
				 $wojosec->writeLog(_USER . ' ' . $user->username . ' ' . _LG_COMMENT_SENT, "", "no", "user");
			  }
      } else 
          print $core->msgStatus();
  }  
?>