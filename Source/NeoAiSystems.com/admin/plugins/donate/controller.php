<?php
  /**
   * Controller
   *
   * @version $Id: controller.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  define("_VALID_PHP", true);
  
  require_once("../../init.php");
  if (!$user->is_Admin())
      redirect_to("../../login.php");

  require_once("lang/" . $core->language . ".lang.php");
  require_once("admin_class.php");
  
  $row = new Donate();
?>
<?php
  /* Proccess Configuration */
  if (isset($_POST['processConfig'])): 
  $row->processConfig();
  endif;
?>
<?php
  /* Empty Donations */
  if (isset($_GET['emptyDonations'])): 
  $db->query("TRUNCATE TABLE plug_donate");
  redirect_to("../../index.php?do=plugins&action=config&plug=donate");
  endif;
?>
<?php
  /* Export Donations */
  if (isset($_GET['exportDonations'])) {
      $sql = "SELECT * FROM plug_donate";
      $result = $db->query($sql);
      
      $type = "vnd.ms-excel";
	  $date = date('m-d-Y H:i');
	  $title = "Exported from the " . $core->site_name . " on $date";

      header("Pragma: public");
      header("Expires: 0");
      header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
      header("Content-Type: application/force-download");
      header("Content-Type: application/octet-stream");
      header("Content-Type: application/download");
	  header("Content-Type: application/$type");
      header("Content-Disposition: attachment;filename=temp_" . time() . ".xls");
      header("Content-Transfer-Encoding: binary ");
      
      echo("$title\n");
      $sep = "\t";
      
      for ($i = 0; $i < $db->numfields($result); $i++) {
          echo mysql_field_name($result, $i) . "\t";
      }
      print("\n");
      
      while ($row = $db->fetchrow($result)) {
          $schema_insert = "";
          for ($j = 0; $j < $db->numfields($result); $j++) {
              if (!isset($row[$j]))
                  $schema_insert .= "NULL" . $sep;
              elseif ($row[$j] != "")
                  $schema_insert .= "$row[$j]" . $sep;
              else
                  $schema_insert .= "" . $sep;
          }
          $schema_insert = str_replace($sep . "$", "", $schema_insert);
          $schema_insert = preg_replace("/\r\n|\n\r|\n|\r/", " ", $schema_insert);
          $schema_insert .= "\t";
          print(trim($schema_insert));
          print "\n";
      }
	  exit();
  }
?>