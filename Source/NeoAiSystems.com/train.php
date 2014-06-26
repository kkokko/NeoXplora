<?php
define("_VALID_PHP", true);
require_once ("init.php");
include (THEMEDIR . "/header.php");
?>

<script language="javascript" src="assets/train-script.js"></script>
<style type="text/css">
  @import url("assets/train-style.css");
</style>

<?php if(isset($_GET['action']) && $_GET['action'] == 'examples') { ?>

<script language="javascript">
  $(document).ready(function() {
    loadExamples(1);
  });
</script>

<div id="container">
  <div class="top">
    <div class="story-title">
      Examples
    </div>
    <div class="story-controls">
      <ul>
        <li><a href="train.php?action=incorporate">Back to training</a></li>
      </ul>
    </div>
    <div style="clear: both"></div>
  </div>
  <div class="content-wrapper">
    <div class="content">
      <table width="70%">
        <tr>
          <th>Sentence</th>
          <th>Representation</th>
        </tr>
      </table>
      <div class="pagination"></div>
    </div>
  </div>
</div>

<?php } else if(isset($_GET['action']) && $_GET['action'] == 'incorporate') { ?>

<script language="javascript">
  $(document).ready(function() {
    loadStory();
  });
</script>

<div id="container">
  <div class="top">
    <div class="story-title">
      Training CONTEXT
    </div>
    <div class="story-controls">
      <ul>
        <li><a href="javascript:void(0)" class="skipButton">Skip</a></li>
        <li><a href="javascript:void(0)" class="byeButton">Bye</a></li>
        <li><a href="train.php?action=examples">Examples</a></li>
        <li>Trainer name (optional): <input type="text" name="trainername" id="trainername" value="<?php echo htmlentities($_COOKIE['trainername']); ?>" style="width: 100px;"/></li>
      </ul>
    </div>
    <div style="clear: both"></div>
  </div>
  <div class="content-wrapper">
    <div class="content">
      <table width="70%">
        <tr>
          <th width="33%" align="left">Sentence</th>
          <th width="33%" align="left">Representation</th>
          <th width="33%" align="left">Contextual Representation</th>
        </tr>
      </table>
    </div>
  </div>
</div>

<?php } else if(isset($_GET['action']) && $_GET['action'] == 'understand') { ?>

<script language="javascript">
  $(document).ready(function() {
    loadUnderstandSentence();
  });
</script>

<div id="container">
  <div class="description">
    For each sentence in the story, please correct Neo's current guess for his representation of it and hit enter (or check it correct if he's right).<br/><br/>
    People are p1, p2 etc. Objects are o1, o2 etc. Living beings (and AIs) can be referred to by their type, eg dog1, dog2 etc). Each sentence is analysed in ISOLATION (so p1 in sentence 1 might be one person and another in sentence 2). That's OK. Neo will correct that using context later.<br/><br/>
    Check out our <a href="train.php?action=examples">examples</a>.
  </div>
  <div class="top">
    <div class="story-title">
      Understand SENTENCES
    </div>
    <div class="story-controls">
      <ul>
        <li><a href="javascript:void(0)" class="byeButton">Bye</a></li>
        <li><a href="train.php?action=examples">Examples</a></li>
        <li>Trainer name (optional): <input type="text" name="trainername" id="trainername" value="<?php echo htmlentities($_COOKIE['trainername']); ?>" style="width: 100px;" /></li>
      </ul>
    </div>
    <div style="clear: both"></div>
  </div>
  <div class="content-wrapper">
    <div class="content">
      
    </div>
  </div>
</div>

