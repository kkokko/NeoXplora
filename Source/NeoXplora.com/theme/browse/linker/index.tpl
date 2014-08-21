<?php echo $this->fetch("header"); ?>
      <div style="padding: 20px 30px;">
        <div class="buttons leftMenu">
          <a href="train.php?type=linker">Train</a>
          <a href="browse.php?type=linker" class='active'>Browse</a>
        </div>
        <div class="button bigButton rightMenu">
          Linker
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
        <div class="buttons smaller">
          <a href="javascript:void(0)" class="firstBtn">First</a>
          <a href="javascript:void(0)" class="previousBtn">Previous</a>
          <a href="javascript:void(0)" class="nextBtn">Next</a>
          <a href="javascript:void(0)" class="lastBtn">Last</a>
        </div>
        <div class='clear'></div>
        <div class="boxContent">
          
        </div>
        <div class="boxPagination">
    
        </div>
        <div class='clear'></div>
      </div>
    
<?php echo $this->fetch("footer"); ?>

<!--
background: #bfd255; /* Old browsers */
background: -moz-linear-gradient(left, #bfd255 0%, #72aa00 100%); /* FF3.6+ */
background: -webkit-gradient(linear, left top, right top, color-stop(0%,#bfd255), color-stop(100%,#72aa00)); /* Chrome,Safari4+ */
background: -webkit-linear-gradient(left, #bfd255 0%,#72aa00 100%); /* Chrome10+,Safari5.1+ */
background: -o-linear-gradient(left, #bfd255 0%,#72aa00 100%); /* Opera 11.10+ */
background: -ms-linear-gradient(left, #bfd255 0%,#72aa00 100%); /* IE10+ */
background: linear-gradient(to right, #bfd255 0%,#72aa00 100%); /* W3C */
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#bfd255', endColorstr='#72aa00',GradientType=1 ); /* IE6-9 */ -->