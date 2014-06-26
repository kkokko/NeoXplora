<?php
  /**
   * Logs
   *
   * @version $Id: logs.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  
  if(!$user->getAcl("Logs")): print $core->msgAlert(_CG_ONLYADMIN, false); return; endif;
  $logdata = $wojosec->getLogs();
?>
<div class="block-top-header">
  <h1><img src="images/log-sml.png" alt="" /><?php echo _LG_TITLE1;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _LG_INFO1;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><span><a href="javascript:void(0)" class="delete"><?php echo _LG_EMPTY;?></a></span><?php echo _LG_SUBTITLE1;?></h2>
  </div>
  <div class="block-content">
    <div class="utility">
      <table class="display">
        <tr>
          <td><form action="#" method="post" id="dForm">
              <strong> <?php echo _UR_SHOW_FROM;?></strong>
              <input name="fromdate" type="text" style="margin-right:3px" class="inputbox-sml" size="11" id="fromdate" />
              <strong> <?php echo _UR_SHOW_TO;?></strong>
              <input name="enddate" type="text" class="inputbox-sml" size="11" id="enddate" />
              <input name="find" type="submit" class="button-blue" value="<?php echo _UR_FIND;?>" />
            </form></td>
          <td class="right"><?php echo $pager->items_per_page();?>&nbsp;&nbsp;<?php echo $pager->jump_menu();?></td>
        </tr>
      </table>
    </div>
    <table class="display sortable-table">
      <thead>
        <tr>
          <th class="left sortable"><?php echo _LG_WHEN;?></th>
          <th class="left sortable"><?php echo _LG_USER;?></th>
          <th class="lef sortablet"><?php echo _LG_IP;?></th>
          <th class="left sortable"><?php echo _LG_TYPE;?></th>
          <th class="left sortable"><?php echo _LG_DATA;?></th>
          <th class="left sortable"><?php echo _LG_MESSAGE;?></th>
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
        <?php if(!$logdata):?>
        <tr>
          <td colspan="6"><?php echo $core->msgAlert(_LG_NOLOGS,false);?></td>
        </tr>
        <?php else:?>
        <?php foreach ($logdata as $row):?>
        <?php $message = cleanSanitize($row['message']);?>
        <tr>
          <td><?php echo dodate($core->long_date, $row['created']);?></td>
          <td><?php echo $row['user_id'];?></td>
          <td><?php echo $row['ip'];?></td>
          <td><?php echo $row['type'];?></td>
          <td><?php echo $row['info_icon'];?></td>
          <td><?php echo $message;?></td>
        </tr>
        <?php endforeach;?>
        <?php unset($row);?>
        <?php endif;?>
      </tbody>
    </table>
  </div>
</div>
<script type="text/javascript"> 
// <![CDATA[
$(document).ready(function () {
    $('.container').on('click', 'a.delete', function () {
        var title = $(this).attr('data-title');
        var text = '<div><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><?php echo _DEL_CONFIRM;?></div>';
        $.confirm({
            title: '<?php echo _LG_EMPTY_LOGS;?>',
            message: text,
            buttons: {
                '<?php echo _DELETE;?>': {
                    'class': 'yes',
                    'action': function () {
                        $.ajax({
                            type: 'post',
                            url: 'ajax.php',
                            data: 'deleteLogs=1',
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
    $(".sortable-table").tablesorter();
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