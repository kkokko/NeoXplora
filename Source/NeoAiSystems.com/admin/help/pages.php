<?php
  /**
   * Pages
   *
   * @version $Id: pages.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>

<div id="dialog" title="<?php echo _HP_PAGES_TITLE;?>">
  <?php echo _HP_PAGES_BODY;?>
  <?php if($content->pageid):?>
  <div class="box">
    <p class="info"><?php echo _HP_PAGES_TIP;?></p>
  </div>
  <?php endif;?>
</div>