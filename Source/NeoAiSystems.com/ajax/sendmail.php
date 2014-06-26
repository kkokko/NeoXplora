<?php
  /**
   * Send Mail
   *
   * @version $Id: sendmail.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  define("_VALID_PHP", true);
  require_once("../init.php");
?>
<?php
  $post = (!empty($_POST)) ? true : false;
  
  if ($post) {
      if ($_POST['name'] == "")
          $core->msgs['name'] = _CF_NAME_R;
      
      if ($_POST['code'] == "")
          $core->msgs['code'] = _CF_TOTAL_R;
      
	  if ($_SESSION['captchacode'] != $_POST['code'])
          $core->msgs['code'] = _CF_TOTAL_ERR;
      
      if ($_POST['email'] == "")
          $core->msgs['email'] = _CF_EMAIL_R;
      
      if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/", $_POST['email']))
          $core->msgs['email'] = _CF_EMAIL_ERR;
      
      if ($_POST['message'] == "")
          $core->msgs['message'] = _CF_MSG_R;
      
      if (empty($core->msgs)) {
          
          $sender_email = sanitize($_POST['email']);
          $name = sanitize($_POST['name']);
		  $phone = sanitize($_POST['phone']);
          $message = strip_tags($_POST['message']);
		  $mailsubject = sanitize($_POST['subject']);
		  $ip = sanitize($_SERVER['REMOTE_ADDR']);

		  require_once(WOJOLITE . "lib/class_mailer.php");
		  $mailer = $mail->sendMail();	
					  
		  $row = $core->getRowById("email_templates", 10);
		  
		  $body = str_replace(array('[MESSAGE]', '[SENDER]', '[NAME]', '[PHONE]', '[MAILSUBJECT]', '[IP]', '[SITE_NAME]', '[URL]'), 
		  array($message, $sender_email, $name, $phone, $mailsubject, $ip, $core->site_name, $core->site_url), $row['body'.$core->dblang]);

		  $message = Swift_Message::newInstance()
					->setSubject($row['subject'.$core->dblang])
					->setTo(array($core->site_email => $core->site_name))
					->setFrom(array($sender_email => $name))
					->setBody(cleanOut($body), 'text/html');
	
          if($mailer->send($message)) {
			  print 'OK';
			  $wojosec->writeLog(_USER . ' ' . $user->username . ' ' . _LG_CONTACT_SENT, "", "no", "user");
		  }
		  
      } else 
          print $core->msgStatus();
  }
?>