<?php
  /**
   * Comments Main
   *
   * @version $Id: main.php, v2.00 2011-04-20 16:18:34 gewa Exp $
   */
  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
  
  require(WOJOLITE . "admin/modules/comments/admin_class.php");
  require_once(WOJOLITE . "admin/modules/comments/lang/" . $core->language . ".lang.php");
  $com = new Comments();
  $pageid = $this->modpageid;

  $counter = countEntries("mod_comments", "page_id", $pageid);
  $pages = ceil($counter/$com->perpage)
?>
<div class="hr2"></div>
  <?php echo ($counter <> 0) ? "<h3>" . MOD_CM_HAS_C . $counter." ". MOD_CM_COMMENTS."</h3>" : "<h4>".MOD_CM_NOCOMMENTS."</h4>";?>
  <div id="comments" class="clearfix"></div>
  <?php if($counter > $com->perpage):?>
  <div id="pagination" class="clearfix">
    <?php for($j=1; $j<=$pages; $j++):?>
    <a href="javascript:void(0);" class="number" id="pg-<?php echo $j;?>"><?php echo $j;?></a>
    <?php endfor;?>
  </div>
  <?php endif;?>
  <?php if($com->public_access):?>
  <?php include("form.tpl.php");?>
  <?php elseif(!$com->public_access and $user->logged_in):?>
  <?php include("form.tpl.php");?>
  <?php else:?>
  <?php echo $core->msgInfo(MOD_CM_MSGERR3,false);?>
  <?php endif;?>
  <div id="response"></div>

<script type="text/javascript">
// <![CDATA[
function showsmLoader() {
    $('.loader2').fadeIn(200);
}

function hidesmLoader() {
    $('.loader2').fadeOut(200);
};

function updateOptions(id) {
	$("span.temp_id").remove();
	$('#commentform').append($('<span class="temp_id"><input name="parent_id" type="hidden" value="' + id + '" /></span>'));
}
function loadList() {
    $("#pg-1").addClass("current");
    $.ajax({
        url: SITEURL + "/modules/comments/loadComments.php?pageid=<?php echo $pageid;?>",
        data: "pg=1",
        cache: false,
        beforeSend: function () {
            $('#comments').animate({
                opacity: 0.1
            }, 500);
        },
        success: function (html) {
            $('#comments').animate({
                opacity: 1
            }, 500).html(html);
        }
    });
}

//Char Limiter
(function ($) {
    $.fn.limit = function (options) {
        var defaults = {
            limit: 200,
            id_result: false,
            alertClass: false
        }
        var options = $.extend(defaults, options);
        return this.each(function () {
            var characters = options.limit;
            if (options.id_result != false) {
                $("#" + options.id_result).append("<?php echo MOD_CHAR_REMAIN1;?>" + characters + "<?php echo MOD_CHAR_REMAIN2;?>");
            }
            $(this).keyup(function () {
                if ($(this).val().length > characters) {
                    $(this).val($(this).val().substr(0, characters));
                }
                if (options.id_result != false) {
                    var remaining = characters - $(this).val().length;
                    $("#" + options.id_result).html("<?php echo MOD_CHAR_REMAIN1;?>" + remaining + "<?php echo MOD_CHAR_REMAIN2;?>");
                    if (remaining <= 10) {
                        $("#" + options.id_result).addClass(options.alertClass);
                    } else {
                        $("#" + options.id_result).removeClass(options.alertClass);
                    }
                }
            });
        });
    };
})(jQuery);

$(document).ready(function () {
    loadList();
	
	//Pagination
    $("#pagination a").click(function (e) {
        e.preventDefault();
        $(".current").removeClass("current");
        $(this).addClass("current");
        //showLoader();
        $.ajax({
            url: SITEURL + "/modules/comments/loadComments.php?pageid=<?php echo $pageid;?>",
            data: "pg=" + this.id.replace('pg-', ''),
            cache: false,
            beforeSend: function () {
                $('#comments').animate({
                    opacity: 0.1
                }, 500);
            },
            success: function (html) {
                $('#comments').animate({
                    opacity: 1
                }, 500).html(html);
            }
        });
        //hideLoader();
    });
		
    //Limiter
    $("#combody").limit({
        limit: <?php echo $com->char_limit;?>,
        id_result : "counter",
        alertClass : "char-alert"
    });
	
	//Post Comment
	$("#commentform").submit(function () {
		var str = $(this).serialize();
		showsmLoader();
		$.ajax({
			type: "POST",
			url: SITEURL + "/modules/comments/processComment.php",
			data: str,
			success: function (msg) {
				$("#response").ajaxComplete(function (event, request, settings) {
					if (msg == 'OK1') {
						result = '<div class="msgOk"><?php echo MOD_CM_MSGOK1;?><\/div>';
						$("#reply").hide();
					} else if (msg == 'OK2') {
						result = '<div class="msgOk"><?php echo MOD_CM_MSGOK2;?><\/div>';
						$("#reply").hide();
					} else {
						result = msg;
					}
					$(this).html(result);
					hidesmLoader();
				});
				loadList();
			}
		});
		return false;
	});
});
// ]]>
</script>