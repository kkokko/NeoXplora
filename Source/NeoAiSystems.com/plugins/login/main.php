<?php
  /**
   * Login Module
   *
   * @version $Id: main.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<!-- Start Login Module -->
<?php if($user->logged_in):?>
<strong><?php echo _WELCOME;?>:</strong>&nbsp; <a href="<?php echo SITEURL;?>/account.php"><?php echo $user->name;?></a> &bull; <a href="<?php echo SITEURL;?>/logout.php"><?php echo _N_LOGOUT;?></a>
<?php else:?>
<form action="<?php echo SITEURL;?>/login.php" method="post" id="login_form_mod" name="login_form_mod">
  <div class="row">
    <div class="col grid_24">
      <div class="placeholder">
        <input name="username" type="text" class="inputbox" id="m_username" maxlength="20"/>
      </div>
    </div>
  </div>
  <div class="row top20">
    <div class="col grid_24">
      <div class="placeholder">
        <input name="password" type="password" maxlength="20" class="inputbox" id="m_password"/>
      </div>
    </div>
  </div>
  <div class="row top20">
    <input name="submit" value="<?php echo _UA_LOGINNOW;?>" type="submit" class="button"/>
  </div>
  <input name="doLogin" type="hidden" value="1" />
</form>
<?php endif;?>
<!-- End Login Module /-->