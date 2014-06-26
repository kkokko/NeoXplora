<?php
  /**
   * Donations
   *
   * @version $Id: main.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
  
  require_once(WOJOLITE . "admin/plugins/donate/lang/" . $core->language . ".lang.php");
  require_once(WOJOLITE . "admin/plugins/donate/admin_class.php");
  $donate = new Donate();
  
  $total = $donate->countDonations();
  $percentage = $donate->donationPercentage($total, $donate->atarget)
?>
<!-- Start Donations -->
<div id="donations">
<div class="progress-bar">
  <div style="width:<?php echo $percentage;?>%" class="green"><?php echo $percentage;?>%</div>
</div>
<div class="box total-box">
<span class="dtotal"><?php echo $core->cur_symbol . number_format($total, 2, '.', ',');?></span> <?php echo PLG_DP_TARGET1;?> <span class="dtarget"><?php echo $core->cur_symbol . number_format($donate->atarget, 2, '.', ',');?></span> </div>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" id="pp_form" name="pp_form">
    <input type="hidden" name="cmd" value="_donations" />
    <input type="hidden" name="business" value="<?php echo $donate->paypal;?>" />
    <input type="hidden" name="item_name" value="Donations For <?php echo $core->company;?>" />
    <input type="hidden" name="item_number" value="<?php echo rand(10,3);?>" />
    <input type="hidden" name="return" value="<?php echo SITEURL.'/'.$donate->thankyou;?>.html" />
    <input type="hidden" name="rm" value="2" />
    <input type="hidden" name="notify_url" value="<?php echo SITEURL;?>/plugins/donate/ipn.php" />
    <input type="hidden" name="cancel_return" value="<?php echo SITEURL;?>" />
    <input type="hidden" name="no_note" value="1" />
    <input type="hidden" name="currency_code" value="<?php echo $core->currency;?>" />

</form>
<a href="javascript:void(0);" class="button dodonation"><?php echo PLG_DP_DONATE;?></a>
</div>
<script type="text/javascript">
  $(document).ready(function() {
     $(".dodonation").click(function() { $("#pp_form").submit(); });
 });
</script>
<!-- End Donations /-->