<table class='trainer splitter-list'>
  <tr class='aproto'>
    <td width='70'>Id</td>
    <td>Name</td>
    <td>Splits</td>
  </tr>
  <?php foreach($this->splits AS $split) { ?>
    <tr class='asentence <?php echo $split['rowclass']; ?>'>
      <td><?php echo $split['id']; ?></td>
      <td><?php echo $split['proto']; ?></td>
      <td><?php echo $split['splits']; ?></td>
    </tr>
  <?php } ?>
</table>