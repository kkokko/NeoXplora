<tr class='aproto' data-id='<?php echo $this->sentence['id']; ?>'>
  <td>
    <span class="sName"><b><?php echo htmlspecialchars($this->sentence['name'], ENT_QUOTES); ?></b></span>
    <input type='hidden' class='pageID' value='<?php echo $this->sentence['id']; ?>' />
  </td>
  <td width='70'>
    <a href="javascript:void(0)" class="resplitBtn"><b>ReSplit</b></a>
  </td>
</tr>

<tr class='areviewedsentence row1' data-id='<?php echo $this->sentence['id']; ?>'>
  <td colspan="2">
    <?php echo htmlspecialchars($this->sentence['rep'], ENT_QUOTES); ?>
  </td>
</tr>

