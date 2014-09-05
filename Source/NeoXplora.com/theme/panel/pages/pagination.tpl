<?php 
foreach($this->pagination as $apage) { ?>
  <?php if($apage == "skip") { ?>
    <div class='button inactive'>...</div>
  <?php } else { ?>
    <a href="panel.php?type=pages&page=<?php echo $apage; ?>&categoryId=<?php echo $this->currentCategory; ?>&status=<?php echo $this->currentStatus; ?>" class='button <?php echo (($apage == $this->currentPage)?" currentPage":""); ?>'><?php echo $apage; ?></a>
  <?php } ?>
<?php } ?>
<div class="clear"></div>