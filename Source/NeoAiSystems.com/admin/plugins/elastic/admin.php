<?php
  /**
   * Elastic Slider
   *
   * @version $Id: admin.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');

  if(!$user->getAcl("elastic")): print $core->msgAlert(_CG_ONLYADMIN, false); return; endif;
  
  require_once("lang/" . $core->language . ".lang.php");
  require_once("admin_class.php");
  
  $slider = new elasticSlider();
?>
<?php switch($core->paction): case "edit": ?>
<?php $slrow = $core->getRowById("plug_elastic", $slider->sliderid);?>
<div class="block-top-header">
  <h1><img src="images/plug-sml.png" alt="" /><?php echo PLG_JQE_TITLE;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo PLG_JQE_INFO3 . _REQ1 . required() . _REQ2;;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo PLG_JQE_TITLE.' '.PLG_JQE_UPDATE;?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo PLG_JQE_UPDATE;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=plugins&amp;action=config&amp;plug=elastic" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo PLG_JQE_CAPTION;?>: <?php echo required();?></th>
            <td><input type="text" name="title<?php echo $core->dblang;?>" class="inputbox" value="<?php echo $slrow['title'.$core->dblang];?>"  size="55"/></td>
          </tr>
          <tr>
            <th><?php echo PLG_JQE_DESC;?>:</th>
            <td><input type="text" name="description<?php echo $core->dblang;?>" class="inputbox" value="<?php echo $slrow['description'.$core->dblang];?>" size="55"/></td>
          </tr>
          <tr>
            <th><?php echo PLG_JQE_IMG_SEL;?>:</th>
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
<?php echo $core->doForm("processSliderImage","plugins/elastic/controller.php");?>
<?php break;?>
<?php case"add": ?>
<div class="block-top-header">
  <h1><img src="images/plug-sml.png" alt="" /><?php echo PLG_JQE_TITLE;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo PLG_JQE_INFO3 . _REQ1 . required() . _REQ2;;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo PLG_JQE_TITLE.' '.PLG_JQE_IMGUPLOAD;?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo PLG_JQE_IMGUPLOAD;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=plugins&amp;action=config&amp;plug=elastic" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo PLG_JQE_CAPTION;?>: <?php echo required();?></th>
            <td><input type="text" name="title<?php echo $core->dblang;?>" class="inputbox" size="55"/></td>
          </tr>
          <tr>
            <th><?php echo PLG_JQE_DESC;?>:</th>
            <td><input type="text" name="description<?php echo $core->dblang;?>" class="inputbox" size="55"/></td>
          </tr>
          <tr>
            <th><?php echo PLG_JQE_IMG_SEL;?>:</th>
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
<?php echo $core->doForm("processSliderImage","plugins/elastic/controller.php");?>
<?php break;?>
<?php case"configure": ?>
<?php $conf = $slider->getConfiguration();?>
<div class="block-top-header">
  <h1><img src="images/plug-sml.png" alt="" /><?php echo PLG_JQE_TITLE;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo PLG_JQE_INFO3;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo PLG_JQE_TITLE.' '.PLG_JQE_CONF;?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo PLG_JQE_BUT_CONF_U;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=plugins&amp;action=config&amp;plug=elastic" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo PLG_JQE_ANITYPE;?>:</th>
            <td><select name="animation" class="custombox" style="width:150px">
                <option value="center"<?php if($conf['animation'] == 'center') echo ' selected="selected"';?>><?php echo PLG_JQE_ANITYPE_C;?></option>
                <option value="sides"<?php if($conf['animation'] == 'sides') echo ' selected="selected"';?>><?php echo PLG_JQE_ANITYPE_S;?></option>
              </select>
              <?php echo tooltip(PLG_JQE_ANITYPE_T);?></td>
          </tr>
          <tr>
            <th><?php echo PLG_JQE_AUTOPLAY;?>:</th>
            <td><?php echo _YES;?>
              <input name="autoplay" type="radio" value="1" <?php getChecked($conf['autoplay'], 1); ?> />
              <?php echo _NO;?>
              <input name="autoplay" type="radio" value="0" <?php getChecked($conf['autoplay'], 0); ?>  />
              <?php echo tooltip(PLG_JQE_AUTOPLAY_T);?></td>
          </tr>
          <tr>
            <th><?php echo PLG_JQE_INTERVAL;?>: <?php echo required();?></th>
            <td><input type="text" name="interval" class="inputbox" value="<?php echo $conf['interval'];?>" size="5"/>
              <?php echo tooltip(PLG_JQE_INTERVAL_T);?></td>
          </tr>
          <tr>
            <th><?php echo PLG_JQE_SPEED;?>: <?php echo required();?></th>
            <td><input type="text" name="speed" class="inputbox" value="<?php echo $conf['speed'];?>" size="5"/>
              <?php echo tooltip(PLG_JQE_SPEED_T);?></td>
          </tr>
          <tr>
            <th><?php echo PLG_JQE_TITLESPEED;?>: <?php echo required();?></th>
            <td><input type="text" name="titlespeed" class="inputbox" value="<?php echo $conf['titlespeed'];?>" size="5"/>
              <?php echo tooltip(PLG_JQE_TITLESPEED_T);?></td>
          </tr>
          <tr>
            <th><?php echo PLG_JQE_HEIGHT;?>: <?php echo required();?></th>
            <td><input type="text" name="height" class="inputbox" value="<?php echo $conf['height'];?>" size="5"/>
              <?php echo tooltip(PLG_JQE_HEIGHT_T);?></td>
          </tr>
          <tr>
            <th><?php echo PLG_JQE_THUMB;?>: <?php echo required();?></th>
            <td><input type="text" name="thumbMaxWidth" class="inputbox" value="<?php echo $conf['thumbMaxWidth'];?>" size="5"/>
              <?php echo tooltip(PLG_JQE_THUMB_T);?></td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php echo $core->doForm("updateConfig","plugins/elastic/controller.php");?>
<?php break;?>
<?php default: ?>
<?php $getimgs = $slider->getSliderImages();?>
<div class="block-top-header">
  <h1><img src="images/plug-sml.png" alt="" /><?php echo PLG_JQE_TITLE;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo PLG_JQE_INFO3 . PLG_JQE_INFO3_1;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><span><a href="index.php?do=plugins&amp;action=config&amp;plug=elastic&amp;plug_action=configure"><?php echo PLG_JQE_CONF;?></a> <a href="index.php?do=plugins&amp;action=config&amp;plug=elastic&amp;plug_action=add"><?php echo PLG_JQE_IMGUPLOAD;?></a> </span><?php echo PLG_JQE_SUBTITLE3 . $content->getPluginName(get("plug"));?></h2>
  </div>
  <div class="block-content">
    <table class="display" id="pagetable">
      <thead>
        <tr>
          <th class="firstrow">#</th>
          <th class="left"><?php echo PLG_JQE_CAPTION;?></th>
          <th><?php echo PLG_JQE_POS;?></th>
          <th><?php echo PLG_JQE_EDIT;?></th>
          <th><?php echo PLG_JQE_DEL;?></th>
        </tr>
      </thead>
      <tbody>
        <?php if($getimgs == 0):?>
        <tr>
          <td colspan="5"><?php echo $core->msgAlert(PLG_JQE_NOIMG,false);?></td>
        </tr>
        <?php else:?>
        <?php foreach ($getimgs as $slrow):?>
        <tr id="node-<?php echo $slrow['id'];?>">
          <th class="id-handle center"><?php echo $slrow['id'];?>.</th>
          <td><?php echo $slrow['title'.$core->dblang];?></td>
          <td class="center"><?php echo $slrow['position'];?></td>
          <td class="center"><a href="index.php?do=plugins&amp;action=config&amp;plug=elastic&amp;plug_action=edit&amp;sliderid=<?php echo $slrow['id'];?>"><img src="images/edit.png" class="tooltip"  alt="" title="<?php echo PLG_JQE_EDIT.': '.$slrow['title'.$core->dblang];?>"/></a></td>
          <td class="center"><a href="javascript:void(0);" class="delete" data-title="<?php echo $slrow['title'.$core->dblang];?>" id="item_<?php echo $slrow['id'].':'.$slrow['filename'];?>"><img src="images/delete.png" alt="" class="tooltip" title="<?php echo PLG_JQE_DEL.': '.$slrow['title'.$core->dblang];?>" /></a></td>
        </tr>
        <?php endforeach;?>
        <?php unset($slrow);?>
        <tr>
          <td colspan="5"><a href="javascript:void(0);" id="serialize" class="button"><?php echo PLG_JQE_SAVE_POS;?></a></td>
        </tr>
        <?php endif;?>
      </tbody>
    </table>
  </div>
</div>
<?php echo Core::doDelete(_DELETE.' '.PLG_JQE_SLIDE, "deleteSlide","plugins/elastic/controller.php");?> 
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
            url: "plugins/elastic/controller.php?sortslides",
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