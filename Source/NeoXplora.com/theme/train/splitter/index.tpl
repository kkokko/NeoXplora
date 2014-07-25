<?php echo $this->fetch("header"); ?>
<div class="boxContainer">
  <div class="buttons leftMenu">
    <a href="train.php?type=splitter" class='active'>Train</a>
    <a href="browse.php?type=splitter">Browse</a>
    <?php if($this->userlevel == 'admin') { ?>
    <a href="review.php?type=splitter">Review</a>
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
  <br/>
  <div class="boxContent">
          
  </div>
</div>
<?php echo $this->fetch("footer"); ?>