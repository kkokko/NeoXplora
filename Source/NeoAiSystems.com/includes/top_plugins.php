<?php
  /**
   * Top Plugin Layout
   *
   * @version $Id: top_plugins.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php $total = count($plugtop);?>
<?php foreach ($plugtop as $i => $trow): ?>
<?php $lastchild = (end($plugtop) === $trow and $total > 1) ? ' last-item' : null;?>
<?php $i++;?>
<div class="col <?php echo ($total > 1 ) ? "grid_" . (24/$total) : "full";?><?php if ($i == 1 and $total > 1 ) echo " first-item";?><?php echo $lastchild;?>">
  <div class="widget-wrap<?php if($trow['alt_class'] !="") echo ' '.$trow['alt_class'];?>">
    <div class="widget-inner<?php if($trow['alt_class'] !="") echo ' '.$trow['alt_class'];?>">
      <?php if ($trow['show_title'] == 1) echo "<h4>".$trow['title'.$core->dblang]."</h4>";?>
      <?php if ($trow['body'.$core->dblang]) echo "<div class=\"widget-body\">".cleanOut($trow['body'.$core->dblang])."</div>";?>
      <?php if ($trow['jscode']) echo cleanOut($trow['jscode']);?>
      <?php if ($trow['system'] == 1):?>
      <?php $plugfile = PLUGDIR .$trow['plugalias']."/main.php";?>
      <?php if(file_exists($plugfile)) include_once($plugfile);?>
      <?php endif;?>
    </div>
  </div>
</div>
<?php endforeach; ?>
<?php unset($i);?>
<?php unset($trow);?>