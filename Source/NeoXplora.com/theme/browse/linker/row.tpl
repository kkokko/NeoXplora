<tr id="s<?php echo $this->sentence['Id']; ?>">
  <td><?php echo $this->sentence['Name']; ?></td>
  <td class="rep first-rep">
    <?php echo htmlspecialchars($this->sentence['Rep'], ENT_QUOTES) ?>
    <input type="hidden" class="original-rep" value="<?php echo htmlspecialchars($this->sentence['Rep'], ENT_QUOTES) ?>" />
  </td>
</tr>
