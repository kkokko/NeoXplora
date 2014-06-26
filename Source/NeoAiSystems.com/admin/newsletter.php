<?php
  /**
   * Newsletter
   *
   * @version $Id: newsletter.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  
  if(!$user->getAcl("Newsletter")): print $core->msgAlert(_CG_ONLYADMIN, false); return; endif;
?>
<?php $row = (isset($request->get['emailid'])) ? $core->getRowById("email_templates", 12) : $core->getRowById("email_templates", 4);?>
<div class="block-top-header">
  <h1><img src="images/news-sml.png" alt="" /><?php echo _NL_TITLE1;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _NL_INFO1;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo _NL_SUBTITLE1;?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td colspan="2"><div class="button arrow">
                <input type="submit" value="<?php echo _NL_SEND;?>" name="dosubmit" />
                <span></span></div></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo _NL_RECIPIENTS;?>:</th>
            <td><?php if(isset($request->get['emailid'])):?>
              <input name="recipient" type="text" class="inputbox" size="45" value="<?php echo sanitize($request->get['emailid']);?>"/>
              <?php else:?>
              <select name="recipient" style="width:200px" class="custombox">
                <option value="all"><?php echo _NL_ALL;?></option>
                <option value="free"><?php echo _NL_REGED;?></option>
                <option value="paid"><?php echo _NL_PAID;?></option>
                <option value="newsletter"><?php echo _NL_SUBSCRIBED;?></option>
              </select>
              <?php endif;?></td>
          </tr>
          <tr>
            <th width="200"><?php echo _NL_SUBJECT;?>: <?php echo required();?></th>
            <td><input name="subject<?php echo $core->dblang;?>" type="text"  class="inputbox" value="<?php echo $row['subject'.$core->dblang];?>" size="60"/></td>
          </tr>
          <tr>
            <td colspan="2" class="editor"><textarea id="bodycontent" name="body<?php echo $core->dblang;?>" rows="4" cols="30"><?php echo $row['body'.$core->dblang];?></textarea>
              <?php loadEditor("bodycontent","100%",600); ?></td>
          </tr>
          <tr>
            <td colspan="2"><strong><?php echo _ET_VAR_T;?></strong></td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php echo $core->doForm("processNewsletter","controller.php");?>