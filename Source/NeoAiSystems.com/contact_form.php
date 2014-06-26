<?php
  /**
   * Contact Form
   *
   * @version $Id: contact_form.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<div id="response"></div>
<div id="fullform" class="top30">
  <form action="#" method="post" id="admin_form" name="admin_form">
    <table class="display">
      <tfoot>
        <tr>
          <td colspan="2"><input name="submit" class="button" value="<?php echo _CF_SEND;?>" type="submit" /></td>
        </tr>
      </tfoot>
      <tbody>
        <tr>
          <th><?php echo _CF_NAME;?>: <?php echo required(true);?></th>
          <td><div class="placeholder">
              <input name="name" id="name" class="inputbox" value="<?php if ($user->logged_in) echo $user->name;?>" type="text" />
            </div></td>
        </tr>
        <tr>
          <th><?php echo _CF_EMAIL;?>: <?php echo required(true);?></th>
          <td><div class="placeholder">
              <input name="email" id="email" class="inputbox" value="<?php if ($user->logged_in) echo $user->email;?>" type="text"/>
            </div></td>
        </tr>
        <tr>
          <th><?php echo _CF_PHONE;?>:</th>
          <td><div class="placeholder">
              <input name="phone" class="inputbox" size="45"  type="text"/>
            </div></td>
        </tr>
        <tr>
          <th><?php echo _CF_SUBJECT;?>:</th>
          <td><div class="placeholder nopad">
              <select name="subject" class="custombox">
                <option value=""><?php echo _CF_SUBJECT_1;?></option>
                <option value="<?php echo _CF_SUBJECT_2;?>"><?php echo _CF_SUBJECT_2;?></option>
                <option value="<?php echo _CF_SUBJECT_3;?>"><?php echo _CF_SUBJECT_3;?></option>
                <option value="<?php echo _CF_SUBJECT_4;?>"><?php echo _CF_SUBJECT_4;?></option>
                <option value="<?php echo _CF_SUBJECT_5;?>"><?php echo _CF_SUBJECT_5;?></option>
                <option value="<?php echo _CF_SUBJECT_6;?>"><?php echo _CF_SUBJECT_6;?></option>
                <option value="<?php echo _CF_SUBJECT_7;?>"><?php echo _CF_SUBJECT_7;?></option>
              </select>
            </div></td>
        </tr>
        <tr>
          <th><?php echo _CF_MSG;?>: <?php echo required(true);?></th>
          <td><div class="placeholder">
              <textarea name="message" cols="30" rows="8" class="inputbox" style="height:100px"></textarea>
            </div></td>
        </tr>
        <tr>
          <th><?php echo _CF_TOTAL;?>: <?php echo required(true);?></th>
          <td><div class="placeholder relative">
              <input name="code" type="text" class="inputbox" size="10" maxlength="5" />
              <img src="<?php echo SITEURL;?>/includes/captcha.php" alt="" class="captcha" /></div></td>
        </tr>
      </tbody>
    </table>
  </form>
</div>
<script type="text/javascript">
// <![CDATA[
$(document).ready(function() {
    $("#admin_form").submit(function () {
        var str = $(this).serialize();
        $.ajax({
            type: "POST",
            url: SITEURL + "/ajax/sendmail.php",
            data: str,
            success: function (msg) {
                $("#response").ajaxComplete(function(event, request, settings) {
				if(msg  == 'OK') {
					result = '<div class="msgOk"><?php echo _CF_OK;?><\/div>';
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