<?php echo $this->fetch("header"); ?>
      <div style="padding: 20px 30px;">
        <div class="buttons leftMenu">
          <a href="train.php?type=linker" class='active'>Train</a>
          <a href="browse.php?type=linker">Browse</a>
          <a href="review.php?type=linker">Review</a>
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
          <a href="javascript:void(0)" class="firstBtn">Skip</a>
          <a href="javascript:void(0)" class="previousBtn">Finish</a>
        </div>
        <div class="clear"></div>
        <table class="color-pallette">
          <tr>
            <td rowspan="2" style="padding: 5px; text-align: center;" class="s0 currentStyle">NO COLOR</td>
            <td class="s1"></td>
            <td class="s2"></td>
            <td class="s3"></td>
            <td class="s4"></td>
            <td class="s5"></td>
            <td class="s6"></td>
            <td class="s7"></td>
            <td class="s8"></td>
            <td class="s9"></td>
            <td class="s10"></td>
            <td class="s11"></td>
            <td class="s12"></td>
            <td class="s13"></td>
            <td class="s14"></td>
            <td class="s15"></td>
            <td class="s16"></td>
          </tr>
          <tr>
            <td class="s17"></td>
            <td class="s18"></td>
            <td class="s19"></td>
            <td class="s20"></td>
            <td class="s21"></td>
            <td class="s22"></td>
            <td class="s23"></td>
            <td class="s24"></td>
            <td class="s25"></td>
            <td class="s26"></td>
            <td class="s27"></td>
            <td class="s28"></td>
            <td class="s29"></td>
            <td class="s30"></td>
            <td class="s31"></td>
            <td class="s32"></td>
          </tr>
        </table>
        <br/>
        <div class='clear'></div>
        <div class="buttons smaller">
          <a href="javascript:void(0)" class="addRepColumn">Add Rep Column</a>
        </div>
        <div class='clear'></div>
        <div class="boxContent">
          
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