<?php echo $this->fetch("header"); ?>
<div class="boxContainer">
  <div class="buttons">
    <a href="train.php?type=interpreter">Train</a>
    <a href="browse.php?type=interpreter">Browse</a>
    <?php if($this->userlevel == 'admin') { ?>
    <a href="review.php?type=interpreter" class='active'>Review</a>
    <?php } ?>
  </div>
  <div class="clear"></div>
  <div class="buttons smaller">
    <a href="javascript:void(0)" class="approveAllBtn">Approve All</a>
    <a href="javascript:void(0)" class="dismissAllBtn">Dismiss All</a>
  </div>
  <div class="clear"></div>
  <br/>
  <div class="boxContent">
    
  </div>
  <div class="boxPagination">
    
  </div>
</div>
<?php echo $this->fetch("footer"); ?>