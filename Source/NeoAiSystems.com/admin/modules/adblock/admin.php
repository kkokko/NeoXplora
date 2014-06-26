<?php
  /**
   * AdBlock
   *
   * @package CMS Pro
   * @author wojoscripts.com
   * @copyright 2012
   * @version $Id: class_admin.php, v1.00 2012-12-24 12:12:12 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
  
  if(!$user->getAcl("adblock")): print $core->msgAlert(_CG_ONLYADMIN, false); return; endif;
  
  require_once("lang/" . $core->language . ".lang.php");
  require_once("admin_class.php");
  $adblock = new AdBlock();
?>
<?php switch($core->maction): case "edit": ?>
<?php $row = $adblock->getSingle();?>
<?php $memberlevels = ($row['memberlevels']) ? explode(',',$row['memberlevels']):array(); ?>
<div class="block-top-header">
  <h1><img src="images/mod-sml.png" alt="" /><?php echo MOD_AB_TITLE1;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo MOD_AB_INFO1 . _REQ1 . required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo MOD_AB_SUBTITLE1.' &rsaquo; '.$row['title'.$core->dblang];?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo MOD_AB_EDIT;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=modules&amp;action=config&amp;mod=adblock" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo MOD_AB_NAME;?>: <?php echo required();?></th>
            <td><input name="title<?php echo $core->dblang;?>" type="text" class="inputbox" value="<?php echo $row['title' . $core->dblang];?>" size="55" title="<?php echo MOD_AB_NAME_R;?>"/></td>
          </tr>
          <tr>
            <th><?php echo MOD_AB_DATE_S;?>: <?php echo required();?></th>
            <td><input name="date_start" type="text" class="inputbox" id="date_start" value="<?php echo $row['start_date'];?>" size="25"/></td>
          </tr>
          <tr>
            <th><?php echo MOD_AB_DATE_E;?>: <?php echo required();?></th>
            <td><input type="radio" name="date_end_no" value="1" id="date_end_no" <?php if($row['end_date'] == '0000-00-00'):?>checked="checked"<?php endif;?> />
              <?php echo MOD_AB_DATE_E_NO?><br />
              <input type="radio" name="date_end_no" value="0" id="date_end_yes" <?php if($row['end_date'] != '0000-00-00'):?>checked="checked"<?php endif;?> />
              <?php echo MOD_AB_DATE_E_YES?><br />
              <input name="date_end" type="text" class="inputbox" id="date_end" value="<?php echo date('d/m/Y',strtotime($row['end_date']))?>" size="25" <?php if($row['end_date'] == '0000-00-00'):?>style="display:none"<?php endif;?> /></td>
          </tr>
          <tr>
            <th><?php echo MOD_AB_MAX_VIEWS;?>:</th>
            <td><input name="max_views" type="text" class="inputbox" value="<?php echo $row['total_views_allowed'];?>" size="45" />
              <?php echo tooltip(MOD_AB_MAX_VIEWS_DESC);?></td>
          </tr>
          <tr>
            <th><?php echo MOD_AB_MAX_CLICKS;?>:</th>
            <td><input name="max_clicks" type="text" class="inputbox" value="<?php echo $row['total_clicks_allowed'];?>" size="45" />
              <?php echo tooltip(MOD_AB_MAX_CLICKS_DESC);?></td>
          </tr>
          <tr>
            <th><?php echo MOD_AB_MIN_CTR;?>:</th>
            <td><input name="min_ctr" type="text" class="inputbox" value="<?php echo $row['minimum_ctr'];?>" size="45" />
              <p><?php echo MOD_AB_MIN_CTR_DESC;?></p></td>
          </tr>
          <tr>
            <th><?php echo MOD_AB_BLOCK_ASSIGNMENT;?>: <?php echo required();?></th>
            <td><input name="block_assignment" type="text" class="inputbox" value="<?php echo $row['block_assignment'];?>" size="45" />
              <?php echo tooltip(MOD_AB_BLOCK_ASSIGNMENT_T) ?></td>
          </tr>
          <tr>
            <th><?php echo _UR_LEVEL;?>: <?php echo required();?></th>
            <td><span class="input-out">
              <label for="userlevel-1"><?php echo _UR_SADMIN;?></label>
              <input name="userlevel[]" type="checkbox" id="userlevel-1" value="9" <?php if(in_array(9,$memberlevels)):?>checked="checked"<?php endif;?> />
              <label for="userlevel-2"><?php echo _UR_ADMIN;?></label>
              <input name="userlevel[]" type="checkbox" id="userlevel-2" value="8" <?php if(in_array(8,$memberlevels)):?>checked="checked"<?php endif;?> />
              <label for="userlevel-3"><?php echo _USER;?></label>
              <input name="userlevel[]" type="checkbox" id="userlevel-3" value="1" <?php if(in_array(1,$memberlevels)):?>checked="checked"<?php endif;?> />
              <label for="userlevel-4"><?php echo _GUEST;?></label>
              <input name="userlevel[]" type="checkbox" id="userlevel-4" value="0" <?php if(in_array(0,$memberlevels)):?>checked="checked"<?php endif;?> />
              <?php echo tooltip(MOD_AB_ULEVEL_T);?></span></td>
          </tr>
          <tr>
            <th><?php echo MOD_AB_ADVERTISEMENT_MEDIA;?>: <?php echo required();?></th>
            <td><table>
                <tr>
                  <td><input type="radio" name="banner_type" value="0" id="show_banner_image" <?php if($row['banner_image'] != ''):?>checked="checked"<?php endif;?> />
                    <?php echo MOD_AB_BANNER_IMAGE?></td>
                </tr>
                <tr>
                  <td><input type="radio" name="banner_type" value="1" id="show_banner_html" <?php if($row['banner_image'] == ''):?>checked="checked"<?php endif;?> />
                    <?php echo MOD_AB_BANNER_HTML?> <?php echo tooltip(MOD_AB_ADVERTISEMENT_MEDIA_DESC)?></td>
                </tr>
              </table>
              <table id="banner_image_tbl" class="display">
                <tr>
                  <th><?php echo MOD_AB_BANNER_IMAGE ?>: <?php echo required();?></th>
                  <td><div class="fileuploader" <?php if($row['banner_image'] == ''):?>style="display:none"<?php endif;?>>
                      <input type="text" class="filename" readonly="readonly"/>
                      <input type="button" name="file" class="filebutton" value="<?php echo _BROWSE;?>"/>
                      <input type="file" name="banner_image" />
                    </div></td>
                </tr>
                <tr>
                  <th><?php echo MOD_AB_BANNER_LINK ?>: <?php echo required();?></th>
                  <td><input name="banner_image_link" id="banner_image_link" type="text" class="inputbox" size="45" <?php if($row['banner_image'] == ''):?>style="display:none"<?php endif;?> /></td>
                </tr>
                <tr>
                  <th><?php echo MOD_AB_BANNER_ALT ?>: <?php echo required();?></th>
                  <td><input name="banner_image_alt" id="banner_image_alt" type="text" class="inputbox" size="45" <?php if($row['banner_image'] == ''):?>style="display:none"<?php endif;?> /></td>
                </tr>
              </table>
              <textarea name="banner_html" id="banner_html" rows="4" cols="50" <?php if($row['banner_image'] != ''):?>style="display:none"<?php endif;?>><?php echo $row['banner_html']?></textarea></td>
          </tr>
        </tbody>
      </table>
      <input name="adblockid" type="hidden" value="<?php echo $adblock->adblockid;?>" />
    </form>
  </div>
</div>
<script type="text/javascript"> 
// <![CDATA[
  $(document).ready(function(){
	  $('#date_start').datepicker({
		dateFormat: 'yy-mm-dd'
	  });

	  <?php if($row['start_date']):?>
	  var startDate = '<?php echo $row['start_date']?>';
	  var parsedDate = $.datepicker.parseDate('yy-mm-dd',startDate);
	  $('#date_start').datepicker('setDate', parsedDate);
	  <?php endif; ?>
	  
	  $('#date_end').datepicker({dateFormat: 'yy-mm-dd'});
	  $('#date_end_no').click(function(){
		$('#date_end').hide();
	  });
	  $('#date_end_yes').click(function(){
			$('#date_end').show();
	  });
	  
	  $('#show_banner_image').click(function(){
		$('#banner_html').hide();
		$('#banner_image_tbl').show();
		  
	  });
	  $('#show_banner_html').click(function(){
		  $('#banner_html').show();
		  $('#banner_image_tbl').hide();
	  });
	  <?php if($row['banner_image'] == ''):?>$('#show_banner_html').click();<?php endif;?>
  });
// ]]>
</script> 
<?php echo $core->doForm("processAdBlock","modules/adblock/controller.php");?>
<?php break;?>
<?php case"add": ?>
<div class="block-top-header">
  <h1><img src="images/mod-sml.png" alt="" /><?php echo MOD_AB_TITLE2;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo MOD_AB_INFO2 . _REQ1 . required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo MOD_AB_SUBTITLE2;?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo MOD_AB_ADD;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=modules&amp;action=config&amp;mod=adblock" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo MOD_AB_NAME;?>: <?php echo required();?></th>
            <td><input name="title<?php echo $core->dblang;?>" type="text" class="inputbox" size="55" title="<?php echo MOD_AB_NAME_R;?>"/></td>
          </tr>
          <tr>
            <th><?php echo MOD_AB_DATE_S;?>: <?php echo required();?></th>
            <td><input name="date_start" type="text" class="inputbox" id="date_start" value="" size="25"/></td>
          </tr>
          <tr>
            <th><?php echo MOD_AB_DATE_E;?>: <?php echo required();?></th>
            <td><input type="radio" name="date_end_no" value="1" id="date_end_no" checked="checked" />
              <?php echo MOD_AB_DATE_E_NO?><br />
              <input type="radio" name="date_end_no" value="0" id="date_end_yes" />
              <?php echo MOD_AB_DATE_E_YES?><br />
              <input name="date_end" type="text" class="inputbox" id="date_end" value="" size="25" style="display:none"  /></td>
          </tr>
          <tr>
            <th><?php echo MOD_AB_MAX_VIEWS;?>:</th>
            <td><input name="max_views" type="text" class="inputbox" size="20" value="0"  />
              <?php echo tooltip(MOD_AB_MAX_VIEWS_DESC);?></td>
          </tr>
          <tr>
            <th><?php echo MOD_AB_MAX_CLICKS;?>:</th>
            <td><input name="max_clicks" type="text" class="inputbox" size="20" value="0" />
              <?php echo tooltip(MOD_AB_MAX_CLICKS_DESC);?></td>
          </tr>
          <tr>
            <th><?php echo MOD_AB_MIN_CTR;?>:</th>
            <td><input name="min_ctr" type="text" class="inputbox" size="20" value="0.0" />
              <p><small><?php echo MOD_AB_MIN_CTR_DESC;?></small></p></td>
          </tr>
          <tr>
            <th><?php echo MOD_AB_BLOCK_ASSIGNMENT;?>: <?php echo required();?></th>
            <td><input name="block_assignment" type="text" class="inputbox" value="" size="45" />
              <?php echo tooltip(MOD_AB_BLOCK_ASSIGNMENT_T) ?></td>
          </tr>
          <tr>
            <th><?php echo _UR_LEVEL;?>: <?php echo required();?></th>
            <td><span class="input-out">
              <label for="userlevel-1"><?php echo _UR_SADMIN;?></label>
              <input name="userlevel[]" type="checkbox" id="userlevel-1" value="9" />
              <label for="userlevel-2"><?php echo _UR_ADMIN;?></label>
              <input name="userlevel[]" type="checkbox" id="userlevel-2" value="8" />
              <label for="userlevel-3"><?php echo _USER;?></label>
              <input name="userlevel[]" type="checkbox" id="userlevel-3" value="1" />
              <label for="userlevel-4"><?php echo _GUEST;?></label>
              <input name="userlevel[]" type="checkbox" id="userlevel-4" value="0" />
              <?php echo tooltip(MOD_AB_ULEVEL_T);?></span></td>
          </tr>
          <tr>
            <th><?php echo MOD_AB_ADVERTISEMENT_MEDIA;?>: <?php echo required();?></th>
            <td><table>
                <tr>
                  <td><input type="radio" name="banner_type" value="0" id="show_banner_image" checked="checked" />
                    <?php echo MOD_AB_BANNER_IMAGE_UPL?></td>
                </tr>
                <tr>
                  <td><input type="radio" name="banner_type" value="1" id="show_banner_html" />
                    <?php echo MOD_AB_BANNER_HTML?> <?php echo tooltip(MOD_AB_ADVERTISEMENT_MEDIA_DESC)?></td>
                </tr>
              </table>
              <table id="banner_image_tbl" class="display">
                <tr>
                  <th><?php echo MOD_AB_BANNER_IMAGE ?>: <?php echo required();?></th>
                  <td><div class="fileuploader">
                      <input type="text" class="filename" readonly="readonly"/>
                      <input type="button" name="file" class="filebutton" value="<?php echo _BROWSE;?>"/>
                      <input type="file" name="banner_image" />
                    </div></td>
                </tr>
                <tr>
                  <th><?php echo MOD_AB_BANNER_LINK ?>: <?php echo required();?></th>
                  <td><input name="banner_image_link" id="banner_image_link" type="text" class="inputbox" size="45" /></td>
                </tr>
                <tr>
                  <th><?php echo MOD_AB_BANNER_ALT ?>: <?php echo required();?></th>
                  <td><input name="banner_image_alt" id="banner_image_alt" type="text" class="inputbox" size="45" /></td>
                </tr>
              </table>
              <textarea name="banner_html" id="banner_html" rows="4" cols="50" style="display:none"></textarea></td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<script type="text/javascript"> 
// <![CDATA[
$(document).ready(function () {
	$('#date_start, #date_end').datepicker({
		dateFormat: 'yy-mm-dd'
	});
	$('#date_end_no').click(function () {
		$('#date_end').hide();
	});
	$('#date_end_yes').click(function () {
		$('#date_end').show();
	});
	$('#show_banner_image').click(function () {
		$('#banner_html').hide();
		$('#banner_image_tbl').show();
	});
	$('#show_banner_html').click(function () {
		$('#banner_html').show();
		$('#banner_image_tbl').hide();
	});
});
// ]]>
</script> 
<?php echo $core->doForm("processAdBlock","modules/adblock/controller.php");?>
<?php break;?>
<?php default: ?>
<?php $adrow = $adblock->getAdBlock();?>
<div class="block-top-header">
  <h1><img src="images/mod-sml.png" alt="" /><?php echo MOD_AB_TITLE4;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo MOD_AB_INFO4;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><span><a href="index.php?do=modules&amp;action=config&amp;mod=adblock&amp;mod_action=add"><?php echo MOD_AB_ADD;?></a></span><?php echo MOD_AB_SUBTITLE3 . $content->getModuleName(get("mod"));?></h2>
  </div>
  <div class="block-content">
    <table class="display sortable-table">
      <thead>
        <tr>
          <th class="firstrow">#</th>
          <th class="left sortable"><?php echo MOD_AB_NAME;?></th>
          <th class="left sortable"><?php echo MOD_AB_CREATED;?></th>
          <th class="left sortable"><?php echo MOD_AB_IS_ONLINE;?></th>
          <th class="left sortable"><?php echo MOD_AB_BLOCK_ASSIGNMENT;?></th>
          <th><?php echo _EDIT;?></th>
          <th><?php echo _DELETE;?></th>
        </tr>
      </thead>
      <?php if($pager->display_pages()):?>
      <tfoot>
        <tr>
          <td colspan="6"><div class="pagination"><?php echo $pager->display_pages();?></div></td>
        </tr>
      </tfoot>
      <?php endif;?>
      <tbody>
        <?php if(!$adrow):?>
        <tr>
          <td colspan="7"><?php echo $core->msgAlert(MOD_AB_NOADBLOCKS,false);?></td>
        </tr>
        <?php else:?>
        <?php foreach ($adrow as $row):?>
        <tr>
          <th><?php echo $row['id'];?>.</th>
          <td><?php echo $row['title'.$core->dblang];?></td>
          <td><?php echo $row['created'];?></td>
          <td><?php echo $row['is_online_str'];?></td>
          <td><?php echo $row['block_assignment'];?></td>
          <td class="center"><a href="index.php?do=modules&amp;action=config&amp;mod=adblock&amp;mod_action=edit&amp;adblockid=<?php echo $row['id'];?>"><img src="images/edit.png" class="tooltip"  alt="" title="<?php echo _EDIT;?>"/></a></td>
          <td class="center"><a href="javascript:void(0);" class="delete" data-title="<?php echo $row['title'.$core->dblang];?>" id="item_<?php echo $row['id'];?>"><img src="images/delete.png" class="tooltip"  alt="" title="<?php echo _DELETE;?>"/></a></td>
        </tr>
        <?php endforeach;?>
        <?php unset($row);?>
        <?php endif;?>
      </tbody>
    </table>
  </div>
</div>
<?php echo Core::doDelete(_DELETE.' '.MOD_AB_ADBLOCK, "deleteAdBlock","modules/adblock/controller.php");?> 
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