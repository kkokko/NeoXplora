<?php
  /**
   * Admin
   *
   * @version $Id: admin.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  
  if(!$user->getAcl("comments")): print $core->msgAlert(_CG_ONLYADMIN, false); return; endif;

  require_once("lang/" . $core->language . ".lang.php");
  require_once("admin_class.php");
  $com = new Comments();
?>
<?php switch($core->maction): case "config": ?>
<div class="block-top-header">
  <h1><img src="images/mod-sml.png" alt="" /><?php echo MOD_CM_TITLE1;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo MOD_CM_INFO1 . _REQ1 . required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo MOD_CM_SUBTITLE1;?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo MOD_CM_UPDATE_B;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=modules&amp;action=config&amp;mod=comments" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo MOD_CM_UNAME_R;?>:</th>
            <td><span class="input-out">
              <label for="username_req-1"><?php echo _YES;?></label>
              <input name="username_req" type="radio" id="username_req-1" value="1" <?php getChecked($com->username_req, 1); ?> />
              <label for="username_req-2"><?php echo _NO;?></label>
              <input name="username_req" type="radio" id="username_req-2" value="0" <?php getChecked($com->username_req, 0); ?> />
              &nbsp; <?php echo tooltip(MOD_CM_UNAME_T);?></span></td>
          </tr>
          <tr>
            <th><?php echo MOD_CM_EMAIL_R;?>:</th>
            <td><span class="input-out">
              <label for="email_req-1"><?php echo _YES;?></label>
              <input name="email_req" type="radio" id="email_req-1" value="1" <?php getChecked($com->email_req, 1); ?> />
              <label for="email_req-2"><?php echo _NO;?></label>
              <input name="email_req" type="radio" id="email_req-2" value="0" <?php getChecked($com->email_req, 0); ?> />
              &nbsp; <?php echo tooltip(MOD_CM_EMAIL_T);?></span></td>
          </tr>
          <tr>
            <th><?php echo MOD_CM_CAPTCHA;?>:</th>
            <td><span class="input-out">
              <label for="show_captcha-1"><?php echo _YES;?></label>
              <input name="show_captcha" type="radio" id="show_captcha-1" value="1" <?php getChecked($com->show_captcha, 1); ?> />
              <label for="show_captcha-2"><?php echo _NO;?></label>
              <input name="show_captcha" type="radio" id="show_captcha-2" value="0" <?php getChecked($com->show_captcha, 0); ?> />
              &nbsp; <?php echo tooltip(MOD_CM_CAPTCHA_T);?></span></td>
          </tr>
          <tr>
            <th><?php echo MOD_CM_WWW;?>:</th>
            <td><span class="input-out">
              <label for="show_www-1"><?php echo _YES;?></label>
              <input name="show_www" type="radio" id="show_www-1" value="1" <?php getChecked($com->show_www, 1); ?> />
              <label for="show_www-2"><?php echo _NO;?></label>
              <input name="show_www" type="radio" id="show_www-2" value="0" <?php getChecked($com->show_www, 0); ?> />
              &nbsp; <?php echo tooltip(MOD_CM_WWW_T);?></span></td>
          </tr>
          <tr>
            <th><?php echo MOD_CM_UNAME_S;?>:</th>
            <td><span class="input-out">
              <label for="show_username-1"><?php echo _YES;?></label>
              <input name="show_username" type="radio" id="show_username-1" value="1" <?php getChecked($com->show_username, 1); ?> />
              <label for="show_username-2"><?php echo _NO;?></label>
              <input name="show_username" type="radio" id="show_username-2" value="0" <?php getChecked($com->show_username, 0); ?> />
              &nbsp; <?php echo tooltip(MOD_CM_UNAME_ST);?></span></td>
          </tr>
          <tr>
            <th><?php echo MOD_CM_EMAIL_S;?>:</th>
            <td><span class="input-out">
              <label for="show_email-1"><?php echo _YES;?></label>
              <input name="show_email" type="radio" id="show_email-1" value="1" <?php getChecked($com->show_email, 1); ?> />
              <label for="show_email-2"><?php echo _NO;?></label>
              <input name="show_email" type="radio" id="show_email-2" value="0" <?php getChecked($com->show_email, 0); ?> />
              &nbsp; <?php echo tooltip(MOD_CM_EMAIL_ST);?></span></td>
          </tr>
          <tr>
            <th><?php echo MOD_CM_REG_ONLY;?>:</th>
            <td><span class="input-out">
              <label for="public_access-1"><?php echo _YES;?></label>
              <input name="public_access" type="radio" id="public_access-1" value="1" <?php getChecked($com->public_access, 1); ?> />
              <label for="public_access-2"><?php echo _NO;?></label>
              <input name="public_access" type="radio" id="public_access-2" value="0" <?php getChecked($com->public_access, 0); ?> />
              &nbsp; <?php echo tooltip(MOD_CM_REG_ONLY_T);?></span></td>
          </tr>
          <tr>
            <th><?php echo MOD_CM_SORTING;?>:</th>
            <td><select class="custombox" name="sorting" style="width:250px">
                <option value="DESC"<?php if($com->sorting == "DESC") echo ' selected="selected"';?>><?php echo MOD_CM_SORTING_T;?></option>
                <option value="ASC"<?php if($com->sorting == "ASC") echo ' selected="selected"';?>><?php echo MOD_CM_SORTING_B;?></option>
              </select></td>
          </tr>
          <tr>
            <th><?php echo MOD_CM_CHAR;?>:</th>
            <td><input name="char_limit" type="text" class="inputbox" value="<?php echo $com->char_limit; ?>" size="4" />
              &nbsp; <?php echo tooltip(MOD_CM_CHAR_T);?></td>
          </tr>
          <tr>
            <th><?php echo MOD_CM_PERPAGE;?>:</th>
            <td><input name="perpage" type="text" class="inputbox" value="<?php echo $com->perpage; ?>" size="4" />
              &nbsp; <?php echo tooltip(MOD_CM_PERPAGE_T);?></td>
          </tr>
          <tr>
            <th><?php echo MOD_CM_AA;?>:</th>
            <td><span class="input-out">
              <label for="auto_approve-1"><?php echo _YES;?></label>
              <input name="auto_approve" type="radio" id="auto_approve-1" value="1" <?php getChecked($com->auto_approve, 1); ?> />
              <label for="auto_approve-2"><?php echo _NO;?></label>
              <input name="auto_approve" type="radio" id="auto_approve-2" value="0" <?php getChecked($com->auto_approve, 0); ?> />
              &nbsp; <?php echo tooltip(MOD_CM_AA_T);?></span></td>
          </tr>
          <tr>
            <th><?php echo MOD_CM_NOTIFY;?>:</th>
            <td><span class="input-out">
              <label for="notify_new-1"><?php echo _YES;?></label>
              <input name="notify_new" type="radio" id="notify_new-1" value="1" <?php getChecked($com->notify_new, 1); ?> />
              <label for="notify_new-2"><?php echo _NO;?></label>
              <input name="notify_new" type="radio" id="notify_new-2" value="0" <?php getChecked($com->notify_new, 0); ?> />
              &nbsp; <?php echo tooltip(MOD_CM_NOTIFY_T);?></span></td>
          </tr>
          <tr>
            <th><?php echo MOD_CM_DATE;?>:</th>
            <td><select class="custombox" name="dateformat" style="width:250px">
                <?php echo $com->getDateFormat();?>
              </select>
              &nbsp; <?php echo tooltip(MOD_CM_DATE_T);?></td>
          </tr>
          <tr>
            <th><?php echo MOD_CM_WORDS;?>:</th>
            <td><textarea name="blacklist_words" cols="45" rows="6"><?php echo $com->blacklist_words;?></textarea>
              &nbsp; <?php echo tooltip(MOD_CM_WORDS_T);?></td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php echo $core->doForm("updateConfig","modules/comments/controller.php");?>
<?php break;?>
<?php default: ?>
<?php $commentrow = $com->getComments();?>
<div class="block-top-header">
  <h1><img src="images/mod-sml.png" alt="" /><?php echo MOD_CM_TITLE3;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo MOD_CM_INFO3;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><span><a href="index.php?do=modules&amp;action=config&amp;mod=comments&amp;mod_action=config"><?php echo MOD_CM_CONFIG;?></a></span><?php echo MOD_CM_SUBTITLE3 . $content->getModuleName(get("mod"));?></h2>
  </div>
  <div class="block-content">
    <div class="utility">
      <table class="display">
        <tr>
          <td><form action="#" method="post" id="dForm">
              <strong><?php echo MOD_CM_SHOWFROM;?></strong>
              <input name="fromdate" type="text" style="margin-right:3px" class="inputbox-sml" size="10" id="fromdate" />
              <strong><?php echo MOD_CM_SHOWTO;?></strong>
              <input name="enddate" type="text" style="margin-right:3px" class="inputbox-sml" size="10" id="enddate" />
              <input name="find" type="submit" class="button-blue" value="<?php echo MOD_CM_FIND;?>" />
            </form></td>
          <td class="right"><?php echo $pager->items_per_page();?>&nbsp;&nbsp;<?php echo $pager->jump_menu();?></td>
        </tr>
      </table>
    </div>
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="display sortable-table">
        <thead>
          <tr>
            <th class="firstrow">#</th>
            <th class="left sortable"><?php echo MOD_CM_UNAME;?></th>
            <th class="left sortable"><?php echo MOD_CM_EMAIL;?></th>
            <th class="left sortable"><?php echo MOD_CM_CREATED;?></th>
            <th class="left sortable"><?php echo MOD_CM_PNAME;?></th>
            <th><?php echo MOD_CM_VIEW;?></th>
            <th><?php echo MOD_CM_STATUS;?></th>
            <th class="right"><input type="checkbox" name="masterCheckbox" id="masterCheckbox" class="checkbox"/></th>
          </tr>
        </thead>
        <?php if($commentrow):?>
        <tfoot>
          <tr>
            <td colspan="8"><input type="submit" name="approve" id="approve" value="<?php echo MOD_CM_APPROVE;?>" class="button doform" />
              <input type="submit" name="disapprove" id="disapprove" value="<?php echo MOD_CM_DISAPPROVE;?>" class="button-green doform" />
              <input type="submit" name="delete" id="delete" value="<?php echo _DELETE;?>" class="button-orange doform" /></td>
          </tr>
          <?php if($pager->display_pages()):?>
          <tr>
            <td colspan="8"><div class="pagination"><?php echo $pager->display_pages();?></div></td>
          </tr>
          <?php endif;?>
        </tfoot>
        <?php endif;?>
        <tbody>
          <?php if($commentrow == 0):?>
          <tr>
            <td colspan="8"><?php echo $core->msgInfo(MOD_CM_NONCOMMENTS,false);?></td>
          </tr>
          <?php else:?>
          <?php foreach ($commentrow as $crow):?>
          <tr>
            <th><?php echo $crow['cid'];?>.</th>
            <td><?php echo $crow['username'];?></td>
            <td><?php echo $crow['email'];?></td>
            <td><?php echo dodate($core->long_date, $crow['created']);?></td>
            <td><a href="index.php?do=pages&amp;action=edit&amp;pageid=<?php echo $crow['id'];?>"><?php echo $crow['title'];?></a></td>
            <td class="center"><a href="javascript:void(0);"  data-username="<?php echo $crow['username'];?>" class="viewcomment" id="item_<?php echo $crow['cid'];?>"><img src="images/view.png" class="tooltip"  alt="" title="<?php echo MOD_CM_VIEW_P;?>"/></a></td>
            <td class="center"><?php echo isActive($crow['active']);?></td>
            <td class="right"><input name="comid[]" type="checkbox" class="checkbox" id="status<?php echo $crow['cid'];?>" value="<?php echo $crow['cid'];?>" /></td>
          </tr>
          <?php endforeach;?>
          <?php unset($crow);?>
          <?php endif;?>
        </tbody>
      </table>
    </form>
  </div>
</div>
<script type="text/javascript">
// <![CDATA[
$(document).ready(function () {
    $('.doform').click(function () {
        var action = $(this).attr('id');
        var str = $("#admin_form").serialize();
        str += '&comproccess=1';
        str += '&action=' + action;
        $.ajax({
            type: "post",
            url: "modules/comments/controller.php",
            data: str,
            beforeSend: function () {
                $('.display tbody tr').each(function () {
                    if ($(this).find('input:checked').length) {
                        $(this).animate({
                            'backgroundColor': '#FFBFBF'
                        }, 400);
                    }
                });
            },
            success: function (msg) {
                $('.display tbody tr').each(function () {
                    if ($(this).find('input:checked').length) {
                        if (action == "delete") {
                            $(this).fadeOut(400, function () {
                                $(this).remove();
                            });
                        } else {
                            $(this).animate({
                                'backgroundColor': '#fff'
                            }, 400);
                        }
                    }
                });
                $("#msgholder").html(msg);
            }
        });
        return false;
    });
    $('a.viewcomment').on('click', function () {
        var id = $(this).attr('id').replace('item_', '')
        var title = $(this).attr('data-username');
        $.ajax({
            type: 'post',
            url: "modules/comments/controller.php",
            dataType: 'json',
            data: {
                getComment: 1,
                id: id,
            },
            cache: false,
            success: function (json) {
                $("#jboxOverlay #bodyid").html(json.bodyid);
                $("#jboxOverlay #webid").val(json.webid);
                $("#jboxOverlay #ipid").html(json.ipid);
            }
        });
        var text = '<textarea name="body" id="bodyid" cols="50" rows="5"></textarea>';
        text += '<div style="margin-top:10px">';
        text += '<input name="www" type="text" id="webid" class="inputbox" size="50" />';
        text += '</div>';
        text += '<div style="margin-top:10px;border-top-width: 1px; border-top-style: dashed; border-top-color: #CCC;font-size:11px">';
        text += '<p>IP: <span id="ipid"></span></p>';
        text += '</div>';
        $.confirm({
            title: '<?php echo MOD_CM_VIEW_P;?> : ' + title,
            message: text,
            buttons: {
                '<?php echo _UPDATE;?>': {
                    'class': 'yes',
                    'action': function () {
                        $.ajax({
                            type: 'post',
                            url: "modules/comments/controller.php",
                            data: {
                                doComment: 1,
                                id: id,
                                content: $("#bodyid").val(),
                                www: $("#webid").val()
                            },
                            success: function (msg) {
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
    $(".sortable-table").tablesorter({
        headers: {
            0: {
                sorter: false
            },
            6: {
                sorter: false
            },
            7: {
                sorter: false
            },
            8: {
                sorter: false
            }
        }
    });
    $('#masterCheckbox').click(function (e) {
        $(this).parent().toggleClass("ez-checked");
        $('input[name^="comid"]').each(function () {
            ($(this).is(':checked')) ? $(this).removeAttr('checked') : $(this).attr({
                "checked": "checked"
            });
            $(this).trigger('change');
        });
        return false;
    });
    var dates = $('#fromdate, #enddate').datepicker({
        defaultDate: "+1w",
        changeMonth: false,
        numberOfMonths: 2,
        dateFormat: 'yy-mm-dd',
        onSelect: function (selectedDate) {
            var option = this.id == "fromdate" ? "minDate" : "maxDate";
            var instance = $(this).data("datepicker");
            var date = $.datepicker.parseDate(instance.settings.dateFormat || $.datepicker._defaults.dateFormat, selectedDate, instance.settings);
            dates.not(this).datepicker("option", option, date);
        }
    });
});
// ]]>
</script>
<?php break;?>
<?php endswitch;?>