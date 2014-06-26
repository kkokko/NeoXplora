<?php
  /**
   * jQuery Tabs
   *
   * @version $Id: admin.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');

  if(!$user->getAcl("jtabs")): print $core->msgAlert(_CG_ONLYADMIN, false); return; endif;
    
  require_once("lang/" . $core->language . ".lang.php");
  require_once("admin_class.php");
  
  $tab = new jTabs();
?>
<?php switch($core->paction): case "edit": ?>
<?php $row = $core->getRowById("plug_tabs", $tab->tabid);?>
<div class="block-top-header">
  <h1><img src="images/plug-sml.png" alt="" /><?php echo PLG_JT_TITLE1;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo PLG_JT_INFO1 . _REQ1. required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo PLG_JT_SUBTITLE1 . $row['title'.$core->dblang];?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo PLG_JT_UPDATE;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=plugins&amp;action=config&amp;plug=jtabs" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo PLG_JT_TITLE;?>: <?php echo required();?></th>
            <td><input name="title<?php echo $core->dblang;?>" type="text" class="inputbox" value="<?php echo $row['title'.$core->dblang];?>" size="55" /></td>
          </tr>
          <tr>
            <th><?php echo PLG_JT_PUB;?>:</th>
            <td><span class="input-out">
              <label for="active-1"><?php echo _YES;?></label>
              <input name="active" type="radio" id="active-1" value="1" <?php getChecked($row['active'], 1); ?> />
              <label for="active-2"><?php echo _NO;?></label>
              <input name="active" type="radio" id="active-2" value="0" <?php getChecked($row['active'], 0); ?> />
              </span></td>
          </tr>
          <tr>
            <td colspan="2" class="editor"><textarea id="bodycontent" name="body<?php echo $core->dblang;?>" rows="4" cols="30"><?php echo $core->out_url($row['body'.$core->dblang]);?></textarea>
              <?php loadEditor("bodycontent"); ?></td>
          </tr>
        </tbody>
      </table>
      <input name="tabid" type="hidden" value="<?php echo $tab->tabid;?>" />
    </form>
  </div>
</div>
<?php echo $core->doForm("processTabs","plugins/jtabs/controller.php");?>
<?php break;?>
<?php case"add": ?>
<div class="block-top-header">
  <h1><img src="images/plug-sml.png" alt="" /><?php echo PLG_JT_TITLE2;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo PLG_JT_INFO2 . _REQ1. required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo PLG_JT_SUBTITLE2;?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo PLG_JT_ADD;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=plugins&amp;action=config&amp;plug=jtabs" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo PLG_JT_TITLE;?>: <?php echo required();?></th>
            <td><input name="title<?php echo $core->dblang;?>" type="text" class="inputbox" size="55" title="<?php echo PLG_JT_TITLE_R;?>"/></td>
          </tr>
          <tr>
            <th><?php echo PLG_JT_PUB;?>:</th>
            <td><span class="input-out">
              <label for="active-1"><?php echo _YES;?></label>
              <input name="active" type="radio" id="active-1" value="1" checked="checked" />
              <label for="active-2"><?php echo _NO;?></label>
              <input name="active" type="radio" id="active-2" value="0" />
              </span></td>
          </tr>
          <tr>
            <td colspan="2" class="editor"><textarea id="bodycontent" name="body<?php echo $core->dblang;?>" rows="4" cols="30"></textarea>
              <?php loadEditor("bodycontent"); ?></td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php echo $core->doForm("processTabs","plugins/jtabs/controller.php");?>
<?php break;?>
<?php default: ?>
<?php $tabrow = $tab->getTabs();?>
<div class="block-top-header">
  <h1><img src="images/plug-sml.png" alt="" /><?php echo PLG_JT_TITLE3;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo PLG_JT_INFO3;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><span><a href="index.php?do=plugins&amp;action=config&amp;plug=jtabs&amp;plug_action=add" class="button-sml"><?php echo PLG_JT_ADD;?></a></span><?php echo PLG_JT_SUBTITLE3 . $content->getPluginName(get("plug"));?></h2>
  </div>
  <div class="block-content">
    <table class="display" id="pagetable">
      <thead>
        <tr>
          <th class="firstrow">#</th>
          <th class="left"><?php echo PLG_JT_TITLE;?></th>
          <th><?php echo PLG_JT_POS;?></th>
          <th><?php echo PLG_JT_EDIT;?></th>
          <th><?php echo _DELETE;?></th>
        </tr>
      </thead>
      <tbody>
        <?php if($tabrow == 0):?>
        <tr style="background-color:transparent">
          <td colspan="5"><?php echo $core->msgAlert(PLG_JT_NONEWS,false);?></td>
        </tr>
        <?php else:?>
        <?php foreach ($tabrow as $tbrow):?>
        <?php $body = cleanOut($tbrow['body'.$core->dblang]);?>
        <tr id="node-<?php echo $tbrow['id'];?>">
          <th class="center id-handle"><?php echo $tbrow['id'];?>.</th>
          <td><?php echo $tbrow['title'.$core->dblang];?></td>
          <td class="center"><?php echo $tbrow['position'];?></td>
          <td class="center"><a href="index.php?do=plugins&amp;action=config&amp;plug=jtabs&amp;plug_action=edit&amp;tabid=<?php echo $tbrow['id'];?>"><img src="images/edit.png" class="tooltip"  alt="" title="<?php echo PLG_JT_EDIT.': '.$tbrow['title'.$core->dblang];?>"/></a></td>
          <td class="center"><a href="javascript:void(0);" class="delete" data-title="<?php echo $tbrow['title'.$core->dblang];?>" id="item_<?php echo $tbrow['id'];?>"><img src="images/delete.png" alt="" class="tooltip" title="<?php echo _DELETE.': '.$tbrow['title'.$core->dblang];?>" /></a></td>
        </tr>
        <?php endforeach;?>
        <?php unset($tbrow);?>
        <tr>
          <td colspan="5"><a href="javascript:void(0);" id="serialize" class="button"><?php echo PLG_JT_POS_SAVE;?></a></td>
        </tr>
        <?php endif;?>
      </tbody>
    </table>
  </div>
</div>
<?php echo Core::doDelete(_DELETE.' '.PLG_JT_TAB, "deleteTab","plugins/jtabs/controller.php");?> 
<script type="text/javascript"> 
// <![CDATA[
var tableHelper = function (e, tr) {
    tr.children().each(function () {
        $(this).width($(this).width());
    });
    return tr;
};
$(document).ready(function () {
    $("#pagetable tbody").sortable({
        helper: tableHelper,
        handle: '.id-handle',
        opacity: .6
    }).disableSelection();

    $('#serialize').click(function () {
        serialized = $("#pagetable tbody").sortable('serialize');
        $.ajax({
            type: "POST",
            url: "plugins/jtabs/controller.php?sorttabs",
            data: serialized,
            success: function (msg) {
                $("#msgholder").html(msg);
            }
        });
    })
});
// ]]>
</script>
<?php break;?>
<?php endswitch;?>