<table class='trainer splitter-review loading'>
  <tr>
    <th>Sentence</th>
    <th style="width: 400px">Actions</th>
  </tr>
  <?php foreach($this->data AS $row) { ?>
    <?php if($row['type'] == "proto") { ?>
      <?php $this->proto = $row; ?>
      <?php echo $this->fetch("proto", "train/splitter"); ?>
    <?php } else { ?>
      <?php $this->sentence = $row; ?>
      <?php echo $this->fetch("sentence", "train/splitter"); ?>
    <?php } ?>
  <?php } ?>
</table>