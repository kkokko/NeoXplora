<tr class='aproto'>
  <td colspan="2">
    <b><?php echo $this->sentence['name'] ?></b>
  </td>
  
</tr>
<tr id='s<?php echo $this->sentence['id']; ?>' class='areviewedsentence <?php echo $this->sentence['rowclass']; ?>'>
  <td>
    <input type='text' class='newValue' value='<?php echo htmlspecialchars($this->sentence['rep'], ENT_QUOTES); ?>' />
  </td>
  <td>
    <div href="javascript:void(0)" class="approveBtn button">Approve</div>
    <div href="javascript:void(0)" class="dismissBtn button">Dismiss</div>
  </td>
</tr>