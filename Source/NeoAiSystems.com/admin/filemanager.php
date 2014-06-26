<?php
  /**
   * File Manager
   *
   * @version $Id: filemanager.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');

  if(!$user->getAcl("FM")): print $core->msgAlert(_CG_ONLYADMIN, false); return; endif;
?>
<div class="block-top-header">
  <h1><img src="images/filemngr-sml.png" alt="" /><?php echo _FM_TITLE;?></h1>
  <div class="divider"><span></span></div>
</div>
<div id="maindata"></div>
<div id="dialog-view-item" style="display:none;"></div>
<script type="text/javascript">
// <![CDATA[
function requestDefault() {
    $('#dataholder tbody tr').each(function () {
        if ($(this).find('input:checked').length) {
            $(this).animate({
                'backgroundColor': '#FFBFBF'
            }, 400);
        }
    });
}

function responseDelete(msg) {
    $('#dataholder tbody tr').each(function () {
        if ($(this).find('input:checked').length) {
            $(this).fadeOut(400, function () {
                $(this).remove();
            });
        }
    });
    $("#msgholder").html(msg);
}

function responseDefault(msg) {
    $('#dataholder tbody tr').each(function () {
        if ($(this).find('input:checked').length) {
            $(this).animate({
                'backgroundColor': '#fff'
            }, 400);
        }
    });
    $(this).html(msg);
}

$(document).ready(function () {
    $(function () {
        $('#masterCheckbox').live('click', function () {
            $(this).parents('#admin_form:eq(0)').find(':checkbox').attr('checked', this.checked);
        });
    });

    function showLoader() {
        $('#loader').fadeIn(200);
    }

    function hideLoader() {
        $('#loader').fadeOut(200);
    };

    function loadList(dirdata) {
        showLoader();
        $.ajax({
            type: 'post',
            url: "manager/controller.php",
            data: 'rel_dir=' + dirdata,
            cache: false,
            success: function (html) {
                $("#maindata").html(html);
            }
        });
        hideLoader();
    }

    loadList('');


	$('#maindata').on('click', 'a.dirchange', function (e) {
        e.preventDefault();
        var dirdata = escape($(this).attr('id'))
        showLoader();
        $.ajax({
            type: 'post',
            url: "manager/controller.php",
            data: 'rel_dir=' + dirdata,
            cache: false,
            beforeSend: function () {
                showLoader();
            },
            success: function (html) {
				hideLoader();
                $("#maindata").html(html);
            }
        });
        hideLoader();
    });
	
    /** Multiple Delete **/
    $('#maindata').on("click", "#delete-multi", function () {
		var str = $("#admin_form").serialize();
		    str += '&fmaction=deleteMulti';
		  $.ajax({
			  type: "post",
			  url: "manager/controller.php",
			  data: str,
			  beforeSend: requestDefault,
			  success: responseDelete
		  });
		  return false;
    }); 
	
    // Delete single file/folder
	$('#maindata').on("click", "a.del-single", function () {
        var id = $(this).attr('id')
        var parent = $(this).parent().parent();
        var name = $(this).attr('data-path');
		var path = id + name;
		var text = '<div><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><?php echo _DEL_CONFIRM;?></div>';
		$.confirm({
			title: '<?php echo _FM_DELFILE_D;?>',
			message: text,
			buttons: {
				'<?php echo _DELETE;?>': {
					'class': 'yes',
					'action': function () {
						$.ajax({
							type: 'post',
							url: "manager/controller.php",
							data: 'fmaction=deleteSingle&path=' + path + '&name=' + encodeURIComponent(name),
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
	
	// View single file
    $('#maindata').on("click", "a.view-single", function () {
        var id = $(this).attr('id')
        var parent = $(this).parent().parent();
        var title = $(this).attr('data-path');
		  $.ajax({
			  type: 'post',
			  url: "manager/controller.php",
			  data: 'fmaction=viewItem&path=' + id + '&name=' + title,
			  success: function (res) {
				$.confirm({
					'title': '<?php echo _FM_VIEWING.' '._FM_FILE;?>',
					'message': res,
					'buttons': {
						'<?php echo _CANCEL;?>': {
							'class': 'no'
						}
					}
				});
			  }
		  });	
        return false;
    });
	
    // Create Directory
	$('#maindata').on("click", "a#create-dir", function () {
        var id = $(this).attr('data-path');
		var text = '<p><strong><?php echo _FM_DIR_NAME_T;?></strong></p>';
		    text += '<div><input name="dirname" type="text" class="inputbox-sml" id="dirname" size="30" /></div>';
		$.confirm({
			title: '<?php echo _FM_NEWDIR;?>',
			message: text,
			buttons: {
				'<?php echo _FM_CREATE;?>': {
					'class': 'yes',
					'action': function () {
					  var dirname = $("#dirname").val()	
						$.ajax({
							type: 'post',
							url: "manager/controller.php",
							data: 'fmaction=createDir&path=' + id + '&name=' + dirname,
							success: function (res) {
								$("#msgholder").html(res);
								setTimeout(function () {
									$(loadList(id)).fadeIn("slow");
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
	
    // Create File
	$('#maindata').on("click", "a#create-file", function () {
        var id = $(this).attr('data-path');
		var text = '<p><strong><?php echo _FM_FILENAME_T;?></strong></p>';
		    text += '<div><input name="dirname" type="text" class="inputbox-sml" id="filename" size="30" /></div>';
		$.confirm({
			title: '<?php echo _FM_NEWFILE;?>',
			message: text,
			buttons: {
				'<?php echo _FM_CREATE;?>': {
					'class': 'yes',
					'action': function () {
					  var filename = $("#filename").val()	
						$.ajax({
							type: 'post',
							url: "manager/controller.php",
							data: 'fmaction=createFile&path' + id + '&name=' + filename,
							success: function (res) {
								$("#msgholder").html(res);
								setTimeout(function () {
									$(loadList(id)).fadeIn("slow");
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
	
	
	// File Upload
	$('#maindata').on("click", "#fileupload", function () {
		var id = $(this).attr('data-path');
		$('#admin_form').ajaxSubmit({
			target: "#msgholder",
			url: "manager/controller.php",
			clearForm: 1,
			data: {
				fmaction: "uploadFile"
			},
			success: function (res) {
			  setTimeout(function () {
				  $(loadList(id)).fadeIn("slow");
			  }, 2000);
			}
		});
		return false;
	});
});
// ]]>
</script>