<table class='trainer'>
  <tr>
    <th>Sentence</th>
    
  </tr>
  <?php foreach($this->sentences AS $sentence) { ?>
    <?php $this->sentence = $sentence; ?>
    <?php echo $this->fetch("sentence", "browse/interpreter"); ?>
    
  <?php } ?>
</table>