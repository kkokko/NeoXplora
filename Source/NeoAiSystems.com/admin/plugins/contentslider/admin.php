<?php
  /**
   * Content Slider
   *
   * @version $Id: admin.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');

  if(!$user->getAcl("contentslider")): print $core->msgAlert(_CG_ONLYADMIN, false); return; endif;
  
  require_once("lang/" . $core->language . ".lang.php");
  require_once("admin_class.php");
  
  $slider = new ContentSlider();
?>
<?php switch($core->paction): case "edit": ?>
<?php $slrow = $core->getRowById("plug_content_slider", $slider->sliderid);?>
<div class="block-top-header">
  <h1><img src="images/plug-sml.png" alt="" /><?php echo PLG_CS_TITLE;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo PLG_CS_INFO3 . _REQ1. required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo PLG_CS_TITLE.' '.PLG_CS_UPDATE;?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo PLG_CS_UPDATE;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=plugins&amp;action=config&amp;plug=contentslider" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo PLG_CS_CAPTION;?>: <?php echo required();?></th>
            <td><input type="text" name="title<?php echo $core->dblang;?>" class="inputbox" value="<?php echo $slrow['title'.$core->dblang];?>"  size="55"/></td>
          </tr>
          <tr>
            <th><?php echo PLG_CS_DESC;?>:</th>
            <td class="editor"><textarea id="bodycontent" name="description<?php echo $core->dblang;?>" rows="4" cols="30"><?php echo $core->out_url($slrow['description'.$core->dblang]);?></textarea>
              <?php loadEditor("bodycontent"); ?></td>
          </tr>
          <tr>
            <th><?php echo PLG_CS_ALIGN_T;?>:</th>
            <td><span class="input-out">
              <label for="align-1"><?php echo PLG_CS_LEFT;?></label>
              <input name="align" type="radio" id="align-1"  value="0" <?php getChecked($slrow['align'], 0); ?> />
              <label for="align-2"><?php echo PLG_CS_RIGHT;?></label>
              <input name="align" type="radio" id="align-2" value="1" <?php getChecked($slrow['align'], 1); ?> />
              </span></td>
          </tr>
          <tr>
            <th><?php echo PLG_CS_IMG_SEL;?>:</th>
            <td><div class="fileuploader">
                <input type="text" class="filename" readonly="readonly"/>
                <input type="button" name="file" class="filebutton" value="<?php echo _BROWSE;?>"/>
                <input type="file" name="filename" />
              </div></td>
          </tr>
        </tbody>
      </table>
      <input name="sliderid" type="hidden" value="<?php echo $slider->sliderid;?>" />
    </form>
  </div>
</div>
<?php echo $core->doForm("processSliderImage","plugins/contentslider/controller.php");?>
<?php break;?>
<?php case"add": ?>
<div class="block-top-header">
  <h1><img src="images/plug-sml.png" alt="" /><?php echo PLG_CS_TITLE;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo PLG_CS_INFO3 . _REQ1. required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo PLG_CS_TITLE.' '.PLG_CS_IMGUPLOAD;?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo PLG_CS_IMGUPLOAD;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=plugins&amp;action=config&amp;plug=contentslider" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo PLG_CS_CAPTION;?>: <?php echo required();?></th>
            <td><input type="text" name="title<?php echo $core->dblang;?>" class="inputbox" title="<?php echo PLG_CS_CAPTION_R;?>" size="55"/></td>
          </tr>
          <tr>
            <th><?php echo PLG_CS_DESC;?>:</th>
            <td class="editor"><textarea id="bodycontent" name="description<?php echo $core->dblang;?>" cols="50" rows="6"></textarea>
              <?php loadEditor("bodycontent"); ?></td>
          </tr>
          <tr>
            <th><?php echo PLG_CS_ALIGN_T;?>:</th>
            <td><span class="input-out">
              <label for="align-1"><?php echo PLG_CS_LEFT;?></label>
              <input name="align" type="radio" id="align-1"  value="0" checked="checked" />
              <label for="align-2"><?php echo PLG_CS_RIGHT;?></label>
              <input name="align" type="radio" id="align-2" value="1"  />
              </span></td>
          </tr>
          <tr>
            <th><?php echo PLG_CS_IMG_SEL;?>:</th>
            <td><div class="fileuploader">
                <input type="text" class="filename" readonly="readonly"/>
                <input type="button" name="file" class="filebutton" value="<?php echo _BROWSE;?>"/>
                <input type="file" name="filename" />
              </div></td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php echo $core->doForm("processSliderImage","plugins/contentslider/controller.php");?>
<?php break;?>
<?php default: ?>
<?php $getimgs = $slider->getSliderImages();?>
<div class="block-top-header">
  <h1><img src="images/plug-sml.png" alt="" /><?php echo PLG_CS_TITLE;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo PLG_CS_INFO3 . PLG_CS_INFO3_1;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><span><a href="index.php?do=plugins&amp;action=config&amp;plug=contentslider&amp;plug_action=add" class="button-sml"><?php echo PLG_CS_IMGUPLOAD;?></a> </span><?php echo PLG_CS_SUBTITLE3 . $content->getPluginName(get("plug"));?></h2>
  </div>
  <div class="block-content">
    <table class="display" id="pagetable">
      <thead>
        <tr>
          <th class="firstrow">#</th>
          <th class="left"><?php echo PLG_CS_CAPTION;?></th>
          <th><?php echo PLG_CS_POS;?></th>
          <th><?php echo PLG_CS_VIEW;?></th>
          <th><?php echo PLG_CS_EDIT;?></th>
          <th><?php echo PLG_CS_DEL;?></th>
        </tr>
      </thead>
      <tbody>
        <?php if($getimgs == 0):?>
        <tr style="background-color:transparent">
          <td colspan="6"><?php echo $core->msgAlert(PLG_CS_NOIMG,false);?></td>
        </tr>
        <?php else:?>
        <?php foreach ($getimgs as $slrow):?>
        <tr id="node-<?php echo $slrow['id'];?>">
          <th class="id-handle center"><?php echo $slrow['id'];?>.</th>
          <td><?php echo $slrow['title'.$core->dblang];?></td>
          <td class="center"><?php echo $slrow['position'];?></td>
          <td class="center"><?php if($slrow['filename']):?>
            <a href="<?php echo SITEURL;?>/plugins/contentslider/slides/<?php echo $slrow['filename'];?>" class="fancybox" title="<?php echo $slrow['title'.$core->dblang];?>"><img src="images/view.png" class="tooltip"  alt="" title="<?php echo PLG_CS_VIEW;?>"/></a>
            <?php endif;?></td>
          <td class="center"><a href="index.php?do=plugins&amp;action=config&amp;plug=contentslider&amp;plug_action=edit&amp;sliderid=<?php echo $slrow['id'];?>"><img src="images/edit.png" class="tooltip"  alt="" title="<?php echo PLG_CS_EDIT.': '.$slrow['title'.$core->dblang];?>"/></a></td>
          <td class="center"><a href="javascript:void(0);" class="delete" data-title="<?php echo $slrow['title'.$core->dblang];?>" id="item_<?php echo $slrow['id'].':'.$slrow['filename'];?>"><img src="images/delete.png" alt="" class="tooltip" title="<?php echo PLG_CS_DEL.': '.$slrow['title'.$core->dblang];?>" /></a></td>
        </tr>
        <?php endforeach;?>
        <?php unset($slrow);?>
        <tr>
          <td colspan="6"><a href="javascript:void(0);" id="serialize" class="button"><?php echo PLG_CS_SAVE_POS;?></a></td>
        </tr>
        <?php endif;?>
      </tbody>
    </table>
  </div>
</div>
<?php echo Core::doDelete(_DELETE.' '.PLG_CS_SLIDE, "deleteSlide","plugins/contentslider/controller.php");?> 
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
            url: "plugins/contentslider/controller.php?sortslides",
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