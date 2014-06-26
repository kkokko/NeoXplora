<?php
  /**
   * News Slider
   *
   * @version $Id: main.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
  
  require_once(WOJOLITE . "admin/plugins/newsslider/admin_class.php");
  $slider = new newsSlider();
  $sliderrow = $slider->renderNewsItems();
?>
<!-- Start News Slider -->
<?php if($sliderrow):?>
<div class="clearfix">
  <div id="newsslider">
    <?php foreach ($sliderrow as $nrow):?>
    <div class="newsslider_item">
      <?php if($nrow['show_title']):?>
      <h5><?php echo $nrow['title'.$core->dblang];?></h5>
      <?php endif;?>
      <?php if($nrow['show_created']):?>
      <div><small><?php echo dodate($core->short_date, $nrow['created']);?></small></div>
      <?php endif;?>
      <?php echo cleanOut($nrow['body'.$core->dblang]);?> </div>
    <?php endforeach;?>
  </div>
  <div class="npaging"><span id="slidePrev"></span> <span id="slideNext"></span></div>
</div>
<script type="text/javascript">
  $(document).ready(function() {
	  if ($('#newsslider').length > 0) {
		  $('#newsslider').cycle({
			  fx: 'scrollHorz',
			  speed: 300,
			  randomizeEffects: true,
			  timeout: 6000,
			  cleartype: true,
			  cleartypeNoBg: true,
			  pause: true,
			  next: '#slideNext',
			  prev: '#slidePrev'
		  });
	  }
  });
</script>
<?php endif;?>
<!-- End News Slider /-->