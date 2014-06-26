<?php
  /**
   * AdBlock Class
   *
   * @package CMS Pro
   * @author wojoscripts.com
   * @copyright 2012
   * @version $Id: class_admin.php, v1.00 2012-12-24 12:12:12 gewa Exp $
   */

  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');

  require_once (WOJOLITE . "admin/modules/adblock/lang/" . $core->language . ".lang.php");

  class AdBlock
  {

      private $mTable = "mod_adblock";
      private $pTable = "plugins";
      private $amTable = "mod_adblock_memberlevels";
      public $adblockid = null;
      public $imagepath = "modules/adblock/dataimages/";
      public $pluginspath = "plugins/adblock/";

      /**
       * AdBlock::__construct()
       * 
       * @return
       */
      function __construct()
      {
          $this->getAdBlockId();
      }

      /**
       * AdBlock::getAdBlockId()
       * 
       * @return
       */
      private function getAdBlockId()
      {
          global $core;
          if (isset($_GET['adblockid'])) {
              $adblockid = (is_numeric($_GET['adblockid']) && $_GET['adblockid'] > -1) ? intval($_GET['adblockid']) : false;
              $adblockid = sanitize($adblockid);

              if ($adblockid == false) {
                  $core->error("You have selected an Invalid AdBlockId", "AdBlock::getAdBlockId()");
              } else
                  return $this->adblockid = $adblockid;
          }
      }

      /**
       * AdBlock::getAdBlock()
       * 
       * @return
       */
      public function getAdBlock()
      {
          global $db, $core, $pager;

          require_once (WOJOLITE . "lib/class_paginate.php");
          $pager = new Paginator();

          $counter = countEntries($this->mTable);

          $pager->items_total = $counter;
          $pager->default_ipp = $core->perpage;
          $pager->paginate();

          if ($counter == 0) {
              $pager->limit = null;
          }

          $sql = "SELECT m.*" . "\n FROM " . $this->mTable . " as m" . "\n ORDER BY m.id, m.title{$core->dblang}" . $pager->limit;
          $row = $db->fetch_all($sql);

          if (is_array($row))
              foreach ($row as $k => $r) {
                  $row[$k]['is_online'] = self::isOnline($r);
                  $row[$k]['is_online_str'] = self::isOnlineStr($r);
                  $row[$k]['block_assignment'] = str_replace('adblock/', '', $r['block_assignment']);
              }

          return ($row) ? $row : 0;
      }

      /**
       * AdBlock::isOnline($row)
       *
       * @return
       */
      public static function isOnline($row)
      {
          $now = strtotime(date('Y-m-d', time()));

          //time-period checking
          if (strtotime($row['start_date']) > $now)
              return false;
          if ($row['end_date'] > 0 && strtotime($row['end_date']) <= $now)
              return false;

          $total_views_allowed = $row['total_views_allowed'];
          $total_views = $row['total_views'];
          $total_clicks_allowed = $row['total_clicks_allowed'];
          $total_clicks = $row['total_clicks'];
          $min_ctr = $row['minimum_ctr'];
          $ctr = ($total_views) ? round($total_clicks / $total_views) : 0;


          //conditions checking
          if ($total_views_allowed > 0 && $total_views > 0 && $total_views_allowed <= $total_views)
              return false;
          if ($total_clicks_allowed > 0 && $total_clicks > 0 && $total_clicks_allowed <= $total_clicks)
              return false;
          if ($min_ctr > 0 && $total_views > 0 && $ctr < $min_ctr)
              return false;

          return true;
      }

      /**
       * AdBlock::isOnlineStr($row)
       *
       * @return
       */
      public static function isOnlineStr($row)
      {
          return (self::isOnline($row)) ? MOD_AB_ONLINE : MOD_AB_OFFLINE;
      }


      /**
       * AdBlock::getSingle()
       * 
       * @return
       */
      public function getSingle()
      {
          global $db, $core;

          $sql = "SELECT m.*, GROUP_CONCAT(am.memberlevel_id) AS memberlevels" 
		  . "\n FROM " . $this->mTable . " as m" 
		  . "\n JOIN " . $this->amTable . " as am ON m.id = am.adblock_id" 
		  . "\n WHERE id = {$this->adblockid}";

          $row = $db->first($sql);

          if ($row) {
              $row['is_online'] = self::isOnline($row);
              $row['is_online_str'] = self::isOnlineStr($row);
              $row['block_assignment'] = str_replace('adblock/', '', $row['block_assignment']);
          }

          return ($row) ? $row : 0;
      }


      /**
       * AdBlock::processAdBlock()
       * 
       * @return
       */
      function processAdBlock()
      {
          global $db, $core, $wojosec;

          if (empty($_POST['title' . $core->dblang]))
              $core->msgs['title'] = MOD_AB_NAME_R;

          if (!$this->adblockid) {
              if (empty($_POST['date_start']) || strtotime($_POST['date_start']) < strtotime(date('Y-m-d', time())))
                  $core->msgs['date_start'] = MOD_AB_DATE_S_INVALID;
		  } else {
              if (empty($_POST['date_start']) || $this->checkDate($_POST['date_start']))
				$core->msgs['date_start'] = MOD_AB_DATE_S_INVALID;
		  }

          if ($_POST['date_end_no'] == 0 && (empty($_POST['date_end']) || strtotime($_POST['date_end']) < strtotime(date('Y-m-d', time()))))
              $core->msgs['date_end'] = MOD_AB_DATE_E_INVALID;

          if ($_POST['date_end_no'] == 0 && (strtotime($_POST['date_end']) <= strtotime($_POST['date_start'])))
              $core->msgs['date_end_2'] = MOD_AB_DATE_E_INVALID2;

          if (!preg_match('/^\d+$/', $_POST['max_views']) || $_POST['max_views'] < 0)
              $core->msgs['max_views'] = MOD_AB_MAX_VIEWS_INVALID;

          if (!preg_match('/^\d+$/', $_POST['max_clicks']) || $_POST['max_clicks'] < 0)
              $core->msgs['max_clicks'] = MOD_AB_MAX_CLICKS_INVALID;

          if (!is_numeric($_POST['min_ctr']) || $_POST['min_ctr'] < 0 || $_POST['min_ctr'] > 1)
              $core->msgs['max_clicks'] = MOD_AB_MIN_CTR_INVALID;

          //block assignment
          if (empty($_POST['block_assignment']))
              $core->msgs['block_assignment'] = MOD_AB_BLOCK_ASSIGNMENT_INVALID;

          if ($this->adblockid) {
              $currentData = $core->getRowById($this->mTable, $this->adblockid);
              $current_block_assignment = $currentData['block_assignment'];

              $sqlSelectPlugins = 'SELECT id FROM ' . $this->pTable . ' WHERE plugalias = \'' . 'adblock/' . sanitize($_POST['block_assignment']) . '\' AND plugalias <> \'' . $current_block_assignment . '\' LIMIT 1';
          } else {
              $sqlSelectPlugins = 'SELECT id FROM ' . $this->pTable . ' WHERE plugalias = \'' . 'adblock/' . sanitize($_POST['block_assignment']) . '\' LIMIT 1';
          }

          $existingPluginsRow = $db->first($sqlSelectPlugins);
          $existingPluginsId = $existingPluginsRow['id'];

          if ($existingPluginsId)
              $core->msgs['block_assignment'] = MOD_AB_BLOCK_ASSIGNMENT_EXISTS;

          //user level
          if (!isset($_POST['userlevel']) || (!is_array($_POST['userlevel']) && count($_POST['userlevel']) == 0))
              $core->msgs['userlevel'] = MOD_AB_ULEVEL_INVALID;

          //banner_type = image
          if ($_POST['banner_type'] == 0 && !empty($_FILES['banner_image']['name'])) {
              if (!preg_match("/(\.jpg|\.jpeg|\.png|\.gif)$/i", $_FILES['banner_image']['name']))
                  $core->msgs['banner_image'] = MOD_AB_BANNER_IMAGE_INVALID;

              if ($_FILES['banner_image']['size'] > 204800)
                  $core->msgs['banner_image'] = MOD_AB_BANNER_IMAGE_INVALID;

              $file_info = getimagesize($_FILES['banner_image']['tmp_name']);
              if (empty($file_info))
                  $core->msgs['banner_image'] = MOD_AB_BANNER_IMAGE_INVALID;
          }

          if (!$this->adblockid) {
              if ($_POST['banner_type'] == 0 && empty($_FILES['banner_image']['name']))
                  $core->msgs['banner_image'] = MOD_AB_BANNER_IMAGE_INVALID;
          }

          if ($_POST['banner_type'] == 0 && $_POST['banner_image_link'] == '')
              $core->msgs['banner_image_link'] = MOD_AB_BANNER_LINK_INVALID;
          if ($_POST['banner_type'] == 0 && $_POST['banner_image_alt'] == '')
              $core->msgs['banner_image_alt'] = MOD_AB_BANNER_ALT_INVALID;

          //banner_type = html
          if ($_POST['banner_type'] == 1 && $_POST['banner_html'] == '')
              $core->msgs['banner_html'] = MOD_AB_BANNER_HTML_INVALID;

          if (empty($core->msgs)) {
              $data = array(
                  'title' . $core->dblang => sanitize($_POST['title' . $core->dblang]),
                  'start_date' => date('Y-m-d', strtotime(($_POST['date_start']))),
                  'end_date' => date('Y-m-d', strtotime(($_POST['date_end']))),
                  'total_views_allowed' => sanitize($_POST['max_views']),
                  'total_clicks_allowed' => sanitize($_POST['max_clicks']),
                  'minimum_ctr' => sanitize($_POST['min_ctr']),
                  'block_assignment' => 'adblock/' . paranoia($_POST['block_assignment']),
                  'banner_html' => sanitize($_POST['banner_html']),
                  'banner_image_link' => sanitize($_POST['banner_image_link']),
                  'banner_image_alt' => sanitize($_POST['banner_image_alt']),
                  );

              if (!$this->adblockid) {
                  $data['created'] = "NOW()";
              }
              // Procces Image
              $file = getValue("banner_image", $this->mTable, "id = '" . $this->adblockid . "'");
              if ($_POST['banner_type'] == 0 && !empty($_FILES['banner_image']['name'])) {
                  $filedir = WOJOLITE . $this->imagepath;
                  $newName = "IMG_" . randName();
                  $ext = substr($_FILES['banner_image']['name'], strrpos($_FILES['banner_image']['name'], '.') + 1);
                  $fullname = $filedir . $newName . "." . strtolower($ext);

                  if ($file)
                      @unlink($filedir . $file);
                  $res = move_uploaded_file($_FILES['banner_image']['tmp_name'], $fullname);
                  $data['banner_image'] = $newName . "." . strtolower($ext);
              } else {
                  $data['banner_image'] = $file;
              }

              if ($_POST['date_end_no'] == 1)
                  $data['end_date'] = '0000-00-00';
              if ($_POST['banner_type'] == 0)
                  $data['banner_html'] = '';
              if ($_POST['banner_type'] == 1) {
                  $data['banner_image'] = $data['banner_image_link'] = $data['banner_image_alt'] = '';
              }

              $mode = ($this->adblockid) ? 'update' : 'insert';

              $current_block_assignment = '';
              //get current value of block_assignment column
              if ($mode == 'update') {
                  $currentData = $core->getRowById($this->mTable, $this->adblockid);
                  $current_block_assignment = $currentData['block_assignment'];
                  $current_block_assignment_clean = str_replace('adblock/', '', $current_block_assignment);
              }

              ($mode == 'update') ? $db->update($this->mTable, $data, "id='" . (int)$this->adblockid . "'") : $db->insert($this->mTable, $data);
              $message = ($mode == 'update') ? MOD_AB_PUPDATED : MOD_AB_PADDED;

              $this->adblockid = $this->adblockid ? $this->adblockid : $db->insertid();

              $block_assignment_clean = str_replace('adblock/', '', $data['block_assignment']);
              $plugin_file = WOJOLITE . $this->pluginspath . $block_assignment_clean . '/main.php';
              $plugin_file_main = WOJOLITE . $this->pluginspath . 'main.php';

              if ($mode == 'insert') {
                  mkdir(str_replace('/main.php', '', $plugin_file));
                  file_put_contents($plugin_file, str_replace('###ADBLOCKID###', $this->adblockid, file_get_contents($plugin_file_main)));

				  $pdata = array(
					  'title' . $core->dblang => $block_assignment_clean,
					  'plugalias' => $data['block_assignment'],
					  'hasconfig' => 0,
					  'created' => "NOW()",
					  'system' => 1,
					  'active' => 1
					  );

                  $db->insert($this->pTable, $pdata);
              } else
                  if ($current_block_assignment != $data['block_assignment']) {
                      $plugin_file_current = WOJOLITE . $this->pluginspath . $current_block_assignment_clean . '/main.php';
                      unlink($plugin_file_current);
                      rmdir(str_replace('/main.php', '', $plugin_file_current));

                      mkdir(str_replace('/main.php', '', $plugin_file));
                      file_put_contents($plugin_file, str_replace('###ADBLOCKID###', $this->adblockid, file_get_contents($plugin_file_main)));

					  $pdata = array(
						  'title' . $core->dblang => $block_assignment_clean,
						  'plugalias' => $data['block_assignment']
						  );
					  
                      $db->update($this->pTable, $pdata, "plugalias = '" . $data['block_assignment'] . "'");
                  }


              //handle adblock => memberlevel
              if ($this->adblockid)
                  $db->delete($this->amTable, "adblock_id = {$this->adblockid}");

              if (is_array($_POST['userlevel'])) {
                  $sqlInsert = 'INSERT INTO ' . $this->amTable . ' (adblock_id,memberlevel_id) VALUES ';
                  foreach ($_POST['userlevel'] as $ulevel)
                      $sqlInsert .= "({$this->adblockid},{$ulevel}),";

                  $sqlInsert = rtrim($sqlInsert, ',');
                  $db->query($sqlInsert);
              }

              ($this->adblockid) ? $wojosec->writeLog($message, "", "no", "module") . $core->msgOk($message) : $core->msgAlert(_SYSTEM_PROCCESS);


          } else
              print $core->msgStatus();
      }

      /**
       * AdBlock::checkDate($date)
       *
       * @return
       */
      function checkDate($date)
      {
          if (date('dd/mm/YYYY', strtotime($date)) == $date) {
              return true;
          } else {
              return false;
          }
      }

      /**
       * AdBlock::incrementViewsNumbers()
       *
       * @return
       */
      function incrementViewsNumber()
      {
          global $db, $core;

          if ($this->adblockid) {
              $data['total_views'] = "INC(1)";
              $db->update($this->mTable, $data, "id = '" . $this->adblockid . "'");
          }

      }

      /**
       * AdBlock::incrementClicksNumbers()
       *
       * @return
       */
      function incrementClicksNumber()
      {
          global $db, $core;

          if ($this->adblockid) {
              $data['total_clicks'] = "INC(1)";
              $db->update($this->mTable, $data, "id = '" . $this->adblockid . "'");
          }

      }

  }
?>