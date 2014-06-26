<?php
  /**
   * Menus
   *
   * @version $Id: menus.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  
  if(!$user->getAcl("Menus")): print $core->msgAlert(_CG_ONLYADMIN, false); return; endif;
?>
<?php switch($core->action): case "edit": ?>
<?php $row = $core->getRowById("menus", $content->id);?>
<div class="block-top-header">
  <h1><img src="images/menus-sml.png" alt="" /><?php echo _MU_TITLE1;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _MU_INFO1 . _REQ1 . required() . _REQ2;?></p>
<div style="float:right;width:270px;margin:0" class="box">
  <h4><?php echo _MU_MENUS;?></h4>
  <div class="clearfix">
    <div class="sortable"></div>
    <img src="images/save.png" alt="" id="serialize" title="<?php echo _MU_SAVE;?>" class="tooltip" /> </div>
</div>
<div style="margin-right:295px">
  <div class="block-border">
    <div class="block-header">
      <h2><?php echo _MU_SUBTITLE1 . $row['name'.$core->dblang];?></h2>
    </div>
    <div class="block-content">
      <form action="#" method="post" id="admin_form" name="admin_form">
        <table class="forms">
          <tfoot>
            <tr>
              <td><div class="button arrow">
                  <input type="submit" value="<?php echo _MU_UPDATE;?>" name="dosubmit" />
                  <span></span></div></td>
              <td><a href="index.php?do=menus" class="button-orange"><?php echo _CANCEL;?></a></td>
            </tr>
          </tfoot>
          <tr>
            <th><?php echo _MU_NAME;?>: <?php echo required();?></th>
            <td><input name="name<?php echo $core->dblang;?>" type="text" class="inputbox" value="<?php echo $row['name'.$core->dblang];?>" size="55"/>
              <?php echo tooltip(_MU_NAME_T);?></td>
          </tr>
          <tr>
            <th><?php echo _MU_PARENT;?>:</th>
            <td><select name="parent_id" class="custombox" style="width:200px">
                <option value="0"><?php echo _MU_TOP;?></option>
                <?php $content->getMenuDropList(0, 0,"&#166;&nbsp;&nbsp;&nbsp;&nbsp;", $row['parent_id']);?>
              </select>
              &nbsp;<?php echo tooltip(_MU_TOP_T);?></td>
          </tr>
          <tr>
            <th><?php echo _MU_TYPE;?>: <?php echo required();?></th>
            <td><select name="content_type" class="custombox" style="width:200px" id="contenttype">
                <option value="NA" selected="selected"><?php echo _MU_TYPE_SEL;?></option>
                <?php echo $content->getContentType($row['content_type']);?>
              </select>
              &nbsp;<?php echo tooltip(_MU_TYPE_SEL_T);?></td>
          </tr>
          <tr>
            <th><?php echo _MU_LINK;?>:</th>
            <td><span id="contentId">
              <?php if($row['content_type'] == "web"):?>
              <input name="web" type="text" class="inputbox" size="45" value="<?php echo $row['link'];?>"/>
              &nbsp;<?php echo tooltip(_MU_LINK_T);?>
              <select name="target" style="width:100px">
                <option value=""><?php echo _MU_TARGET;?></option>
                <option value="_blank"<?php if ($row['target'] == "_blank") echo ' selected="selected"';?>><?php echo _MU_TARGET_B;?></option>
                <option value="_self"<?php if ($row['target'] == "_self") echo ' selected="selected"';?>><?php echo _MU_TARGET_S;?></option>
              </select>
              <input name="page_id" type="hidden" value="0" />
              <?php elseif($row['content_type'] == "module"):?>
              <?php $modlist = $content->displayMenuModule();?>
              <?php if($modlist):?>
              <select name="mod_id" class="custombox" style="width:200px">
                <?php foreach($modlist as $mrow):?>
                <?php $sel = ($mrow['id'] == $row['mod_id']) ? " selected=\"selected\"" : "" ?>
                <option value="<?php echo $mrow['id'];?>"<?php echo $sel;?>><?php echo $mrow['title'.$core->dblang];?></option>
                <?php endforeach;?>
                <?php unset($mrow);?>
              </select>
              <?php endif;?>
              <?php else:?>
              <select name="page_id" class="custombox" style="width:200px">
                <?php $clist = $content->getPages();?>
                <?php foreach($clist as $crow):?>
                <?php $sel = ($crow['id'] == $row['page_id']) ? " selected=\"selected\"" : "" ?>
                <option value="<?php echo $crow['id'];?>"<?php echo $sel;?>><?php echo $crow['title'.$core->dblang];?></option>
                <?php endforeach;?>
                <?php unset($crow);?>
              </select>
              <?php endif;?>
              </span></td>
          </tr>
          <tr>
            <th><?php echo _MU_ICON;?>:</th>
            <td><div class="scrollbox">
                <div>
                  <input type="radio" name="icon" value=""  <?php if($row['icon'] =='') echo 'checked="checked"';?>/>
                  <?php echo _MU_NOICON;?></div>
                <?php print $content->getMenuIcons($row['icon']);?></div></td>
          </tr>
          <tr>
            <th><?php echo _MU_PUB;?>:</th>
            <td><span class="input-out">
              <label for="active-1"><?php echo _YES;?></label>
              <input name="active" type="radio" id="active-1" value="1" <?php getChecked($row['active'], 1); ?> />
              <label for="active-2"><?php echo _NO;?></label>
              <input name="active" type="radio" id="active-2" value="0" <?php getChecked($row['active'], 0); ?> />
              </span></td>
          </tr>
          <tr>
            <th><?php echo _MU_HOME;?>:</th>
            <td><span class="input-out">
              <label for="home_page-1"><?php echo _YES;?></label>
              <input name="home_page" type="radio" id="home_page-1" value="1" <?php getChecked($row['home_page'], 1); ?> />
              <label for="home_page-2"><?php echo _NO;?></label>
              <input name="home_page" type="radio" id="home_page-2" value="0" <?php getChecked($row['home_page'], 0); ?> />
              <?php echo tooltip(_MU_HOME_T);?></span></td>
          </tr>
        </table>
        <input name="id" type="hidden" value="<?php echo $content->id;?>" />
      </form>
    </div>
  </div>
</div>
<div class="clear"></div>
<?php break;?>
<?php default: ?>
<div class="block-top-header">
  <h1><img src="images/menus-sml.png" alt="" /><?php echo _MU_TITLE2;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _MU_INFO2;?></p>
<div style="float:right;width:270px;margin:0" class="box">
  <h4><?php echo _MU_MENUS;?></h4>
  <div class="clearfix">
    <div class="sortable"></div>
    <img src="images/save.png" alt="" id="serialize" title="<?php echo _MU_SAVE;?>" class="tooltip" /> </div>
</div>
<div style="margin-right:295px">
  <div class="block-border">
    <div class="block-header">
      <h2><?php echo _MU_SUBTITLE2;?></h2>
    </div>
    <div class="block-content">
      <form action="#" method="post" id="admin_form" name="admin_form">
        <table class="forms">
          <tfoot>
            <tr>
              <td><div class="button arrow">
                  <input type="submit" value="<?php echo _MU_ADD;?>" name="dosubmit" />
                  <span></span></div></td>
              <td>&nbsp;</td>
            </tr>
          </tfoot>
          <tbody>
            <tr>
              <th><?php echo _MU_NAME;?>: <?php echo required();?></th>
              <td><input name="name<?php echo $core->dblang;?>" type="text" class="inputbox" size="45" />
                &nbsp;<?php echo tooltip(_MU_NAME_T);?></td>
            </tr>
            <tr>
              <th><?php echo _MU_PARENT;?>:</th>
              <td><select name="parent_id" class="custombox" style="width:200px">
                  <option value="0"><?php echo _MU_TOP;?></option>
                  <?php $content->getMenuDropList(0, 0,"&#166;&nbsp;&nbsp;&nbsp;&nbsp;");?>
                </select>
                &nbsp;<?php echo tooltip(_MU_TOP_T);?></td>
            </tr>
            <tr>
              <th><?php echo _MU_TYPE;?>: <?php echo required();?></th>
              <td><select name="content_type" class="custombox" style="width:200px" id="contenttype">
                  <option value="NA" selected="selected"><?php echo _MU_TYPE_SEL;?></option>
                  <?php echo $content->getContentType();?>
                </select>
                &nbsp;<?php echo tooltip(_MU_TYPE_SEL_T);?></td>
            </tr>
            <tr>
              <th><?php echo _MU_LINK;?>:</th>
              <td><span id="contentId">
                <select name="page_id" id="content_id" class="custombox" style="width:200px">
                  <option value="0"><?php echo _MU_NONE;?></option>
                </select>
                </span></td>
            </tr>
            <tr>
              <th><?php echo _MU_ICON;?>:</th>
              <td><div class="scrollbox">
                  <div>
                    <input name="icon" type="radio" value="" checked="checked"  />
                    <?php echo _MU_NOICON;?></div>
                  <?php print $content->getMenuIcons();?> </div></td>
            </tr>
            <tr>
              <th><?php echo _MU_PUB;?>:</th>
              <td><span class="input-out">
                <label for="active-1"><?php echo _YES;?></label>
                <input name="active" type="radio" id="active-1" value="1" checked="checked" />
                <label for="active-2"><?php echo _NO;?></label>
                <input name="active" type="radio" id="active-2" value="0" />
                </span></td>
            </tr>
            <tr>
              <th><?php echo _MU_HOME;?>:</th>
              <td><span class="input-out">
                <label for="home_page-1"><?php echo _YES;?></label>
                <input name="home_page" type="radio" id="home_page-1" value="1" />
                <label for="home_page-2"><?php echo _NO;?></label>
                <input name="home_page" type="radio" id="home_page-2" value="0" checked="checked" />
                <?php echo tooltip(_MU_HOME_T);?></span></td>
            </tr>
          </tbody>
        </table>
      </form>
    </div>
  </div>
  <div class="clear"></div>
</div>
<?php break;?>
<?php endswitch;?>
<?php echo Core::doDelete(_DELETE.' '._MENU, "deleteMenu");?> 
<script type="text/javascript"> 
// <![CDATA[
$(document).ready(function () {
    function loadList() {
        $.ajax({
            type: 'post',
            url: "ajax.php",
            data: 'getmenus=1',
            cache: false,
            success: function (html) {
                $("div.sortable").html(html);
            }
        });
    }

    loadList();

    $("#admin_form").ajaxForm({
        target: "#msgholder",
        url: "controller.php",
        data: {
            processMenu: 1
        },
        success: showResponse,
    });

    function showResponse(msg) {
        $(this).html(msg);
        setTimeout(function () {
            $(loadList()).fadeIn("slow");
        }, 2000);
        $("html, body").animate({
            scrollTop: 0
        }, 600);
    }

    $('div.sortable').nestedSortable({
        forcePlaceholderSize: true,
        handle: 'div',
        helper: 'clone',
        items: 'li',
        opacity: .6,
        placeholder: 'placeholder',
        tabSize: 25,
        tolerance: 'pointer',
        toleranceElement: '> div'
    });

    $('#serialize').live('click', function () {
        serialized = $('.sortable').nestedSortable('serialize');
        serialized += '&sortmenuitems=1';
        $.ajax({
            type: 'post',
            url: "ajax.php",
            data: serialized,
            success: function (msg) {
			$("#msgholder").html(msg);
			  setTimeout(function () {
				  $(loadList()).fadeIn("slow");
			  }, 2000);
            }

        });
    })

    $('#contenttype').change(function () {
        var option = $(this).val();
        $.get('ajax.php', {
            contenttype: option
        }, function (data) {
            $('#contentId').html(data).show();
        });

    });
});
// ]]>
</script>