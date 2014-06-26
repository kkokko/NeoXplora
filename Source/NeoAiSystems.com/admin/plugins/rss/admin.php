<?php
  /**
   * Rss
   *
   * @version $Id: admin.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');

  if(!$user->getAcl("rss")): print $core->msgAlert(_CG_ONLYADMIN, false); return; endif;
    
  require_once("lang/" . $core->language . ".lang.php");
  require_once("admin_class.php");
  $rss = new Rss();
?>
<div class="block-top-header">
  <h1><img src="images/plug-sml.png" alt="" /><?php echo PLG_RS_TITLE1;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo PLG_RS_INFO1 . _REQ1. required() . _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo PLG_RS_SUBTITLE1;?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo PLG_RS_UPDATE;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=plugins" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo PLG_RS_URL;?>: <?php echo required();?></th>
            <td><input name="url" type="text" class="inputbox" value="<?php echo $rss->url;?>" size="65"/>
              <?php echo tooltip(PLG_RS_URL_T);?></td>
          </tr>
          <tr>
            <th><?php echo PLG_RS_TITLETRIM;?>:</th>
            <td><input name="title_trim" type="text" class="inputbox" value="<?php echo $rss->title_trim;?>" size="5" />
              <?php echo tooltip(PLG_RS_TITLETRIM_T);?></td>
          </tr>
          <tr>
            <th><?php echo PLG_RS_SHOW_BODY;?>:</th>
            <td><span class="input-out">
              <label for="show_body-1"><?php echo _YES;?></label>
              <input name="show_body" type="radio" id="show_body-1" value="1" <?php getChecked($rss->show_body, 1); ?> />
              <label for="show_body-2"><?php echo _NO;?></label>
              <input name="show_body" type="radio" id="show_body-2" value="0" <?php getChecked($rss->show_body, 0); ?> />
              </span></td>
          </tr>
          <tr>
            <th><?php echo PLG_RS_BODYTRIM;?>:</th>
            <td><input name="body_trim" type="text" class="inputbox" value="<?php echo $rss->body_trim;?>" size="10" />
              <?php echo tooltip(PLG_RS_BODYTRIM_T);?></td>
          </tr>
          <tr>
            <th><?php echo PLG_RS_SHOW_DATE;?>:</th>
            <td><span class="input-out">
              <label for="show_date-1"><?php echo _YES;?></label>
              <input name="show_date" type="radio" id="show_date-1" value="1" <?php getChecked($rss->show_date, 1); ?> />
              <label for="show_date-2"><?php echo _NO;?></label>
              <input name="show_date" type="radio" id="show_date-2" value="0" <?php getChecked($rss->show_date, 0); ?> />
              </span></td>
          </tr>
          <tr>
            <th><?php echo PLG_RS_DATEFORMAT;?>: <?php echo required();?></th>
            <td><select name="dateformat" class="custombox" style="width:200px">
                <option value=""><?php echo PLG_RS_DATE_T;?></option>
                <?php echo $rss->getDateFormat();?>
              </select></td>
          </tr>
          <tr>
            <th><?php echo PLG_RS_ITEMS;?>:</th>
            <td><input name="perpage" type="text" class="inputbox" value="<?php echo $rss->perpage;?>" size="5" />
              <?php echo tooltip(PLG_RS_ITEMS_T);?></td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php echo $core->doForm("processConfig","plugins/rss/controller.php");?>