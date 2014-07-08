<?php echo $this->fetch("header"); ?>
<div class="boxContainer">
  <div class="buttons">
    <a href="train.php?type=splitter">Train</a>
    <a href="browse.php?type=splitter">Browse</a>
    <?php if($this->userlevel == 'admin') { ?>
    <a href="review.php?type=splitter" class='active'>Review</a>
    <?php } ?>
  </div>
  <div class="clear"></div>
  <div class="buttons smaller">
    <a href="train.php?type=splitter">Approve All</a>
    <a href="browse.php?type=splitter">Dismiss All</a>
  </div>
  <div class="clear"></div>
  <br/>
  <div class="boxContent">
    
  </div>
  <div class="boxPagination">
    
  </div>
</div>
<?php echo $this->fetch("footer"); ?>