<?php
  /**
   * latestTwitts
   *
   * @version $Id: admin.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');

  if(!$user->getAcl("upevent")): print $core->msgAlert(_CG_ONLYADMIN, false); return; endif;
    
  require_once("lang/" . $core->language . ".lang.php");
  require_once("admin_class.php");
  $conf = new upEvent();
  
  require_once(WOJOLITE . "/admin/modules/events/admin_class.php");
  $event = new eventManager();
  $eventrow = $event->getEvents();
?>
<div class="block-top-header">
  <h1><img src="images/plug-sml.png" alt="" /><?php echo PLG_UE_TITLE1;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo PLG_UE_INFO1 . _REQ1. required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo PLG_UE_SUBTITLE1;?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo PLG_UE_UPDATE;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=plugins" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <td width="200"><?php echo PLG_UE_SELECT;?>:<?php echo required();?></td>
            <td><select class="custombox" name="event_id" style="width:250px">
                <?php if($eventrow):?>
                <?php foreach($eventrow as $row):?>
                <?php $sel = ($conf->event_id == $row['id']) ? ' selected="selected"' : '';?>
                <option value="<?php echo $row['id'];?>"<?php echo $sel;?>><?php echo $row['title'.$core->dblang];?></option>
                <?php endforeach;?>
                <?php else:?>
                <option value=""><?php echo PLG_UE_NOEVENT;?></option>
                <?php endif;?>
              </select></td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php echo $core->doForm("processConfig","plugins/upevent/controller.php");?>