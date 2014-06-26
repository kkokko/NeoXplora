<?php
  /**
   * Modules
   *
   * @version $Id: modules.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  
  if(!$user->getAcl("Modules")): print $core->msgAlert(_CG_ONLYADMIN, false); return; endif;
?>
<?php switch($core->action): case "edit": ?>
<?php $row = $core->getRowById("modules", $content->id);?>
<div class="block-top-header">
  <h1><img src="images/mod-sml.png" alt="" /><?php echo _MO_TITLE1;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _MO_INFO1 . _REQ1 . required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><span><small>v.<?php echo $row['ver'];?></small></span><?php echo _MO_SUBTITLE1 . $row['title'.$core->dblang];?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo _MO_UPDATE;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=modules" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo _MO_TITLE;?>: <?php echo required();?></th>
            <td><input name="title<?php echo $core->dblang;?>" type="text" class="inputbox" value="<?php echo $row['title'.$core->dblang];?>" size="55"/></td>
          </tr>
          <tr>
            <th><?php echo _MO_DESC;?>:</th>
            <td><textarea cols="60" name="info<?php echo $core->dblang;?>" rows="3"><?php echo $row['info'.$core->dblang];?></textarea></td>
          </tr>
          <?php if($row['system']):?>
          <tr>
            <th><?php echo _MO_THEME;?></th>
            <td><select name="theme" class="custombox" style="width:250px">
                <option value=""><?php echo _MO_THEME_DEFAULT;?></option>
                <?php getTemplates(WOJOLITE."/theme/", $row['theme']);?>
              </select></td>
          </tr>
          <?php endif;?>
          <tr>
            <th><?php echo _METAKEYS;?>:</th>
            <td><input name="metakey<?php echo $core->dblang;?>" type="text" value="<?php echo $row['metakey'.$core->dblang];?>" class="inputbox" size="45"  />
              <?php echo tooltip(_CG_METAKEY_T);?></td>
          </tr>
          <tr>
            <th><?php echo _METADESC;?>:</th>
            <td><textarea name="metadesc<?php echo $core->dblang;?>" cols="60" rows="5" class="inputbox"><?php echo $row['metadesc'.$core->dblang];?></textarea>
              <?php echo tooltip(_CG_METADESC_T);?></td>
          </tr>
        </tbody>
      </table>
      <input name="id" type="hidden" value="<?php echo $content->id;?>" />
    </form>
  </div>
</div>
<?php echo $core->doForm("processModule","controller.php");?>
<?php break;?>
<?php case"config": ?>
<?php $admfile = WOJOLITE . "admin/modules/".sanitize(get("mod"))."/admin.php";?>
<?php if(file_exists($admfile)) include_once($admfile);?>
<?php break;?>
<?php default: ?>
<?php $module = $content->getPageModules();?>
<div class="block-top-header">
  <h1><img src="images/mod-sml.png" alt="" /><?php echo _MO_TITLE3;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _MO_INFO3;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo _MO_SUBTITLE3;?></h2>
  </div>
  <div class="block-content">
    <?php if($pager->display_pages()):?>
    <div class="utility">
      <table class="display">
        <tr>
          <td class="right"><?php echo $pager->items_per_page();?>&nbsp;&nbsp;<?php echo $pager->jump_menu();?></td>
        </tr>
      </table>
    </div>
    <?php endif;?>
    <table class="display sortable-table">
      <thead>
        <tr>
          <th class="firstrow">#</th>
          <th class="left sortable"><?php echo _MO_TITLE;?></th>
          <th class="left sortable"><?php echo _MO_CREATED;?></th>
          <th><?php echo _MO_PUB2;?></th>
          <th class="right"><?php echo _ACTIONS;?></th>
        </tr>
      </thead>
      <?php if($pager->display_pages()):?>
      <tfoot>
        <tr>
          <td colspan="5"><div class="pagination"><?php echo $pager->display_pages();?></div></td>
        </tr>
      </tfoot>
      <?php endif;?>
      <tbody>
        <?php if(!$module):?>
        <tr>
          <td colspan="5"><?php echo $core->msgAlert(_MO_NOMOD,false);?></td>
        </tr>
        <?php else:?>
        <?php foreach ($module as $row):?>
        <tr>
          <th><?php echo $row['id'];?>.</th>
          <td><?php echo $row['title'.$core->dblang];?></td>
          <td><?php echo dodate($core->short_date, $row['created']);?></td>
          <td class="center"><?php echo isActive($row['active']);?></td>
          <td class="right hasimg"><a href="index.php?do=modules&amp;action=edit&amp;id=<?php echo $row['id'];?>"><img src="images/edit.png" class="tooltip"  alt="" title="<?php echo _MO_EDIT;?>"/></a>
            <?php if($row['hasconfig'] == 1):?>
            <a href="index.php?do=modules&amp;action=config&amp;mod=<?php echo $row['modalias'];?>"><img src="images/mod-config.png" class="tooltip" alt="" title="<?php echo _MO_CONFIG.': '.$row['title'.$core->dblang];?>"/></a>
            <?php endif;?>
            <a href="javascript:void(0);" class="delete" data-title="<?php echo $row['title'.$core->dblang];?>" id="item_<?php echo $row['id'];?>"><img src="images/delete.png" class="tooltip"  alt="" title="<?php echo _DELETE;?>"/></a></td>
        </tr>
        <?php endforeach;?>
        <?php unset($row);?>
        <?php endif;?>
      </tbody>
    </table>
  </div>
</div>
<?php echo Core::doDelete(_DELETE.' '._MODULE, "deleteModule");?> 
<script type="text/javascript"> 
// <![CDATA[
$(document).ready(function () {
    $(".sortable-table").tablesorter({
        headers: {
            0: {
                sorter: false
            },
            3: {
                sorter: false
            },
            4: {
                sorter: false
            }
        }
    });
});
// ]]>
</script>
<?php break;?>
<?php endswitch;?>