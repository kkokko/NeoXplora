<?php
  /**
   * Bottom Plugin Layout
   *
   * @version $Id: bot_plugins.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php $total = count($plugbot);?>
<?php foreach ($plugbot as $i => $brow): ?>
<?php $lastchild = (end($plugbot) === $brow and $total > 1) ? ' last-item' : null;?>
<?php $i++;?>
<div class="col <?php echo ($total > 1 ) ? "grid_" . (24/$total) : "full";?><?php if ($i == 1 and $total > 1 ) echo " first-item";?><?php echo $lastchild;?>">
  <div class="widget-wrap<?php if($brow['alt_class'] !="") echo ' '.$brow['alt_class'];?>">
    <div class="widget-inner<?php if($brow['alt_class'] !="") echo ' '.$brow['alt_class'];?>">
      <?php if ($brow['show_title'] == 1) echo "<h4>".$brow['title'.$core->dblang]."</h4>";?>
      <?php if ($brow['body'.$core->dblang]) echo "<div class=\"widget-body\">".cleanOut($brow['body'.$core->dblang])."</div>";?>
      <?php if ($brow['jscode']) echo cleanOut($brow['jscode']);?>
      <?php if ($brow['system'] == 1):?>
      <?php $plugfile = PLUGDIR .$brow['plugalias']."/main.php";?>
      <?php if(file_exists($plugfile)) include_once($plugfile);?>
      <?php endif;?>
    </div>
  </div>
</div>
<?php endforeach; ?>
<?php unset($i);?>
<?php unset($brow);?>