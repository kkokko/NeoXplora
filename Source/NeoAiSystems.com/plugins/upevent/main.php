<?php
  /**
   * Upcoming Event
   *
   * @version $Id: main.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
  
  require_once(WOJOLITE . "admin/plugins/upevent/lang/" . $core->language . ".lang.php");
  require_once(WOJOLITE . "admin/plugins/upevent/admin_class.php");
  $conf = new upEvent();
  
  $erow = $conf->getEvent();
  $edate = str_replace("-","/",$erow['date_end']) . ' ' . $erow['time_end'];
?>
<!-- Start Upcoming Event -->
<?php if($conf->event_id):?>
<div id="upcommingevent">
<?php if(strtotime(date('y-m-d H:i:s')) > strtotime($erow['date_end'] . ' ' . $erow['time_end'])):?>
  <?php echo PLG_UE_ENDED;?>
  <?php else:?>
  <!-- Counter Section -->
  <div class="countersec clearfix">
    <h5><?php echo PLG_UE_TIMELEFT;?></h5>
    <div id="ecounter" class="row"></div>
  </div>
  <hr />
  <h5><?php echo $erow['title'.$core->dblang];?></h5>
  <?php echo cleanSanitize($erow['body'.$core->dblang],60);?>
  <div class="edate"><?php echo dodate($core->short_date, $erow['date_start']).' '.$erow['time_start'];?></div>
  <?php if($erow['venue'.$core->dblang]):?>
  <div class="evenue"><?php echo $erow['venue'.$core->dblang];?></div>
  <?php endif;?>
<script type="text/javascript">
// <![CDATA[
$(document).ready(function () {
  var endDate = "<?php echo $edate;?>";
  $('#ecounter').countdown({
	date: endDate,
	render: function(data) {
	  var el = $(this.el);
	  el.empty()
		.append("<div class=\"col grid_6\"><div>" + this.leadingZeros(data.days, 3) + " <span><?php echo PLG_UE_DAYS;?></span></div></div>")
		.append("<div class=\"col grid_6\"><div>" + this.leadingZeros(data.hours, 2) + " <span><?php echo PLG_UE_HOURS;?></span></div></div>")
		.append("<div class=\"col grid_6\"><div>" + this.leadingZeros(data.min, 2) + " <span><?php echo PLG_UE_MINUTES;?></span></div></div>")
		.append("<div class=\"col grid_6\"><div>" + this.leadingZeros(data.sec, 2) + " <span><?php echo PLG_UE_SECONDS;?></span></div></div>");
	}
  });
});	
// ]]>
</script>
  <?php endif;?>
</div>
<?php endif;?>
<!-- End Upcoming Event /-->