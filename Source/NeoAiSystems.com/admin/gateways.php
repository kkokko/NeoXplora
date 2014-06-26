<?php
  /**
   * Gateways
   *
   * @version $Id: gateways.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  
  if(!$user->getAcl("Gateways")): print $core->msgAlert(_CG_ONLYADMIN, false); return; endif;
?>
<?php switch($core->action): case "edit": ?>
<?php $row = $core->getRowById("gateways", $content->id);?>
<div class="block-top-header">
  <h1><img src="images/pay-sml.png" alt="" /><?php echo _GW_TITLE1;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _GW_INFO1. _REQ1 . required(). _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><span><a href="javascript:void(0);" class="viewhelp"><img src="images/help.png" alt="" /></a></span><?php echo _GW_SUBTITLE1 . $row['displayname'];?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo _GW_UPDATE;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=gateways" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo _GW_NAME;?>: <?php echo required();?></th>
            <td><input name="displayname" type="text"  class="inputbox" value="<?php echo $row['displayname'];?>" size="45" /></td>
          </tr>
          <tr>
            <th><?php echo $row['extra_txt'];?>: </th>
            <td><input name="extra" type="text" class="inputbox" value="<?php echo $row['extra'];?>" size="45"/></td>
          </tr>
          <tr>
            <th><?php echo $row['extra_txt2'];?>:</th>
            <td><input name="extra2" type="text" class="inputbox" value="<?php echo $row['extra2'];?>" size="45"/></td>
          </tr>
          <tr>
            <th><?php echo $row['extra_txt3'];?>:</th>
            <td><?php if($row['name'] == "offline"):?>
              <textarea name="extra3" rows="4" cols="50"><?php echo $row['extra3'];?></textarea>
              <?php else:?>
              <input name="extra3" type="text" class="inputbox" value="<?php echo $row['extra3'];?>" size="45"/>
              <?php endif;?></td>
          </tr>
          <tr>
            <th><?php echo _GW_LIVE;?>:</th>
            <td><span class="input-out">
              <label for="demo-1"><?php echo _YES;?></label>
              <input name="demo" type="radio" id="demo-1"  value="1" <?php getChecked($row['demo'], 1); ?> />
              <label for="demo-2"><?php echo _NO;?></label>
              <input name="demo" type="radio" id="demo-2" value="0" <?php getChecked($row['demo'], 0); ?> />
              <?php echo tooltip(_GW_LIVE_T);?></span></td>
          </tr>
          <tr>
            <th><?php echo _GW_ACTIVE;?>:</th>
            <td><span class="input-out">
              <label for="active-1"><?php echo _YES;?></label>
              <input name="active" type="radio" id="active-1"  value="1" <?php getChecked($row['active'], 1); ?> />
              <label for="active-2"><?php echo _NO;?></label>
              <input name="active" type="radio" id="active-2" value="0" <?php getChecked($row['active'], 0); ?> />
              <?php echo tooltip(_GW_ACTIVE_T);?></span></td>
          </tr>
          <tr>
            <th><?php echo _GW_IPNURL;?>:</th>
            <td><?php echo SITEURL.'/gateways/'.$row['dir'].'/ipn.php';?></td>
          </tr>
        </tbody>
      </table>
      <input name="id" type="hidden" value="<?php echo $content->id;?>" />
    </form>
  </div>
</div>
<div id="showhelp" style="display:none"><?php echo ($row['name'] == "paypal" ? _GW_HELP_PP : ($row['name'] == "moneybookers" ? _GW_HELP_MB : _GW_HELP_OL));?></div>
<script type="text/javascript"> 
// <![CDATA[
$(document).ready(function () {
	$('a.viewhelp').on('click', function () {
		var text = $("#showhelp").html();
		$.confirm({
			title: '<?php echo $row['displayname'];?>',
			message: text,
			buttons: {
				'Close': {
					'class': 'no',
					'action': function () {}
				}
			}
		});
	});
});
// ]]>
</script> 
<?php echo $core->doForm("processGateway","controller.php");?>
<?php break;?>
<?php default: ?>
<div class="block-top-header">
  <h1><img src="images/pay-sml.png" alt="" /><?php echo _GW_TITLE2;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _GW_INFO2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo _GW_SUBTITLE2;?></h2>
  </div>
  <div class="block-content">
    <table class="display">
      <thead>
        <tr>
          <th class="firstrow">#</th>
          <th class="left"><?php echo _GW_NAME;?></th>
          <th class="right"><?php echo _EDIT;?></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!$member->getGateways()):?>
        <tr>
          <td colspan="3"><?php echo $core->msgError(_GW_NOGATEWAY,false);?></td>
        </tr>
        <?php else:?>
        <?php foreach ($member->getGateways() as $row):?>
        <tr>
          <th><?php echo $row['id'];?>.</th>
          <td><?php echo $row['displayname'];?></td>
          <td class="right"><a href="index.php?do=gateways&amp;action=edit&amp;id=<?php echo $row['id'];?>"><img src="images/edit.png" alt="" class="tooltip" title="<?php echo _GW_EDIT.': '.$row['displayname'];?>"/></a></td>
        </tr>
        <?php endforeach;?>
        <?php unset($row);?>
        <?php endif;?>
      </tbody>
    </table>
  </div>
</div>
<?php break;?>
<?php endswitch;?>