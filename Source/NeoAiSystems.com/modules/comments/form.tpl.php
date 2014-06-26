<?php
  /**
   * Comments Form
   *
   * @version $Id: form.tpl.php,<?php echo  2011-01-20 16:17:34 gewa Exp $
   */
  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<div id="reply">
  <h3 class="top10"><?php echo MOD_CM_REPLY;?></h3>
  <form action="#" method="post" name="commentform" id="commentform">
    <table class="display">
      <tfoot>
        <tr>
          <td colspan="2"><input name="submit" type="submit" class="button"  value="<?php echo MOD_CM_ADDCOMMENT;?>" /> <span class="loader2" style="display:none"></span></td>
        </tr>
      </tfoot>
      <tbody>
        <tr>
          <th><?php echo MOD_CM_NAME;?>: <?php echo required(true);?> </th>
          <td><div class="placeholder">
              <input name="username" type="text"  class="inputbox" id="username" value="<?php if ($user->logged_in) echo $user->username;?>" size="45" maxlength="20" />
            </div></td>
        </tr>
        <tr>
          <th><?php echo MOD_CM_EMAIL;?>:
            <?php if($com->email_req) echo required(true);?>
            <small><?php echo MOD_CM_E_NOT_V;?></small></th>
          <td><div class="placeholder">
              <input name="email" type="text" class="inputbox" id="email" value="<?php if ($user->logged_in) echo $user->email;?>" size="45" maxlength="30" />
            </div></td>
        </tr>
        <tr>
          <th><?php echo MOD_CM_WEB;?>: </th>
          <td><div class="placeholder">
              <input name="www" type="text" class="inputbox" id="www" value="" size="45" maxlength="30" />
            </div></td>
        </tr>
        <?php if($com->show_captcha):?>
        <tr>
          <th><?php echo MOD_CM_CAPTCHA_N;?>: <?php echo required(true);?> </th>
          <td><div class="placeholder relative">
              <input name="captcha" type="text" class="inputbox" id="captcha" value="" size="10" maxlength="5" />
              <img src="<?php echo SITEURL;?>/includes/captcha.php" alt="" class="captcha" /></div></td>
        </tr>
        <?php endif;?>
        <tr>
          <th><?php echo MOD_CM_COMMENT;?>: <?php echo required(true);?> </th>
          <td><div class="placeholder">
              <textarea name="body" id="combody" cols="30" class="inputbox" rows="8" style="height:100px"></textarea>
            </div><p id="counter"></p></td>
        </tr>
      </tbody>
    </table>
    <input name="page_id" type="hidden" value="<?php echo $pageid;?>" />
  </form>
</div>