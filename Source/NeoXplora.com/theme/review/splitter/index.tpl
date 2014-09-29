<?php echo $this->fetch("header"); ?>
<div style="padding: 20px 30px;">
  <div class="buttons leftMenu">
    <a href="train.php?type=splitter">Train</a>
    <a href="browse.php?type=splitter">Browse</a>
    <?php if($this->userlevel == 'admin') { ?>
    <a href="review.php?type=splitter" class='active'>Review</a>
    <a href="list.php?type=splitter">List</a>
    <?php } ?>
  </div>
  <div class="button bigButton rightMenu">
    Splitter
    <ul class='button-dropdown'>
      <li><a href="train.php?type=splitter">Splitter</a></li>
      <li><a href="train.php?type=interpreter">Interpreter</a></li>
      <li><a href="train.php?type=linker">Linker</a></li>
      <li><a href="train.php?type=tutor">Tutor</a></li>
      <li><a href="train.php?type=deducer">Deducer</a></li>
      <li><a href="train.php?type=summarizer">Summarizer</a></li>
      <li><a href="train.php?type=quizzer">Quizzer</a></li>
    </ul>
  </div>
  <div class="clear"></div>
  <div style="display: inline-block; padding-top: 5px; margin-bottom: 10px; padding-left: 5px;">
    <input type="checkbox" id="dofix" />Show Reviewed
  </div>
  <br/>
  <div class="buttons smaller">
    <a href="javascript:void(0)" class="approveAllSplitButton">Approve All</a>
    <a href="javascript:void(0)" class="dismissAllSplitButton">Dismiss All</a>
  </div>
  <div class="clear"></div>
  <br/>
  <input type="hidden" class="ThePageId" value="<?php echo $this->thePageId; ?>" />
  <div class="boxContent">
    
  </div>
  <div class="boxPagination">
    
  </div>
</div>
<?php echo $this->fetch("footer"); ?>