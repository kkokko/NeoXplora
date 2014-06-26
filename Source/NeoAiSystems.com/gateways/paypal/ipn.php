<?php
  /**
   * PayPal IPN
   *
   * @package CMS Pro
   * @author wojoscripts.com
   * @copyright 2013
   * @version $Id: ipn.php,<?php echo  2013-04-10 21:12:05 gewa Exp $
   */
  define("_VALID_PHP", true);
  define("_PIPN", true);


  //ini_set('log_errors', true);
  //ini_set('error_log', dirname(__FILE__).'/ipn_errors.log');

  if (isset($_POST['payment_status'])) {
      require_once ("../../init.php");
      require_once (WOJOLITE . "lib/class_pp.php");

      $demo = getValue("demo", "gateways", "name = 'paypal'");

      $listener = new IpnListener();
      $listener->use_live = $demo;
      $listener->use_ssl = false;
      $listener->use_curl = false;


      try {
          $listener->requirePostMethod();
          $ppver = $listener->processIpn();
      }
      catch (exception $e) {
          error_log($e->getMessage());
          exit(0);
      }

      $payment_status = $_POST['payment_status'];
      $receiver_email = $_POST['business'];
      list($membership_id, $user_id) = explode("_", $_POST['item_number']);
      $mc_gross = $_POST['mc_gross'];
      $txn_id = $_POST['txn_id'];

      $getxn_id = $member->verifyTxnId($txn_id);
      $price = getValue("price", "memberships", "id = '" . (int)$membership_id . "'");
      $pp_email = getValue("extra", "gateways", "name = 'paypal'");

      $v1 = number_format($mc_gross, 2, '.', '');
      $v2 = number_format($price, 2, '.', '');

      if ($ppver) {
          if ($_POST['payment_status'] == 'Completed') {
              if ($receiver_email == $pp_email && $v1 == $v2 && $getxn_id == true) {
                  $sql = "SELECT * FROM memberships WHERE id='" . (int)$membership_id . "'";
                  $row = $db->first($sql);

                  $username = getValue("username", "users", "id = " . (int)$user_id);

                  $data = array(
                      'txn_id' => $txn_id,
                      'membership_id' => $row['id'],
                      'user_id' => (int)$user_id,
                      'rate_amount' => (float)$mc_gross,
                      'ip' => $_SERVER['REMOTE_ADDR'],
                      'date' => "NOW()",
                      'pp' => "PayPal",
                      'currency' => $_POST['mc_currency'],
                      'status' => 1);

                  $db->insert("payments", $data);

                  $udata = array(
                      'membership_id' => $row['id'],
                      'mem_expire' => $user->calculateDays($row['id']),
                      'trial_used' => ($row['trial'] == 1) ? 1 : 0,
                      'memused' => 1);

                  $db->update("users", $udata, "id='" . (int)$user_id . "'");

                  /* == Notify Administrator == */
                  require_once (WOJOLITE . "lib/class_mailer.php");
                  $row2 = $core->getRowById("email_templates", 5);

                  $body = str_replace(array(
                      '[USERNAME]',
                      '[ITEMNAME]',
                      '[PRICE]',
                      '[STATUS]',
                      '[PP]',
                      '[IP]'), array(
                      $username,
                      $row['title' . $core->dblang],
                      $core->formatMoney($mc_gross),
                      "Completed",
                      "PayPal",
                      $_SERVER['REMOTE_ADDR']), $row2['body' . $core->dblang]);

                  $newbody = cleanOut($body);

                  $mailer = $mail->sendMail();
                  $message = Swift_Message::newInstance()
							->setSubject($row2['subject' . $core->dblang])
							->setTo(array($core->site_email => $core->site_name))
							->setFrom(array($core->site_email => $core->site_name))
							->setBody($newbody, 'text/html');

                  $mailer->send($message);
                  $wojosec->writeLog($username . ' ' . _LG_PAYMENT_OK . ' ' . $row['title' . $core->dblang], "", "yes", "payment");
              }

          } else {
              /* == Failed Transaction= = */
              require_once (WOJOLITE . "lib/class_mailer.php");
              $row2 = $core->getRowById("email_templates", 6);
              $itemname = getValue("title" . $core->dblang, "memberships", "id = " . $membership_id);

              $body = str_replace(array(
                  '[USERNAME]',
                  '[ITEMNAME]',
                  '[PRICE]',
                  '[STATUS]',
                  '[PP]',
                  '[IP]'), array(
                  $username,
                  $itemname,
                  $core->formatMoney($mc_gross),
                  "Failed",
                  "PayPal",
                  $_SERVER['REMOTE_ADDR']), $row2['body' . $core->dblang]);

              $newbody = cleanOut($body);

              $mailer = $mail->sendMail();
              $message = Swift_Message::newInstance()
						->setSubject($row2['subject' . $core->dblang])
						->setTo(array($core->site_email => $core->site_name))
						->setFrom(array($core->site_email => $core->site_name))
						->setBody($newbody, 'text/html');

              $mailer->send($message);

              $wojosec->writeLog(_LG_PAYMENT_ERR . $username, "", "yes", "payment");

          }
      }
  }
?>