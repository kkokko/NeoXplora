<tr>
  <td width="150" style="padding-left:  <?php echo $this->indentation; ?>px">Sentence <?php echo $this->index; ?></td>
  <td style="padding-left: 10px; padding-right: 20px;">
    <input type="hidden" class="originalValue" value="<?php echo htmlspecialchars($this->sentence); ?>" />
    <input type="text" style="width:100%" class="newSplitValue" value="<?php echo htmlspecialchars($this->newSentence); ?>" />
    <input type="hidden" class="level" value="<?php echo $this->level; ?>" />
    <input type="hidden" class="sentenceID" value="<?php echo $this->sentenceID; ?>" />
  </td>
  <td width="340" align="center">
    <?php if($this->splitBtn) { ?>
      <div class="button doneSplitButton">Split</div>
    <?php } ?>
    <?php if($this->dontSplitBtn) { ?> 
      <div class="button doneNoSplitButton">No need</div>
    <?php } ?>
    <?php if($this->skipBtn) { ?>
      <div class="button skipSplitButton">Skip</div>
    <?php } ?> 
    <?php if($this->userlevel == 'admin' && $this->approveBtn) { ?>
      <div class="button approveSplitButton">Approve</div> 
    <?php } ?>
  </td>
</tr>
