<?php
  /**
   * Plugins
   *
   * @version $Id: plugins.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  
  if(!$user->getAcl("Plugins")): print $core->msgAlert(_CG_ONLYADMIN, false); return; endif;
?>
<?php switch($core->action): case "edit": ?>
<?php $row = $core->getRowById("plugins", $content->id);?>
<div class="block-top-header">
  <h1><img src="images/plug-sml.png" alt="" /><?php echo _PL_TITLE1;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _PL_INFO1 . _REQ1 . required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><span><small>v.<?php echo $row['ver'];?></small></span><?php echo _PL_SUBTITLE1 . $row['title'.$core->dblang];?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo _PL_UPDATE;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=plugins" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo _PL_TITLE;?>: <?php echo required();?></th>
            <td><input name="title<?php echo $core->dblang;?>" type="text" class="inputbox" value="<?php echo $row['title'.$core->dblang];?>" size="55" /></td>
          </tr>
          <tr>
            <th><?php echo _PL_PUB;?>:</th>
            <td><span class="input-out">
              <label for="active-1"><?php echo _YES;?></label>
              <input name="active" type="radio" id="active-1" value="1" <?php getChecked($row['active'], 1); ?> />
              <label for="active-2"><?php echo _NO;?></label>
              <input name="active" type="radio" id="active-2" value="0" <?php getChecked($row['active'], 0); ?> />
              </span></td>
          </tr>
          <tr>
            <th><?php echo _PL_SHOW_TITLE;?>:</th>
            <td><span class="input-out">
              <label for="show_title-1"><?php echo _YES;?></label>
              <input name="show_title" type="radio" id="show_title-1" value="1" <?php getChecked($row['show_title'], 1); ?> />
              <label for="show_title-2"><?php echo _NO;?></label>
              <input name="show_title" type="radio" id="show_title-2" value="0" <?php getChecked($row['show_title'], 0); ?> />
              </span></td>
          </tr>
          <tr>
            <th><?php echo _PL_ALT_CLASS;?>:</th>
            <td><input name="alt_class" type="text" class="inputbox" value="<?php echo $row['alt_class'];?>" size="55"/>
              &nbsp;&nbsp; <?php echo tooltip(_PL_ALT_CLASS_T);?></td>
          </tr>
          <tr>
            <td colspan="2" class="editor"><textarea id="bodycontent" name="body<?php echo $core->dblang;?>" rows="4" cols="30"><?php echo $core->out_url($row['body'.$core->dblang]);?></textarea>
              <?php loadEditor("bodycontent"); ?></td>
          </tr>
          <tr>
            <th><?php echo _PL_DESC;?>:</th>
            <td><textarea cols="60" name="info<?php echo $core->dblang;?>" rows="3"><?php echo $row['info'.$core->dblang];?></textarea></td>
          </tr>
          <?php if(!$row['system']):?>
          <tr>
            <th><?php echo _PO_JSCODE;?>:</th>
            <td><textarea name="jscode" rows="4" cols="60"><?php echo cleanOut($row['jscode']);?></textarea>
              <?php echo tooltip(_PO_JSCODE_T);?></td>
          </tr>
          <?php endif;?>
        </tbody>
      </table>
      <input name="id" type="hidden" value="<?php echo $content->id;?>" />
    </form>
  </div>
</div>
<?php echo $core->doForm("processPlugin");?>
<?php break;?>
<?php case"add": ?>
<div class="block-top-header">
  <h1><img src="images/plug-sml.png" alt="" /><?php echo _PL_TITLE2;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _PL_INFO2 . _REQ1 . required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo _PL_SUBTITLE2;?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo _PL_ADD;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=plugins" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo _PL_TITLE;?>: <?php echo required();?></th>
            <td><input name="title<?php echo $core->dblang;?>" type="text" class="inputbox"  size="55" /></td>
          </tr>
          <tr>
            <th><?php echo _PL_PUB;?>:</th>
            <td><span class="input-out">
              <label for="active-1"><?php echo _YES;?></label>
              <input name="active" type="radio" id="active-1" value="1" checked="checked" />
              <label for="active-2"><?php echo _NO;?></label>
              <input name="active" type="radio" id="active-2" value="0" />
              </span></td>
          </tr>
          <tr>
            <th><?php echo _PL_SHOW_TITLE;?>:</th>
            <td><span class="input-out">
              <label for="show_title-1"><?php echo _YES;?></label>
              <input name="show_title" type="radio" id="show_title-1" value="1" checked="checked" />
              <label for="show_title-2"><?php echo _NO;?></label>
              <input name="show_title" type="radio" id="show_title-2" value="0" />
              </span></td>
          </tr>
          <tr>
            <th><?php echo _PL_ALT_CLASS;?>:</th>
            <td><input name="alt_class" type="text" class="inputbox" size="55"/>
              &nbsp;&nbsp; <?php echo tooltip(_PL_ALT_CLASS_T);?></td>
          </tr>
          <tr>
            <td colspan="2" class="editor"><textarea id="bodycontent" name="body<?php echo $core->dblang;?>" rows="4" cols="30"></textarea>
              <?php loadEditor("bodycontent"); ?></td>
          </tr>
          <tr>
            <th><?php echo _PL_DESC;?>:</th>
            <td><textarea cols="60" rows="3" name="info<?php echo $core->dblang;?>"></textarea></td>
          </tr>
          <tr>
            <th><?php echo _PO_JSCODE;?>:</th>
            <td><textarea name="jscode" rows="4" cols="60"></textarea>
              <?php echo tooltip(_PO_JSCODE_T);?></td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php echo $core->doForm("processPlugin");?>
<?php break;?>
<?php case"config": ?>
<?php $admfile = WOJOLITE . "admin/plugins/".sanitize(get("plug"))."/admin.php";?>
<?php if(file_exists($admfile)) include_once($admfile);?>
<?php break;?>
<?php default: ?>
<?php $plugin = $content->getPagePlugins();?>
<div class="block-top-header">
  <h1><img src="images/plug-sml.png" alt="" /><?php echo _PL_TITLE3;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _PL_INFO3;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><span><a href="index.php?do=plugins&amp;action=add" class="button-sml"><?php echo _PL_ADD;?></a></span><?php echo _PL_SUBTITLE3;?></h2>
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
          <th class="left sortable"><?php echo _PL_TITLE;?></th>
          <th class="left sortable"><?php echo _PL_CREATED;?></th>
          <th><?php echo _PL_PUB2;?></th>
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
        <?php if(!$plugin):?>
        <tr>
          <td colspan="5"><?php echo $core->msgAlert(_PL_NOPLUG,false);?></td>
        </tr>
        <?php else:?>
        <?php foreach ($plugin as $row):?>
        <tr>
          <th><?php echo $row['id'];?>.</th>
          <td><?php echo $row['title'.$core->dblang];?></td>
          <td><?php echo dodate($core->short_date, $row['created']);?></td>
          <td class="center"><?php echo isActive($row['active']);?></td>
          <td class="right hasimg"><a href="index.php?do=plugins&amp;action=edit&amp;id=<?php echo $row['id'];?>"><img src="images/edit.png" class="tooltip"  alt="" title="<?php echo _PL_EDIT;?>"/></a>
            <?php if($row['hasconfig'] == 1):?>
            <a href="index.php?do=plugins&amp;action=config&amp;plug=<?php echo $row['plugalias'];?>"><img src="images/mod-config.png" class="tooltip" alt="" title="<?php echo _PL_CONFIG.': '.$row['title'.$core->dblang];?>"/></a>
            <?php endif;?>
            <?php if($row['system'] == 0):?>
            <a href="javascript:void(0);" class="delete" data-title="<?php echo $row['title'.$core->dblang];?>" id="item_<?php echo $row['id'];?>"><img src="images/delete.png" class="tooltip"  alt="" title="<?php echo _DELETE;?>"/></a>
            <?php else:?>
            <img src="images/sys-module.png" class="tooltip" alt="" title="<?php echo _PL_SYS.': '.$row['title'.$core->dblang];?>"/>
            <?php endif;?></td>
        </tr>
        <?php endforeach;?>
        <?php unset($row);?>
        <?php endif;?>
      </tbody>
    </table>
  </div>
</div>
<?php echo Core::doDelete(_DELETE.' '._PLUGIN, "deletePlugin");?> 
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