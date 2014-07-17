<table class='trainer'>
  <tr>
    <th>Sentence</th>
    <th style="width: 172px">Actions</th>
  </tr>
  <?php foreach($this->sentences AS $sentence) { ?>
    <?php $this->sentence = $sentence; ?>
    <?php echo $this->fetch("row", "review/interpreter"); ?>
  <?php } ?>
</table>