<?php
  /**
   * Gallery
   *
   * @version $Id: gallery.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');

  if(!$user->getAcl("gallery")): print $core->msgAlert(_CG_ONLYADMIN, false); return; endif;
    
  require_once("lang/" . $core->language . ".lang.php");
  require_once("admin_class.php");
  $gal = new Gallery();
?>
<?php switch($core->maction): case "edit": ?>
<div class="block-top-header">
  <h1><img src="images/mod-sml.png" alt="" /><?php echo MOD_GA_TITLE1;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo MOD_GA_INFO1 . _REQ1. required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo MOD_GA_SUBTITLE1.' &rsaquo; '.$gal->title;?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo MOD_GA_UPDATE;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=modules&amp;action=config&amp;mod=gallery" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo MOD_GA_NAME;?>: <?php echo required();?></th>
            <td><input name="title<?php echo $core->dblang;?>" type="text" class="inputbox" value="<?php echo $gal->title;?>" size="55" /></td>
          </tr>
          <tr>
            <th><?php echo MOD_GA_IMG_W;?>: <?php echo required();?></th>
            <td><input name="image_w" type="text" class="inputbox" value="<?php echo $gal->image_w;?>" size="5" />
              &nbsp;&nbsp; <?php echo tooltip(MOD_GA_IMG_W_T);?></td>
          </tr>
          <tr>
            <th><?php echo MOD_GA_IMG_H;?>: <?php echo required();?></th>
            <td><input name="image_h" type="text" class="inputbox" value="<?php echo $gal->image_h;?>" size="5" />
              &nbsp;&nbsp; <?php echo tooltip(MOD_GA_IMG_H_T);?></td>
          </tr>
          <tr>
            <th><?php echo MOD_GA_RESIZE;?>:</th>
            <td><span class="input-out">
              <label for="method-1"><?php echo MOD_GA_METHOD_1;?></label>
              <input name="method" type="radio" id="method-1"  value="1" <?php getChecked($gal->method, 1); ?> />
              <label for="method-2"><?php echo MOD_GA_METHOD_0;?></label>
              <input name="method" type="radio" id="method-2" value="0" <?php getChecked($gal->method, 0); ?>  />
              <?php echo tooltip(MOD_GA_RESIZE_T);?></span></td>
          </tr>
          <tr>
            <th><?php echo MOD_GA_WATERMARK;?>:</th>
            <td><span class="input-out">
              <label for="watermark-1"><?php echo _YES;?></label>
              <input name="watermark" type="radio" id="watermark-1"  value="1" <?php getChecked($gal->watermark, 1); ?> />
              <label for="watermark-2"><?php echo _NO;?></label>
              <input name="watermark" type="radio" id="watermark-2" value="0" <?php getChecked($gal->watermark, 0); ?> />
              <?php echo tooltip(MOD_GA_WATERMARK_T);?></span></td>
          </tr>
        </tbody>
      </table>
      <input name="galid" type="hidden" value="<?php echo $gal->galid;?>" />
    </form>
  </div>
</div>
<?php echo $core->doForm("processGallery","modules/gallery/controller.php");?>
<?php break;?>
<?php case"add": ?>
<div class="block-top-header">
  <h1><img src="images/mod-sml.png" alt="" /><?php echo MOD_GA_TITLE2;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo MOD_GA_INFO2 . _REQ1. required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo MOD_GA_SUBTITLE2;?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo MOD_GA_ADDMOD_GALLERY;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=modules&amp;action=config&amp;mod=gallery" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo MOD_GA_NAME;?>: <?php echo required();?></th>
            <td><input name="title<?php echo $core->dblang;?>" type="text" class="inputbox" size="55" /></td>
          </tr>
          <tr>
            <th><?php echo MOD_GA_FOLDER;?>: <?php echo required();?></th>
            <td><input name="folder" type="text" class="inputbox" size="55"/>
              &nbsp;&nbsp; <?php echo tooltip(MOD_GA_FOLDER_T);?></td>
          </tr>
          <tr>
            <th><?php echo MOD_GA_IMG_W;?>: <?php echo required();?></th>
            <td><input name="image_w" type="text" class="inputbox" size="5" />
              &nbsp;&nbsp; <?php echo tooltip(MOD_GA_IMG_W_T);?></td>
          </tr>
          <tr>
            <th><?php echo MOD_GA_IMG_H;?>: <?php echo required();?></th>
            <td><input name="image_h" type="text" class="inputbox" size="5" />
              &nbsp;&nbsp; <?php echo tooltip(MOD_GA_IMG_H_T);?></td>
          </tr>
          <tr>
            <th><?php echo MOD_GA_RESIZE;?>:</th>
            <td><span class="input-out">
              <label for="method-1"><?php echo MOD_GA_METHOD_1;?></label>
              <input name="method" type="radio" id="method-1"  value="1" checked="checked" />
              <label for="method-2"><?php echo MOD_GA_METHOD_0;?></label>
              <input name="method" type="radio" id="method-2" value="0" />
              <?php echo tooltip(MOD_GA_RESIZE_T);?></span></td>
          </tr>
          <tr>
            <th><?php echo MOD_GA_WATERMARK;?>:</th>
            <td><span class="input-out">
              <label for="watermark-1"><?php echo _YES;?></label>
              <input name="watermark" type="radio" id="watermark-1"  value="1" />
              <label for="watermark-2"><?php echo _NO;?></label>
              <input name="watermark" type="radio" id="watermark-2" value="0" checked="checked" />
              <?php echo tooltip(MOD_GA_WATERMARK_T);?></span></td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php echo $core->doForm("processGallery","modules/gallery/controller.php");?>
<?php break;?>
<?php case"images": ?>
<?php $galdata = $gal->getGalleryImages();?>
<div class="block-top-header">
  <h1><img src="images/mod-sml.png" alt="" /><?php echo MOD_GA_TITLE3 . $gal->title;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo MOD_GA_INFO3;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><span><a href="javascript:void(0);" onclick="$('#uploader').slideToggle();"><?php echo MOD_GA_ADD_IMG;?></a></span><?php echo MOD_GA_SUBTITLE3_. $gal->title . MOD_GA_SUBTITLE31_;?></h2>
  </div>
  <div class="block-content">
    <div id="uploader" style="display:none">
      <div class="uploader">
        <div class="dragspace hidden">
          <p class="zonemessage"><?php echo MOD_GA_DRAGF;?></p>
          <p class="message"><?php echo MOD_GA_FILEINFO;?></p>
        </div>
        <div class="buttons">
          <div class="addbutton hidden">
            <form action="modules/gallery/controller.php" method="post" id="uploadform" name="uploadform" enctype="multipart/form-data">
              <div class="fileuploader">
                <input type="text" class="filename" readonly="readonly"/>
                <input type="button" name="file" class="filebutton" value="<?php echo _BROWSE;?>"/>
                <input type="file" name="filedata" />
                <input name="gid" type="hidden" value="<?php echo $gal->galid;?>" />
                <input name="gfolder" type="hidden" value="<?php echo $gal->folder;?>" />
              </div>
            </form>
          </div>
        </div>
        <div class="uploadspace hidden"></div>
        <div class="buttons"> <a class="startbutton button-green hidden"><?php echo MOD_GA_START;?></a> <a class="clearbutton button button-orange hidden"><?php echo MOD_GA_CLEAR;?></a> </div>
      </div>
    </div>
    <table class="display">
      <tbody>
        <tr>
          <td><div id="maindata">
              <?php if(!$galdata):?>
              <div style="padding:10px"> <?php echo $core->msgInfo(MOD_GA_NOIMG,false);?></div>
              <?php else:?>
              <?php foreach ($galdata as $row):?>
              <div class="gallview" id="gid_<?php echo $row['id'];?>">
                <div class="gal-inner">
                  <figure> <img src="<?php echo SITEURL;?>/modules/gallery/thumbmaker.php?src=<?php echo SITEURL.'/'.$gal->galpath . $gal->folder.'/'.$row['thumb'];?>&amp;w=280&amp;h=160" alt="" class="galimg" /> </figure>
                  <div class="title"><a href="javascript:void(0);" data-title="<?php echo $row['title'.$core->dblang];?>"  class="delete" id="item_<?php echo $row['id'].'::' .$gal->folder;?>"> <img src="images/trash.png" alt="" title="<?php echo _DELETE;?>" class="tooltip"/></a> <a href="javascript:void(0);" data-title="<?php echo $row['title'.$core->dblang];?>" data-desc="<?php echo $row['description'.$core->dblang];?>" class="edit" id="list_<?php echo $row['id'];?>"> <img src="images/pencil.png" alt="" title="<?php echo _EDIT;?>" class="tooltip"/></a><?php echo character_limiter($row['title'.$core->dblang],50);?> </div>
                </div>
              </div>
              <?php endforeach;?>
              <?php endif;?>
            </div>
            <div class="clear"></div></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<script type="text/javascript" src="assets/fileupload.js"></script> 
<script type="text/javascript">
// <![CDATA[
  function showLoader() {
	  $('#loader').fadeIn(200);
  }

  function hideLoader() {
	  $('#loader').fadeOut(200);
  };

  function loadList() {
	  showLoader();
	  $.ajax({
		  type: 'post',
		  url: "modules/gallery/controller.php",
		  data: {
			  loadPhotos: 1,
			  'gid' :<?php echo $gal->galid;?>,
			  'gfolder' :'<?php echo $gal->folder;?>'
		  },
		  cache: false,
		  success: function (html) {
			  $("#maindata").html(html);
		  }
	  });
	  hideLoader();
  }

  var galHelper = function (e, div) {
	  div.children().each(function () {
		  $(this).width($(this).width());
	  });
	  return div;
  };
  
  $(window).ready(function(){
	   $(".uploader").FileUploader({
		  url: "modules/gallery/controller.php?gid=<?php echo $gal->galid;?>&gfolder=<?php echo $gal->folder;?>",
		  maxAllowedFiles: 10,
		  maxFileSize: 1024 * 1024 * 3,
		  allowedTypes: ["jpeg", "jpg", "png"],
		  msg: {
			  extTitle : "<?php echo MOD_GA_ERRFILETYPE;?>",
			  extError : "<?php echo MOD_GA_ERRFILETYPE_T;?>",
			  sizeTitle : "<?php echo MOD_GA_ERRFILESIZE;?>",
			  sizeError : "<?php echo str_replace("[LIMIT]", 3, MOD_GA_ERRFILESIZE_T);?>",
			  dropFiles : "<?php echo MOD_GA_DROPF;?>",
			  ierror : "<?php echo _ERROR;?>",
		  },
		  onAllComplete: function(msg){
			  showLoader()
			setTimeout(function () {
				$(loadList()).fadeIn("slow");
			}, 2000);
		  }
	  });
  });
  
  $(document).ready(function () {
	  $('#maindata').on('click', 'a.delete', function () {
		  var id = $(this).attr('id').replace('item_', '')
		  var parent = $(this).closest('.gallview');
		  var title = $(this).attr('data-title');
		  var text = '<div><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><?php echo _DEL_CONFIRM;?></div>';
		  $.confirm({
			  'title': '<?php echo _DELETE.' '._IMAGE;?>',
			  'message': text,
			  'buttons': {
				  '<?php echo _DELETE;?>': {
					  'class': 'yes',
					  'action': function () {
						  $.ajax({
							  type: 'post',
							  url: "modules/gallery/controller.php",
							  data: 'deletePhoto=' + id + '&title=' + encodeURIComponent(title),
							  success: function (msg) {
								  parent.fadeOut(400, function () {
									  parent.remove();
								  });
								  $("#msgholder").html(msg);
							  }
						  });
					  }
				  },
				  '<?php echo _CANCEL;?>': {
					  'class': 'no',
					  'action': function () {}
				  }
			  }
		  });

	  });
	  
	  // Edit Caption
	  $('#maindata').on('click', 'a.edit', function () {
		  var id = $(this).attr('id').replace('list_', '')
		  var parent = $(this).closest('.gallview');
		  var pre = $(this).closest('.title');
		  var caption = $(this).attr('data-title');
		  var desc = $(this).attr('data-desc');
		  var text = '<span class="ui-icon ui-icon-info" style="float:left; margin:0 10px 10px 0;"></span><?php echo MOD_GA_CAPTION;?><br />';
		  text += '<div><input name="title" type="text" id="caption" style="width:280px" class="inputbox" value="' + caption + '" size="45" />';
		  text += '<div style="margin-top:10px"><textarea name="desc" id="desc" style="width:280px;height:60px">' + desc + '</textarea>';
		  $.confirm({
			  'title': '<?php echo MOD_GA_CAPTION_E;?>',
			  'message': text,
			  'buttons': {
				  '<?php echo _RENAME;?>': {
					  'class': 'yes',
					  'action': function () {
						  var title = $('#caption').attr('value');
						  var desc = $('#desc').attr('value');
						  $.ajax({
							  type: 'post',
							  url: "modules/gallery/controller.php",
							  data: {
								  'renamePhoto' : id,
								  'title' :title,
								  'desc' :desc
							  },
							  success: function (res) {
								  pre.html("<img src='images/v-preloader.gif' />");
								  $("#msgholder").html(res);
								  setTimeout(function () {
									  $(loadList()).fadeIn("slow");
								  }, 2000);
							  }
						  });
					  }
				  },
				  '<?php echo _CANCEL;?>': {
					  'class': 'no',
					  'action': function () {}
				  }
			  }
		  });
	  });

    $("#maindata").sortable({
        opacity: 0.6,
        helper: galHelper,
        update: function() {
            var order = $('#maindata').sortable('serialize');
			    order += '&sortPhotos=1';
                $.ajax({
                    type: 'post',
                    url: "modules/gallery/controller.php",
                    data: order,

                    success: function (msg) {
						$("#msgholder").html(msg);
                    }
                });
			$("#maindata").disableSelection();
        }
    });
	  
  });
// ]]>
</script>
<?php break;?>
<?php default: ?>
<div class="block-top-header">
  <h1><img src="images/mod-sml.png" alt="" /><?php echo MOD_GA_TITLE4;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo MOD_GA_INFO4;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><span><a href="index.php?do=modules&amp;action=config&amp;mod=gallery&amp;mod_action=add"><?php echo MOD_GA_SUBTITLE4;?></a></span><?php echo MOD_GA_SUBTITLE3 . $content->getModuleName(get("mod"));?></h2>
  </div>
  <div class="block-content">
    <table class="display">
      <thead>
        <tr>
          <th class="firstrow">#</th>
          <th class="left"><?php echo MOD_GA_NAME;?></th>
          <th><?php echo MOD_GA_TOTAL_IMG;?></th>
          <th><?php echo _CREATED;?></th>
          <th><?php echo MOD_GA_EDITMOD_GAL;?></th>
          <th><?php echo MOD_GA_VIEW_IMGS;?></th>
          <th><?php echo _DELETE;?></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!$gal->getGalleries()):?>
        <tr>
          <td colspan="7"><?php echo $core->msgAlert(MOD_GA_NOMOD_GAL,false);?></td>
        </tr>
        <?php else:?>
        <?php foreach ($gal->getGalleries() as $row):?>
        <tr>
          <th><?php echo $row['id'];?>.</th>
          <td><?php echo $row['title'.$core->dblang];?></td>
          <td class="center"><?php echo $row['totalpics'];?></td>
          <td class="center"><?php echo dodate($core->short_date, $row['created']);?></td>
          <td class="center"><a href="index.php?do=modules&amp;action=config&amp;mod=gallery&amp;mod_action=edit&amp;galid=<?php echo $row['id'];?>"><img src="images/edit.png" class="tooltip"  alt="" title="<?php echo MOD_GA_EDITMOD_GAL;?>"/></a></td>
          <td class="center"><a href="index.php?do=modules&amp;action=config&amp;mod=gallery&amp;mod_action=images&amp;galid=<?php echo $row['id'];?>"><img src="images/search.png" class="tooltip"  alt="" title="<?php echo MOD_GA_VIEW_IMGS;?>"/></a></td>
          <td class="center"><a href="javascript:void(0);" class="delete" data-title="<?php echo $row['title'.$core->dblang];?>" id="item_<?php echo $row['id'];?>"><img src="images/delete.png" class="tooltip"  alt="" title="<?php echo _DELETE;?>"/></a></td>
        </tr>
        <?php endforeach;?>
        <?php unset($row);?>
        <?php endif;?>
      </tbody>
    </table>
  </div>
</div>
<?php echo Core::doDelete(_DELETE.' '._GALLERY, "deleteGallery","modules/gallery/controller.php");?>
<?php break;?>
<?php endswitch;?>