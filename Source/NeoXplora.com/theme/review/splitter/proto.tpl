<tr id='pr<?php echo $this->proto['id']; ?>' class='aproto'>
  <td>
    <b><?php echo $this->proto['name'] ?></b>
    <input type='hidden' class='pageID' value='<?php echo $this->proto['pageid']; ?>' />
  </td>
  <td>
    <div href="javascript:void(0)" class="revertReviewSplitButton button">Revert</div> 
    <div href="javascript:void(0)" class="approveReviewSplitButton button">Approve</div> 
    <div href="javascript:void(0)" class="dismissReviewSplitButton button">Re-Split</div>
  </td>
</tr>