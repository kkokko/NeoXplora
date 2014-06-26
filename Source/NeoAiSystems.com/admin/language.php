<?php
  /**
   * Languages
   *
   * @version $Id: language.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
	  
  if(!$user->getAcl("Language")): print $core->msgAlert(_CG_ONLYADMIN, false); return; endif;
?>
<?php switch($core->action): case "edit": ?>
<?php $row = $core->getRowById("language", $content->id);?>
<div class="block-top-header">
  <h1><img src="images/lang-sml.png" alt="" /><?php echo _LA_TITLE1;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _LA_INFO1. _REQ1 . required(). _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo _LA_SUBTITLE1 . $row['name'];?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo _LA_UPDATE;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=language" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo _LA_TTITLE;?>: <?php echo required();?></th>
            <td><input name="name" type="text"  class="inputbox" value="<?php echo $row['name'];?>" size="45" /></td>
          </tr>
          <tr>
            <th><?php echo _LA_COUNTRY_ABB;?>: <?php echo required();?></th>
            <td><input name="flag" type="text" disabled="disabled" class="inputbox" value="<?php echo $row['flag'];?>" size="5" maxlength="2" readonly="readonly"/>
              <?php echo tooltip(_LA_COUNTRY_ABB_T);?></td>
          </tr>
          <tr>
            <th><?php echo _LA_LANGDIR;?>:</th>
            <td><span class="input-out">
              <label for="langdir-1"><?php echo _LA_LTR;?></label>
              <input type="radio" name="langdir" id="langdir-1" value="ltr" <?php getChecked($row['langdir'], "ltr"); ?> />
              <label for="langdir-2"><?php echo _LA_RTL;?></label>
              <input type="radio" name="langdir" id="langdir-2" value="rtl" <?php getChecked($row['langdir'], "rtl"); ?> />
              </span></td>
          </tr>
          <tr>
            <th><?php echo _LA_AUTHOR;?>:</th>
            <td><input name="author" type="text"  class="inputbox" value="<?php echo $row['author'];?>" size="45"/></td>
          </tr>
        </tbody>
      </table>
      <input name="id" type="hidden" value="<?php echo $content->id;?>" />
    </form>
  </div>
</div>
<?php echo $core->doForm("updateLanguage","controller.php");?>
<?php break;?>
<?php case "add": ?>
<div class="block-top-header">
  <h1><img src="images/lang-sml.png" alt="" /><?php echo _LA_UPDATE;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _LA_INFO2. _REQ1 . required(). _REQ2;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><?php echo _LA_SUBTITLE2;?></h2>
  </div>
  <div class="block-content">
    <form action="#" method="post" id="admin_form" name="admin_form">
      <table class="forms">
        <tfoot>
          <tr>
            <td><div class="button arrow">
                <input type="submit" value="<?php echo _LA_ADD;?>" name="dosubmit" />
                <span></span></div></td>
            <td><a href="index.php?do=language" class="button-orange"><?php echo _CANCEL;?></a></td>
          </tr>
        </tfoot>
        <tbody>
          <tr>
            <th><?php echo _LA_TTITLE;?>: <?php echo required();?></th>
            <td><input name="name" type="text"  class="inputbox" size="45" /></td>
          </tr>
          <tr>
            <th><?php echo _LA_COUNTRY_ABB;?>: <?php echo required();?></th>
            <td><input name="flag" type="text" class="inputbox"   size="2" maxlength="2"/>
              &nbsp;&nbsp; <?php echo tooltip(_LA_COUNTRY_ABB_T);?></td>
          </tr>
          <tr>
            <th><?php echo _LA_LANGDIR;?>:</th>
            <td><span class="input-out">
              <label for="langdir-1"><?php echo _LA_LTR;?></label>
              <input name="langdir" type="radio" id="langdir-1" value="ltr" checked="checked" />
              <label for="langdir-2"><?php echo _LA_RTL;?></label>
              <input type="radio" name="langdir" id="langdir-2" value="rtl" />
              </span></td>
          </tr>
          <tr>
            <th><?php echo _LA_AUTHOR;?>:</th>
            <td><input name="author" type="text"  class="inputbox" size="45"/></td>
          </tr>
          <tr>
            <td colspan="2"><p class="box"><?php echo _LA_ADD_INFO;?></p></td>
          </tr>
        </tbody>
      </table>
    </form>
  </div>
</div>
<?php echo $core->doForm("addLanguage","controller.php");?>
<?php break;?>
<?php default: ?>
<div class="block-top-header">
  <h1><img src="images/lang-sml.png" alt="" /><?php echo _LA_TITLE3;?></h1>
  <div class="divider"><span></span></div>
</div>
<p class="info"><span><?php echo $core->langIcon();?></span><?php echo _LA_INFO3;?></p>
<div class="block-border">
  <div class="block-header">
    <h2><span><a href="index.php?do=language&amp;action=add" class="button-sml"><?php echo _LA_ADD_NEW;?></a></span><?php echo _LA_SUBTITLE3;?></h2>
  </div>
  <div class="block-content">
    <table class="display sortable-table">
      <thead>
        <tr>
          <th class="firstrow">#</th>
          <th class="left"><?php echo _LA_TTITLE;?></th>
          <th><?php echo _LA_FLAG;?></th>
          <th class="left"><?php echo _LA_AUTHOR;?></th>
          <th><?php echo _EDIT;?></th>
          <th><?php echo _DELETE;?></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!$core->langList()):?>
        <tr>
          <td colspan="6"><?php echo $core->msgError(_LA_NOLANG,false);?></td>
        </tr>
        <?php else:?>
        <?php foreach ($core->langList() as $row):?>
        <tr>
          <td class="center"><?php echo $row['id'];?>.</td>
          <td><?php echo $row['name'];?></td>
          <td class="center"><img src="<?php echo SITEURL;?>/lang/<?php echo $row['flag'];?>.png" alt="" title="<?php echo $row['name'];?>" class="img-wrap tooltip"/></td>
          <td><?php echo $row['author'];?></td>
          <td class="center"><a href="index.php?do=language&amp;action=edit&amp;id=<?php echo $row['id'];?>"><img src="images/edit.png" alt="" class="tooltip" title="<?php echo _LA_EDIT.': '.$row['name'];?>"/></a></td>
          <td class="center"><a href="javascript:void(0);" class="delete" data-title="<?php echo $row['name'];?>" id="item_<?php echo $row['flag'];?>"><img src="images/delete.png" alt="" class="tooltip" title="<?php echo _DELETE;?>"/></a></td>
        </tr>
        <?php endforeach;?>
        <?php unset($row);?>
        <?php endif;?>
      </tbody>
    </table>
  </div>
</div>
<?php echo Core::doDelete(_DELETE.' '._LANGUAGE, "deleteLanguage");?>
<?php break;?>
<?php endswitch;?>