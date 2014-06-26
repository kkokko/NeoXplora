<?php
  /**
   * jQuery Tabs
   *
   * @version $Id: main.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
  
  require_once(WOJOLITE . "admin/plugins/jtabs/admin_class.php");
  $tab = new jTabs();
  $tabrow = $tab->renderTabs();
  $count = count($tabrow);
?>
<!-- Start Tab Slider -->
<?php if($tabrow):?>
<div class="clearfix">
  <ul class="tabs">
    <?php foreach ($tabrow as $j => $tbrow):?>
    <li><a href="#" data-title="tab<?php echo $j++;?>" title="<?php echo $tbrow['title'.$core->dblang];?>"><?php echo $tbrow['title'.$core->dblang];?></a></li>
    <?php endforeach;?>
  </ul>
  <div class="row">
  <div class="tab-content">
    <?php foreach ($tabrow as $j => $tbrow):?>
    <div id="tab<?php echo $j++;?>"><?php echo cleanOut($tbrow['body'.$core->dblang]);?></div>
    <?php endforeach;?>
  </div>
  </div>
</div>
<?php unset($tbrow);?>
<?php unset($j);?>
<?php endif;?>
<!-- End Tab Slider /-->