<?php
  /**
   * Slideout Slider
   *
   * @version $Id: main.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
  
  require_once(WOJOLITE . "admin/plugins/slideout/admin_class.php");
  $slideout = new Slideout();
  $slidedata = $slideout->getSliderImages();
  $total = count($slidedata);
?>
<!-- Start Slideout Slider -->
<?php if($slidedata == 0):?>
<div class="msgError">You don't have any slides yet</div>
<?php else:?>
<div id="slideout-wrapper" class="row">
  <div class="slideout wide-slider">
    <ul class="kwicks horizontal" >
      <?php foreach ($slidedata as $slrow):?>
      <li id="kwick_<?php echo $slrow['id']?>">
      <img src="<?php echo SITEURL;?>/plugins/slideout/slides/<?php echo $slrow['filename']?>" alt="" />
      <span class="slideout-shade"></span>
        <div class="slideout-description">
          <h3 class="slideout-title"><?php echo $slrow['title'.$core->dblang];?></h3>
          <?php if($slrow['description'.$core->dblang]):?>
           <?php echo cleanOut($slrow['description'.$core->dblang])?>
          <?php endif;?>
        </div>
      </li>
      <?php endforeach;?>
      <?php unset($slrow);?>
    </ul>
  </div>
</div>
<span id="responsiveFlag"></span>
<script type="text/javascript">
$(document).ready(function(){
	  var elwidth = $("#slideout-wrapper").width();
	  var totalslides = <?php echo $total;?>;
	  $('.kwicks').find('li').each(function() {
		 $(this).css('width',Math.round(elwidth/totalslides));
	  });

	$('.kwicks').kwicks({
		min : 50,
		spacing : 0
	});
});
</script>
<?php endif;?>
<!-- End Slideout Slider /-->