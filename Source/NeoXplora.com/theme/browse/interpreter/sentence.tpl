<tr class='aproto'>
  <td>
    <span class="sName"><b><?php echo htmlspecialchars($this->sentence['name'], ENT_QUOTES); ?></b></span>
    <input type='hidden' class='pageID' value='<?php echo $this->sentence['id']; ?>' />
  </td>
  
</tr>

<tr class='areviewedsentence row1' data-id='<?php echo $this->sentence['id']; ?>'>
  <td>
    <?php echo htmlspecialchars($this->sentence['rep'], ENT_QUOTES); ?>
  </td>
</tr>

