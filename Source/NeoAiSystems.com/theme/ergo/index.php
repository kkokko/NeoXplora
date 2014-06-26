<?php
  /**
   * Index
   *
   * @version $Id: index.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php include("header.php");?>

  <?php if($content->slug):?>
  <!-- Breadcrumbs -->
  <section id="crumbs" class="row">
  <div class="container">  
    <div class="clearfix">
      <div class="col grid_24">
        <nav><span class="bread-home"><a href="<?php echo SITEURL;?>/index.php"><?php echo _HOME;?></a></span><?php echo $content->getBreadcrumbs();?></nav>
      </div>
    </div>
    </div>
  </section>
  <!-- Breadcrumbs /-->
  <?php endif;?>
<div class="container">  
  <?php if ($plugtop <> 0): ?>
  <?php if($content->slug):?>
  <!-- Top Plugins /-->
  <section id="topplugin" class="clearfix">
    <div class="row">
      <?php include(WOJOLITE . "includes/top_plugins.php");?>
    </div>
  </section>
  <!-- Top Plugins /-->
  <?php else:?>
  <!-- Top Plugins /-->
  <section id="home-top-plugin">
    <div class="row grid_24">
      <?php include(WOJOLITE . "includes/top_plugins.php");?>
    </div>
  </section>
  <!-- Top Plugins /-->
  <?php endif;?>
  <?php endif; ?>
 
  <!-- Left and Right Layout -->
  <?php switch(true): case $totalleft >= 1 && $totalright >= 1: ?>
  <div id="content-left-right" class="row grid_24">
    <div class="clearfix" id="page">
      <aside id="sidebar" class="col grid_6">
        <?php include(WOJOLITE . "includes/left_plugins.php");?>
      </aside>
      <div id="maincontent" class="col grid_14">
        <div class="content-box">
          <?php $content->displayPage();?>
        </div>
      </div>
      <aside id="sidebar2" class="col grid_4">
        <?php include(WOJOLITE . "includes/right_plugins.php");?>
      </aside>
    </div>
  </div>
  <?php break;?>
  <!-- Left and Right Layout /--> 
  
  <!-- Left Layout -->
  <?php case $totalleft >= 1: ?>
  <div id="content-left" class="row grid_24">
    <div id="page" class="clearfix">
      <aside id="sidebar" class="col grid_7">
        <?php include(WOJOLITE . "includes/left_plugins.php");?>
      </aside>
      <div id="maincontent" class="col grid_17">
        <div class="content-box">
          <?php $content->displayPage();?>
        </div>
      </div>
    </div>
  </div>
  <?php break;?>
  <!-- Left Layout /--> 
  
  <!-- Right Layout -->
  <?php case $totalright >= 1: ?>
  <div id="content-right" class="grid_24">
    <div class="clearfix" id="page">
      <div id="maincontent"  class="col grid_17">
        <div class="content-box">
          <?php $content->displayPage();?>
        </div>
      </div>
      <aside id="sidebar" class="col grid_7">
        <?php include(WOJOLITE . "includes/right_plugins.php");?>
      </aside>
    </div>
  </div>
  <?php break;?>
  <!-- Right Layout -->
  
  <?php default: ?>
  <!-- Full Layout -->
  <div id="page" class="row grid_24">
    <div class="content-box">
      <?php $content->displayPage();?>
    </div>
  </div>
  <?php break;?>
  <!-- Full Layout /-->
  <?php endswitch;?>
</div>

<?php if ($plugbot != 0): ?>
<!-- Bottom Plugins -->
<section id="botplugin">
  <div class="container">
    <div class="row">
      <?php include(WOJOLITE . "includes/bot_plugins.php");?>
    </div>
  </div>
</section>
<!-- Bottom Plugins /-->
<?php endif; ?>
<?php include("footer.php");?>