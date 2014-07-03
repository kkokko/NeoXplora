<table class="splitter">
  <tr>
    <td width="150">Sentence 1</td>
    <td style="padding-left: 10px; padding-right: 10px;">
      <input type="hidden" class="originalValue" value="<?php echo htmlspecialchars($this->sentence); ?>" />
      <input type="text" style="width:100%" class="newSplitValue" value="<?php echo htmlspecialchars($this->sentence); ?>" />
      <input type="hidden" class="level" value="0" />
      <input type="hidden" class="sentenceID" value="<?php echo $this->sentenceID; ?>" />
    </td>
    <td width="310" align="center">
      <div class="button doneSplitButton">Split</div> 
      <div class="button doneNoSplitButton">No need</div> 
      <div class="button skipSplitButton">Skip</div> 
      <?php if($this->userlevel == 'admin') { ?>
      <div class="button approveSplitButton">Approve</div> 
      <?php } ?>
    </td>
  </tr>
</table>