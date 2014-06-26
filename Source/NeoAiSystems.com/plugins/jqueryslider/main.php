<?php
  /**
   * jQuery Slider
   *
   * @version $Id: main.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
  
  require_once(WOJOLITE . "admin/plugins/jqueryslider/admin_class.php");
  $slider = new jQuerySlider();
  $slides = $slider->getSliderImages();
  $conf = $slider->getConfiguration();
?>
<!-- Start jQuery Slider -->
<?php if($slides == 0):?>
<div class="error">You don't have any images uploaded yet</div>
<?php else:?>
<div id="jqslider">
  <ul class="slides">
    <?php foreach ($slides as $slrow):?>
    <li> <a href="<?php echo SITEURL . "/" . $slrow['url'];?>" <?php echo ($slrow['urltype'] == "ext") ? "target=\"_blank\"" : "target=\"_self\"";?>><img src="<?php echo SITEURL;?>/plugins/jqueryslider/slides/<?php echo $slrow['filename'];?>" alt="" title="<?php echo $slrow['title'.$core->dblang];?>" /></a>
      <div class="slider-caption hide-phone">
        <?php if($conf['showcaption']):?>
        <h4 class="slider-title"><?php echo $slrow['title'.$core->dblang];?></h4>
        <?php endif;?>
        <?php if($slrow['description'.$core->dblang]):?>
        <p><span><?php echo $slrow['description'.$core->dblang]?></span></p>
        <?php endif;?>
      </div>
    </li>
    <?php endforeach;?>
  </ul>
</div>
<div class="shadowSlider"></div>
<script type="text/javascript">
$(window).load(function() {
    $('#jqslider').flexslider({
		touch: true,
		animation: "<?php echo $conf['animation'];?>",
		controlsContainer: "#jqslider .flexslider-container",
		animationLoop: true,
        slideshowSpeed:<?php echo $conf['anispeed'];?>, //Slide transition speed
        animationDuration:<?php echo $conf['anitime'];?>,
        directionNav:<?php echo $conf['shownav'];?>, //Next & Prev
        pauseOnAction:<?php echo $conf['shownavhide'];?>, //Only show on hover
        controlNav:<?php echo $conf['controllnav'];?>, //1,2,3...
        keyboardNav:true, //Use left & right arrows
        pauseOnHover:<?php echo $conf['hoverpause'];?>, //Stop animation while hovering
		captionOpacity:0.7 //Universal caption opacity
    });
});
</script>
<?php endif;?>
<!-- End jQuery Slider /-->