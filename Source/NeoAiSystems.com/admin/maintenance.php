<?php
  /**
   * Maintenance
   *
   * @version $Id: config.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  
  if(!$user->getAcl("Maintenance")): print $core->msgAlert(_CG_ONLYADMIN, false); return; endif;
?>
<div class="block-top-header">
  <h1><img src="images/settings-sml.png" alt="" /><?php echo _SM_TITLE1;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _SM_INFO1;?></p>
<form action="#" method="post" id="admin_form" name="admin_form">
  <div class="block-border">
    <div class="block-content">
      <table class="forms">
        <tr>
          <td colspan="2" class="left"><strong><?php echo _SM_SUBTITLE1;?></strong></td>
        </tr>
        <tr>
          <td colspan="2"><?php echo _SM_SUBTITLE1_T;?></td>
        </tr>
        <tr>
          <th><?php echo _SM_DAYS;?>:</th>
          <td><select name="days" class="custombox" style="width:60px">
              <option value="3">3</option>
              <option value="7">7</option>
              <option value="14">14</option>
              <option value="30">30</option>
              <option value="60">60</option>
              <option value="100">100</option>
              <option value="180">180</option>
              <option value="365">365</option>
            </select></td>
        </tr>
        <tr>
          <td colspan="2"><input type="submit" name="inactive" class="button" value="<?php echo _SM_DELINACTIVE;?>" /></td>
        </tr>
      </table>
    </div>
  </div>
  <br />
  <div class="block-border">
    <div class="block-content">
      <table class="forms">
        <tr>
          <td class="left"><strong><?php echo _SM_SUBTITLE2;?></strong></td>
        </tr>
        <tr>
          <td><?php echo str_replace("[NUMBER]", countEntries("users","active","t"), _SM_SUBTITLE2_T);?></td>
        </tr>
        <tr>
          <td><input type="submit" name="pending" class="button" value="<?php echo _SM_DELPENDING;?>" /></td>
        </tr>
      </table>
    </div>
  </div>
  <br />
  <div class="block-border">
    <div class="block-content">
      <table class="forms">
        <tr>
          <td class="left"><strong><?php echo _SM_SUBTITLE3;?></strong></td>
        </tr>
        <tr>
          <td><?php echo str_replace("[NUMBER]", countEntries("users","active","b"), _SM_SUBTITLE3_T);?></td>
        </tr>
        <tr>
          <td><input type="submit" name="banned" class="button" value="<?php echo _SM_DELBANNED;?>" /></td>
        </tr>
      </table>
    </div>
  </div>
  <br />
  <div class="block-border">
    <div class="block-content">
      <table class="forms">
        <tr>
          <td colspan="2" class="left"><strong><?php echo _SM_SUBTITLE4;?></strong></td>
        </tr>
        <tr>
          <td colspan="2"><?php echo _SM_SUBTITLE4_T;?></td>
        </tr>
        <?php if($core->checkTable("mod_articles")):?>
        <tr>
          <th><?php echo _SM_DOAM;?></th>
          <td><input name="am" type="checkbox" class="checkbox" value="1" checked="checked"/></td>
        </tr>
        <?php endif;?>
        <?php if($core->checkTable("mod_digishop")):?>
        <tr>
          <th><?php echo _SM_DODS;?></th>
          <td><input name="ds" type="checkbox" class="checkbox" value="1" checked="checked"/></td>
        </tr>
        <?php endif;?>
        <?php if($core->checkTable("mod_portfolio")):?>
        <tr>
          <th><?php echo _SM_DOPF;?></th>
          <td><input name="pf" type="checkbox" class="checkbox" value="1" checked="checked"/></td>
        </tr>
        <?php endif;?>
        <tr>
          <td colspan="2"><input type="submit" name="sitemap" class="button" value="<?php echo _SM_CREATESM;?>" /></td>
        </tr>
      </table>
    </div>
  </div>
</form>
<?php echo $core->doForm("processMaintenance");?> 