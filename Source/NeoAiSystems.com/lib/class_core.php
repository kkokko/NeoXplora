<?php
  /**
   * Core Class
   *
   * @version $Id: core_class.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
  
  class Core
  {
      
	  public $msgs = array();
	  public $showMsg;
	  private $sTable = "settings";
	  public $action = null;
	  public $maction = null;
	  public $paction = null;
	  public $do = null;
      public $year = null;
      public $month = null;
      public $day = null;
	  
      private $langdir;
      public $language;
	  public $lang_dir;
	  public $dblang;
	  public $langlist;
	  
	  
      /**
       * Core::__construct()
       * 
       * @return
       */
      public function __construct()
      {
          $this->getSettings();
		  $this->getLanguage();
		  $this->getAction();
		  $this->getModAction();
		  $this->getPlugAction();
		  $this->getDo();
		  
		  ($this->dtz) ? date_default_timezone_set($this->dtz) : date_default_timezone_set('GMT');
		  
          $this->year = (get('year')) ? get('year') : strftime('%Y');
          $this->month = (get('month')) ? get('month') : strftime('%m');
          $this->day = (get('day')) ? get('day') : strftime('%d');
          
          return mktime(0, 0, 0, $this->month, $this->day, $this->year);
      }
      
      /**
       * Core::getSettings()
       *
       * @return
       */
      private function getSettings()
      {
          global $db;
          $sql = "SELECT * FROM " . $this->sTable;
          $row = $db->first($sql);
          
          $this->site_name = cleanOut($row['site_name']);
		  $this->company = cleanOut($row['company']);
          $this->site_url = $row['site_url'];
		  $this->site_email = $row['site_email'];
		  $this->theme = $row['theme'];
		  $this->theme_var = $row['theme_var'];
		  $this->seo = $row['seo'];
		  $this->perpage = $row['perpage'];
		  $this->backup = $row['backup'];
		  $this->thumb_w = $row['thumb_w'];
		  $this->thumb_h = $row['thumb_h'];
		  $this->img_w = $row['img_w'];
		  $this->img_h = $row['img_h'];
		  $this->avatar_w = $row['avatar_w'];
		  $this->avatar_h = $row['avatar_h'];
		  $this->short_date = $row['short_date'];
		  $this->long_date = $row['long_date'];
		  $this->dtz = $row['dtz'];
		  $this->weekstart = $row['weekstart'];
		  $this->lang = $row['lang'];
		  $this->show_lang = $row['show_lang'];
		  $this->lang_dir = $row['langdir'];
		  $this->eucookie = $row['eucookie'];
		  $this->logo = $row['logo'];
		  $this->showlogin = $row['showlogin'];
		  $this->showsearch = $row['showsearch'];
		  $this->bgimg = $row['bgimg'];
		  $this->repbg = $row['repbg'];
		  $this->bgalign = $row['bgalign'];
		  $this->bgfixed = $row['bgfixed'];
		  $this->bgcolor = $row['bgcolor'];
		  $this->currency = $row['currency'];
		  $this->cur_symbol = $row['cur_symbol'];
		  $this->offline = $row['offline'];
		  $this->offline_msg = $row['offline_msg'];
		  $this->offline_data = $row['offline_data'];
		  $this->reg_verify = $row['reg_verify'];
		  $this->notify_admin = $row['notify_admin'];
		  $this->auto_verify = $row['auto_verify'];
		  $this->reg_allowed = $row['reg_allowed'];
		  $this->user_limit = $row['user_limit'];
		  $this->flood = $row['flood'];
		  $this->attempt = $row['attempt'];
		  $this->logging = $row['logging'];
		  $this->enablefb = $row['enablefb'];
		  $this->fbapi = $row['fbapi'];
		  $this->fbsecret = $row['fbsecret'];
		  $this->analytics = $row['analytics'];
          $this->metakeys = $row['metakeys'];
          $this->metadesc = $row['metadesc'];
		  $this->mailer = $row['mailer'];
		  $this->smtp_host = $row['smtp_host'];
		  $this->smtp_user = $row['smtp_user'];
		  $this->smtp_pass = $row['smtp_pass'];
		  $this->smtp_port = $row['smtp_port'];
		  $this->is_ssl = $row['is_ssl'];
		  $this->sendmail = $row['sendmail'];
		  
		  $this->version = $row['version'];

      }

      /**
       * Core::processConfig()
       * 
       * @return
       */
	  public function processConfig()
	  {
		  global $db, $wojosec;
		  
		  if (empty($_POST['site_name']))
			  $this->msgs['site_name'] = _CG_SITENAME_R;
		  
		  if (empty($_POST['site_url']))
			  $this->msgs['site_url'] = _CG_WEBURL_R;
		  
		  if (empty($_POST['site_email']))
			  $this->msgs['site_email'] = _CG_WEBEMAIL_R;
		  
		  if (empty($_POST['thumb_w']))
			  $this->msgs['thumb_w'] = _CG_THUMB_W_R;
		  
		  if (empty($_POST['thumb_h']))
			  $this->msgs['thumb_h'] = _CG_THUMB_H_R;

		  if (empty($_POST['img_w']))
			  $this->msgs['img_w'] = _CG_IMG_W_R;
		  
		  if (empty($_POST['img_h']))
			  $this->msgs['img_h'] = _CG_IMG_H_R;

		  if (empty($_POST['avatar_w']))
			  $this->msgs['avatar_w'] = _CG_AVATAR_W_R;
		  
		  if (empty($_POST['avatar_h']))
			  $this->msgs['avatar_h'] = _CG_AVATAR_H_R;
			  
		  if (empty($_POST['currency']))
			  $this->msgs['currency'] = _CG_CURRENCY_R;

		  if (empty($_POST['theme']))
			  $this->msgs['theme'] = _CG_THEME_R;
			  
          switch($_POST['mailer']) {
			  case "SMTP" :
				  if (empty($_POST['smtp_host']))
					  Filter::$msgs['smtp_host'] = _CG_SMTP_HOST_R;
				  if (empty($_POST['smtp_user']))
					  Filter::$msgs['smtp_user'] = _CG_SMTP_USER_R;
				  if (empty($_POST['smtp_pass']))
					  Filter::$msgs['smtp_pass'] = _CG_SMTP_PASS_R;
				  if (empty($_POST['smtp_port']))
					  Filter::$msgs['smtp_port'] = _CG_SMTP_PORT_R;
				  break;
			  
			  case "SMAIL" :
				  if (empty($_POST['sendmail']))
					  Filter::$msgs['sendmail'] = _CG_SMAILPATH_R;
			  break;
		  }
		  
		  
		  if (empty($this->msgs)) {
			  $data = array(
					  'site_name' => sanitize($_POST['site_name']), 
					  'company' => sanitize($_POST['company']),
					  'site_url' => sanitize($_POST['site_url']),
					  'site_email' => sanitize($_POST['site_email']),
					  'theme' => sanitize($_POST['theme']), 
					  'theme_var' => isset($_POST['theme_var']) ? sanitize($_POST['theme_var']) : "NULL",
					  'seo' => intval($_POST['seo']),
					  'perpage' => intval($_POST['perpage']),
					  'thumb_w' => intval($_POST['thumb_w']),
					  'thumb_h' => intval($_POST['thumb_h']),
					  'img_w' => intval($_POST['img_w']),
					  'img_h' => intval($_POST['img_h']),
					  'showlogin' => intval($_POST['showlogin']),
					  'showsearch' => intval($_POST['showsearch']),
					  'repbg' => intval($_POST['repbg']),
					  'bgalign' => sanitize($_POST['bgalign']),
					  'bgfixed' => intval($_POST['bgfixed']),
					  'bgcolor' => sanitize($_POST['bgcolor']),
					  'avatar_w' => intval($_POST['avatar_w']),
					  'avatar_h' => intval($_POST['avatar_h']),
					  'short_date' => sanitize($_POST['short_date']),
					  'long_date' => sanitize($_POST['long_date']),
					  'dtz' => trim($_POST['dtz']),
					  'weekstart' => intval($_POST['weekstart']),
					  'lang' => sanitize($_POST['lang']),
					  'show_lang' => intval($_POST['show_lang']),
					  'langdir' => getValue("langdir", "language","flag = '" . sanitize($_POST['lang']) . "'"),
					  'eucookie' => intval($_POST['eucookie']),
					  'currency' => sanitize($_POST['currency']),
					  'cur_symbol' => sanitize($_POST['cur_symbol']),
					  'offline' => intval($_POST['offline']),
					  'offline_msg' => sanitize($_POST['offline_msg']),
					  'offline_data' => (empty($_POST['offline_data'])) ?  "DEFAULT(offline_data)" : sanitize($_POST['offline_data']),
					  'reg_verify' => intval($_POST['reg_verify']),
					  'auto_verify' => intval($_POST['auto_verify']),
					  'reg_allowed' => intval($_POST['reg_allowed']),
					  'notify_admin' => intval($_POST['notify_admin']),
					  'user_limit' => intval($_POST['user_limit']),
					  'flood' => intval($_POST['flood']),
					  'logging' => intval($_POST['logging']),
					  'enablefb' => intval($_POST['enablefb']),
					  'fbapi' => sanitize($_POST['fbapi']),
					  'fbsecret' => sanitize($_POST['fbsecret']),
					  'attempt' => intval($_POST['attempt']),
					  'analytics' => trim($_POST['analytics']),
					  'metadesc' => trim($_POST['metadesc']),
					  'metakeys' => trim($_POST['metakeys']),
					  'mailer' => sanitize($_POST['mailer']),
					  'sendmail' => sanitize($_POST['sendmail']),
					  'smtp_host' => sanitize($_POST['smtp_host']),
					  'smtp_user' => sanitize($_POST['smtp_user']),
					  'smtp_pass' => sanitize($_POST['smtp_pass']),
					  'smtp_port' => intval($_POST['smtp_port']),
					  'is_ssl' => intval($_POST['is_ssl'])
			  );

              if (isset($_POST['dellogo']) and $_POST['dellogo'] == 1) {
				  $data['logo'] = "NULL";
			  } elseif (!empty($_FILES['logo']['name'])) {
				  if ($this->logo) {
					  @unlink(UPLOADS . $this->logo);
				  }
					  move_uploaded_file($_FILES['logo']['tmp_name'], UPLOADS.$_FILES['logo']['name']);

				  $data['logo'] = sanitize($_FILES['logo']['name']);
			  } else {
				$data['logo'] = $this->logo;
			  }

              if (isset($_POST['dellbgimg']) and $_POST['dellbgimg'] == 1) {
				  $data['bgimg'] = "NULL";
			  } elseif (!empty($_FILES['bgimg']['name'])) {
				  if ($this->bgimg) {
					  @unlink(WOJOLITE . "theme/". $this->theme . "/images/" . $this->bgimg);
				  }
					  move_uploaded_file($_FILES['bgimg']['tmp_name'], WOJOLITE . "theme/". $this->theme . "/images/" . $_FILES['bgimg']['name']);

				  $data['bgimg'] = sanitize($_FILES['bgimg']['name']);
			  } else {
				$data['bgimg'] = $this->bgimg;
			  }
			  
			  $db->update($this->sTable, $data);
			  ($db->affected()) ? $wojosec->writeLog(_CG_UPDATED, "", "no", "config") . $this->msgOk(_CG_UPDATED) : $this->msgAlert(_SYSTEM_PROCCESS);
		  } else
			  print $this->msgStatus();
	  }

	  /**
	   * Core:::renderThemeBg()
	   * 
	   * @return
	   */
	  public function renderThemeBg()
	  {
		  $css = '';
		  $repeat = ($this->repbg) ?  "repeat" : "no-repeat"; 
		  $attach = ($this->bgfixed) ?  "fixed" : "scroll";
		  
		  if($this->bgimg) {
			  $css .= 'background-image: url(' . SITEURL . '/theme/' . $this->theme . '/images/' . $this->bgimg . ');';
			  $css .= 'background-repeat:' . $repeat . ';';
			  $css .= 'background-position:' . $this->bgalign . ' top;';
			  $css .= 'background-attachment:' . $attach . ';';
		  }
		  if($this->bgcolor) {
			  $css .= 'background-color:' . $this->bgcolor . ';';
		  }
		  
		  echo ($this->repbg or $this->bgcolor) ? ' style="' . $css . '"' : '';
	  }
	  
 
	  /**
	   * Core:::getLanguage()
	   * 
	   * @return
	   */
	  private function getLanguage()
	  {
		  $this->langdir = WOJOLITE . "lang/";
		  
		  if (isset($_COOKIE['LANG_CMSPRO'])) {
			  $sel_lang = sanitize($_COOKIE['LANG_CMSPRO'], 2);
			  if ($this->validLang($sel_lang)) {
				  $this->language = $sel_lang;
				  $this->lang_dir = getValue("langdir", "language", "flag = '" . $sel_lang . "'");
				  $this->dblang = ($sel_lang == $this->lang) ? "_" . $this->lang : "_" . $this->language;
			  } else {
				  $this->language = $this->lang;
				  $this->lang_dir = getValue("langdir", "language", "flag = '" . $this->lang . "'");
				  $this->dblang = ($this->language == $this->lang) ? "_" . $this->lang : "_" . $this->language;
			  }
			  if (file_exists($this->langdir . $this->language . ".lang.php")) {
				  include($this->langdir . $this->language . ".lang.php");
			  } else {
				  include($this->langdir . $this->lang . ".lang.php");
			  }
		  } else {
			  $this->language = $this->lang;
			  $this->lang_dir = getValue("langdir", "language", "flag = '" . $this->lang. "'");
			  $this->dblang = "_" . $this->language;
			  include($this->langdir . $this->lang . ".lang.php");
		  }
	  }
				
	  /**
	   * Core:::langList()
	   * 
	   * @return
	   */
	  public function langList()
	  {
		  global $db;
		  
		  $sql = "SELECT * FROM language ORDER BY flag";
          $row = $db->fetch_all($sql);
          
		  return ($row) ? $this->langlist = $row : 0;
	  }
 
 	  /**
	   * Core:::validLang()
	   * 
       * @param mixed $var
       * @return
       */
	  public function validLang($var)
	  {
		  foreach ($this->langList() as $value) {
			  if(in_array($var, $value))
			     return true; 
		  }
	  }

 	  /**
	   * Core:::langIcon()
	   * 
	   * @return
	   */
	  public function langIcon()
	  {
		  return "<img src=\"".SITEURL."/lang/".$this->language.".png\" class=\"img-wrap tooltip\" alt=\"\" title=\"".strtoupper($this->language)."\" />"; 
	  }
	  
	  /**
	   * Core::deleteLanguage()
	   *
       * @param mixed $flag_id
       * @return
       */
	  public function deleteLanguage($flag_id)
	  {
		  global $db, $wojosec;
		  
		  if (!$this->validLang($flag_id))
			  $this->msgs['flag'] =  _LA_FLAG_ERR;
		  
		  if (empty($this->msgs)) {
 
              $db->query('LOCK TABLES email_templates WRITE');
			  $db->query("ALTER TABLE email_templates DROP COLUMN name_" . $flag_id);
			  $db->query("ALTER TABLE email_templates DROP COLUMN subject_" . $flag_id);
			  $db->query("ALTER TABLE email_templates DROP COLUMN help_" . $flag_id);
			  $db->query("ALTER TABLE email_templates DROP COLUMN body_" . $flag_id);
			  $db->query('UNLOCK TABLES');

              $db->query('LOCK TABLES memberships WRITE');
			  $db->query("ALTER TABLE memberships DROP COLUMN title_" . $flag_id);
			  $db->query("ALTER TABLE memberships DROP COLUMN description_" . $flag_id);
			  $db->query('UNLOCK TABLES');

              $db->query('LOCK TABLES menus WRITE');
			  $db->query("ALTER TABLE menus DROP COLUMN name_" . $flag_id);
			  $db->query('UNLOCK TABLES');
			  
              $db->query('LOCK TABLES modules WRITE');
			  $db->query("ALTER TABLE modules DROP COLUMN title_" . $flag_id);
			  $db->query("ALTER TABLE modules DROP COLUMN info_" . $flag_id);
			  $db->query("ALTER TABLE modules DROP COLUMN metakey_" . $flag_id);
			  $db->query("ALTER TABLE modules DROP COLUMN metadesc_" . $flag_id);
			  $db->query('UNLOCK TABLES');

              $db->query('LOCK TABLES plugins WRITE');
			  $db->query("ALTER TABLE plugins DROP COLUMN title_" . $flag_id);
			  $db->query("ALTER TABLE plugins DROP COLUMN body_" . $flag_id);
			  $db->query("ALTER TABLE plugins DROP COLUMN info_" . $flag_id);
			  $db->query('UNLOCK TABLES');

              $db->query('LOCK TABLES pages WRITE');
			  $db->query("ALTER TABLE pages DROP COLUMN title_" . $flag_id);
			  $db->query("ALTER TABLE pages DROP COLUMN keywords_" . $flag_id);
			  $db->query("ALTER TABLE pages DROP COLUMN description_" . $flag_id);
			  $db->query('UNLOCK TABLES');
			  
              $db->query('LOCK TABLES posts WRITE');
			  $db->query("ALTER TABLE posts DROP COLUMN title_" . $flag_id);
			  $db->query("ALTER TABLE posts DROP COLUMN body_" . $flag_id);
			  $db->query('UNLOCK TABLES');
			  
			  $getplugindata = $db->fetch_all("SELECT plugalias FROM plugins WHERE system = '1'");
			  if($getplugindata) {
				  foreach($getplugindata as $pdata) {
					  $plangdata = WOJOLITE . 'admin/plugins/'.$pdata['plugalias'].'/lang-delete.php';
					  if(is_file($plangdata)) {
						  include_once($plangdata);
					  }
				  }
				  unset($pdata);
			  }

			  $getmoduledata = $db->fetch_all("SELECT modalias FROM modules");
			  if($getmoduledata) {
				  foreach($getmoduledata as $mdata) {
					  $mlangdata = WOJOLITE . 'admin/modules/'.$mdata['modalias'].'/lang-delete.php';
					  if( is_file($mlangdata)) {
						  include_once($mlangdata);
					  }
				  }
				  unset($mdata);
			  }
			  
			  $db->delete("language", "flag='" . $flag_id . "'");
			  $wojosec->writeLog(_LA_LANG_DELOK, "", "no", "content") . $this->msgOk(_LA_LANG_DELOK);
		  } else
			  print $this->msgStatus();
	  }

	  /**
	   * Core::addLanguage()
	   *
       * @return
       */
	  public function addLanguage()
	  {
		  global $db, $wojosec;

		  if (empty($_POST['name']))
			  $this->msgs['name'] = _LA_TTITLE_R;

		  if (empty($_POST['flag']))
			  $this->msgs['flag'] = _LA_COUNTRY_ABB_R;
			  			  
		  if ($this->validLang($_POST['flag']))
			  $this->msgs['flag'] =  _LA_COUNTRY_ABB_ERR;
		  
		  if (empty($this->msgs)) {
              $flag_id = sanitize($_POST['flag'],2);
              $db->query('LOCK TABLES email_templates WRITE');
			  $db->query("ALTER TABLE email_templates ADD COLUMN name_$flag_id VARCHAR(200) NOT NULL AFTER name_en");
			  $db->query("ALTER TABLE email_templates ADD COLUMN subject_$flag_id VARCHAR(255) NOT NULL AFTER subject_en");
			  $db->query("ALTER TABLE email_templates ADD COLUMN help_$flag_id TEXT AFTER help_en");
			  $db->query("ALTER TABLE email_templates ADD COLUMN body_$flag_id TEXT AFTER body_en");
			  $db->query('UNLOCK TABLES');
			  
			  if($email_templates = $db->fetch_all("SELECT * FROM email_templates")) {
				  foreach ($email_templates as $row) {
					  $data = array(
					  'name_' . $flag_id => $row['name_en'],
					  'subject_' . $flag_id => $row['subject_en'],
					  'help_' . $flag_id => $row['help_en'],
					  'body_' . $flag_id => $row['body_en']
					  );
					  
					  $db->update("email_templates", $data, "id = '".$row['id']."'");
				  }
				  unset($data, $row);
			  }

              $db->query('LOCK TABLES memberships WRITE');
			  $db->query("ALTER TABLE memberships ADD COLUMN title_$flag_id VARCHAR(255) NOT NULL AFTER title_en");
			  $db->query("ALTER TABLE memberships ADD COLUMN description_$flag_id TEXT AFTER description_en");
			  $db->query('UNLOCK TABLES');

			  if($memberships = $db->fetch_all("SELECT * FROM memberships")) {
				  foreach ($memberships as $row) {
					  $data = array(
					  'title_' . $flag_id => $row['title_en'],
					  'description_' . $flag_id => $row['description_en']
					  );
					  
					  $db->update("memberships", $data, "id = '".$row['id']."'");
				  }
				  unset($data, $row);
			  }
			  
              $db->query('LOCK TABLES menus WRITE');
			  $db->query("ALTER TABLE menus ADD COLUMN name_$flag_id VARCHAR(100) NOT NULL AFTER name_en");
			  $db->query('UNLOCK TABLES');

			  if($menus = $db->fetch_all("SELECT * FROM menus")) {
				  foreach ($menus as $row) {
					  $data['name_' . $flag_id] = $row['name_en'];
					  $db->update("menus", $data, "id = '".$row['id']."'");
				  }
				  unset($data, $row);
			  }
			  
              $db->query('LOCK TABLES modules WRITE');
			  $db->query("ALTER TABLE modules ADD COLUMN title_$flag_id VARCHAR(120) NOT NULL AFTER title_en");
			  $db->query("ALTER TABLE modules ADD COLUMN info_$flag_id TEXT AFTER info_en");
			  $db->query("ALTER TABLE modules ADD COLUMN metakey_$flag_id VARCHAR(200) NOT NULL AFTER metakey_en");
			  $db->query("ALTER TABLE modules ADD COLUMN metadesc_$flag_id TEXT AFTER metadesc_en");
			  $db->query('UNLOCK TABLES');

			  if($modules = $db->fetch_all("SELECT * FROM modules")) {
				  foreach ($modules as $row) {
					  $data = array(
					  'title_' . $flag_id => $row['title_en'],
					  'info_' . $flag_id => $row['info_en'],
					  'metakey_' . $flag_id => $row['metakey_en'],
					  'metadesc_' . $flag_id => $row['metadesc_en']
					  );
					  
					  $db->update("modules", $data, "id = '".$row['id']."'");
				  }
				  unset($data, $row);
			  }
			  			  			  
              $db->query('LOCK TABLES plugins WRITE');
			  $db->query("ALTER TABLE plugins ADD COLUMN title_$flag_id VARCHAR(120) NOT NULL AFTER title_en");
			  $db->query("ALTER TABLE plugins ADD COLUMN body_$flag_id TEXT AFTER body_en");
			  $db->query("ALTER TABLE plugins ADD COLUMN info_$flag_id TEXT AFTER info_en");
			  $db->query('UNLOCK TABLES');

			  if($plugins = $db->fetch_all("SELECT * FROM plugins")) {
				  foreach ($plugins as $row) {
					  $data = array(
					  'title_' . $flag_id => $row['title_en'],
					  'body_' . $flag_id => $row['body_en'],
					  'info_' . $flag_id => $row['info_en']
					  );
					  
					  $db->update("plugins", $data, "id = '".$row['id']."'");
				  }
				  unset($data, $row);
			  }
			  			  
              $db->query('LOCK TABLES pages WRITE');
			  $db->query("ALTER TABLE pages ADD COLUMN title_$flag_id VARCHAR(200) NOT NULL AFTER title_en");
			  $db->query("ALTER TABLE pages ADD COLUMN keywords_$flag_id TEXT AFTER keywords_en");
			  $db->query("ALTER TABLE pages ADD COLUMN description_$flag_id TEXT AFTER description_en");
			  $db->query('UNLOCK TABLES');

			  if($pages = $db->fetch_all("SELECT * FROM pages")) {
				  foreach ($pages as $row) {
					  $data = array(
					  'title_' . $flag_id => $row['title_en'],
					  'keywords_' . $flag_id => $row['keywords_en'],
					  'description_' . $flag_id => $row['description_en']
					  );
					  
					  $db->update("pages", $data, "id = '".$row['id']."'");
				  }
				  unset($data, $row);
			  }
			  			  
              $db->query('LOCK TABLES posts WRITE');
			  $db->query("ALTER TABLE posts ADD COLUMN title_$flag_id VARCHAR(150) NOT NULL AFTER title_en");
			  $db->query("ALTER TABLE posts ADD COLUMN body_$flag_id TEXT AFTER body_en");
			  $db->query('UNLOCK TABLES');

			  if($posts = $db->fetch_all("SELECT * FROM posts")) {
				  foreach ($posts as $row) {
					  $data = array(
					  'title_' . $flag_id => $row['title_en'],
					  'body_' . $flag_id => $row['body_en']
					  );
					  
					  $db->update("posts", $data, "id = '".$row['id']."'");
				  }
				  unset($data, $row);
			  }

			  $getplugindata = $db->fetch_all("SELECT plugalias FROM plugins WHERE system = '1'");
			  if($getplugindata) {
				  foreach($getplugindata as $pdata) {
					  $plangdata = WOJOLITE . 'admin/plugins/'.$pdata['plugalias'].'/lang-add.php';
					  if(is_file($plangdata)) {
						  include_once($plangdata);
					  }
				  }
				  unset($pdata);
			  }

			  $getmoduledata = $db->fetch_all("SELECT modalias FROM modules");
			  if($getmoduledata) {
				  foreach($getmoduledata as $mdata) {
					  $mlangdata = WOJOLITE . 'admin/modules/'.$mdata['modalias'].'/lang-add.php';
					  if(is_file($mlangdata)) {
						  include_once($mlangdata);
					  }
				  }
				  unset($mdata);
			  }
			  			  
			  $ldata = array(
				  'name' => sanitize($_POST['name']), 
				  'flag' => sanitize($_POST['flag']),
				  'langdir' => sanitize($_POST['langdir']),
				  'author' => sanitize($_POST['author'])
			  );
			  $db->insert("language", $ldata);
			  
			  $wojosec->writeLog(_LA_LANG_ADDOK, "", "no", "content") . $this->msgOk(_LA_LANG_ADDOK);

		  } else
			  print $this->msgStatus();
	  }

	  /**
	   * Core::updateLanguage()
	   * 
	   * @return
	   */
	  public function updateLanguage()
	  {
		  global $db, $content, $wojosec;
		  
		  if (empty($_POST['name']))
			  $this->msgs['name'] = _LA_TTITLE_R;
		  
		  if (empty($this->msgs)) {
			  $data = array(
				  'name' => sanitize($_POST['name']),
				  'langdir' => sanitize($_POST['langdir']),  
				  'author' => sanitize($_POST['author'])
			  );
			  
			  $ldata['langdir'] = sanitize($_POST['langdir']);
			  
			  $res = $db->update("language", $data, "id='" . (int)$content->id . "'");
			  $db->update($this->sTable, $ldata);
			  
			  ($res) ? $wojosec->writeLog(_LA_UPDATED, "", "no", "content") . $this->msgOk(_LA_UPDATED) : $this->msgAlert(_SYSTEM_PROCCESS);
		  } else
			  print $this->msgStatus();
	  }

      /**
       * Core::getThemeOptions()
       * 
       * @return
       */ 
	  public function getThemeOptions($themename)
	  {
		  $options = glob("" . WOJOLITE . "/theme/" . $themename . "/skins/*.css");
	
		  $html = '';
		  if (!$options) {
			  print _CG_THEME_VAR_N;
		  } else {
			  $html .= '<select name="theme_var" class="custombox2" style="width:250px">';
			  $html .= "<option value=\"\">" . _CG_THEME_VAR_S . "</option>\n";
			  foreach ($options as $val) {
				  $newval = basename(stripExt($val));
				  if ($newval == $this->theme_var) {
					  $html .= "<option selected=\"selected\" value=\"" . $newval . "\">" . $newval . "</option>\n";
				  } else
					  $html .= "<option value=\"" . $newval . "\">" . $newval . "</option>\n";
			  }
			  $html .= '</select>';
			  unset($val);
			  return $html;
		  }
	  }
	    	  	   	  	  
      /**
       * Core::getShortDate()
       * 
       * @return
       */ 
      public function getShortDate()
	  {
		  $arr = array(
				 '%m-%d-%Y' => '12-21-2009 (MM-DD-YYYY)',
				 '%e-%m-%Y' => '21-12-2009 (D-MM-YYYY)',
				 '%m-%e-%y' => '12-21-09 (MM-D-YY)',
				 '%e-%m-%y' => '21-12-09 (D-MM-YY)',
				 '%b %d %Y' => 'Dec 21 2009'
		  );
		  
		  $shortdate = '';
		  foreach ($arr as $key => $val) {
              if ($key == $this->short_date) {
                  $shortdate .= "<option selected=\"selected\" value=\"" . $key . "\">" . $val . "</option>\n";
              } else
                  $shortdate .= "<option value=\"" . $key . "\">" . $val . "</option>\n";
          }
          unset($val);
          return $shortdate;
      }
	  
      /**
       * Core::getLongDate()
       * 
       * @return
       */ 	  
      public function getLongDate()
	  {
		  $arr = array(
				'%B %d, %Y' => 'December 21, 2009',
				'%d %B %Y %H:%M' => '21 December 2009 19:00',
				'%B %d, %Y %I:%M %p' => 'December 21, 2009 4:00 am',
				'%A %d %B, %Y' => 'Monday 21 December, 2009',
				'%A %d %B, %Y %H:%M' => 'Monday 21 December 2009 07:00',
				'%a %d, %B' => 'Mon. 12, December'
		  );
		  
		  $longdate = '';
		  foreach ($arr as $key => $val) {
              if ($key == $this->long_date) {
                  $longdate .= "<option selected=\"selected\" value=\"" . $key . "\">" . $val . "</option>\n";
              } else
                  $longdate .= "<option value=\"" . $key . "\">" . $val . "</option>\n";
          }
          unset($val);
          return $longdate;
      }

      /**
       * Core::monthList()
       * 
       * @return
       */ 	  
      public function monthList()
	  {
		  $selected = is_null(get('month')) ? strftime('%m') : get('month');
		  
		  $arr = array(
				'01' => _JAN,
				'02' => _FEB,
				'03' => _MAR,
				'04' => _APR,
				'05' => _MAY,
				'06' => _JUN,
				'07' => _JUL,
				'08' => _AUG,
				'09' => _SEP,
				'10' => _OCT,
				'11' => _NOV,
				'12' => _DEC
		  );
		  
		  $monthlist = '';
		  foreach ($arr as $key => $val) {
			  $monthlist .= "<option value=\"$key\"";
			  $monthlist .= ($key == $selected) ? ' selected="selected"' : '';
			  $monthlist .= ">$val</option>\n";
          }
          unset($val);
          return $monthlist;
      }

      /**
       * Core::weekList()
       * 
       * @return
       */ 	  
      public function weekList()
	  {
		  $arr = array(
		        '1' => _SUNDAY,
				'2' => _MONDAY,
				'3' => _TUESDAY,
				'4' => _WEDNESDAY,
				'5' => _THURSDAY,
				'6' => _FRIDAY,
				'7' => _SATURDAY
		  );
		  
		  $weeklist = '';
		  foreach ($arr as $key => $val) {
              if ($key == $this->weekstart) {
                  $weeklist .= "<option selected=\"selected\" value=\"" . $key . "\">" . $val . "</option>\n";
              } else
                  $weeklist .= "<option value=\"" . $key . "\">" . $val . "</option>\n";
          }
          unset($val);
          return $weeklist;
      }
	  
      /**
       * Core::yearList()
	   *
       * @param mixed $start_year
       * @param mixed $end_year
       * @return
       */
	  function yearList($start_year, $end_year)
	  {
		  $selected = is_null(get('year')) ? date('Y') : get('year');
		  $r = range($start_year, $end_year);
		  
		  $select = '';
		  foreach ($r as $year) {
			  $select .= "<option value=\"$year\"";
			  $select .= ($year == $selected) ? ' selected="selected"' : '';
			  $select .= ">$year</option>\n";
		  }
		  return $select;
	  }
	  	  
      /**
       * Core::monthlyStats()
       * 
       * @return
       */ 	  
      public function monthlyStats()
	  {
          global $db;
          $sql = "SELECT id, SUM(pageviews) as views, SUM(uniquevisitors) as visits," 
		  . "\n (SELECT COUNT(id) FROM stats WHERE MONTH(day) = " . $this->month . " AND  YEAR(day) = " . $this->year . "  ) as total"
		  . "\n FROM stats" 
		  . "\n WHERE day > '" . $this->year . "-" . $this->month . "-01'" 
		  . "\n AND day < '" . $this->year . "-" . $this->month . "-31 23:59:59' GROUP BY MONTH(day)";
          
          $row = $db->first($sql);
          
		  return ($row['total'] > 0) ? $row : false;
      }
	  
      /**
       * Core::getStats()
       * 
       * @return
       */ 	  
      public function getStats()
	  {
          global $db;
          $sql = "SELECT *, SUM(pageviews) as views, SUM(uniquevisitors) as visits FROM stats" 
		  . "\n WHERE YEAR(day) = '" . $this->year . "'"
		  . "\n AND MONTH(day) = '" . $this->month . "' GROUP BY DATE(day)"; 
          
          $row = $db->fetch_all($sql);
          
          return ($row) ? $row : 0;
      }

      /**
       * Core::countEvents()
       * 
       * @return
       */
      public function countEvents()
      {
		  global $db, $user;
		  
		  $sql = "SELECT COUNT(id) as total"
		  . "\n FROM mod_events"
		  . "\n WHERE YEAR(date_start) = " . date('Y')
		  . "\n AND MONTH(date_start) = " . date('m')
		  . "\n AND DAY(date_start) = " . date('d')
		  . "\n AND user_id = {$user->uid}";
		  $row = $db->first($sql);
		  
		  return ($row) ? $row['total'] : 0;

      }
	    
      /**
       * Core::getVisitors()
       * 
       * @return
       */
	  function getVisitors()
	  {
		  global $db;
		  if (@getenv("HTTP_CLIENT_IP")) {
			  $vInfo['ip'] = getenv("HTTP_CLIENT_IP");
		  } elseif (@getenv("HTTP_X_FORWARDED_FOR")) {
			  $vInfo['ip'] = getenv('HTTP_X_FORWARDED_FOR');
		  } elseif (@getenv('REMOTE_ADDR')) {
			  $vInfo['ip'] = getenv('REMOTE_ADDR');
		  } elseif (isset($_SERVER['REMOTE_ADDR'])) {
			  $vInfo['ip'] = $_SERVER['REMOTE_ADDR'];
		  } else {
			  $vInfo['ip'] = "Unknown";
		  }
		  
		  if (!preg_match("/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/i", $vInfo['ip']) && $vInfo['ip'] != "Unknown") {
			  $pos = strpos($vInfo['ip'], ",");
			  $vInfo['ip'] = substr($vInfo['ip'], 0, $pos);
			  if ($vInfo['ip'] == "")
				  $vInfo['ip'] = "Unknown";
		  }
		  
		  $vInfo['ip'] = str_replace("[^0-9\.]", "", $vInfo['ip']);
		  setcookie("hitcookie", time(), time() + 3600);
		  $vCookie['is_cookie'] = (isset($_COOKIE['hitcookie'])) ? 1 : 0;
		  $date = date('Y-m-d');
		  
		  $sql = "SELECT * FROM stats WHERE day='" . $date . "'";
		  $row = $db->first($sql);
		  if ($row) {
			  $hid = intval($row['id']);
			  $pageviews = $row['pageviews'];
			  $unique = $row['uniquevisitors'];
			  
			  $stats['pageviews'] = "INC(1)";
			  
			  $db->update("stats", $stats, "id='" . $hid . "'");
			  
			  if (!isset($_COOKIE['unique']) && $vCookie['is_cookie']) {
				  setcookie("unique", time(), time() + 3600);
				  
				  $stats['uniquevisitors'] = "INC(1)";
				  
				  $db->update("stats", $stats, "id='" . $hid . "'");
			  }
		  } else {
			  $istats['id'] = "null";
			  $istats['day'] = $date;
			  $istats['pageviews'] = 1;
			  $istats['uniquevisitors'] = 1;
			  $db->insert("stats", $istats);
		  }
	  }

      /**
       * Core::getTimezones()
       * 
       * @return
       */
	  public function getTimezones()
	  {
		  $data = '';
		  $tzone = DateTimeZone::listIdentifiers();
		  $data .='<select name="dtz" style="width:200px" class="custombox">';
		  foreach ($tzone as $zone) {
			  $selected = ($zone == $this->dtz) ? ' selected="selected"' : '';
			  $data .= '<option value="' . $zone . '"' . $selected . '>' . $zone . '</option>';
		  }
		  $data .='</select>';
		  return $data;
	  }

	  /**
	   * Core::formatMoney()
	   * 
	   * @param mixed $amount
	   * @return
	   */
	  public function formatMoney($amount)
	  {
		  return ($amount == 0) ? _FREE : $this->cur_symbol . number_format($amount, 2, '.', ',') . ' ' .$this->currency;
	  }

	  /**
	   * Core::in_url()
	   * 
	   * @param mixed $data
	   * @return
	   */
	  public function in_url($data)
	  {
          
		  return str_replace("../uploads/","uploads/",$data);  
	  }

	  /**
	   * Core::out_url()
	   * 
	   * @param mixed $data
	   * @return
	   */
	  public function out_url($data)
	  {
		  return  str_replace("uploads/","../uploads/",$data);  
	  }
	  				  
      /**
       * getRowById()
       * 
       * @param mixed $table
       * @param mixed $id
       * @param bool $and
       * @param bool $is_admin
       * @return
       */
      public function getRowById($table, $id, $and = false, $is_admin = true)
      {
          global $db;
		  $id = sanitize($id, 8, true);
		  if ($and) {
			  $sql = "SELECT * FROM " . (string)$table . " WHERE id = '" . $db->escape((int)$id) . "' AND " . $db->escape($and) . "";
		  } else
			  $sql = "SELECT * FROM " . (string)$table . " WHERE id = '" . $db->escape((int)$id) . "'";
		  
          $row = $db->first($sql);
          
		  if ($row) {
			  return $row;
		  } else {
			  if ($is_admin)
				  $this->error("You have selected an Invalid Id - #".$id, "Core::getRowById()");
		  }
	  }
  	  	  
      /**
       * Core::setActiveInactive()
       * 
       * @param mixed $table
       * @param mixed $redirect
       * @return
       */
      public function setActiveInactive($table, $redirect)
      {
          global $db;
          
          if (isset($_GET['publish'])) {
              $id = intval($_GET['id']);
              
              $data['active'] = intval($_GET['publish']);
              
              $db->update($table, $data, "id='" . $id . "'");
              if ($db->affected() == 1)
                  redirect_to($redirect);
          }
      }

      /**
       * Core::msgAlert()
       * 
	   * @param mixed $msg
	   * @param bool $fader
	   * @param bool $altholder
       * @return
       */	  
	  public function msgAlert($msg, $fader = true, $altholder = false)
	  {
		$this->showMsg = "<div class=\"msgAlert\">" . $msg . "</div>";
		if ($fader == true)
		  $this->showMsg .= "<script type=\"text/javascript\"> 
		  // <![CDATA[
			setTimeout(function() {       
			  $(\".msgAlert\").customFadeOut(\"slow\",    
			  function() {       
				$(\".msgAlert\").remove();  
			  });
			},
			4000);
		  // ]]>
		  </script>";	
		  
		  print ($altholder) ? '<div id="alt-msgholder">'.$this->showMsg.'</div>' : $this->showMsg;
	  }

      /**
       * Core::msgOk()
       * 
	   * @param mixed $msg
	   * @param bool $fader
	   * @param bool $altholder
       * @return
       */	  
	  public function msgOk($msg, $fader = true, $altholder = false)
	  {
		$this->showMsg = "<div class=\"msgOk\">" . $msg . "</div>";
		if ($fader == true)
		  $this->showMsg .= "<script type=\"text/javascript\"> 
		  // <![CDATA[
			setTimeout(function() {       
			  $(\".msgOk\").customFadeOut(\"slow\",    
			  function() {       
				$(\".msgOk\").remove();  
			  });
			},
			4000);
		  // ]]>
		  </script>";	
		  
		  print ($altholder) ? '<div id="alt-msgholder">'.$this->showMsg.'</div>' : $this->showMsg;
	  }

      /**
       * Core::msgError()
       * 
	   * @param mixed $msg
	   * @param bool $fader
	   * @param bool $altholder
       * @return
       */	  
	  public function msgError($msg, $fader = true, $altholder = false)
	  {
		$this->showMsg = "<div class=\"msgError\">" . $msg . "</div>";
		if ($fader == true)
		  $this->showMsg .= "<script type=\"text/javascript\"> 
		  // <![CDATA[
			setTimeout(function() {       
			  $(\".msgError\").customFadeOut(\"slow\",    
			  function() {       
				$(\".msgError\").remove();  
			  });
			},
			4000);
		  // ]]>
		  </script>";	
	  
		  print ($altholder) ? '<div id="alt-msgholder">'.$this->showMsg.'</div>' : $this->showMsg;
	  } 	


	  /**
	   * msgInfo()
	   * 
	   * @param mixed $msg
	   * @param bool $fader
	   * @param bool $altholder
	   * @return
	   */
	  public function msgInfo($msg, $fader = true, $altholder = false)
	  {
		$this->showMsg = "<div class=\"msgInfo\">" . $msg . "</div>";
		if ($fader == true)
		  $this->showMsg .= "<script type=\"text/javascript\"> 
		  // <![CDATA[
			setTimeout(function() {       
			  $(\".msgInfo\").customFadeOut(\"slow\",    
			  function() {       
				$(\".msgInfo\").remove();  
			  });
			},
			4000);
		  // ]]>
		  </script>";
	  
		  print ($altholder) ? '<div id="alt-msgholder">'.$this->showMsg.'</div>' : $this->showMsg;
	  }
	    
      /**
       * Core::msgStatus()
       * 
       * @return
       */
	  public function msgStatus()
	  {
		  $this->showMsg = "<div class=\"msgError\">" . _SYSTEM_ERR . "<ul class=\"error\">";
		  foreach ($this->msgs as $msg) {
			  $this->showMsg .= "<li>" . $msg . "</li>\n";
		  }
		  $this->showMsg .= "</ul></div>";
		  
		  return $this->showMsg;
	  }	  

	  /**
	   * doForm()
	   * 
	   * @param mixed $data
	   * @param string $url
	   * @param integer $reset
	   * @param integer $clear
	   * @param string $form_id
	   * @param string $msgholder
	   * @return
	   */  
	  public function doForm($data, $url = "controller.php", $reset = 0, $clear = 0, $form_id = "admin_form", $msgholder = "msgholder")
	  {
		  $display ='
		  <script type="text/javascript">
		  // <![CDATA[
			  $(document).ready(function () {
				  var options = {
					  target: "#' . $msgholder . '",
					  beforeSubmit:  showLoader,
					  success: showResponse,
					  url: "' . $url . '",
					  resetForm : ' . $reset . ',
					  clearForm : ' . $clear . ',
					  data: {
						  ' .$data . ': 1
					  }
				  };
				  $("#' . $form_id . '").ajaxForm(options);
			  });
			  
			  function showLoader() {
				  $("#loader").fadeIn(200);
			  }
		  
			  function hideLoader() {
				  $("#loader").fadeOut(200);
			  };	
			  		  
			  function showResponse(msg) {
				  hideLoader();
				  $(this).html(msg);
				  $("html, body").animate({
					  scrollTop: 0
				  }, 600);
			  }
			  ';
          $display .='
		  // ]]>
		  </script>';
		  
		  print $display;
	  }

      /**
       * Core::doDelete()
       * 
       * @param mixed $title
       * @param mixed $varpost
       * @param string $attr
       * @param string $id
       * @param string $url
       * @return
       */
      public static function doDelete($title, $varpost, $url = "ajax.php", $attr = 'item_', $id = 'a.delete')
      {
          $display = "
		  <script type=\"text/javascript\"> 
		  // <![CDATA[
		  $(document).ready(function () {
			  $('.container').on('click', '" . $id . "', function () {
				  var id = $(this).attr('id').replace('" . $attr . "', '')
				  var parent = $(this).parent().parent();
				  var title = $(this).attr('data-title');
				  var text = '<div><span class=\"ui-icon ui-icon-alert\" style=\"float:left; margin:0 7px 20px 0;\"></span>" . _DEL_CONFIRM . "</div>';
				  $.confirm({
					  title: '" . $title . "',
					  message: text,
					  buttons: {
						  '" . _DELETE . "': {
							  'class': 'yes',
							  'action': function () {
								  $.ajax({
									  type: 'post',
									  url: '" . $url . "',
									  data: '" . $varpost . "=' + id + '&title=' + encodeURIComponent(title),
									  beforeSend: function () {
										  parent.animate({
											  'backgroundColor': '#FFBFBF'
										  }, 400);
									  },
									  success: function (msg) {
										  parent.fadeOut(400, function () {
											  parent.remove();
										  });
										  $('html, body').animate({
											  scrollTop: 0
										  }, 600);
										  $(\"#msgholder\").html(msg);
									  }
								  });
							  }
						  },
						  '" . _CANCEL . "': {
							  'class': 'no',
							  'action': function () {}
						  }
					  }
				  });
			  });
		  });
		  // ]]>
		  </script>";

          print $display;
      }
	  
      /**
       * Core::checkTable()
       * 
	   * @param mixed $tablename
       * @return
       */
	  function checkTable($tablename)
	  {
		  global $db;
		  return $db->numrows($db->query("SHOW TABLES LIKE '" . $tablename . "'")) ? true : false;
	  }
  
      /**
       * Core::ooops()
       * 
       * @return
       */
      public static function ooops()
      {
          $the_error = "<div class=\"msgError\" style=\"color:#444;width:400px;margin-left:auto;margin-right:auto;border:1px solid #C3C3C3;font-family:Arial, Helvetica, sans-serif;font-size:13px;padding:10px;background:#f2f2f2;border-radius:5px;text-shadow:1px 1px 0 #fff\">";
          $the_error .= "<h4 style=\"font-size:18px;margin:0;padding:0\">Oops!!!</h4>";
          $the_error .= "<p>Something went wrong. Looks like the page you're looking for was moved or never existed. Make sure you typed the correct URL or followed a valid link.</p>";
          $the_error .= "<p>&lsaquo; <a href=\"javascript:history.go(-1)\" style=\"color:#0084FF;\"><strong>Go Back to previous page</strong></a></p>";
          $the_error .= '</div>';
          print $the_error;
          die();
      }
	  
      /**
       * Core::error()
       * 
	   * @param mixed $msg
	   * @param mixed $source
       * @return
       */
      public function error($msg, $source)
      {

          $the_error = "<div class=\"msgError\">";
          $the_error .= "<span>System ERROR!</span><br />";
          $the_error .= "DB Error: ".$msg." <br /> More Information: <br />";
          $the_error .= "<ul>";
          $the_error .= "<li> Date : " . date("F j, Y, g:i a") . "</li>";
		  $the_error .= "<li> Function: " . $source . "</li>";
          $the_error .= "<li> Script: " . $_SERVER['REQUEST_URI'] . "</li>";
		  $the_error .= "<li>&lsaquo; <a href=\"javascript:history.go(-1)\"><strong>Go Back to previous page</strong></a></li>";
          $the_error .= '</ul>';
          $the_error .= '</div>';
          print $the_error;
          die();
      }
  
      /**
       * Core::getAction()
       * 
       * @return
       */
	  private function getAction()
	  {
		  if (isset($_GET['action'])) {
			  $action = ((string)$_GET['action']) ? (string)$_GET['action'] : false;
			  $action = sanitize($action);
			  
			  if ($action == false) {
				  $this->error("You have selected an Invalid Action Method","Core::getAction()");
			  } else
				  return $this->action = $action;
		  }
	  }

      /**
       * Core::getModAction()
       * 
       * @return
       */
	  private function getModAction()
	  {
		  if (isset($_GET['mod_action'])) {
			  $maction = ((string)$_GET['mod_action']) ? (string)$_GET['mod_action'] : false;
			  $maction = sanitize($maction);
			  
			  if ($maction == false) {
				  $this->error("You have selected an Invalid Mod Action Method","Core::mod_action()");
			  } else
				  return $this->maction = $maction;
		  }
	  }

      /**
       * Core::getPlugAction()
       * 
       * @return
       */
	  private function getPlugAction()
	  {
		  if (isset($_GET['plug_action'])) {
			  $paction = ((string)$_GET['plug_action']) ? (string)$_GET['plug_action'] : false;
			  $paction = sanitize($paction);
			  
			  if ($paction == false) {
				  $this->error("You have selected an Invalid Mod Action Method","Core::plug_action()");
			  } else
				  return $this->paction = $paction;
		  }
	  }
	  	  	  
      /**
       * Core::getDo()
       * 
       * @return
       */
	  private function getDo()
	  {
		  if (isset($_GET['do'])) {
			  $do = ((string)$_GET['do']) ? (string)$_GET['do'] : false;
			  $do = sanitize($do);
			  
			  if ($do == false) {
				  $this->error("You have selected an Invalid Do Method","Core::getDo()");
			  } else
				  return $this->do = $do;
		  }
	  }
  }
?>