<?php 
foreach($this->pagination as $apage) { ?>
  <?php if($apage == "skip") { ?>
    <div class='button inactive'>...</div>
  <?php } else { ?>
    <a href="panel.php?type=separators&page=<?php echo $apage; ?>" class='button <?php echo (($apage == $this->currentPage)?" currentPage":""); ?>'><?php echo $apage; ?></a>
  <?php } ?>
<?php } ?>
<div class="clear"></div>