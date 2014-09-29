<?php foreach($this->pagination as $apage) { ?>
  <?php if($apage == "skip") { ?>
    <div class='button inactive'>...</div>
  <?php } else { ?>
    <div class='button goToPage<?php echo (($apage == $this->currentPage)?" currentPage":""); ?>'><?php echo $apage; ?></div>
  <?php } ?>
<?php } ?>
<div class="clear"></div>