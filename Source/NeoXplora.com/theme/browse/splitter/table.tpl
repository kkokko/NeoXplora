<table class='trainer'>
  <tr>
    <th>Sentence</th>
    
  </tr>
  <?php foreach($this->protos AS $proto) { ?>
    <?php $this->proto = $proto; ?>
    <?php echo $this->fetch("proto", "browse/splitter"); ?>
    <?php foreach($proto['sentences'] AS $sentence) { ?>
      <?php $this->sentence = $sentence; ?>
      <?php echo $this->fetch("sentence", "browse/splitter"); ?>
    <?php } ?>
  <?php } ?>
</table>