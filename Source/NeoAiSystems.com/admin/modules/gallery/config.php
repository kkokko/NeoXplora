<?php
  /**
   * Configuration
   *
   * @version $Id: controller.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');

  require_once("lang/" . $core->language . ".lang.php");
  require_once("admin_class.php");
  $gal = new Gallery();
  $galrow = $gal->getGalleries();
?>
  <th><?php echo _PG_GAL;?>:</th>
  <td><select class="custombox2" name="module_data" style="width:300px">
      <option value="0"><?php echo _PG_GAL_SEL;?></option>
      <?php if($galrow):?>
      <?php foreach($galrow as $grow):?>
      <?php $sel = ($grow['id'] == $module_data) ? ' selected="selected"' : '' ;?>
      <option value="<?php echo $grow['id'];?>"<?php echo $sel;?>><?php echo $grow['title'.$core->dblang];?></option>
      <?php endforeach;?>
      <?php unset($grow);?>
  <?php endif;?>
  </select></td>
<script type="text/javascript">
$("select.custombox2").selectbox();
</script>