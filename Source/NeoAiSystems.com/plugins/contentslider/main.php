<?php
  /**
   * Content Slider
   *
   * @version $Id: main.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
  
  require_once(WOJOLITE . "admin/plugins/contentslider/admin_class.php");
  $cslider = new ContentSlider();
  $csliderdata = $cslider->getSliderImages();
?>
<!-- Start Content Slider -->
<?php if($csliderdata == 0):?>
<div class="msgError">You don't have any slides yet</div>
<?php else:?>
<div id="content-slider-wrapper">
  <div class="jqslider">
    <ul class="slides">
      <?php foreach ($csliderdata as $slrow):?>
      <li>
        <?php if($slrow['filename']):?>
        <img src="<?php echo SITEURL;?>/plugins/contentslider/slides/<?php echo $slrow['filename'];?>" alt="" title="<?php echo $slrow['title'.$core->dblang];?>" />
        <?php endif;?>
        <div class="slider-caption">
          <div class="slider-body clearfix">
            <div class="slider-content"<?php echo ($slrow['align']) ? ' style="float:right;width:50%"' : ' style="float:left;width:50%"';?>>
              <h3><?php echo $slrow['title'.$core->dblang]?></h3>
              <div class="hide-phone"><?php echo cleanOut($slrow['description'.$core->dblang])?> </div>
            </div>
          </div>
        </div>
      </li>
      <?php endforeach;?>
      <?php unset($slrow);?>
    </ul>
  </div>
</div>
<script type="text/javascript">
$(window).load(function() {
    $('.jqslider').flexslider({
		animation: "fade",
		controlsContainer: ".flexslider-container",
		animationLoop: true,
        slideshowSpeed:7000, //Slide transition speed
        animationDuration:600,
        directionNav:1, //Next & Prev
        pauseOnAction:1, //Only show on hover
        controlNav:0, //1,2,3...
        keyboardNav:true, //Use left & right arrows
        pauseOnHover:1, //Stop animation while hovering
		captionOpacity:0.7 //Universal caption opacity
    });
});
</script>
<?php endif;?>
<!-- End Content Slider /-->