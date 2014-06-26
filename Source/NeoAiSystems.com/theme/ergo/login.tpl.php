<?php
  /**
   * Login Template
   *
   * @version $Id: login.tpl.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<!-- Full Layout -->
<div class="container">
  <div id="page" class="row grid_24">
    <div id="msgholder"></div>
    <div id="alt-msgholder"><?php print $core->showMsg;?></div>
    <?php switch($core->action): case "activate": ?>
    <h3><?php echo _UA_SUBTITLE5;?></h3>
    <p class="info"><?php echo _UA_INFO5;?></p>
    <div class="box">
      <form action="#" method="post" id="admin_form" name="admin_form">
        <table class="display">
          <tfoot>
            <tr>
              <td colspan="2"><input name="submit" value="<?php echo _UA_ACTIVATE_ACC;?>" type="submit" class="button"/></td>
            </tr>
          </tfoot>
          <tbody>
          <tbody>
            <tr>
              <th><?php echo _UR_EMAIL;?>: <?php echo required(true);?> </th>
              <td><div class="placeholder">
                  <input name="email" type="text" size="45" maxlength="40" class="inputbox" />
                </div></td>
            </tr>
            <tr>
              <th><?php echo _UA_TOKEN;?>: <?php echo required(true);?> </th>
              <td><div class="placeholder">
                  <input name="token" type="text" size="45" maxlength="40" class="inputbox" />
                </div></td>
            </tr>
          </tbody>
        </table>
      </form>
    </div>
    <?php echo $core->doForm("accActivate","ajax/user.php");?>
    <?php break;?>
    <?php default: ?>
    <h3><?php echo _UA_INFO2;?></h3>
    <p class="info"><?php echo _UA_SUBTITLE2. _REQ1. required(true) . _REQ2;?></p>
    <div class="box">
      <form action="#" method="post" id="login_form" name="login_form">
        <table class="display">
          <tfoot>
            <tr>
              <td><input name="submit" value="<?php echo _UA_LOGINNOW;?>" type="submit" class="button"/>
                <?php if($core->enablefb):?>
                <a href="#" onclick="fblogin();return false;" class="fbbutton"><img src="<?php echo THEMEURL;?>/images/fb.png" alt="" class="image" /></a>
                <?php endif;?></td>
              <td style="text-align:right"><!--<a class="button butgreen" href="register.php"><?php echo _UA_CLICKTOREG;?></a>--></td>
            </tr>
          </tfoot>
          <tbody>
          <tbody>
            <tr>
              <th><?php echo _UA_TITLE2;?>: <?php echo required(true);?> </th>
              <td><div class="placeholder">
                  <input name="username" type="text" size="45" maxlength="20" class="inputbox" />
                </div></td>
            </tr>
            <tr>
              <th><?php echo _PASSWORD;?>: <?php echo required(true);?> </th>
              <td><div class="placeholder">
                  <input name="password" type="password" size="45" maxlength="20" class="inputbox" />
                </div></td>
            </tr>
          </tbody>
        </table>
        <input name="doLogin" type="hidden" value="1" />
      </form>
    </div>
    <h3 class="top20"><?php echo _UA_TITLE3;?></h3>
    <p class="info"><?php echo _UA_SUBTITLE3;?></p>
    <div class="box">
      <form action="#" method="post" id="admin_form" name="admin_form">
        <table class="display">
          <tfoot>
            <tr>
              <td colspan="2"><input name="submit" value="<?php echo _UA_PASS_RSUBMIT;?>" type="submit" class="button"/></td>
            </tr>
          </tfoot>
          <tbody>
          <tbody>
            <tr>
              <th><?php echo _USERNAME;?>: <?php echo required(true);?> </th>
              <td><div class="placeholder">
                  <input name="uname" type="text" size="45" maxlength="20" class="inputbox" />
                </div></td>
            </tr>
            <tr>
              <th><?php echo _UR_EMAIL;?>: <?php echo required(true);?> </th>
              <td><div class="placeholder">
                  <input name="email" type="text" size="45" maxlength="60" class="inputbox" />
                </div></td>
            </tr>
            <tr>
              <th><?php echo _UA_PASS_RTOTAL;?>: <?php echo required(true);?></th>
              <td><div class="placeholder relative">
                  <input name="captcha" type="text" class="inputbox" size="10" maxlength="5" />
                  <img src="<?php echo SITEURL;?>/includes/captcha.php" alt="" class="captcha" /></div></td>
            </tr>
          </tbody>
        </table>
      </form>
    </div>
    <?php echo $core->doForm("passReset","ajax/user.php");?>
    <?php break;?>
    <?php endswitch;?>
  </div>
</div>
<?php if ($core->enablefb):?>
<div id="fb-root"></div>
<script type="text/javascript">
// <![CDATA[
  window.fbAsyncInit = function () {
	  FB.init({
		  appId: '<?php echo $facebook->getAppId();?>',
		  session: <?php echo json_encode($fbsession);?>,
		  status: true,
		  cookie: true,
		  oauth: true,
		  xfbml: true
	  });
	  FB.Event.subscribe('auth.login', function () {
		  window.location.reload();
	  });
  };
  (function () {
	  var e = document.createElement('script');
	  e.src = document.location.protocol + '//connect.facebook.net/en_US/all.js';
	  e.async = true;
	  document.getElementById('fb-root').appendChild(e);
  }());
  
  function fblogin() {
	  FB.login(function (response) {
		  console.log(response);
		  },{scope:'email'});
  }
  
  function fblogout() {
	  FB.logout(function (response) {
		  console.log(response);
		  });
  }
// ]]>
</script>
<?php endif;?>
<!-- Full Layout /--> 