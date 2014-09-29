<?php echo $this->fetch("header"); ?>
<div style="padding: 20px 30px;">
  <div class="buttons leftMenu">
    <a href="train.php?type=splitter" class='active'>Train</a>
    <a href="browse.php?type=splitter">Browse</a>
    <?php if($this->userlevel == 'admin') { ?>
    <a href="review.php?type=splitter">Review</a>
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
  <br/>
  <div style="display: inline-block; padding-top: 5px; margin-bottom: 10px; padding-left: 5px;">
    Category: 
    <select id="categoryId">
      <option value="-1">
        All
      </option>
      <option value="0">
        Uncategorized
      </option>
      <?php foreach($this->categoryList AS $key => $value) { ?>
        <option value="<?php echo $key; ?>" <?php if($this->currentCategory == $key) echo "selected='selected'"; ?>>
          <?php echo $value; ?>
        </option> 
      <?php } ?>
    </select>
  </div>
  <div style="display: inline-block; padding-top: 5px; margin-bottom: 10px; padding-left: 5px;">
    Story Title: <span class="storyTitle">-</span>
    <input type="hidden" class="ThePageId" value="<?php echo $this->thePageId; ?>" />
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