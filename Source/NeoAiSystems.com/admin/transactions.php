<?php
  /**
   * Transactions
   *
   * @version $Id: transactions.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  
  if(!$user->getAcl("Transactions")): print $core->msgAlert(_CG_ONLYADMIN, false); return; endif;
?>
<?php switch($core->action): case "salesyear": ?>
<script type="text/javascript" src="assets/jquery.flot.js"></script>
<script type="text/javascript" src="assets/flot.resize.js"></script> 
<script type="text/javascript" src="assets/excanvas.min.js"></script> 
<div class="block-top-header">
  <h1><img src="images/trans-sml.png" alt="" /><?php echo _TR_TITLE2;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _TR_INFO2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><span class="dropdown-menu"> <a href="javascript:void(0);" class="menu-toggle"><img src="images/options.png" alt="" /></a> <em class="drop-wrap" id="range"> <a href="javascript:void(0);" data-range="day"><?php echo _MN_TODAY;?></a> <a href="javascript:void(0);" data-range="week"><?php echo _MN_WEEK;?></a> <a href="javascript:void(0);" data-range="month"><?php echo _MN_MONTH;?></a> <a href="javascript:void(0);" data-range="year"><?php echo _MN_YEAR;?></a> </em> </span><?php echo _TR_SUBTITLE2.' &rsaquo; '._TR_SALES3.' &rsaquo; '.$core->year;;?></h2>
  </div>
  <div class="block-content">
    <?php $reports = $member->yearlyStats();?>
    <?php $row = $member->getYearlySummary();?>
    <?php if($reports == 0):?>
    <?php echo $core->msgAlert(_TR_NOYEARSALES,false);?>
    <?php else:?>
    <div class="utility">
      <div id="chartdata" style="height:400px;width:100%;overflow:hidden"></div>
    </div>
    <table class="display">
      <thead>
        <tr>
          <th class="left"><?php echo _TR_MONTHYEAR;?></th>
          <th><?php echo _TR_TOTSALES;?></th>
          <th><?php echo _TR_TOTREV;?></th>
        </tr>
      </thead>
      <?php foreach($reports as $report):?>
      <tr>
        <td><?php echo date("M", mktime(0, 0, 0, $report['month'], 10));?> / <?php echo $core->year;?></td>
        <td class="center"><?php echo $report['total'];?></td>
        <td class="center"><?php echo $core->formatMoney($report['totalprice']);?></td>
      </tr>
      <?php endforeach ?>
      <?php unset($report);?>
      <tr>
        <td><strong><?php echo _TR_TOTALYEAR;?></strong></td>
        <td class="center"><strong><?php echo $row['total'];?></strong></td>
        <td class="center"><strong><?php echo $core->formatMoney($row['totalprice']);?></strong></td>
      </tr>
    </table>
    <?php endif;?>
  </div>
</div>
<script type="text/javascript">
function getSalesChart(range) {
    $.ajax({
        type: 'GET',
        url: 'ajax.php?getTransactionStats=1&timerange=' + range,
        dataType: 'json',
        async: false,
        success: function (json) {
            var option = {
                shadowSize: 0,
                lines: {
                    show: true,
                    fill: true,
                    lineWidth: 1
                },
                grid: {
					aboveData: true,
                    backgroundColor: '#fff'
                },
                xaxis: {
                    ticks: json.xaxis
                }
            }
            $.plot($('#chartdata'), [json.order], option);
        }
    });
}
  $(document).ready(function(){
  getSalesChart('year');
  $('#range a').on('click',  function () {
	  var val = $(this).attr('data-range');
	  getSalesChart(val);
	});
});
</script>
<?php break;?>
<?php default: ?>
<?php
  $search = (isset($_POST['search'])) ? intval($_POST['search']) : false;
  $payrow = $member->getPayments($search);
?>
<div class="block-top-header">
  <h1><img src="images/trans-sml.png" alt="" /><?php echo _TR_TITLE1;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _TR_INFO1;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><span><a href="ajax.php?exportTransactions" title="<?php echo _TR_EXPORTXLS;?>" class="tooltip"><img src="images/xls.png" alt="" /></a> <a href="index.php?do=transactions&amp;action=salesyear" title="<?php echo _TR_VIEW_REPORT;?>" class="tooltip"><img src="images/chart.png" alt=""/></a></span><?php echo _TR_SUBTITLE1;?></h2>
  </div>
  <div class="block-content">
    <div class="utility">
      <table class="display">
        <tr>
          <td><form action="#" method="post">
              <input name="search" type="text" class="inputbox" id="search-input" size="40"/>
              <input name="submit" type="submit" class="button-blue" value="<?php echo _TR_FIND;?>" />
            </form></td>
          <td><form action="#" method="post" id="dForm">
              <strong> <?php echo _TR_SHOW_FROM;?> </strong>
              <input name="fromdate" type="text" style="margin-right:3px" class="inputbox-sml" size="12" id="fromdate" />
              <strong> <?php echo _TR_SHOW_TO;?> </strong>
              <input name="enddate" type="text" style="margin-right:3px" class="inputbox-sml" size="12" id="enddate" />
              <input name="find" type="submit" class="button-blue" value="<?php echo _TR_FIND;?>" />
            </form></td>
        </tr>
        <tr>
          <td colspan="2" class="right"><?php echo $pager->items_per_page();?>&nbsp;&nbsp;<?php echo $pager->jump_menu();?></td>
        </tr>
      </table>
    </div>
    <table class="display sortable-table">
      <thead>
        <tr>
          <th class="firstrow">#</th>
          <th class="left sortable"><?php echo _TR_MEMNAME;?></th>
          <th class="left sortable"><?php echo _TR_USERNAME;?></th>
          <th class="left sortable"><?php echo _TR_AMOUNT;?></th>
          <th class="left sortable"><?php echo _TR_PAYDATE;?></th>
          <th class="center"><?php echo _TR_PROCESSOR;?></th>
          <th class="center"><?php echo _TR_STATUS;?></th>
          <th class="center"><?php echo _DELETE;?></th>
        </tr>
      </thead>
      <?php if($pager->display_pages()):?>
      <tfoot>
        <tr>
          <td colspan="8"><div class="pagination"><?php echo $pager->display_pages();?></div></td>
        </tr>
      </tfoot>
      <?php endif;?>
      <tbody>
        <?php if($payrow == 0):?>
        <tr>
          <td colspan="8"><?php echo $core->msgAlert(_TR_NOTRANS,false);?></td>
        </tr>
        <?php else:?>
        <?php foreach ($payrow as $row):?>
        <?php $image = ($row['status'] == 0) ? "pending":"completed";?>
        <?php $status = ($row['status'] == 0) ? 1:0;?>
        <tr>
          <th><?php echo $row['id'];?>.</th>
          <td><?php echo $row['title'];?></td>
          <td><?php echo $row['username'];?></td>
          <td><?php echo $core->formatMoney($row['rate_amount']);?></td>
          <td><?php echo dodate($core->short_date, $row['created']);?></td>
          <td class="center"><img src="images/<?php echo $row['pp'];?>.png" alt="" class="tooltip" title="<?php echo $row['pp'];?>"/></td>
          <td class="center"><img src="images/<?php echo $image;?>.png" alt="" class="tooltip" title="Status: <?php echo ucfirst($image);?>"/></td>
          <td class="center"><a href="javascript:void(0);" class="delete" data-title="<?php echo $row['created'];?>" id="item_<?php echo $row['id'];?>"><img src="images/delete.png" class="tooltip"  alt="" title="<?php echo _DELETE;?>"/></a></td>
        </tr>
        <?php endforeach;?>
        <?php unset($row);?>
        <?php endif;?>
      </tbody>
    </table>
  </div>
</div>
<?php echo Core::doDelete(_DELETE.' '._TRANSACTION, "deleteTransaction");?> 
<script type="text/javascript"> 
// <![CDATA[
$(document).ready(function () {
	$("#search-input").watermark("<?php echo _TR_FINDPAY;?>");
    $(".sortable-table").tablesorter({
        headers: {
            0: {
                sorter: false
            },
            5: {
                sorter: false
            },
            6: {
                sorter: false
            },
            7: {
                sorter: false
            }
        }
    });
});
$(function() {
	var dates = $('#fromdate, #enddate').datepicker({
		defaultDate: "+1w",
		changeMonth: false,
		numberOfMonths: 2,
		dateFormat: 'yy-mm-dd',
		onSelect: function(selectedDate) {
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