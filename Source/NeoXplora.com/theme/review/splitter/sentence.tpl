<tr class='asentence <?php echo $this->sentence['rowclass']; ?>' data-id="<?php echo $this->sentence['id']; ?>" data-proto="<?php echo $this->sentence['protoid']; ?>">
  <td>
    <div class="level-indent-wrapper">
      <?php for($i = 0; $i < $this->sentence['indentation'] + 1; $i++) { ?>
        <div class="level-indent level<?php echo ($i % 5); ?>"></div>
      <?php } ?>
    </div>
    <div class="content-indent">
      <input type="checkbox" class='selectedSentence' style="display: inline-block;" ?> 
      <input type='text' class='newValue' style="width: 95%" value='<?php echo htmlspecialchars($this->sentence['name'], ENT_QUOTES); ?>' />
    </div>
  </td>
  <td>
    <div href="javascript:void(0)" class="modifyReviewSplitButton button">Modify</div>
  </td>
</tr>