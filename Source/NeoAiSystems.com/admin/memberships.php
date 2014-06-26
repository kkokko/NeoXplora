<?php
  /**
   * Memberships
   *
   * @version $Id: membership.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  
  if(!$user->getAcl("Memberships")): print $core->msgAlert(_CG_ONLYADMIN, false); return; endif;
?>
<?php switch($core->action): case "edit": ?>
<?php $row = $core->getRowById("memberships", $content->id);?>
<div class="block-top-header">
  <h1><img src="images/mem-sml.png" alt="" /><?php echo _MS_TITLE1;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _MS_INFO1. _REQ1 . required(). _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo _MS_SUBTITLE1 . $row['title'.$core->dblang];?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo _MS_UPDATE;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=memberships" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo _MS_TITLE;?>: <?php echo required();?></th>
            <td><input name="title<?php echo $core->dblang;?>" type="text"  class="inputbox" value="<?php echo $row['title'.$core->dblang];?>" size="45" /></td>
          </tr>
          <tr>
            <th><?php echo _MS_PRICE;?>: <?php echo required();?></th>
            <td><input name="price" type="text" class="inputbox" value="<?php echo $row['price'];?>" size="10" />
              <?php echo tooltip(_MS_PRICE_T);?></td>
          </tr>
          <tr>
            <th><?php echo _MS_PERIOD;?>: <?php echo required();?></th>
            <td><?php echo $member->getMembershipPeriod($row['period']);?>
              <input name="days" type="text" class="inputbox" value="<?php echo $row['days'];?>" size="10" />
              <?php echo tooltip(_MS_PERIOD_T);?></td>
          </tr>
          <tr>
            <th><?php echo _MS_TRIAL;?>:</th>
            <td><span class="input-out">
              <label for="trial-1"><?php echo _YES;?></label>
              <input name="trial" type="radio" id="trial-1" value="1" <?php getChecked($row['trial'], 1); ?> />
              <label for="trial-2"><?php echo _NO;?></label>
              <input name="trial" type="radio" id="trial-2" value="0" <?php getChecked($row['trial'], 0); ?> />
              <?php echo tooltip(_MS_TRIAL_T);?></span></td>
          </tr>
          <tr>
            <th><?php echo _MS_RECURRING;?></th>
            <td><span class="input-out">
              <label for="recurring-1"><?php echo _YES;?></label>
              <input name="recurring" type="radio" id="recurring-1" value="1" <?php getChecked($row['recurring'], 1); ?> />
              <label for="recurring-2"><?php echo _NO;?></label>
              <input name="recurring" type="radio" id="recurring-2" value="0" <?php getChecked($row['recurring'], 0); ?> />
              <?php echo tooltip(_MS_RECURRING_T);?></span></td>
          </tr>
          <tr>
            <th><?php echo _MS_PRIVATE;?></th>
            <td><span class="input-out">
              <label for="private-1"><?php echo _YES;?></label>
              <input name="private" type="radio" id="private-1" value="1" <?php getChecked($row['private'], 1); ?> />
              <label for="private-2"><?php echo _NO;?></label>
              <input name="private" type="radio" id="private-2" value="0" <?php getChecked($row['private'], 0); ?> />
              <?php echo tooltip(_MS_PRIVATE_T);?></span></td>
          </tr>
          <tr>
            <th><?php echo _MS_ACTIVE;?></th>
            <td><span class="input-out">
              <label for="active-1"><?php echo _YES;?></label>
              <input name="active" type="radio" id="active-1" value="1" <?php getChecked($row['active'], 1); ?> />
              <label for="active-2"><?php echo _NO;?></label>
              <input name="active" type="radio" id="active-2" value="0" <?php getChecked($row['active'], 0); ?> />
              <?php echo tooltip(_MS_ACTIVE_T);?></span></td>
          </tr>
          <tr>
            <th><?php echo _MS_DESC;?>:</th>
            <td><textarea name="description<?php echo $core->dblang;?>" rows="4" cols="45"><?php echo $row['description'.$core->dblang];?></textarea></td>
          </tr>
        </tbody>
      </table>
      <input name="id" type="hidden" value="<?php echo $content->id;?>" />
    </form>
  </div>
</div>
<?php echo $core->doForm("processMembership");?>
<?php break;?>
<?php case"add": ?>
<div class="block-top-header">
  <h1><img src="images/mem-sml.png" alt="" /><?php echo _MS_TITLE2;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _MS_INFO2. _REQ1 . required(). _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo _MS_SUBTITLE2;?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo _MS_ADD;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=memberships" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo _MS_TITLE;?>: <?php echo required();?></th>
            <td><input name="title<?php echo $core->dblang;?>" type="text"  class="inputbox"  size="45" /></td>
          </tr>
          <tr>
            <th><?php echo _MS_PRICE;?>: <?php echo required();?></th>
            <td><input name="price" type="text" class="inputbox" size="10" />
              <?php echo tooltip(_MS_PRICE_T);?></td>
          </tr>
          <tr>
            <th><?php echo _MS_PERIOD;?>: <?php echo required();?></th>
            <td><?php echo $member->getMembershipPeriod();?>
              <input name="days" type="text" class="inputbox" size="10" />
              <?php echo tooltip(_MS_PERIOD_T);?></td>
          </tr>
          <tr>
            <th><?php echo _MS_TRIAL;?>:</th>
            <td><span class="input-out">
              <label for="trial-1"><?php echo _YES;?></label>
              <input name="trial" type="radio" id="trial-1" value="1" />
              <label for="trial-2"><?php echo _NO;?></label>
              <input name="trial" type="radio" id="trial-2" value="0" checked="checked" />
              <?php echo tooltip(_MS_TRIAL_T);?></span></td>
          </tr>
          <tr>
            <th><?php echo _MS_RECURRING;?></th>
            <td><span class="input-out">
              <label for="recurring-1"><?php echo _YES;?></label>
              <input name="recurring" type="radio" id="recurring-1" value="1" />
              <label for="recurring-2"><?php echo _NO;?></label>
              <input name="recurring" type="radio" id="recurring-2" value="0" checked="checked" />
              <?php echo tooltip(_MS_RECURRING_T);?></span></td>
          </tr>
          <tr>
            <th><?php echo _MS_PRIVATE;?></th>
            <td><span class="input-out">
              <label for="private-1"><?php echo _YES;?></label>
              <input name="private" type="radio" id="private-1" value="1" />
              <label for="private-2"><?php echo _NO;?></label>
              <input name="private" type="radio" id="private-2" value="0" checked="checked" />
              <?php echo tooltip(_MS_PRIVATE_T);?></span></td>
          </tr>
          <tr>
            <th><?php echo _MS_ACTIVE;?></th>
            <td><span class="input-out">
              <label for="active-1"><?php echo _YES;?></label>
              <input name="active" type="radio" id="active-1" value="1" />
              <label for="active-2"><?php echo _NO;?></label>
              <input name="active" type="radio" id="active-2" value="0" checked="checked" />
              <?php echo tooltip(_MS_ACTIVE_T);?></span></td>
          </tr>
          <tr>
            <th><?php echo _MS_DESC;?>:</th>
            <td><textarea name="description<?php echo $core->dblang;?>" rows="4" cols="45"></textarea></td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php echo $core->doForm("processMembership");?>
<?php break;?>
<?php default: ?>
<div class="block-top-header">
  <h1><img src="images/mem-sml.png" alt="" /><?php echo _MS_TITLE3;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _MS_INFO3;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><span><a href="index.php?do=memberships&amp;action=add" class="button-sml"><?php echo _MS_ADD_NEW;?></a></span><?php echo _MS_SUBTITLE3;?></h2>
  </div>
  <div class="block-content">
    <table class="display sortable-table">
      <thead>
        <tr>
          <th class="firstrow">#</th>
          <th class="left sortable"><?php echo _MS_TITLE4;?></th>
          <th class="left sortable"><?php echo _MS_PRICE2;?></th>
          <th class="left sortable"><?php echo _MS_EXPIRY;?></th>
          <th class="left sortable"><?php echo _MS_DESC2;?></th>
          <th><?php echo _MS_ACTIVE2;?></th>
          <th class="right"><?php echo _ACTIONS;?></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!$member->getMemberships()):?>
        <tr>
          <td colspan="7"><?php echo $core->msgAlert(_MS_NOMBS,false);?></td>
        </tr>
        <?php else:?>
        <?php foreach ($member->getMemberships() as $row):?>
        <tr>
          <th><?php echo $row['id'];?>.</th>
          <td><?php echo $row['title'.$core->dblang];?></td>
          <td><?php echo $core->formatMoney($row['price']);?></td>
          <td><?php echo $row['days'] . ' ' . $member->getPeriod($row['period']);?></td>
          <td><?php echo $row['description'.$core->dblang];?></td>
          <td class="center"><?php echo isActive($row['active']);?></td>
          <td class="right hasimg"><a href="index.php?do=memberships&amp;action=edit&amp;id=<?php echo $row['id'];?>"><img src="images/edit.png" alt="" class="tooltip" title="<?php echo _MS_EDIT.': '.$row['title'.$core->dblang];?>"/></a><a href="javascript:void(0);" class="delete" data-title="<?php echo $row['title'.$core->dblang];?>" id="item_<?php echo $row['id'];?>"><img src="images/delete.png" class="tooltip"  alt="" title="<?php echo _DELETE;?>"/></a></td>
        </tr>
        <?php endforeach;?>
        <?php unset($row);?>
        <?php endif;?>
      </tbody>
    </table>
  </div>
</div>
<?php echo Core::doDelete(_DELETE.' '._MEMBERSHIP, "deleteMembership");?> 
<script type="text/javascript"> 
// <![CDATA[
$(document).ready(function () {
    $(".sortable-table").tablesorter({
        headers: {
            0: {
                sorter: false
            },
            5: {
                sorter: false
            },
            6: {
                sorter: false
            }
        }
    });
});
// ]]>
</script>
<?php break;?>
<?php endswitch;?>