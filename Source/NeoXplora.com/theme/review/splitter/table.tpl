<table class='splitter'>
  <tr>
    <th>Sentence</th>
    <th style="width: 270px">Actions</th>
  </tr>
  <?php foreach($this->protos AS $proto) { ?>
    <?php $this->proto = $proto; ?>
    <?php echo $this->fetch("proto", "review/splitter"); ?>
    <?php foreach($proto['sentences'] AS $sentence) { ?>
      <?php $this->sentence = $sentence; ?>
      <?php echo $this->fetch("sentence", "review/splitter"); ?>
    <?php } ?>
  <?php } ?>
</table>