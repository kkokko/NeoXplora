<?php
  /**
   * Elastic Slider
   *
   * @package wojo:cms
   * @author wojoscripts.com
   * @copyright 2010
   * @version $Id: main.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  
  require_once(WOJOLITE . "admin/plugins/elastic/admin_class.php");

  $slider = new elasticSlider();
  $slides = $slider->getSliderImages();
  $conf = $slider->getConfiguration();
?>
<!-- Start Slider -->
<?php if($slides == 0):?>
<h1>You don't have any slider images. Please upload!</h1>
<?php else:?>
<div class="elastic-slideshow">
<div id="ei-slider" class="ei-slider" style="height:<?php echo $conf['height'];?>px">
  <ul class="ei-slider-large">
    <?php foreach ($slides as $eslrow):?>
    <li><img src="<?php echo SITEURL;?>/plugins/elastic/slides/<?php echo $eslrow['filename'];?>" alt="<?php echo $eslrow['filename'];?>" />
      <div class="ei-title">
        <h2><?php echo $eslrow['title' . $core->dblang];?></h2>
        <?php if($eslrow['description' . $core->dblang]):?><h3><?php echo $eslrow['description' . $core->dblang]?></h3><?php endif;?>
      </div>
    </li>
    <?php endforeach;?>
  </ul>
  <ul class="ei-slider-thumbs">
    <li class="ei-slider-element">Current</li>
    <?php foreach ($slides as $eslrow):?>
    <li><a href="#"><?php echo $eslrow['title' . $core->dblang];?></a><img src="<?php echo SITEURL;?>/plugins/elastic/slides/<?php echo $eslrow['filename'];?>" alt="<?php echo $eslrow['title' . $core->dblang];?>" /></li>
    <?php endforeach;?>
  </ul>
</div>
</div>
<div class="shadowSlider"></div>
<script type="text/javascript">
  $(function(){
	  $('#ei-slider').eislideshow({
		  animation:'<?php echo $conf['animation'];?>',
		  autoplay:<?php echo $conf['autoplay'];?>,
		  slideshow_interval:<?php echo $conf['interval'];?>,
		  titlesFactor:0,
		  speed:<?php echo $conf['speed'];?>,
		  titlespeed:<?php echo $conf['titlespeed'];?>,
		  thumbMaxWidth:<?php echo $conf['thumbMaxWidth'];?>
	  });
  });
</script>
<?php unset($eslrow);?>
<?php endif;?>
<!-- End Slider /-->