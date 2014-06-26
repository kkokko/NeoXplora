<?php
  /**
   * videoSlider Slider
   *
   * @version $Id: main.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
  
  require_once(WOJOLITE . "admin/plugins/videoslider/admin_class.php");
  $slider = new videoSlider();
  $vidrow = $slider->getSliderImages();
?>
<!-- Start videoSlider Slider -->
<?php if($vidrow == 0):?>
<div class="error">You don't have any images uploaded yet</div>
<?php else:?>
<div id="player-wrapper">
  <div id="ytvideo"></div>
  <div id="scrollbar">
    <div class="scrollbar">
      <div class="track">
        <div class="scroll-thumb">
          <div class="end"></div>
        </div>
      </div>
    </div>
    <div class="viewport clearfix">
      <div class="overview clearfix">
        <ul class="vidlist">
          <?php foreach ($vidrow as $vsrow):?>
          <li><a href="http://www.youtube.com/watch?v=<?php echo $vsrow['vidurl'];?>&amp;wmode=opaque"></a>
            <div class="hide-phone"><?php echo $vsrow['title' . $core->dblang];?></div>
            <?php $desc = cleanOut($vsrow['description' . $core->dblang]);?>
            <div class="hide-phone"><?php echo character_limiter($desc,100);?></div>
          </li>
          <?php endforeach;?>
          <?php unset($vsrow);?>
        </ul>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function () {
	  $('#scrollbar').tinyscrollbar({
		  sizethumb: 80,
		  axis: 'x'
	  });
	  
  });
  $(function () {
	  $("ul.vidlist").ytplaylist({
		  holderId: 'ytvideo',
		  playerHeight: 300,
		  playerWidth: 100,
		  addThumbs: true,
		  thumbSize: 'small'
	  });
  });
</script>
<?php endif;?>
<!-- End videoSlider Slider /-->