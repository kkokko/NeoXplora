<?php
  /**
   * Controller
   *
   * @version $Id: controller.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  define("_VALID_PHP", true);
  require_once("../init.php");

  if (!$user->logged_in)
      redirect_to("../index.php");
?>
<?php
  /* Proccess Cart */
  if (isset($_POST['addtocart']))
      : list($membership_id, $gate_id) = explode(":", $_POST['addtocart']);
  
  $row = $core->getRowById("memberships", $membership_id, false, false);
  $row2 = $core->getRowById("gateways", $gate_id, false, false);
  
  if ($row['trial'] && $user->trialUsed()) {
      $core->msgInfo(_MS_TRIAL_USED);
      die();
  }  
  if ($row['price'] == 0) {
      $data = array(
			'membership_id' => $row['id'], 
			'mem_expire' => $user->calculateDays($row['id']), 
			'trial_used' => ($row['trial'] == 1) ? 1 : 0
	  );
	  
      $db->update("users", $data, "id='" . (int)$user->uid . "'");
      ($db->affected()) ? $wojosec->writeLog(_MEMBERSHIP . ' ' . $row['title'.$core->dblang] . _LG_MEM_ACTIVATED . $user->username, "user", "no", "user") . $core->msgOk(_MS_MEM_ACTIVE_OK . ' ' . $row['title'.$core->dblang], false) : $core->msgError(_SYSTEM_PROCCESS);
  } else {
      $form_url = WOJOLITE . "gateways/" . $row2['dir'] . "/form.tpl.php";
      ($gate_id != "FREE" && file_exists($form_url)) ? include($form_url) : redirect_to("../account.php");
  }
  endif;
?>
<?php
  /* Proccess User */
  if (isset($_POST['processUser']))
      : if (intval($_POST['processUser']) == 0 || empty($_POST['processUser']))
      : redirect_to("../account.php");
  endif;
  $user->updateProfile();
  endif;
?>