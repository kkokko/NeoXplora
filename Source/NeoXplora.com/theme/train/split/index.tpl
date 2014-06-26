<?php echo $this->fetch("header"); ?>
<div id="content">
    <div class="container relative">      
      <div class="boxTrain">        
        <script language="javascript">
          $(document).ready(function() {
            loadSplitSentence();
          });
        </script>
        
        <div id="container">
          <div class="top">
            <div class="story-title">
              Split SENTENCES
            </div>
            <div class="story-controls">
              <ul>
                <li><a href="javascript:void(0)" class="byeButton">Bye</a></li>
                <li>Trainer name (optional): <input type="text" name="trainername" id="trainername" value="<?php echo htmlentities(isset($_COOKIE['trainername'])?$_COOKIE['trainername']:''); ?>" style="width: 100px;" /></li>
              </ul>
            </div>
            <div style="clear: both"></div>
          </div>
          <div class="content-wrapper">
            <div class="content">
              
            </div>
          </div>
        </div>
        
      </div>
    </div>
</div>
<?php echo $this->fetch("footer"); ?>