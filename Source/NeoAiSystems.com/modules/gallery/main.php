<?php
  /**
   * Gallery Main
   *
   * @version $Id: main.php, v2.00 2011-04-20 16:18:34 gewa Exp $
   */
  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
  
  require_once(WOJOLITE . "admin/modules/gallery/admin_class.php");
  require_once(WOJOLITE . "admin/modules/gallery/lang/" . $core->language . ".lang.php");
  
  $gallery = new Gallery($this->module_data);
  $galrow = $gallery->getGalleryImages($this->module_data); 
?>
<?php if(!$galrow):?>
<div class="msgAlert"><?php echo MOD_GA_NOIMG;?></div>
<?php else:?>
<div id="gallerywrap" class="clearfix">
  <ul>
    <?php foreach($galrow as $i => $grow):?>
    <?php $url = SITEURL.'/'.$gallery->galpath.$gallery->folder.'/'.$grow['thumb'];?>
    <li class="dbox">
      <div class="gallery-inner">
        <?php if($gallery->watermark):?>
        <?php $url = SITEURL.'/modules/gallery/getimage.php?folder='.$gallery->folder.'&amp;image='.$grow['thumb'];?>
        <?php else:?>
        <?php $url = SITEURL.'/'.$gallery->galpath.$gallery->folder.'/'.$grow['thumb'];?>
        <?php endif;?>
        <a href="<?php echo $url;?>" title="<?php echo $grow['description'.$core->dblang];?>" data-fancybox-group="gallery" class="fancybox"> <img src="<?php echo SITEURL;?>/modules/gallery/thumbmaker.php?src=<?php echo SITEURL.'/'.$gallery->galpath.$gallery->folder.'/'.$grow['thumb'];?>&amp;w=<?php echo $gallery->image_w;?>&amp;h=<?php echo $gallery->image_h;?>&amp;s=1&amp;a=<?php echo ($gallery->method == 1) ? 'tl' : 'c' ;?>" alt=""/> </a>

        <div class="gallery-data">
          <h4><?php echo character_limiter($grow['title'.$core->dblang],20);?></h4>
          <p class="portfolio-meta-image"> <?php echo character_limiter($grow['description'.$core->dblang],22);?> </p>
        </div>
      </div>
    </li>
    <?php endforeach;?>
  </ul>
</div>
<?php endif;?>