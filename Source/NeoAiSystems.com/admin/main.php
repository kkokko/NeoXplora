<?php
  /**
   * Main
   *
   * @version $Id: main.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<script type="text/javascript" src="assets/jquery.flot.js"></script>
<script type="text/javascript" src="assets/flot.resize.js"></script>
<script type="text/javascript" src="assets/excanvas.min.js"></script>
<div class="block-top-header">
  <h1><img src="images/home-sml.png" alt="" /><?php echo _MN_TITLE;?></h1>
  <div class="divider"><span></span></div>
</div>
<!--<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _MN_INFO;?></p>-->
<div class="block-border">
  <!--<div class="block-header">
    <h2><span class="dropdown-menu"> <a href="javascript:void(0);" class="menu-toggle"><img src="images/options.png" alt="" /></a> <em class="drop-wrap" id="range"> <a href="javascript:void(0);" data-range="day"><?php echo _MN_TODAY;?></a> <a href="javascript:void(0);" data-range="week"><?php echo _MN_WEEK;?></a> <a href="javascript:void(0);" data-range="month"><?php echo _MN_MONTH;?></a> <a href="javascript:void(0);" data-range="year"><?php echo _MN_YEAR;?></a> </em> </span><?php echo _MN_SUBTITLE;?></h2>
  </div>-->
  <div class="block-content">
    <table class="display">
      <tfoot>
        <tr>
          <td><a href="javascript:void(0)" class="button-orange delete"><?php echo _MN_EMPTY_STATS;?></a></td>
        </tr>
      </tfoot>
      <tr>
        <td><!--<div id="chartdata" style="height:400px;width:100%;overflow:hidden"></div>--></td>
      </tr>
    </table>
  </div>
</div>
<script type="text/javascript"> 
// <![CDATA[
function getVisitsChart(range) {
    $.ajax({
        type: 'GET',
        url: 'ajax.php?getVisitsStats=1&timerange=' + range,
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
            $.plot($('#chartdata'), [json.hits, json.visits], option);
        }
    });
}
 $(document).ready(function () {
  getVisitsChart('month');
  $('#range a').on('click',  function () {
	  var val = $(this).attr('data-range');
	  getVisitsChart(val);
	});
		
  $('.container').on('click', 'a.delete', function () {
	  var text = '<div><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><?php echo _DEL_CONFIRM;?></div>';
	  $.confirm({
		  title: '<?php echo _MN_EMPTY_STATS;?>',
		  message: text,
		  buttons: {
			  '<?php echo _DELETE;?>': {
				  'class': 'yes',
				  'action': function () {
					  $.ajax({
						  type: 'post',
						  url: 'ajax.php',
						  data: 'deleteStats=1',
						  success: function (msg) {
							$('body').fadeOut(1200, function () {
								window.location.href = "index.php";
							});
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