<?php } else if(isset($_GET['action']) && $_GET['action'] == 'splitSentences') { ?>

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

<?php } else if(isset($_GET['action']) && $_GET['action'] == 'addQA') { ?>

<script language="javascript">
  $(document).ready(function() {
    loadQAStory();
  });
</script>

<div id="container">
  <div class="top">
    <div class="story-title">
      Add Q&A
    </div>
    <div class="story-controls">
      <ul>
        <li><a href="javascript:void(0)" class="byeButton">Bye</a></li>
        <li>Trainer name (optional): <input type="text" name="trainername" id="trainername" value="<?php echo htmlentities($_COOKIE['trainername']); ?>" style="width: 100px;" /></li>
      </ul>
    </div>
    <div style="clear: both"></div>
  </div>
  <div class="content-wrapper">
    <div class="content">
      
    </div>
  </div>
</div>

<?php } else if(isset($_GET['action']) && $_GET['action'] == 'aboutTrainer') { ?>

<script language="javascript">
  $(document).ready(function() {
    loadAboutStory();
  });
</script>

<div id="container">
  <div class="description">
     Please create one or two short sentences that succinctly describe what this paragraph is about. 
  </div>
  <div class="top">
    <div class="story-title">
      SUMMARY trainer
    </div>
    <div class="story-controls">
      <ul>
        <li><a href="javascript:void(0)" class="byeButton">Bye</a></li>
        <li>Trainer name (optional): <input type="text" name="trainername" id="trainername" value="<?php echo htmlentities($_COOKIE['trainername']); ?>" style="width: 100px;" /></li>
      </ul>
    </div>
    <div style="clear: both"></div>
  </div>
  <div class="content-wrapper">
    <div class="content">
      
    </div>
  </div>
</div>

<?php } else if(isset($_GET['action']) && $_GET['action'] == 'reviewRep') { ?>

<script language="javascript">
  $(document).ready(function() {
    loadReviewRep(1);
  });
</script>

<div id="container">
  <div class="top">
    <div class="story-title">
      Review Reps
    </div>
    <div class="story-controls">
      <ul>
        <li><a href="javascript:void(0)" class="approveAllRepButton">Approve All</a></li>
        <li><a href="javascript:void(0)" class="dismissAllRepButton">Dismiss All</a></li>
        <li><a href="javascript:void(0)" class="byeButton">Bye</a></li>
      </ul>
    </div>
    <div style="clear: both"></div>
  </div>
  <div class="content-wrapper">
    <div class="content">
      
    </div>
    <div class="pagination">
      
    </div>
  </div>
</div>

<?php } else if(isset($_GET['action']) && $_GET['action'] == 'reviewCrep') { ?>

<script language="javascript">
  $(document).ready(function() {
    loadReviewCrep(1);
  });
</script>

<div id="container">
  <div class="top">
    <div class="story-title">
      Review CReps
    </div>
    <div class="story-controls">
      <ul>
        <li><a href="javascript:void(0)" class="approveAllCRepButton">Approve All</a></li>
        <li><a href="javascript:void(0)" class="dismissAllCRepButton">Dismiss All</a></li>
        <li><a href="javascript:void(0)" class="byeButton">Bye</a></li>
      </ul>
    </div>
    <div style="clear: both"></div>
  </div>
  <div class="content-wrapper">
    <div class="content">
      
    </div>
    <div class="pagination">
      
    </div>
  </div>
</div>

<?php } else if(isset($_GET['action']) && $_GET['action'] == 'reviewSplit') { ?>

<script language="javascript">
  $(document).ready(function() {
    loadReviewSplit(1);
  });
</script>

<div id="container">
  <div class="top">
    <div class="story-title">
      Review Splits
    </div>
    <div class="story-controls">
      <ul>
        <li><a href="javascript:void(0)" class="approveAllSplitButton">Approve All</a></li>
        <li><a href="javascript:void(0)" class="dismissAllSplitButton">Dismiss All</a></li>
        <li><a href="javascript:void(0)" class="byeButton">Bye</a></li>
      </ul>
    </div>
    <div style="clear: both"></div>
  </div>
  <div class="content-wrapper">
    <div class="content">
      
    </div>
    <div class="pagination">
      
    </div>
  </div>
</div>

<?php } else if(isset($_GET['action']) && $_GET['action'] == 'reviewQA') { ?>

<script language="javascript">
  $(document).ready(function() {
    loadReviewQA();
  });
</script>

<div id="container">
  <div class="top">
    <div class="story-title">
      Review Q&As
    </div>
    <div class="story-controls">
      <ul>
        <li><a href="javascript:void(0)" class="byeButton">Bye</a></li>
      </ul>
    </div>
    <div style="clear: both"></div>
  </div>
  <div class="content-wrapper">
    <div class="content">
      
    </div>
  </div>
</div>

<?php } else if(isset($_GET['action']) && $_GET['action'] == 'reviewSummary') { ?>

<script language="javascript">
  $(document).ready(function() {
    loadReviewSummary();
  });
</script>

<div id="container">
  <div class="top">
    <div class="story-title">
      Review Summaries
    </div>
    <div class="story-controls">
      <ul>
        <li><a href="javascript:void(0)" class="byeButton">Bye</a></li>
      </ul>
    </div>
    <div style="clear: both"></div>
  </div>
  <div class="content-wrapper">
    <div class="content">
      
    </div>
  </div>
</div>

<?php } else { ?>
  <style type="text/css">
      .top { padding-bottom: 10px; }
  </style>
  <div id="container">
    <div class="top">

      What would you like to teach NEO ?
      
      <ul class='button-list'>
        <li class='splitButton'>SPLIT sentences</li>
        <li class='understandButton'>Understand SENTENCES</li>
        <li class='incorporateButton'>Incorporate CONTEXT</li>
        <li class='addQAButton'>Add Q&A</li>
        <li class='aboutTrainerButton'>SUMMARY trainer</li>
      </ul>
      
      <br/>
      
      <?php if($user->logged_in && ($user->userlevel == '8' || $user->userlevel == '9')) { ?>
        <ul class='button-list admin-buttons'>
          <li class='reviewSplitButton'>Review splits</li>
          <li class='reviewRepButton'>Review reps</li>
          <li class='reviewCrepButton'>Review creps</li>
          <li class='reviewQAButton'>Review Q&As</li>
          <li class='reviewSummaryButton'>Review Summaries</li>
        </ul>        
      <?php } ?>
  
      <br style="clear:both" />
  
      <?php /*Trainer name (optional): <input type="text" name="trainername" id="trainername" style="width: 300px;" /><br />
      <a href="train.php" class="saveTrainerName">Continue</a>*/ ?>
    </div>
  </div>

<?php } ?>

<?php
include (THEMEDIR . "/footer.php");
?>

