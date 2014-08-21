<tr>
  <td width="150" style="padding-left:  <?php echo $this->sentence['indentation']; ?>px">Sentence <?php echo $this->sentence['index']; ?></td>
  <td style="padding-left: 10px; padding-right: 20px;">
    <input type="hidden" class="originalValue" value="<?php echo htmlspecialchars($this->sentence['name']); ?>" />
    <input type="text" style="width:100%" class="newSplitValue" value="<?php echo htmlspecialchars($this->sentence['newName']); ?>" />
    <input type="hidden" class="level" value="<?php echo $this->sentence['level']; ?>" />
    <input type="hidden" class="sentenceID" value="<?php echo $this->sentence['id']; ?>" />
  </td>
  <td width="340" align="center">
    <?php if(isset($this->sentence['splitBtn'])) { ?>
      <div class="button doneSplitButton">Split</div>
    <?php } ?>
    <?php if(isset($this->sentence['dontSplitBtn'])) { ?> 
      <div class="button doneNoSplitButton">No need</div>
    <?php } ?>
    <?php if(isset($this->sentence['skipBtn'])) { ?>
      <div class="button skipSplitButton">Skip</div>
    <?php } ?>
  </td>
</tr>
