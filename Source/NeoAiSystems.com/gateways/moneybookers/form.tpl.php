<?php
  /**
   * Moneybookers Form
   *
   * @version $Id: form.tpl.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<div class="box top5">
  <form action="https://www.moneybookers.com/app/payment.pl" method="post" id="mb_form" name="mb_form">
    <h3><?php echo _UA_P_SUMMARY.' - '.$row2['displayname'];?></h3>
    <table class="display">
      <tfoot>
        <tr>
          <td colspan="2"><input type="image" src="<?php echo SITEURL.'/gateways/'.$row2['dir'].'/'.$row2['name'].'_big.png';?>" name="submit" title="Pay With Moneybookers" alt="" onclick="document.mb_form.submit();"/></td>
        </tr>
      </tfoot>
      <tbody>
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
          <th><?php echo _UA_VALID_UNTIL;?>:</th>
          <td><?php echo $member->calculateDays($row['period'], $row['days']);?></td>
        </tr>
        <tr>
          <th><?php echo _MS_DESC;?>:</th>
          <td><?php echo $row['description'.$core->dblang];?></td>
        </tr>
      </tbody>
    </table>
    <input type="hidden" name="pay_to_email" value="<?php echo $row2['extra'];?>" />
    <input type="hidden" name="return_url" value="<?php echo SITEURL;?>/account.php" />
    <input type="hidden" name="cancel_url" value="<?php echo SITEURL;?>/account.php" />
    <input type="hidden" name="status_url" value="<?php echo SITEURL.'/gateways/'.$row2['dir'];?>/ipn.php" />
    <input type="hidden" name="merchant_fields" value="session_id, item, custom" />
    <input type="hidden" name="item" value="<?php echo $row['title'.$core->dblang];?>" />
    <input type="hidden" name="session_id" value="<?php echo md5(mktime())?>" />
    <input type="hidden" name="custom" value="<?php echo $row['id'] . '_' . $user->uid;?>" />
    <?php if($row['recurring'] == 1):?>
    <input type="hidden" name="rec_amount" value="<?php echo $row['price'];?>" />
    <input type="hidden" name="rec_period" value="<?php echo $member->getTotalDays($row['period'], $row['days']);?>" />
    <input type="hidden" name="rec_cycle" value="day" />
    <?php else: ?>
    <input type="hidden" name="amount" value="<?php echo $row['price'];?>" />
    <?php endif; ?>
    <input type="hidden" name="currency" value="<?php echo ($row2['extra2']) ? $row2['extra2'] : $core->currency;?>" />
    <input type="hidden" name="detail1_description" value="<?php echo $row['title'.$core->dblang];?>" />
    <input type="hidden" name="detail1_text" value="<?php echo $row['description'.$core->dblang];?>" />
  </form>
</div>