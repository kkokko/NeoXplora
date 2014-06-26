<?php
  /**
   * Backup
   *
   * @version $Id: backup.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');

  if(!$user->getAcl("Backup")): print $core->msgAlert(_CG_ONLYADMIN, false); return; endif;
?>
<?php
  require_once(WOJOLITE . "lib/class_dbtools.php");
  $tools = new dbTools();
  
  if (isset($_GET['backupok']) && $_GET['backupok'] == "1")
      $core->msgOk(_BK_BACKUP_OK,1,1);

  if (isset($_GET['restore']) && $_GET['restore'] == "1")
      $core->msgOk(_BK_RESTORE_OK,1,1);
	    
  if (isset($_GET['create']) && $_GET['create'] == "1")
      $tools->doBackup('',false);

  if (isset($_POST['backup_file']))
      $tools->doRestore($_POST['backup_file']);
?>
<div class="block-top-header">
  <h1><img src="images/backup-sml.png" alt="" /><?php echo _BK_TITLE1;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _BK_INFO1;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo _BK_SUBTITLE1;?></h2>
  </div>
  <div class="block-content">
    <form action="index.php?do=backup&amp;create=1" method="post" id="admin_form2" name="admin_form2">
      <table class="forms">
        <tr>
          <td><?php echo _BK_NAME;?>:
            <input name="name" type="text" class="inputbox" size="55"/>
            <?php echo tooltip(_BK_NAME_T);?>
            <button type="submit" class="button-blue"><?php echo _BK_CREATE;?></button></td>
        </tr>
      </table>
    </form>
    <div id="backup" class="box clearfix">
      <?php
        $dir = WOJOLITE . 'admin/backups/';
			  if (is_dir($dir)):
				  $getDir = dir($dir);
				  while (false !== ($file = $getDir->read())):
					  if ($file != "." && $file != ".." && $file != "index.php"):
						  if ($file == $core->backup):
							  echo '<div class="db-backup new">';
							  echo '<span class="file-name">';
							  echo str_replace(".sql", "", $file) . '</span>';
							  echo '<a href="' . ADMINURL . '/backups/' . $file . '" title="' ._DOWNLOAD.': '. $file . '" class="download tooltip">' . _DOWNLOAD . '</a>';
							  echo '<a href="javascript:void(0);" title="' ._DELETE.': '. $file . '" class="delete tooltip">' . _DELETE . '</a>';
							  echo '</div>';
						  else:
							  echo '<div class="db-backup" id="item_' . $file . '">';
							  echo '<span class="file-name">' . str_replace(".sql", "", $file) . '</span>';
							  echo ' <a href="' . ADMINURL . '/backups/' . $file . '" title="' ._DOWNLOAD.': '. $file . '" class="download tooltip">' . _DOWNLOAD . '</a>';
							  echo '<a href="javascript:void(0);" title="' ._DELETE.': '. $file . '" class="delete tooltip">' . _DELETE . '</a>';
							  echo '</div>';
			
						  endif;
					  endif;
				  endwhile;
				  $getDir->close();
			  endif;
      ?>
    </div>
    <div class="box clearfix">
      <form action="#" method="post" id="admin_form" name="admin_form">
        <strong><?php echo _BK_RESTORE_DB;?>:</strong>
        <?php
        if (is_dir($dir))
            : $getDir = dir($dir);
			echo '&nbsp;&nbsp;<div class="mybox"><select name="backup_file" class="custombox" style="width:250px">';
        while (false !== ($file = $getDir->read()))
            : if ($file != "." && $file != ".." && $file != "index.php"): 
        echo '<option value="' . $file . '">' . $file . '</option>';
        endif;
        endwhile;
		echo '</select></div>';
        $getDir->close();
        endif;
      ?>
        &nbsp;&nbsp;
        <button type="submit" class="button-blue"><?php echo _BK_RESTORE_BK;?></button>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"> 
// <![CDATA[
$(document).ready(function () {
  $('.container').on('click', 'a.delete', function () {
	  var parent = $(this).parent();
	  var id = $(this).closest('.db-backup').attr('id').replace('item_', '')
	  var text = '<div><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><?php echo _DEL_CONFIRM;?></div>';
	  $.confirm({
		  title: '<?php echo _BK_DELETE_BK;?>',
		  message: text,
		  buttons: {
			  '<?php echo _DELETE;?>': {
				  'class': 'yes',
				  'action': function () {
					  $.ajax({
						  type: 'post',
						  url: 'ajax.php',
						  data: 'deleteBackup=' + id,
						  beforeSend: function () {
							  parent.animate({
								  'backgroundColor': '#FFBFBF'
							  }, 400);
						  },
						  success: function (msg) {
							  parent.fadeOut(400, function () {
								  parent.remove();
							  });
							  $('html, body').animate({
								  scrollTop: 0
							  }, 600);
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
});
// ]]>
</script>