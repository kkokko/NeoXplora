<?php
  /**
   * Ajax
   *
   * @version $Id: process.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  define("_VALID_PHP", true);
  
  require_once("init.php");
  if (!$user->is_Admin())
    redirect_to("login.php");
?>
<?php
  /* Load Menu */
  if (isset($_POST['getmenus']))
      : $content->getSortMenuList();
  endif;
?>
<?php
  /* Sort Menu */
  if (isset($_POST['sortmenuitems']))
      : $i = 0;
	foreach ($_POST['list'] as $k => $v)
		: $i++;
	$data['parent_id'] = intval($v);
	$data['position'] = intval($i);
	$res = $db->update("menus", $data, "id='" . (int)$k . "'");
	endforeach;
	print ($res) ? $core->msgOk(_MU_SORTED) : $core->msgAlert(_SYSTEM_PROCCESS);
  endif;  
?>
<?php
  /* Delete Menu */
  if (isset($_POST['deleteMenu']))
      : if (intval($_POST['deleteMenu']) == 0 || empty($_POST['deleteMenu']))
      : redirect_to("index.php?do=menus");
  endif;
  
  $id = intval($_POST['deleteMenu']);
  
  $action = $db->delete("menus", "id='" . $id . "'");
  $db->delete("menus", "parent_id='" . $id . "'");
  
  $title = sanitize($_POST['title']);
  print ($action) ? $wojosec->writeLog(_MENU .' <strong>'.$title.'</strong> '._DELETED, "", "no", "content") . $core->msgOk(_MENU .' <strong>'.$title.'</strong> '._DELETED) : $core->msgAlert(_SYSTEM_PROCCESS);   
  endif;
?>
<?php
  /* Delete Content Page */
  if (isset($_POST['deletePage']))
      : if (intval($_POST['deletePage']) == 0 || empty($_POST['deletePage']))
      : redirect_to("index.php?do=pages");
  endif;
  
  $id = intval($_POST['deletePage']);
  $res = $db->delete("pages", "id='" . $id . "'");
  $db->delete("posts", "page_id='" . $id . "'");
  $db->delete("layout", "page_id='" . $id . "'");

  $title = sanitize($_POST['title']);
  print ($res) ? $wojosec->writeLog(_PAGE .' <strong>'.$title.'</strong> '._DELETED, "", "no", "content") . $core->msgOk(_PAGE .' <strong>'.$title.'</strong> '._DELETED) : $core->msgAlert(_SYSTEM_PROCCESS);  
  endif;
?>
<?php
  /* Get Membership List */
  if (isset($_POST['membershiplist'])) :
      if($_POST['membershiplist'] == "Membership"):
	  $memid = getValue("membership_id", "pages", "id='".(int)$_POST['pageid']."'");
	  print $member->getMembershipList($memid);
	  endif; 
  endif;
?>
<?php
  /* Delete Content Post */
  if (isset($_POST['deletePost']))
      : if (intval($_POST['deletePost']) == 0 || empty($_POST['deletePost']))
      : redirect_to("index.php?do=posts");
  endif;
  
  $id = intval($_POST['deletePost']);
  $db->delete("posts", "id='" . $id . "'");
  $title = sanitize($_POST['title']);
  
  print ($db->affected()) ? $wojosec->writeLog(_POST .' <strong>'.$title.'</strong> '._DELETED, "", "no", "content") . $core->msgOk(_POST .' <strong>'.$title.'</strong> '._DELETED) : $core->msgAlert(_SYSTEM_PROCCESS);
  endif;
?>
<?php
  /* Delete Module */
  if (isset($_POST['deleteModule']))
      : if (intval($_POST['deleteModule']) == 0 || empty($_POST['deleteModule']))
      : redirect_to("index.php?do=modules");
  endif;
  
  $id = intval($_POST['deleteModule']);
  $data['module_id'] = 0;
  $data['module_data'] = 0;
  $db->update("pages",$data,"module_id = '".$id."'");
  $db->delete("modules", "id='" . $id . "'");
  $title = sanitize($_POST['title']);
  
  print ($db->affected()) ? $wojosec->writeLog(_MODULE .' <strong>'.$title.'</strong> '._DELETED, "", "no", "module") . $core->msgOk(_MODULE .' <strong>'.$title.'</strong> '._DELETED) : $core->msgAlert(_SYSTEM_PROCCESS);  
  endif;
