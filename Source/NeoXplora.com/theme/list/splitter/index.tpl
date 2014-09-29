<?php echo $this->fetch("header"); ?>
<div style="padding: 20px 30px;">
  <div class="buttons leftMenu">
    <a href="train.php?type=splitter">Train</a>
    <a href="browse.php?type=splitter">Browse</a>
    <?php if($this->userlevel == 'admin') { ?>
    <a href="review.php?type=splitter" >Review</a>
    <a href="list.php?type=splitter" class="active">List</a>
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
    Search: <input type="text" id="search_name" value=''  />
    &nbsp;
    Type: <select id="sstype">
      <option value='any'>Any</option>
      <option value='ssFinishedGenerate'>Not Trained</option>
      <option value='ssTrainedSplit'>Split Trained</option>
      <option value='ssReviewedSplit'>Split Reviewed</option>
      <option value='ssTrainedRep'>Rep Trained</option>
      <option value='ssReviewedRep'>Rep Reviewed</option>
    </select>
    &nbsp;
    Per page: <select id="per_page">
      <option<?php if($this->per_page == 15) echo " selected"; ?>>15</option>
      <option<?php if($this->per_page == 50) echo " selected"; ?>>50</option>
      <option<?php if($this->per_page == 100) echo " selected"; ?>>100</option>
      <option<?php if($this->per_page == 1000) echo " selected"; ?>>1000</option>
    </select>
    &nbsp;
    <input type="button" id="dofilter" value="Filter" />
  </div>
  <br/>
  <div class="boxContent">
    
  </div>
  <div class="boxPagination">
    
  </div>
</div>
<?php echo $this->fetch("footer"); ?>