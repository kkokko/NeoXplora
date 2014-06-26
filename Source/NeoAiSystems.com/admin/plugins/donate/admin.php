<?php
  /**
   * latestTwitts
   *
   * @version $Id: admin.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');

  if(!$user->getAcl("donate")): print $core->msgAlert(_CG_ONLYADMIN, false); return; endif;
    
  require_once("lang/" . $core->language . ".lang.php");
  require_once("admin_class.php");
  $donate = new Donate();
?>
<?php switch($core->paction): case "config": ?>
<div class="block-top-header">
  <h1><img src="images/plug-sml.png" alt="" /><?php echo PLG_DP_TITLE1;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo PLG_DP_INFO1 . _REQ1. required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo PLG_DP_SUBTITLE1;?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo PLG_DP_UPDATE;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=plugins&amp;action=config&amp;plug=donate" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo PLG_DP_TARGET;?>:<?php echo required();?></th>
            <td><input type="text" name="atarget" class="inputbox" value="<?php echo $donate->atarget;?>"  size="20"/></td>
          </tr>
          <tr>
            <th><?php echo PLG_DP_PAYPAL;?>:<?php echo required();?></th>
            <td><input type="text" name="paypal" class="inputbox" value="<?php echo $donate->paypal;?>"  size="55"/></td>
          </tr>
          <tr>
            <th><?php echo PLG_DP_THANKYOU;?>:</th>
            <td><?php echo $donate->getPageList();?></td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php echo $core->doForm("processConfig","plugins/donate/controller.php");?>
<?php break;?>
<?php default: ?>
<?php $donaterow = $donate->getDonations();?>
<div class="block-top-header">
  <h1><img src="images/plug-sml.png" alt="" /><?php echo PLG_DP_TITLE2;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo PLG_DP_INFO2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2> <span class="dropdown-menu"> <a href="javascript:void(0);" class="menu-toggle"><img src="images/options.png" alt="" /></a> <em class="drop-wrap"> <a href="plugins/donate/controller.php?emptyDonations"><?php echo PLG_DP_RESET;?></a> <a href="plugins/donate/controller.php?exportDonations"><?php echo PLG_DP_EMPTY;?></a> <a href="index.php?do=plugins&amp;action=config&amp;plug=donate&amp;plug_action=config"><?php echo PLG_DP_CONFIG;?></a> </em> </span> <?php echo PLG_DP_SUBTITLE2;?></h2>
  </div>
  <div class="block-content">
    <table class="display">
      <thead>
        <tr>
          <th class="firstrow">#</th>
          <th class="left"><?php echo PLG_DP_NAME;?></th>
          <th class="left"><?php echo PLG_DP_EMAIL;?></th>
          <th class="left"><?php echo PLG_DP_AMOUNT;?></th>
          <th class="left"><?php echo PLG_DP_CREATED;?></th>
        </tr>
      </thead>
      <tbody>
        <?php if($donaterow == 0):?>
        <tr>
          <td colspan="5"><div class="msgInfo"><?php echo PLG_DP_NODONATIONS;?></div></td>
        </tr>
        <?php else:?>
        <?php foreach ($donaterow as $slrow):?>
        <tr>
          <th class="center"><?php echo $slrow['id'];?>.</th>
          <td><?php echo $slrow['name'];?></td>
          <td><?php echo $slrow['email'];?></td>
          <td><?php echo $slrow['amount'];?></td>
          <td><?php echo dodate($core->long_date, $slrow['created']);?></td>
        </tr>
        <?php endforeach;?>
        <?php unset($slrow);?>
        <?php if($pager->items_total > $pager->items_per_page):?>
        <tr>
          <td colspan="5"><div class="pagination"><?php echo $pager->display_pages();?></div></td>
        </tr>
        <?php endif;?>
        <?php endif;?>
      </tbody>
    </table>
  </div>
</div>
<?php break;?>
<?php endswitch;?>