?>
<?php
  /* Get Module List */
  if (isset($_POST['modulelist'])) :
      $alias = getValue('modalias','modules','id="'.intval($_POST['modulelist']).'"');
	  $module_data = intval($_POST['module_data']);
	  if(file_exists(MODPATH.$alias.'/config.php'))
	  include(MODPATH.$alias.'/config.php');
  endif;
?>
<?php
  /* Delete Plugin */
  if (isset($_POST['deletePlugin']))
      : if (intval($_POST['deletePlugin']) == 0 || empty($_POST['deletePlugin']))
      : redirect_to("index.php?do=plugins");
  endif;
  
  $id = intval($_POST['deletePlugin']);
  $db->delete("plugins", "id='" . $id . "'");
  $title = sanitize($_POST['title']);
  
  print ($db->affected()) ? $wojosec->writeLog(_PLUGIN .' <strong>'.$title.'</strong> '._DELETED, "", "no", "plugin") . $core->msgOk(_PLUGIN .' <strong>'.$title.'</strong> '._DELETED) : $core->msgAlert(_SYSTEM_PROCCESS);  
  endif;
?>
<?php
  /* Delete Membership */
  if (isset($_POST['deleteMembership']))
      : if (intval($_POST['deleteMembership']) == 0 || empty($_POST['deleteMembership']))
      : redirect_to("index.php?do=memberships");
  endif;
  
  $id = intval($_POST['deleteMembership']);
  $db->delete("memberships", "id='" . $id . "'");
  $title = sanitize($_POST['title']);
  
  print ($db->affected()) ? $wojosec->writeLog(_MEMBERSHIP .' <strong>'.$title.'</strong> '._DELETED, "", "no", "content") . $core->msgOk(_MEMBERSHIP .' <strong>'.$title.'</strong> '._DELETED) : $core->msgAlert(_SYSTEM_PROCCESS);
  endif;
?>
<?php
  /* Delete Transaction */
  if (isset($_POST['deleteTransaction']))
      : if (intval($_POST['deleteTransaction']) == 0 || empty($_POST['deleteTransaction']))
      : redirect_to("index.php?do=transactions");
  endif;
  
  $id = intval($_POST['deleteTransaction']);
  $db->delete("payments", "id='" . $id . "'");
  $title = sanitize($_POST['title']);
  
  print ($db->affected()) ? $wojosec->writeLog(_TRANSACTION .' <strong>'.$title.'</strong> '._DELETED, "", "no", "content") . $core->msgOk(_TRANSACTION .' <strong>'.$title.'</strong> '._DELETED) : $core->msgAlert(_SYSTEM_PROCCESS);
  endif;
