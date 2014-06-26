<?php
  /**
   * PayPal IPN
   *
   * @package CMS Pro
   * @author wojoscripts.com
   * @copyright 2013
   * @version $Id: ipn.php, 2013-04-10 21:12:05 gewa Exp $
   */
  define("_VALID_PHP", true);
  define("_PIPN", true);

  //ini_set('log_errors', true);
  //ini_set('error_log', dirname(__FILE__).'/ipn_errors.log');

  if (isset($_POST['payment_status'])) {
      require_once ("../../init.php");
      require_once (WOJOLITE . "lib/class_pp.php");

      $listener = new IpnListener();
      $listener->use_live = true;
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
      $mc_gross = $_POST['mc_gross'];

      require_once (WOJOLITE . "admin/plugins/donate/admin_class.php");
      $donate = new Donate();

      if ($ppver) {
          if ($_POST['payment_status'] == 'Completed') {
              if ($donate->paypal == $receiver_email) {
                  $data = array(
                      'name' => sanitize($_POST['first_name'] . ' ' . $_POST['last_name']),
                      'email' => isset($_POST['payer_email']) ? sanitize($_POST['payer_email']) : "NULL",
                      'amount' => (float)$mc_gross,
                      'created' => "NOW()");

                  $db->insert("plug_donate", $data);

              }
          }
      }
  }
?>