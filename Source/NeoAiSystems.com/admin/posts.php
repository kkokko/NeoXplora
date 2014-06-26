<?php
  /**
   * Posts
   *
   * @version $Id: posts.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  
  if(!$user->getAcl("Posts")): print $core->msgAlert(_CG_ONLYADMIN, false); return; endif;
?>
<?php //include("help/posts.php");?>
<?php switch($core->action): case "edit": ?>
<?php $row = $core->getRowById("posts", $content->postid);?>
<div class="block-top-header">
  <h1><img src="images/posts-sml.png" alt="" /><?php echo _PO_TITLE1;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _PO_INFO1 . _REQ1 . required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo _PO_SUBTITLE1 . $row['title'.$core->dblang];?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo _PO_UPDATE;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=posts" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo _PO_TITLE;?>: <?php echo required();?></th>
            <td><input name="title<?php echo $core->dblang;?>" type="text" class="inputbox" value="<?php echo $row['title'.$core->dblang];?>" size="55" /></td>
          </tr>
          <tr>
            <th><?php echo _PO_PARENT;?>:</th>
            <td><select name="page_id" class="custombox" style="width:300px">
                <?php $pagerow = $content->getPages();?>
                <?php foreach ($pagerow as $prow):?>
                <?php $sel = ($row['page_id'] == $prow['id']) ? ' selected="selected"' : '' ;?>
                <option value="<?php echo $prow['id'];?>"<?php echo $sel;?>><?php echo $prow['title'.$core->dblang];?></option>
                <?php endforeach;?>
              </select></td>
          </tr>
          <tr>
            <th><?php echo _PO_PUB;?>:</th>
            <td><span class="input-out">
              <label for="active-1"><?php echo _YES;?></label>
              <input name="active" type="radio" id="active-1" value="1" <?php getChecked($row['active'], 1); ?> />
              <label for="active-2"><?php echo _NO;?></label>
              <input name="active" type="radio" id="active-2" value="0" <?php getChecked($row['active'], 0); ?> />
              </span></td>
          </tr>
          <tr>
            <th><?php echo _PO_SHOW_T;?>:</th>
            <td><span class="input-out">
              <label for="show_title-1"><?php echo _YES;?></label>
              <input name="show_title" type="radio" id="show_title-1" value="1" <?php getChecked($row['show_title'], 1); ?> />
              <label for="show_title-2"><?php echo _NO;?></label>
              <input name="show_title" type="radio" id="show_title-2" value="0" <?php getChecked($row['show_title'], 0); ?> />
              </span></td>
          </tr>
          <tr>
            <td colspan="2" class="editor"><textarea id="bodycontent" name="body<?php echo $core->dblang;?>" rows="4" cols="30"><?php echo $core->out_url($row['body'.$core->dblang]);?></textarea>
              <?php loadEditor("bodycontent"); ?></td>
          </tr>
          <tr>
            <th><?php echo _PO_JSCODE;?>:</th>
            <td><textarea name="jscode" rows="4" cols="45"><?php echo cleanOut($row['jscode']);?></textarea>
              <?php echo tooltip(_PO_JSCODE_T);?></td>
          </tr>
        </tbody>
      </table>
      <input name="postid" type="hidden" value="<?php echo $content->postid;?>" />
    </form>
  </div>
</div>
<?php echo $core->doForm("processPost","controller.php");?>
<?php break;?>
<?php case"add": ?>
<div class="block-top-header">
  <h1><img src="images/posts-sml.png" alt="" /><?php echo _PO_TITLE2;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _PO_INFO2 . _REQ1 . required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo _PO_SUBTITLE2;?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo _PO_ADD;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=posts" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo _PO_TITLE;?>: <?php echo required();?></th>
            <td><input name="title<?php echo $core->dblang;?>" type="text" class="inputbox"  size="55" title="<?php echo _PO_TITLE_R;?>"/></td>
          </tr>
          <tr>
            <th><?php echo _PO_PARENT;?>:</th>
            <td><select name="page_id" class="custombox" style="width:300px">
                <?php $pagerow = $content->getPages();?>
                <?php foreach ($pagerow as $prow):?>
                <?php $sel = ($content->pageid == $prow['id']) ? ' selected="selected"' : '' ;?>
                <option value="<?php echo $prow['id'];?>"<?php echo $sel;?>><?php echo $prow['title'.$core->dblang];?></option>
                <?php endforeach;?>
              </select></td>
          </tr>
          <tr>
            <th><?php echo _PO_PUB;?>:</th>
            <td><span class="input-out">
              <label for="active-1"><?php echo _YES;?></label>
              <input name="active" type="radio" id="active-1" value="1" checked="checked" />
              <label for="active-2"><?php echo _NO;?></label>
              <input name="active" type="radio" id="active-2" value="0" />
              </span></td>
          </tr>
          <tr>
            <th><?php echo _PO_SHOW_T;?>:</th>
            <td><span class="input-out">
              <label for="show_title-1"><?php echo _YES;?></label>
              <input name="show_title" type="radio" id="show_title-1" value="1" checked="checked" />
              <label for="show_title-2"><?php echo _NO;?></label>
              <input name="show_title" type="radio" id="show_title-2" value="0" />
              </span></td>
          </tr>
          <tr>
            <td colspan="2" class="editor"><textarea id="bodycontent" name="body<?php echo $core->dblang;?>" rows="4" cols="30"></textarea>
              <?php loadEditor("bodycontent"); ?></td>
          </tr>
          <tr>
            <th><?php echo _PO_JSCODE;?>:</th>
            <td><textarea name="jscode" rows="4" cols="45"></textarea>
              <?php echo tooltip(_PO_JSCODE_T);?></td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php echo $core->doForm("processPost","controller.php");?>
<?php break;?>
<?php default: ?>
<?php $postrow = $content->getPagePost();?>
<div class="block-top-header">
  <h1><img src="images/posts-sml.png" alt="" /><?php echo _PO_TITLE3;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _POINFO3;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><span><a href="index.php?do=posts&amp;action=add" class="button-sml"><?php echo _PO_ADD;?></a></span><?php echo _PO_SUBTITLE3;?></h2>
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
          <th class="left sortable"><?php echo _PO_TITLE;?></th>
          <th class="left sortable"><?php echo _PO_PAGE_TITLE;?></th>
          <th><?php echo _PUBLISHED;?></th>
          <th><?php echo _PO_EDIT;?></th>
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
        <?php if(!$postrow):?>
        <tr>
          <td colspan="6"><?php echo $core->msgAlert(_PO_NOPOST,false);?></td>
        </tr>
        <?php else:?>
        <?php foreach ($postrow as $row):?>
        <tr>
          <th><?php echo $row['id'];?>.</th>
          <td><?php echo $row['title'.$core->dblang];?></td>
          <td><?php echo $row['pagetitle'] ;?></td>
          <td class="center"><?php echo isActive($row['active']);?></td>
          <td class="center"><a href="index.php?do=posts&amp;action=edit&amp;postid=<?php echo $row['id'];?>"><img src="images/edit.png" class="tooltip"  alt="" title="<?php echo _PO_EDIT;?>"/></a></td>
          <td class="center"><a href="javascript:void(0);" class="delete" data-title="<?php echo $row['title'.$core->dblang];?>" id="item_<?php echo $row['id'];?>"><img src="images/delete.png" class="tooltip"  alt="" title="<?php echo _DELETE;?>"/></a></td>
        </tr>
        <?php endforeach;?>
        <?php unset($row);?>
        <?php endif;?>
      </tbody>
    </table>
  </div>
</div>
<?php echo Core::doDelete(_DELETE.' '._POST, "deletePost");?>
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
            },
            5: {
                sorter: false
            }
        }
    });
});
// ]]>
</script>
<?php break;?>
<?php endswitch;?>