?>
<?php
  /* Export Transactions */
  if (isset($_GET['exportTransactions'])) {
      $sql = "SELECT * FROM payments";
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
<?php
  /* Delete User */
  if (isset($_POST['deleteUser']))
      : if (intval($_POST['deleteUser']) == 0 || empty($_POST['deleteUser']))
      : redirect_to("index.php?do=users");
  endif;
  
  $id = intval($_POST['deleteUser']);
	if($id == 1):
	$core->msgError(_UR_ADMIN_E);
	else:
	$db->delete("users", "id='" . $id . "'");
	
	$username = sanitize($_POST['title']);
	
	print ($db->affected()) ? $wojosec->writeLog(_USER .' <strong>'.$username.'</strong> '._DELETED, "", "no", "content") . $core->msgOk(_USER .' <strong>'.$username.'</strong> '._DELETED) : $core->msgAlert(_SYSTEM_PROCCESS);  
  endif;
  endif;
?>
<?php
  /* User Search */
  if (isset($_POST['userSearch']))
      : $string = sanitize($_POST['userSearch'],15);
  
  if (strlen($string) > 3)
      : $sql = "SELECT id, username, email, CONCAT(fname,' ',lname) as name" 
	  . "\n FROM users"
	  . "\n WHERE MATCH (username) AGAINST ('" . $db->escape($string) . "*' IN BOOLEAN MODE)"
	  . "\n ORDER BY username LIMIT 10";
  $display = '';
  if($result = $db->fetch_all($sql)):
  $display .= '<ul id="searchresults">';
	foreach($result as $row):
	  $link = 'index.php?do=users&amp;action=edit&amp;userid=' . (int)$row['id'];
	  $display .= '<li><a href="'.$link.'">'.$row['username'].'<small>'.$row['name'].' - '.$row['email'].'</small></a></li>';
	endforeach;
  $display .= '</ul>';
  print $display;
  endif;
  endif;
  endif;
?>
<?php
  /* Check Username */
  if (isset($_POST['checkUsername'])): 
  
  $username = trim(strtolower($_POST['checkUsername']));
  $username = $db->escape($username);
  
  $sql = "SELECT username FROM users WHERE username = '".$username."' LIMIT 1";
  $result = $db->query($sql);
  $num = $db->numrows($result);
  
  echo $num;
  
  endif;
?>
<?php
  /* Update Post Order */
  if (isset($_GET['sortposts']) && $_GET['sortposts'] == 1) :
      foreach ($_GET['pid'] as $k => $v) :
          $p = $k + 1;
          
          $data['position'] = $p;
          
          $db->update("posts", $data, "id='" . intval($v) . "'");
      endforeach;
 endif;
?>

<?php
  /* Get Content Type */
  if (isset($_GET['contenttype']))
      : $type = sanitize($_GET['contenttype']);
  $display = "";
  switch ($type)
      : case "page":
      $sql = "SELECT id, title{$core->dblang} FROM pages WHERE active = '1' ORDER BY title{$core->dblang} ASC";
  $result = $db->fetch_all($sql);
  
  $display .= "<select name=\"page_id\" class=\"custombox2\" style=\"width:250px\">";
  if ($result)
      : foreach ($result as $row)
      : $display .= "<option value=\"" . $row['id'] . "\">page. " . $row['title'.$core->dblang] . "</option>\n";
  endforeach;
  endif;
  $display .= "</select>\n";
  break;
      
  case "module" :
      $sql = "SELECT id, title{$core->dblang}, modalias FROM modules WHERE active = '1' AND system = '1' ORDER BY title{$core->dblang} ASC";
  $result = $db->fetch_all($sql);
  
  if ($result): 
  $display .= "<select name=\"mod_id\" class=\"custombox2\" style=\"width:250px\">";
  
      foreach ($result as $row)
      : $display .= "<option value=\"" . $row['id'] . "\">module. " . $row['title'.$core->dblang] . "</option>\n";
  endforeach;
  
  $display .= "</select>\n";
  endif;

  break;
  default:
      $display .= "<input name=\"web\" type=\"text\" class=\"inputbox\" value=\"" . post('web') . "\" size=\"45\" />
	  &nbsp;".tooltip(_MU_LINK_T)."
	  <div class=\"mybox\"><select name=\"target\" style=\"width:100px\" class=\"select\">
          <option value=\"\">"._MU_TARGET."</option>
		  <option value=\"_blank\">"._MU_TARGET_B."</option>
		  <option value=\"_self\">"._MU_TARGET_S."</option>
        </select></div>
	  <input name=\"page_id\" type=\"hidden\" value=\"0\" />";
      
      endswitch;

      print $display;

	  print '<script type="text/javascript">
		$(function(){
			$("select.custombox2").selectbox();
		 });   
	  </script>';
	  
  endif;
?>
<?php
  /* Update Layout */

  if (isset($_GET['layout']))
      : $sort = sanitize($_GET['layout']);
  $idata = (isset($_GET['modslug'])) ? 'mod_id' : 'page_id';
  
  @$sorted = str_replace("list-", "", $_POST[$sort]);
  if ($sorted)
      : foreach ($sorted as $plug_id)
      : list($order, $plug_id) = explode("|", $plug_id);
  $stylename = explode("-", $sort);
  $page_id = $stylename[1];
  if ($stylename[0] == "default")
      //continue;
	  $db->delete("layout", "plug_id='" . (int)$plug_id . "' AND $idata = '" . (int)$page_id . "'");
  
  $data = array(
		  'plug_id' => $plug_id, 
		  'page_id' => (isset($_GET['pageslug'])) ? $page_id : 0, 
		  'mod_id' => (isset($_GET['modslug'])) ? $page_id : 0, 
		  'page_slug' => (isset($_GET['pageslug'])) ? sanitize($_GET['pageslug']) : "",
		  'modalias' => (isset($_GET['modslug'])) ? sanitize($_GET['modslug']) : "",
		  'place' => $stylename[0], 
		  'position' => $order
  );
  
  if ($stylename[0] != "default") :
  $db->delete("layout", "plug_id='" . (int)$plug_id . "' AND $idata = '" . (int)$page_id . "'");
  $db->insert("layout", $data);
  endif;
  endforeach;
  endif;
 
  endif;

?>
<?php
  /* Remote Links */
  if (isset($_GET['linktype']) && $_GET['linktype'] == "internal"): 
  $display = "";
  $display .= "<select name=\"content_id\" style=\"width:245px\" id=\"content_id\" onchange=\"updateChooser(this.value);\">";
  $display .= "<option value=\"NA\">"._RL_SELECT."</option>\n";
  
  $sql = $db->query("SELECT slug, title{$core->dblang}" 
  . "\n FROM pages" 
  . "\n ORDER BY title{$core->dblang} ASC");
  
  while ($row = $db->fetch($sql))
  : $title = $row['title'.$core->dblang];
  
  $link = str_replace(SITEURL, "", createPageLink($row['slug']));
  $display .= "<option value=\"" . $link . "\">".$title."</option>\n";
  endwhile;
  $display .= "</select>\n";
  echo $display;
  endif;
?>
<?php
  /* Delete Language */
  if (isset($_POST['deleteLanguage'])): 
  $flag_id = sanitize($_POST['deleteLanguage'],2);
  set_time_limit(120);
  $core->deleteLanguage($flag_id);
  endif;
?>
<?php
  /* == Latest Visitor Stats == */
  if (isset($_GET['getVisitsStats'])):
      if (intval($_GET['getVisitsStats']) == 0 || empty($_GET['getVisitsStats'])):
          die();
      endif;

      $range = (isset($_GET['timerange'])) ? sanitize($_GET['timerange']) : 'month';
      $data = array();
      $data['hits'] = array();
      $data['xaxis'] = array();
      $data['hits']['label'] = _MN_TOTAL_H;
      $data['visits']['label'] = _MN_UNIQUE_V;

      switch ($range)
      {
          case 'day':
		      $date = date('Y-m-d');
			  
              for ($i = 0; $i < 24; $i++)
              {
                  $row = $db->first("SELECT SUM(pageviews) AS total,"
				  . "\n SUM(uniquevisitors) as visits"
				  . "\n FROM stats" 
				  . "\n WHERE DATE(day)='" . $db->escape($date) . "'" 
				  . "\n AND HOUR(day) = '" . (int)$i . "'" 
				  . "\n GROUP BY HOUR(day) ORDER BY day ASC");

                  $data['hits']['data'][] = ($row) ? array($i, (int)$row['total']) : array($i, 0);
                  $data['visits']['data'][] = ($row) ? array($i, (int)$row['visits']) : array($i, 0);
                  $data['xaxis'][] = array($i, date('H', mktime($i, 0, 0, date('n'), date('j'), date('Y'))));
              }
              break;
          case 'week':
              $date_start = strtotime('-' . date('w') . ' days');

              for ($i = 0; $i < 7; $i++)
              {
                  $date = date('Y-m-d', $date_start + ($i * 86400));
                  $row = $db->first("SELECT SUM(pageviews) AS total," 
				  . "\n SUM(uniquevisitors) as visits"
				  . "\n FROM stats"
				  . "\n WHERE DATE(day) = '" . $db->escape($date) . "'" 
				  . "\n GROUP BY DATE(day)");

                  $data['hits']['data'][] = ($row) ? array($i, (int)$row['total']) : array($i, 0);
                  $data['visits']['data'][] = ($row) ? array($i, (int)$row['visits']) : array($i, 0);
                  $data['xaxis'][] = array($i, date('D', strtotime($date)));
              }

              break;
          default:
          case 'month':
              for ($i = 1; $i <= date('t'); $i++)
              {
                  $date = date('Y') . '-' . date('m') . '-' . $i;
                  $row = $db->first("SELECT SUM(pageviews) AS total,"
				  . "\n SUM(uniquevisitors) as visits"
				  . "\n FROM stats" 
				  . "\n WHERE (DATE(day) = '" . $db->escape($date) . "')" 
				  . "\n GROUP BY DAY(day)");

                  $data['hits']['data'][] = ($row) ? array($i, (int)$row['total']) : array($i, 0);
                  $data['visits']['data'][] = ($row) ? array($i, (int)$row['visits']) : array($i, 0);
                  $data['xaxis'][] = array($i, date('j', strtotime($date)));
              }
              break;
          case 'year':
              for ($i = 1; $i <= 12; $i++)
              {
                  $row = $db->first("SELECT SUM(pageviews) AS total,"
				  . "\n SUM(uniquevisitors) as visits"
				  . "\n FROM stats" 
				  . "\n WHERE YEAR(day) = '" . date('Y') . "'" 
				  . "\n AND MONTH(day) = '" . $i . "'" 
				  . "\n GROUP BY MONTH(day)");

                  $data['hits']['data'][] = ($row) ? array($i, (int)$row['total']) : array($i, 0);
                  $data['visits']['data'][] = ($row) ? array($i, (int)$row['visits']) : array($i, 0);
				  $data['xaxis'][] = array($i, doDate('%b',date('M', mktime(0, 0, 0, $i, 1, date('Y')))));
				  
              }
              break;
      }

      print json_encode($data);
  endif;

  /* == Latest Sales Stats == */
  if (isset($_GET['getTransactionStats'])):
	
  $range = (isset($_GET['timerange'])) ? sanitize($_GET['timerange']) : 'year';	  
  $data = array();
  $data['order'] = array();
  $data['xaxis'] = array();
  $data['order']['label'] = _TR_TOTREV;
  
  switch ($range) {
	  case 'day':
	  $date = date('Y-m-d');
		  for ($i = 0; $i < 24; $i++) {
			  $query = $db->first("SELECT COUNT(*) AS total FROM payments" 
			  . "\n WHERE DATE(date) = '" . $db->escape($date) . "'" 
			  . "\n AND HOUR(date) = '" . (int)$i . "'" 
			  . "\n AND status = 1"
			  . "\n GROUP BY HOUR(date) ORDER BY date ASC");
  
			  ($query) ? $data['order']['data'][] = array($i, (int)$query['total']) : $data['order']['data'][] = array($i, 0);
			  $data['xaxis'][] = array($i, date('H', mktime($i, 0, 0, date('n'), date('j'), date('Y'))));
		  }
		  break;
	  case 'week':
		  $date_start = strtotime('-' . date('w') . ' days');
  
		  for ($i = 0; $i < 7; $i++) {
			  $date = date('Y-m-d', $date_start + ($i * 86400));
			  $query = $db->first("SELECT COUNT(*) AS total FROM payments"
			  . "\n WHERE DATE(date) = '" . $db->escape($date) . "'"
			  . "\n AND status = 1"
			  . "\n GROUP BY DATE(date)");
  
			  ($query) ? $data['order']['data'][] = array($i, (int)$query['total']) : $data['order']['data'][] = array($i, 0);
			  $data['xaxis'][] = array($i, date('D', strtotime($date)));
		  }
  
		  break;
	  default:
	  case 'month':
		  for ($i = 1; $i <= date('t'); $i++) {
			  $date = date('Y') . '-' . date('m') . '-' . $i;
			  $query = $db->first("SELECT COUNT(*) AS total FROM payments"
			  . "\n WHERE (DATE(date) = '" . $db->escape($date) . "')"
			  . "\n AND status = 1"
			  . "\n GROUP BY DAY(date)");
  
			  ($query) ? $data['order']['data'][] = array($i, (int)$query['total']) : $data['order']['data'][] = array($i, 0);
			  $data['xaxis'][] = array($i, date('j', strtotime($date)));
		  }
		  break;
	  case 'year':
		  for ($i = 1; $i <= 12; $i++) {
			  $query = $db->first("SELECT COUNT(*) AS total FROM payments"
			  . "\n WHERE YEAR(date) = '" . date('Y') . "'"
			  . "\n AND MONTH(date) = '" . $i . "'"
			  . "\n AND status = 1"
			  . "\n GROUP BY MONTH(date)");
  
			  ($query) ? $data['order']['data'][] = array($i, (int)$query['total']) : $data['order']['data'][] = array($i, 0);
			  $data['xaxis'][] = array($i, date('M', mktime(0, 0, 0, $i, 1, date('Y'))));
		  }
		  break;
  }

   print json_encode($data);
   exit();
  endif;
  
  /* Delete Statistics */
  if (isset($_POST['deleteStats'])): 
  $action = $db->query("TRUNCATE TABLE stats");
  print ($action) ? $wojosec->writeLog(_MN_STATS_EMPTY, "", "no", "content") . $core->msgOk(_MN_STATS_EMPTY) : $core->msgAlert(_SYSTEM_PROCCESS);
  endif;
?>
<?php
  /* Delete SQL Backup */
  if (isset($_POST['deleteBackup'])) :
  $action = @unlink(WOJOLITE . 'admin/backups/'.sanitize($_POST['deleteBackup']));
  
  print ($action) ? $wojosec->writeLog(_BK_DELETE_OK, "", "no", "database") . $core->msgOk(_BK_DELETE_OK) : $core->msgAlert(_SYSTEM_PROCCESS);
  endif;
?>
<?php
  /* Delete Logs */
  if (isset($_POST['deleteLogs'])): 
  $action = $db->query("TRUNCATE TABLE log");
  print ($action) ? $wojosec->writeLog(_LG_STATS_EMPTY, "", "no", "content") . $core->msgOk(_LG_STATS_EMPTY) : $core->msgAlert(_SYSTEM_PROCCESS);
  endif;
?>