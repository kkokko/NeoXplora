<?php
  /**
   * Paypal Form
   *
   * @version $Id: form.tpl.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php $url = ($row2['demo'] == '0') ? 'www.sandbox.paypal.com' : 'www.paypal.com';?>
<div class="box top5">
  <form action="https://<?php echo $url;?>/cgi-bin/webscr" method="post" id="pp_form" name="pp_form">
    <h3><?php echo _UA_P_SUMMARY.' - '.$row2['displayname'];?></h3>
    <table class="display">
      <tfoot>
        <tr>
          <td colspan="2"><input type="image" src="<?php echo SITEURL.'/gateways/'.$row2['dir'].'/'.$row2['name'].'_big.png';?>" name="submit" title="Pay With Paypal" alt="" onclick="document.pp_form.submit();"/></td>
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
    <?php if($row['recurring'] == 1):?>
    <input type="hidden" name="cmd" value="_xclick-subscriptions" />
    <input type="hidden" name="a3" value="<?php echo $row['price'];?>" />
    <input type="hidden" name="p3" value="<?php echo $row['days'];?>" />
    <input type="hidden" name="t3" value="<?php echo $row['period'];?>" />
    <input type="hidden" name="src" value="1" />
    <input type="hidden" name="sr1" value="1" />
    <?php else:?>
    <input type="hidden" name="cmd" value="_xclick" />
    <input type="hidden" name="amount" value="<?php echo $row['price'];?>" />
    <?php endif;?>
    <input type="hidden" name="business" value="<?php echo $row2['extra'];?>" />
    <input type="hidden" name="item_name" value="<?php echo $row['title'.$core->dblang];?>" />
    <input type="hidden" name="item_number" value="<?php echo $row['id'] . '_' . $user->uid;?>" />
    <input type="hidden" name="return" value="<?php echo SITEURL;?>/account.php" />
    <input type="hidden" name="rm" value="2" />
    <input type="hidden" name="notify_url" value="<?php echo SITEURL.'/gateways/'.$row2['dir'];?>/ipn.php" />
    <input type="hidden" name="cancel_return" value="<?php echo SITEURL;?>/account.php" />
    <input type="hidden" name="no_note" value="1" />
    <input type="hidden" name="currency_code" value="<?php echo ($row2['extra2']) ? $row2['extra2'] : $core->currency;?>" />
  </form>
</div>
