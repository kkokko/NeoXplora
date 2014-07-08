<tr id='s<?php echo $this->sentence['id']; ?>' class='asentence <?php echo $this->sentence['rowclass']; ?> pr<?php echo $this->proto['id']; ?>'>
  <td>
    <input type='text' class='newValue' value='<?php echo htmlspecialchars($this->sentence['name'], ENT_QUOTES); ?>' />
  </td>
  <td>
    <div href="javascript:void(0)" class="modifyReviewSplitButton button">Modify</div>
  </td>
</tr>

