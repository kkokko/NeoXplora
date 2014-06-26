<?php
  /**
   * Paypal Form
   *
   * @version $Id: form.tpl.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<div class="box top5">
  <h3><?php echo _UA_P_SUMMARY.' - '.$row2['displayname'];?></h3>
  <table class="display">
    <tr>
      <th><?php echo _MS_TITLE;?>:</th>
      <td><?php echo $row['title'.$core->dblang];?></td>
    </tr>
    <tr>
      <th><?php echo _MS_PRICE;?>:</th>
      <td><?php echo $core->formatMoney($row['price']);?></td>
    </tr>
    <tr>
      <th><?php echo _MS_PERIOD;?>:</th>
      <td><?php echo $row['days'] . ' ' .$member->getPeriod($row['period']);?></td>
    </tr>
    <tr>
      <th><?php echo _MS_RECURRING;?>:</th>
      <td><?php echo ($row['recurring'] == 1) ? _YES : _NO;?></td>
    </tr>
    <tr>
      <th><?php echo _UA_VALID_UNTIL;?><strong>:</th>
      <td><?php echo $member->calculateDays($row['period'], $row['days']);?></td>
    </tr>
    <tr>
      <th><?php echo _MS_DESC;?>:</th>
      <td><?php echo $row['description'.$core->dblang];?></td>
    </tr>
    <tr>
      <td colspan="2"><?php echo $row2['extra3'];?></td>
    </tr>
  </table>
</div>
