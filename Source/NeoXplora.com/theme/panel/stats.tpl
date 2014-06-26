<?php echo $this->fetch("header"); ?>
<div id="content">
  <div class="container relative">
    <div class="panel">
      <strong>Total Pages:</strong> <?php echo $this->pageCounts['totalPages']; ?><br/>
      <strong>Total Pages Split Trained:</strong> <?php echo $this->pageCounts['totalPagesSplitTrained']; ?><br/>
      <strong>Total Pages Rep Trained:</strong> <?php echo $this->pageCounts['totalPagesRepTrained']; ?><br/>
      <strong>Total Pages CRep Trained:</strong> <?php echo $this->pageCounts['totalPagesCRepTrained']; ?><br/><br/>
      
      <strong>Total Sentences:</strong> <?php echo $this->sentenceCounts['totalSentences']; ?><br/>
      <strong>Total Sentences Split Trained: </strong><?php echo $this->sentenceCounts['totalSentencesSplitTrained']; ?><br/>
      <strong>Total Sentences Rep Trained: </strong><?php echo $this->sentenceCounts['totalSentencesRepTrained']; ?><br/>
      <strong>Total Sentences CRep Trained: </strong><?php echo $this->sentenceCounts['totalSentencesCRepTrained']; ?><br/>
    </div>
  </div>
</div>
<?php echo $this->fetch("footer"); ?>