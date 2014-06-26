<?php
  /**
   * Mailer Class
   *
   * @version $Id: class_mailer.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');

  class Mailer
  {
      private $sitename;
      private $mailer;
      private $smtp_host;
      private $smtp_user;
      private $smtp_pass;
      private $smtp_port;
	  private $sendmail;
	  private $is_ssl;
	  
      /**
       * Mailer::__construct()
       * 
       * @return
       */
      function __construct()
      {
          global $core;
          
          $this->sitename = $core->site_name;
          $this->mailer = $core->mailer;
		  $this->sendmail = $core->sendmail;
          $this->smtp_host = $core->smtp_host;
          $this->smtp_user = $core->smtp_user;
          $this->smtp_pass = $core->smtp_pass;
          $this->smtp_port = $core->smtp_port;
		  $this->is_ssl = $core->is_ssl;
      }
	  
      /**
       * Mailer::sendMail()
       * 
	   * Sends a various messages to users
       * @return
       */
      public function sendMail()
      {
          require_once (WOJOLITE . 'lib/swift/swift_required.php');
          
          if ($this->mailer == "SMTP") {
			  $SSL = ($this->is_ssl) ? 'ssl' : null;
              $transport = Swift_SmtpTransport::newInstance($this->smtp_host, $this->smtp_port, $SSL)
						  ->setUsername($this->smtp_user)
						  ->setPassword($this->smtp_pass);
		  } elseif ($this->mailer == "SMAIL") {
			  $transport = Swift_SendmailTransport::newInstance($this->sendmail);
          } else
              $transport = Swift_MailTransport::newInstance();
          
          return Swift_Mailer::newInstance($transport);
	  }
	  
  }
  $mail = new Mailer();
?>