<?php
  /**
   * latestTwitts
   *
   * @version $Id: admin.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');

  if(!$user->getAcl("twitts")): print $core->msgAlert(_CG_ONLYADMIN, false); return; endif;
    
  require_once("lang/" . $core->language . ".lang.php");
  require_once("admin_class.php");
  
  $twitt = new latestTwitts();
?>
<div class="block-top-header">
  <h1><img src="images/plug-sml.png" alt="" /><?php echo PLG_TW_TITLE1;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo PLG_TW_INFO1 . _REQ1. required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo PLG_TW_SUBTITLE1;?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo PLG_TW_UPDATE;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=plugins" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo PLG_TW_USER;?>:<?php echo required();?></th>
            <td><input name="username" type="text" class="inputbox" value="<?php echo $twitt->username;?>" size="20" />
              <?php echo tooltip(PLG_TW_USER_T);?></td>
          </tr>
          <tr>
            <th><?php echo PLG_TW_COUNT;?>:<?php echo required();?></th>
            <td><input name="counter" type="text" class="inputbox" value="<?php echo $twitt->counter;?>" size="5" />
              <?php echo tooltip(PLG_TW_COUNT_T);?></td>
          </tr>
          <tr>
            <th><?php echo PLG_TW_TRANS_S;?>: <?php echo required();?></th>
            <td><input name="speed" type="text" class="inputbox" value="<?php echo $twitt->speed;?>" size="5"/>
              <?php echo tooltip(PLG_TW_TRANS_S_T);?></td>
          </tr>
          <tr>
            <th><?php echo PLG_TW_SHOW_IMG;?>:</th>
            <td><span class="input-out">
              <label for="show_image-1"><?php echo _YES;?></label>
              <input name="show_image" type="radio" id="show_image-1"  value="1" <?php getChecked($twitt->show_image, 1); ?> />
              <label for="show_image-2"><?php echo _NO;?></label>
              <input name="show_image" type="radio" id="show_image-2" value="0" <?php getChecked($twitt->show_image, 0); ?> />
              <?php echo tooltip(PLG_TW_SHOW_IMG_T);?></span></td>
          </tr>
          <tr>
            <th><?php echo PLG_TW_TRANS_T;?>: <?php echo required();?></th>
            <td><input name="timeout" type="text" class="inputbox" value="<?php echo $twitt->timeout;?>" size="7" />
              <?php echo tooltip(PLG_TW_TRANS_S_T);?></td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php echo $core->doForm("processConfig","plugins/twitts/controller.php");?>