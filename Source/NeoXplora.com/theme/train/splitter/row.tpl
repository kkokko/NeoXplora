<tr>
  <td width="150" style="padding-left:  <?php echo $this->sentence['indentation']; ?>px">Sentence <?php echo $this->sentence['index']; ?></td>
  <td style="padding-left: 10px; padding-right: 20px;">
    <input type="hidden" class="originalValue" value="<?php echo htmlspecialchars($this->sentence['name']); ?>" />
    <textarea class="newSplitValue"><?php echo htmlspecialchars($this->sentence['newName']); ?></textarea>
    <input type="hidden" class="level" value="<?php echo $this->sentence['level']; ?>" />
    <input type="hidden" class="sentenceID" value="<?php echo $this->sentence['id']; ?>" />
  </td>
  <td width="230" align="center">
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

