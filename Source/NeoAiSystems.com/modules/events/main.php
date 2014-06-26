<?php
  /**
   * Event Manager
   *
   * @version $Id: main.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  
  require_once(WOJOLITE . "admin/modules/events/lang/" . $core->language . ".lang.php");
  require_once(WOJOLITE . "admin/modules/events/admin_class.php");
  
  $calendar = new eventManager();  
?>
<!-- Start Event Manager -->
<div id="calendar">
  <?php $calendar->renderCalendar('responsive');?>
</div>
<script type="text/javascript">
// <![CDATA[
  function loadList() {
	  $.ajax({
		  url: SITEURL + "/modules/events/calendar.php",
		  cache: false,
		  success: function (html) {
			  $("#calendar").html(html);
		  }
	  });
  }

  $(document).ready(function () {
	  $("#calendar").on("click", "a.changedate", function () {
		  var parent = $(this);
		  var caldata = $(this).attr('id').replace('item_', '');
		  var month = caldata.split(":")[0];
		  var year = caldata.split(":")[1];
		  $.ajax({
			  type: "POST",
			  url: SITEURL + "/modules/events/calendar.php",
			  data: {
				  'year': year,
				  'month': month
			  },
			  success: function (data, status) {
				  $("#calendar").fadeIn("slow", function () {
					  $(this).html(data);
				  });
			  }
		  });
		  return false;
	  });
  });
// ]]>
</script> 
<!-- End Event Manager /-->