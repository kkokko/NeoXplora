<?php
  /**
   * Email Templates
   *
   * @version $Id: templates.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  
  if(!$user->getAcl("Templates")): print $core->msgAlert(_CG_ONLYADMIN, false); return; endif;
?>
<?php switch($core->action): case "edit": ?>
<?php $row = $core->getRowById("email_templates", $content->id);?>
<div class="block-top-header">
  <h1><img src="images/mail-sml.png" alt="" /><?php echo _ET_TITLE1;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _ET_INFO1. _REQ1 . required(). _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo _ET_SUBTITLE1 . $row['name'.$core->dblang];?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo _ET_UPDATE;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=templates" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo _ET_TTITLE;?>: <?php echo required();?></th>
            <td><input name="name<?php echo $core->dblang;?>" type="text"  class="inputbox" value="<?php echo $row['name'.$core->dblang];?>" size="45" /></td>
          </tr>
          <tr>
            <th><?php echo _ET_SUBJECT;?>: <?php echo required();?></th>
            <td><input name="subject<?php echo $core->dblang;?>" type="text" class="inputbox" value="<?php echo $row['subject'.$core->dblang];?>" size="45" /></td>
          </tr>
          <tr>
            <td colspan="2" class="editor"><textarea id="bodycontent" name="body<?php echo $core->dblang;?>" rows="4" cols="30"><?php echo $row['body'.$core->dblang];?></textarea>
              <?php loadEditor("bodycontent","100%",600); ?></td>
          </tr>
          <tr>
            <td colspan="2"><textarea name="help<?php echo $core->dblang;?>" cols="80" rows="3"><?php echo $row['help'.$core->dblang];?></textarea></td>
          </tr>
          <tr>
            <td colspan="2"><strong><?php echo _ET_VAR_T;?></strong></td>
          </tr>
        </tbody>
      </table>
      <input name="id" type="hidden" value="<?php echo $content->id;?>" />
    </form>
  </div>
</div>
<?php echo $core->doForm("processTemplate","controller.php");?>
<?php break;?>
<?php default: ?>
<div class="block-top-header">
  <h1><img src="images/mail-sml.png" alt="" /><?php echo _ET_TITLE2;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _ET_INFO2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo _ET_SUBTITLE2;?></h2>
  </div>
  <div class="block-content">
    <table class="display sortable-table">
      <thead>
        <tr>
          <th class="firstrow">#</th>
          <th class="left  sortable"><?php echo _ET_TTITLE;?></th>
          <th class="right"><?php echo _EDIT;?></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!$member->getEmailTemplates()):?>
        <tr>
          <td colspan="3"><?php echo $core->msgError(_ET_NOTEMPLATE,false);?></td>
        </tr>
        <?php else:?>
        <?php foreach ($member->getEmailTemplates() as $row):?>
        <tr>
          <th><?php echo $row['id'];?>.</th>
          <td><?php echo $row['name'.$core->dblang];?></td>
          <td class="right"><a href="index.php?do=templates&amp;action=edit&amp;id=<?php echo $row['id'];?>"><img src="images/edit.png" alt="" class="tooltip" title="<?php echo _ET_EDIT.': '.$row['name'.$core->dblang];?>"/></a></td>
        </tr>
        <?php endforeach;?>
        <?php unset($row);?>
        <?php endif;?>
      </tbody>
    </table>
  </div>
</div>
<script type="text/javascript"> 
// <![CDATA[
$(document).ready(function () {
    $(".sortable-table").tablesorter({
        headers: {
            0: {
                sorter: false
            },
            2: {
                sorter: false
            }
        }
    });
});
// ]]>
</script>
<?php break;?>
<?php endswitch;?>