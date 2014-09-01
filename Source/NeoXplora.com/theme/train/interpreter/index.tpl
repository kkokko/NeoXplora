<?php echo $this->fetch("header"); ?>
<div class="boxContainer">
  <div class="buttons leftMenu">
    <a href="train.php?type=interpreter" class='active'>Train</a>
    <a href="browse.php?type=interpreter">Browse</a>
    <?php if($this->userlevel == 'admin') { ?>
    <a href="review.php?type=interpreter">Review</a>
    <?php } ?>
  </div>
  <div class="button bigButton rightMenu">
    Interpreter
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
  <div style="display: inline-block; padding-top: 5px; margin-bottom: 10px; padding-left: 5px;">
    Category: 
    <select id="categoryId">
      <option value="-1">
        All
      </option>
      <?php foreach($this->categoryList AS $key => $value) { ?>
        <option value="<?php echo $key; ?>" <?php if($this->currentCategory == $key) echo "selected='selected'"; ?>>
          <?php echo $value; ?>
        </option> 
      <?php } ?>
    </select>
  </div>
  <br/>
  <?php if($this->userlevel == 'admin') { ?>
    <div style="display: inline-block; padding-top: 5px; margin-bottom: 10px; padding-left: 5px;"><input type="checkbox" class="checkApproved" checked /> Approve</div> 
  <?php } ?>
  <br/>
  <div class="boxContent">
          
  </div>
</div>
<?php echo $this->fetch("footer"); ?>