<?php
  /**
   * Register Template
   *
   * @version $Id: register.tpl.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<!-- Full Layout -->
<div class="container">
  <div id="page" class="row grid_24">
    <?php if(!$core->reg_allowed):?>
    <div class="msgInfo"><?php echo _UA_NOMORE_REG;?></div>
    <?php elseif($core->user_limit !=0 and $core->user_limit == $numusers):?>
    <div class="msgInfo"><?php echo _UA_MAX_LIMIT;?></div>
    <?php else:?>
    <h2><?php echo _UA_TITLE4;?></h2>
    <p class="info"><?php echo _UA_INFO4. _REQ1. required(true) . _REQ2;?></p>
    <div class="box">
      <div id="response"></div>
      <div id="fullform">
        <form action="#" method="post" name="user_form" id="user_form">
          <h3><?php echo _UA_SUBTITLE4;?></h3>
          <table class="display">
            <tfoot>
              <tr>
                <td colspan="2"><input name="submit" class="button" value="<?php echo _UA_REG_ACC;?>" type="submit" /></td>
              </tr>
            </tfoot>
            <tbody>
              <tr>
                <th><?php echo _USERNAME;?>: <?php echo required(true);?></th>
                <td><div class="placeholder">
                    <input name="username" type="text" class="inputbox"  id="username" size="45" />
                  </div></td>
              </tr>
              <tr>
                <th><?php echo _PASSWORD;?>: <?php echo required(true);?></th>
                <td><div class="placeholder">
                    <input name="pass" type="password" class="inputbox"  size="45" />
                  </div></td>
              </tr>
              <tr>
                <th><?php echo _UA_PASSWORD2;?>: <?php echo required(true);?></th>
                <td><div class="placeholder">
                    <input name="pass2" type="password" class="inputbox"  size="45" />
                  </div></td>
              </tr>
              <tr>
                <th><?php echo _UR_EMAIL;?>: <?php echo required(true);?></th>
                <td><div class="placeholder">
                    <input name="email" type="text" class="inputbox"  size="45" />
                  </div></td>
              </tr>
              <tr>
                <th><?php echo _UR_FNAME;?>:</th>
                <td><div class="placeholder">
                    <input name="fname" type="text" class="inputbox"  size="45" />
                  </div></td>
              </tr>
              <tr>
                <th><?php echo _UR_LNAME;?>:</th>
                <td><div class="placeholder">
                    <input name="lname" type="text" class="inputbox"  size="45" />
                  </div></td>
              </tr>
              <tr>
                <th><?php echo _UA_REG_RTOTAL;?>: <?php echo required(true);?></th>
                <td><div class="placeholder relative">
                    <input name="captcha" type="text" class="inputbox" size="10" maxlength="5" />
                     <img src="<?php echo SITEURL;?>/includes/captcha.php" alt="" class="captcha" /></div></td>
              </tr>
            </tbody>
          </table>
          <input name="doRegister" type="hidden" value="1" />
        </form>
      </div>
    </div>
<script type="text/javascript">
// <![CDATA[
  $(document).ready(function() {
	  $("#user_form").submit(function () {
		  var str = $(this).serialize();
		  $.ajax({
			  type: "POST",
			  url: "ajax/user.php",
			  data: str,
			  success: function (msg) {
				  $("#response").ajaxComplete(function(event, request, settings) {
				  if(msg  == 'OK') {
					  result = '<div class="msgOk"><?php echo _UA_REG_OK;?><\/div>';
				  $("#fullform").hide();
				  } else {
				  result = msg;
				  }
				 $(this).html(result);
					  });
				  }
			  });
		  return false;
	  });
  });
// ]]>
</script>
    <?php endif;?>
  </div>
</div>
<!-- Full Layout /--> 