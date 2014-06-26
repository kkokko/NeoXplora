<?php
  /**
   * Pages
   *
   * @version $Id: pages.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  
  if(!$user->getAcl("Pages")): print $core->msgAlert(_CG_ONLYADMIN, false); return; endif;
?>
<?php //include_once("help/pages.php");?>
<?php switch($core->action): case "edit": ?>
<?php $row = $core->getRowById("pages", $content->pageid);?>
<?php $postrow = $content->getPosts();?>
<div class="block-top-header">
  <h1><img src="images/pages-sml.png" alt="" /><?php echo _PG_TITLE1;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _PG_INFO1. _REQ1 . required(). _REQ2;?></p>
<!--<div style="float:right;width:270px;margin:0" class="box">
  <div class="postmenu">
    <?php if($postrow == 0):?>
    <?php echo $core->msgAlert(_PG_NO_POSTS,false);?>
    <?php else:?>
    <h4><?php echo _PG_YES_POSTS;?></h4>
    <ul id="sortable-list">
      <?php foreach ($postrow as $i => $prow):?>
      <li id="pid_<?php echo $prow['id'];?>"> <?php echo $i+1;?>. <a href="index.php?do=posts&amp;action=edit&amp;postid=<?php echo $prow['id'];?>"><?php echo $prow['title'.$core->dblang];?></a></li>
      <?php endforeach;?>
    </ul>
    <?php unset($prow);?>
    <?php endif;?>
  </div>
</div>-->
<div >
  <div class="block-border">
    <div class="block-header">
      <h2><?php echo _PG_SUBTITLE1 . $row['title'.$core->dblang];?></h2>
    </div>
    <div class="block-content">
      <form action="#" method="post" id="admin_form" name="admin_form">
        <table class="forms">
          <tfoot>
            <tr>
              <td><div class="button arrow">
                  <input type="submit" value="<?php echo _PG_UPDATE;?>" name="dosubmit" />
                  <span></span></div></td>
              <td><a href="index.php?do=pages" class="button-orange"><?php echo _CANCEL;?></a></td>
            </tr>
          </tfoot>
          <tbody>
            <tr>
              <th><?php echo _PG_TITLE;?>: <?php echo required();?></th>
              <td><input name="title<?php echo $core->dblang;?>" type="text" class="inputbox" value="<?php echo $row['title'.$core->dblang];?>" size="55" /></td>
            </tr>
            <tr>
              <th><?php echo _PG_SLUG;?>:</th>
              <td><input name="slug" type="text" class="inputbox" value="<?php echo $row['slug'];?>" size="55" />
                &nbsp; <?php echo tooltip(_PG_SLUG_T);?></td>
            </tr>
            <tr>
              <th><?php echo _PG_CC;?>:</th>
              <td><span class="input-out">
                <label for="contact_form-1"><?php echo _YES;?></label>
                <input name="contact_form" type="radio" id="contact_form-1" value="1" <?php getChecked($row['contact_form'], 1); ?> />
                <label for="contact_form-2"><?php echo _NO;?></label>
                <input name="contact_form" type="radio" id="contact_form-2" value="0" <?php getChecked($row['contact_form'], 0); ?> />
                <?php echo tooltip(_PG_CC_T);?></span></td>
            </tr>
            <tr>
              <th><?php echo _PG_ACCESS_L;?>:</th>
              <td><?php echo $member->getAccessList($row['access']);?></td>
            </tr>
       
            <tr id="memrow" style="display:none">
              <th><?php echo _PG_MEM_LEVEL;?>:</th>
              <td id="membership"><?php if($row['membership_id'] == 0):?>
                <?php echo _PG_NOMEM_REQ;?>
                <?php else:?>
                <?php echo $member->getMembershipList($row['membership_id']);?>
                <?php endif;?></td>
            </tr>
            <tr style="display:none">
              <th><?php echo _PG_SEL_MODULE;?>:</th>
              <td><?php echo $content->getModuleList($row['module_id']);?></td>
            </tr>
            <tr id="modshow">
              <th>&nbsp;</th>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <th><?php echo _PG_KEYS;?>:</th>
              <td><input name="keywords<?php echo $core->dblang;?>" type="text" class="inputbox" value="<?php echo $row['keywords'.$core->dblang];?>" size="55" />
                &nbsp;&nbsp; <?php echo tooltip(_PG_KEYS_T);?></td>
            </tr>
            <tr>
              <th><?php echo _PG_DESC;?>:</th>
              <td ><textarea name="description<?php echo $core->dblang;?>" rows="6" cols="55"><?php echo $row['description'.$core->dblang];?></textarea>
            
              </td>
            </tr>
            <tr>
            <td colspan="2" class="editor"><textarea id="bodycontent" name="body<?php echo $core->dblang;?>" rows="4" cols="30"><?php echo $core->out_url($row['body'.$core->dblang]);?></textarea>
              <?php loadEditor("bodycontent"); ?></td>
          </tr>
          </tbody>
        </table>
        <input name="pageid" type="hidden" value="<?php echo $content->pageid;?>" />
      </form>
    </div>
  </div>
</div>
<div class="clear"></div>
<?php echo $core->doForm("processPage");?> 
<script type="text/javascript"> 
// <![CDATA[
$(document).ready(function() {
    $('#access_id').change(function() {
        var option = $(this).val();
        var result = 'pageid=<?php echo $content->pageid;?>';
        result += '&membershiplist=' + option;
		  $.ajax({
			  type: "post",
			  url: "ajax.php",
			  data: result,
			  cache: false,
			  success: function (res) {
				  (option == "Membership") ? $('#memrow').show(): $('#memrow').hide();
				  $('#membership').html(res);
			  }
		  });
    });

    function loadList() {
        var option = $('#modulename').val();
		var result = 'module_data=<?php echo $row['module_data'];?>';
        result += '&modulelist=' + option;
		  $.ajax({
			  type: "post",
			  url: "ajax.php",
			  data: result,
			  cache: false,
			  success: function (res) {
				  (option == "<?php echo $row['module_id'];?>") ? $('#modshow').show(): $('#modshow').hide();
				  $('#modshow').html(res);
			  }
		  });
    }

    loadList();
	
    $('#modulename').change(function() {
		var option = $(this).val();
		var result = 'module_data=<?php echo $row['module_data'];?>';
        result += '&modulelist=' + option;
		  $.ajax({
			  type: "post",
			  url: "ajax.php",
			  data: result,
			  cache: false,
			  success: function (res) {
				  $('#modshow').html(res);
			  }
		  });
    });
		
    $("ul#sortable-list").sortable({
        //handle: '.smallHandle',
		placeholder: 'modPlace',
        opacity: 0.6,
        helper: 'helper',
        update: function() {
            var order = $('ul#sortable-list').sortable('serialize');
            $.post("ajax.php?sortposts=1&" + order, function(theResponse) {
                $("#msgDisplay").html(theResponse);
            });
        }

    });
});
// ]]>
</script>
<?php break;?>
<?php case"add": ?>
<div class="block-top-header">
  <h1><img src="images/pages-sml.png" alt="" /><?php echo _PG_TITLE2;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _PG_INFO2. _REQ1 . required(). _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo _PG_SUBTITLE2;?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo _PG_ADD;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=pages" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo _PG_TITLE;?>: <?php echo required();?></th>
            <td><input name="title<?php echo $core->dblang;?>" type="text" class="inputbox"  size="55" /></td>
          </tr>
          <tr>
            <th><?php echo _PG_SLUG;?>:</th>
            <td><input name="slug" type="text" class="inputbox" size="55" />
              <?php echo tooltip(_PG_SLUG_T);?></td>
          </tr>
          <tr>
            <th><?php echo _PG_CC;?>:</th>
            <td><span class="input-out">
              <label for="contact_form-1"><?php echo _YES;?></label>
              <input name="contact_form" type="radio" id="contact_form-1" value="1" />
              <label for="contact_form-2"><?php echo _NO;?></label>
              <input name="contact_form" type="radio" id="contact_form-2" value="0" checked="checked" />
              <?php echo tooltip(_PG_CC_T);?></span></td>
          </tr>
          <tr>
            <th><?php echo _PG_ACCESS_L;?>:</th>
            <td><?php echo $member->getAccessList();?></td>
          </tr>
          <tr id="memrow">
            <th><?php echo _PG_MEM_LEVEL;?>:</th>
            <td id="membership"><?php echo $member->getMembershipList();?></td>
          </tr>
          <tr>
            <th><?php echo _PG_SEL_MODULE;?>:</th>
            <td><?php echo $content->getModuleList();?></td>
          </tr>
          <tr id="modshow">
            <th>&nbsp;</th>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <th><?php echo _PG_KEYS;?>:</th>
            <td><input name="keywords<?php echo $core->dblang;?>" type="text" class="inputbox" size="80" />
              <?php echo tooltip(_PG_KEYS_T);?></td>
          </tr>
          <tr>
            <th><?php echo _PG_DESC;?>:</th>
            <td><textarea  name="description<?php echo $core->dblang;?>" rows="6" cols="55"></textarea>
           
            </td>
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
<script type="text/javascript"> 
// <![CDATA[
$(document).ready(function() {
    $('#memrow').hide();
    $('#access_id').change(function() {
        var option = $(this).val();
        var result = 'pageid=<?php echo $content->pageid;?>';
        result += '&membershiplist=' + option;
		  $.ajax({
			  type: "post",
			  url: "ajax.php",
			  data: result,
			  cache: false,
			  success: function (res) {
				  (option == "Membership") ? $('#memrow').show(): $('#memrow').hide();
				  $('#membership').html(res);
			  }
		  });
    });
	
	$('#modshow').hide();
    $('#modulename').change(function() {
		var option = $(this).val();
		var result = 'module_data=0';
        result += '&modulelist=' + option;
		  $.ajax({
			  type: "post",
			  url: "ajax.php",
			  data: result,
			  cache: false,
			  success: function (res) {
				  (option == 0) ? $('#modshow').hide(): $('#modshow').show();
				  $('#modshow').html(res);
			  }
		  });
    });
});
// ]]>
</script> 
<?php echo $core->doForm("processPage");?>
<?php break;?>
<?php default: ?>
<div class="block-top-header">
  <h1><img src="images/pages-sml.png" alt="" /><?php echo _PG_TITLE3;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _PG_INFO3;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><span><a href="index.php?do=pages&amp;action=add" class="button-sml"><?php echo _PG_ADD;?></a></span><?php echo _PG_SUBTITLE3;?></h2>
  </div>
  <div class="block-content">
    <table class="display sortable-table">
      <thead>
        <tr>
          <th class="firstrow">#</th>
          <th class="left sortable"><?php echo _PG_TITLE;?></th>
          <th><?php echo _PG_HOME;?></th>
          <th><?php echo _PG_ISCC;?></th>
          <th><?php echo _PUBLISHED;?></th>
          <th class="right"><?php echo _ACTIONS;?></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!$content->getPages()):?>
        <tr>
          <td colspan="7"><?php echo $core->msgAlert(_PG_NOPAGES,false);?></td>
        </tr>
        <?php else:?>
        <?php $home = getValue("page_id", "menus", "home_page = 1");?>
        <?php foreach ($content->getPages() as $row):?>
        <?php $ishome = ($home == $row['id']) ? true : false;?>
        <tr>
          <th><?php echo $row['id'];?>.</th>
          <td><?php echo $row['title'.$core->dblang];?></td>
          <td class="center"><?php echo ($ishome) ? '<img src="images/home.png" alt="" class="tooltip" title="'._PG_HOME.'"/>' : '' ;?></td>
          <td class="center"><?php echo ($row['contact_form'] == 1) ? '<img src="images/mail.png" class="tooltip"  alt="" title="'._PG_ISCCPAGE.'"/>' : '' ;?></td>
          <td class="center"><?php echo isActive($row['active']);?></td>
          <td class="right hasimg"><a href="index.php?do=pages&amp;action=edit&amp;pageid=<?php echo $row['id'];?>"><img src="images/edit.png" class="tooltip"  alt="" title="<?php echo _PG_EDIT;?>"/></a><a href="index.php?do=posts&amp;action=view&amp;pageid=<?php echo $row['id'];?>"><img src="images/search.png" class="tooltip"  alt="" title="<?php echo _PG_VIEW_P;?>"/></a><a href="index.php?do=posts&amp;action=add&amp;pageid=<?php echo $row['id'];?>"><img src="images/add.png" class="tooltip"  alt="" title="<?php echo _PG_NEW_P;?>"/></a><a href="javascript:void(0);" class="delete" data-title="<?php echo $row['title'.$core->dblang];?>" id="item_<?php echo $row['id'];?>"><img src="images/delete.png" class="tooltip"  alt="" title="<?php echo _DELETE;?>"/></a></td>
        </tr>
        <?php endforeach;?>
        <?php unset($row);?>
        <?php endif;?>
      </tbody>
    </table>
  </div>
</div>
<?php echo Core::doDelete(_DELETE.' '._PAGE, "deletePage");?>
<script type="text/javascript"> 
// <![CDATA[
$(document).ready(function () {
    $(".sortable-table").tablesorter({
        headers: {
            0: {
                sorter: false
            },
            2: {
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