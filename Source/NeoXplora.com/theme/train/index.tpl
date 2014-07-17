<?php echo $this->fetch("header"); ?>      
      <div class="boxTrain">        
        <span class="boxTrainSpan">How would you like to train Neo ?</span>
        <div class="trainIcons">
          <div class="btnTrain" >
            <a href="<?php echo $this->site_url; ?>train.php?type=splitter">
                <img src="images/btnSplitter.png" width="100" height="100"   alt="Train Splitter"/>
                <span class="spanTrain"> Split sentences into parts</span>
            </a>
          </div>
          <div class="btnTrain" >  
            <a href="<?php echo $this->site_url; ?>train.php?type=interpreter">
              <img src="images/btnInterpreter.png" width="100" height="100" alt="Train Interpreter"/>
              <span class="spanTrain">Identify people, objects, properties & actions </span>
            </a>
          </div>  
          <div class="btnTrain">
            <a href="<?php echo $this->site_url; ?>train.php?type=linker">
              <img src="images/btnLinker.png" width="100" height="100"   alt="Train Linker"/>
              <span class="spanTrain"> Match up objects across a web page</span>
            </a>
          </div>  
          <div class="btnTrain">
            <a href=#>
              <img src="images/btnTutor.png" width="100" height="100"   alt="Train Tutor"/>
              <span class="spanTrain">Add common sense knowledge</span>
            </a>
          </div>  
          <div class="btnTrain">
            <a href=#>
              <img src="images/btnDeducer.png" width="100" height="100"   alt="Train Deducer"/>
              <span class="spanTrain"> Add inferences for sentence pairs</span>
            </a>
          </div>
          <div class="btnTrain">
            <a href=#>
              <img src="images/btnSummarizer.png" width="100" height="100"   alt="Train Summarizer"/>
              <span class="spanTrain"> Summarize paragraphs</span>
            </a>
          </div>
          <div class="btnTrain">
            <a href=#>
              <img src="images/btnQuizzer.png" width="100" height="100"   alt="Train Quizzer"/>
              <span class="spanTrain"> Add quiz Q&A </span>
            </a>
          </div>
        </div>
        
        <span class="boxTrainSpan">
          Remember, when you train Neo something, he applies it to up to millions of other occurrences.
        </span>
        
      </div>
<?php echo $this->fetch("footer"); ?>