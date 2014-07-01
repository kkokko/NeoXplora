<?php
  /**
   * Content Class
   *
   * @version $Id: class_content.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
  
  class Content
  {
      
	  public $pageid = null;
	  public $modpageid = null;
	  public $modalias = null;
	  public $moduledata = array();
	  public $id = null;
	  public $slug = null;
	  public $homeslug = null;
	  public $homeid = null;
	  public $postid = null;
	  public $is_homemod = null;
	  private $menutree = array();
	  private $menulist = array();
	  

      /**
       * Content::__construct()
       * 
       * @param bool $menutre
       * @return
       */
      public function __construct($menutre = true)
      {
          $this->getPageId();
		  $this->getId();
		  $this->getPageSlug();
		  $this->getHomePageSlug();
		  $this->getModAlias();
		  $this->getPostId();
		  ($menutre) ? $this->menutree = $this->getMenuTree() : $this->menutree = null;
		  $this->getPageSettings();
		  ($this->modalias) ? $this->moduledata = $this->getModuleMetaData() : null;
      }

	  /**
	   * Content::getPageSlug()
	   * 
	   * @return
	   */
	  private function getPageSlug()
	  {
	  	  global $db;
		  
		  if (isset($_GET['pagename'])) {
			  $this->slug = sanitize($_GET['pagename'],50);
			  return $db->escape($this->slug);
		  }
	  }

	  /**
	   * Content::getModAlias()
	   * 
	   * @return
	   */
	  private function getModAlias()
	  {
	  	  global $db;
		  
		  if (isset($_GET['module'])) {
			  $this->modalias = sanitize($_GET['module'],20);
			  return $db->escape($this->modalias);
		  }
	  }

	  /**
	   * Content::getHomePageSlug()
	   * 
	   * @return
	   */
	  private function getHomePageSlug()
	  {
	  	  global $db;
		  
		  $row = $db->first("SELECT page_id, mod_id, page_slug FROM menus WHERE home_page = '1'");
		  $this->homeslug = $row['page_slug'];
		  $this->homeid = $row['page_id'];
		  if($row['mod_id'] and preg_match('/index.php/', $_SERVER['PHP_SELF'])) {
			  $this->modalias =$row['page_slug'];
		  }

		  $this->is_homemod = $row['mod_id'];
	  }

	  /**
	   * Content::getPageId()
	   * 
	   * @return
	   */
	  private function getPageId()
	  {
	  	  global $core, $DEBUG;
		  if (isset($_GET['pageid'])) {
			  $_GET['pageid'] = sanitize($_GET['pageid'],6,true);
			  $pageid = (is_numeric($_GET['pageid']) && $_GET['pageid'] > -1) ? intval($_GET['pageid']) : false;

			  if ($pageid == false) {
				  $DEBUG == true ? $core->error("You have selected an Invalid Id", "Core::getPageId()") : $core->ooops();
			  } else
				  return $this->pageid = $pageid;
		  }
	  }

	  /**
	   * Content::getPostId()
	   * 
	   * @return
	   */
	  private function getPostId()
	  {
	  	  global $core, $DEBUG;
		  if (isset($_GET['postid'])) {
			  $postid = (is_numeric($_GET['postid']) && $_GET['postid'] > -1) ? intval($_GET['postid']) : false;
			  $postid = sanitize($postid,8,true);
			  
			  if ($postid == false) {
				  $DEBUG == true ? $core->error("You have selected an Invalid Id", "Core::getPostId()") : $core->ooops();
			  } else
				  return $this->postid = $postid;
		  }
	  }

	  /**
	   * Content::getId()
	   * 
	   * @return
	   */
	  private function getId()
	  {
	  	  global $core, $DEBUG;
		  if (isset($_GET['id'])) {
			  $id = (is_numeric($_GET['id']) && $_GET['id'] > -1) ? intval($_GET['id']) : false;
			  $id = sanitize($id,8,true);
			  
			  if ($id == false) {
				  $DEBUG == true ? $core->error("You have selected an Invalid Id", "Content::getId()") : $core->ooops();
			  } else
				  return $this->id = $id;
		  }
	  }

      /**
       * Content::getPageSettings()
       * 
       * @return
       */
      private function getPageSettings()
      {
          global $db, $core;
		  
          $sql = "SELECT * FROM pages WHERE slug = '".$this->slug."'";
          $row = $db->first($sql);
          
		  $this->title = $row['title'.$core->dblang];
		  $this->slug = $row['slug'];
		  $this->contact_form = $row['contact_form'];
		  $this->membership_id = $row['membership_id'];
		  $this->module_id = $row['module_id'];
		  $this->module_data = $row['module_data'];
		  $this->module_name = $row['module_name'];
		  $this->access = $row['access'];

		  $this->keywords = $row['keywords'.$core->dblang];
		  $this->description = $row['description'.$core->dblang];
		  $this->created = $row['created'];
		  $this->active = $row['active'];
		  $this->modpageid = $row['id'];

      }

	  /**
	   * Content::getHomePage()
	   * 
	   * @return
	   */
	  private function getHomePage()
	  {
		  global $db, $core, $user, $pager;

		  $sql = "SELECT pg.title{$core->dblang}, pg.body{$core->dblang}, m.home_page AS home" 
		  . "\n FROM pages AS pg" 
		  . "\n LEFT JOIN menus AS m ON pg.slug = m.page_slug" 
		 // . "\n LEFT JOIN posts AS p ON p.page_slug = pg.slug" 
		  . "\n WHERE m.home_page = '1'";
		  $result = $db->fetch_all($sql);
		  
		  if ($result) {
			foreach ($result as $row) {
				print "<article class=\"post\">";
				//if ($row['show_title'] == 1) {
					print "<header class=\"home-header\">";
					print "<h1><span>" . $row['title' . $core->dblang] . "</span></h1>\n";
					print "</header>";
				//}
				print "<div class=\"home-body\">" . cleanOut($row['body' . $core->dblang]) . "\n";
				//print ($row['jscode']) ? cleanOut($row['jscode']) : "";
				print "</div>\n";
				print "</article>\n";

			}
			
		  } elseif (file_exists(MODDIR . $this->homeslug.'/main.php') and $this->is_homemod and preg_match('/index.php/', $_SERVER['PHP_SELF'])) {
			   require(MODDIR . $this->homeslug.'/main.php'); 
			   $this->module_name = $this->homeslug;
			   
		  } else
			  print _CONTENT_NOT_FOUND;
	  }

	  /**
	   * Content::displayPage()
	   * 
	   * @return
	   */
	  public function displayPage()
	  {
		 ($this->slug) ? $this->getPagePosts() : $this->getHomePage();

	  }

	  /**
	   * Content::displayModule()
	   * 
	   * @return
	   */
	  public function displayModule()
	  {
		  global $db, $core, $user, $pager;
		  
		   if (file_exists(MODDIR . $this->moduledata['modalias'].'/main.php')) {
			   require(MODDIR . $this->moduledata['modalias'].'/main.php');  
		  } else {
			  redirect_to(SITEURL);
		  }
	  }

	  /**
	   * Content::getPagePosts()
	   * 
	   * @return
	   */
	  private function getPagePosts()
	  {
		  global $db, $core, $user, $pager;

		  $sql = "SELECT * FROM pages" 
		  . "\n WHERE slug = '" . $this->slug . "'" 
		  . "\n AND active = '1'";
		  $result = $db->fetch_all($sql);

		  $sql2 = "SELECT p.*, m.modalias, m.active as mactive FROM posts as p" 
		  . "\n LEFT JOIN modules AS m ON m.id = '".$this->module_id . "'"
		  . "\n WHERE p.page_slug = '" . $this->slug . "'" 
		  . "\n AND p.active = '1'"
		  . "\n AND m.system = '0'";
		  $row2 = $db->first($sql2);	
		  
		  if ($result) {
			  if($this->getAccess()) {
				  foreach ($result as $row) {
					  print "<article class=\"post\">";
					  //if ($row['show_title'] == 1) {
					      print "<header class=\"post-header\">";
						  print "<h1><span>" . $row['title' . $core->dblang] . "</span></h1>\n";
						  print "</header>";
					 // }
					  print "<div class=\"post-body\">" . cleanOut($row['body' . $core->dblang]) . "\n";
					//  print ($row['jscode']) ? cleanOut($row['jscode']) : "";
					  print "</div>\n";
					  print "</article>\n";
				  }
				  
				  if ($this->contact_form <> 0)
					  include("contact_form.php");
					  
				  if ($row2['mactive'] and file_exists(MODDIR . $row2['modalias'].'/main.php')) {
					  include(MODDIR . $row2['modalias'].'/main.php');  
				  }
			  }
		  } else
			  print _CONTENT_NOT_FOUND;
	  } 

	  /**
	   * Content::getBreadcrumbs()
	   * 
	   * @return
	   */
	  public function getBreadcrumbs()
	  {
		  global $db, $core;
          
		  $crumbs = WOJOLITE . 'admin/modules/'.$this->modalias.'/crumbs.php';
		  if (file_exists($crumbs) and $this->moduledata['system']) {
			 include($crumbs);  
		  }
			 
		  $pageid = ($this->slug) ? $this->title : "";
		  $data = ($this->modalias and $this->moduledata['system']) ? $nav : $pageid;

		  return $data;
	  }

	  /**
	   * Content::getAccess()
	   * 
	   * @return
	   */
	  public function getAccess($showMsg = true)
	  {
		  global $db, $user, $core;
		  $m_arr = explode(",", $this->membership_id);
		  reset($m_arr);
		  
		  switch ($this->access) {
			  case "Registered":
				  if (!$user->logged_in) {
					  $showMsg ? $core->msgError(_UA_ACC_ERR1, false) : null;
					  return false;
				  } else
					  return true;
				  break;
				  
			  case "Membership":
				  if ($user->logged_in and $user->validateMembership() and in_array($user->membership_id, $m_arr)) {
					  return true;
				  } else {
					  if ($user->logged_in and $user->memused) {
						  $showMsg ? $core->msgError(_UA_ACC_ERR3 . $this->listMemberships($this->membership_id), false) : null;
					  } else {
						  $showMsg ? $core->msgError(_UA_ACC_ERR2 . $this->listMemberships($this->membership_id), false) : null;
					  }
					  
					  return false;
				  }
				  break;
				  
			  case "Public":
				  return true;
				  break;
				  
			  default:
				  return true;
				  break;
		  }
	  }

      /**
       * Content::listMemberships()
       * 
       * @param mixed $memid
       * @return
       */
	  private function listMemberships($memid)
	  {
		  global $db, $core;
		  
		  $data = $db->fetch_all("SELECT title{$core->dblang} as mtitle FROM memberships WHERE id IN(" . $memid . ")");
		  if ($data) {
			  $display = _UA_ACC_MEMBREQ;
			  $display .= '<ul class="error">';
			  foreach($data as $row) {
				  $display .= '<li>' . $row['mtitle'] . '</li>';
			  }
			  $display .= '</ul>';
			  return $display;
		  }
		  
	  }
	  
	  /**
	   * Content::getPages()
	   * 
	   * @return
	   */
	  public function getPages()
	  {
		  global $db, $core;
		  
		  $sql = "SELECT *,"
		  . "\n DATE_FORMAT(created, '" . $core->long_date . "') as date"
		  . "\n FROM pages"
		  . "\n ORDER BY title{$core->dblang}";
          $row = $db->fetch_all($sql);
          
		  return ($row) ? $row : 0;
	  }

      /**
       * Content::getModuleList()
       * 
       * @param bool $sel
       * @return
       */
      public function getModuleList($sel = false)
	  {
		  global $db, $core;
		  
		  $sql = "SELECT id, modalias, title{$core->dblang} FROM modules" 
		  . "\n WHERE active = '1' AND hasconfig = '1' AND system = '0' ORDER BY title{$core->dblang}";
		  $sqldata = $db->fetch_all($sql);
		  
		  $data = '';
		  $data .= '<select name="module_id" style="width:200px" class="custombox" id="modulename">';
		  $data .= "<option value=\"0\"> --- No Module Assigned---</option>\n";
		  foreach ($sqldata as $val) {
              if ($val['id'] == $sel) {
                  $data .= "<option selected=\"selected\" value=\"" . $val['id'] . "\">" . $val['title' . $core->dblang] . "</option>\n";
              } else
                  $data .= "<option value=\"" . $val['id'] . "\">" . $val['title' . $core->dblang] . "</option>\n";
          }
          unset($val);
		  $data .= "</select>";
          return $data;
      }

	  /**
	   * Content::displayMenuModule()
	   * 
	   * @return
	   */
	  public function displayMenuModule()
	  {
		  global $db, $core;
		  
		  $sql = "SELECT id, title{$core->dblang} FROM modules" 
		  . "\n WHERE active = '1' AND system = '1' ORDER BY title{$core->dblang}";
          $row = $db->fetch_all($sql);
          
		  return ($row) ? $row : 0;
	  }

	  /**
	   * Content::getSitemap()
	   * 
	   * @return
	   */
	  public function getSitemap()
	  {
		  global $db, $core;
		  
		  $sql = "SELECT title{$core->dblang} as pgtitle, slug FROM pages ORDER BY created DESC";
		  $row = $db->fetch_all($sql);
		  
		  return ($row) ? $row : 0;

	  }

	  /**
	   * Content::getArticleSitemap()
	   * 
	   * @return
	   */
	  public function getArticleSitemap()
	  {
		  global $db, $core;
		  
		  $sql = "SELECT title{$core->dblang} as atitle, slug FROM mod_articles WHERE active = 1 ORDER BY created DESC";
		  $row = $db->fetch_all($sql);
		  
		  return ($row) ? $row : 0;

	  }
	  
	  /**
	   * Content::getDigishopSitemap()
	   * 
	   * @return
	   */
	  public function getDigishopSitemap()
	  {
		  global $db, $core;
		  
		  $sql = "SELECT title{$core->dblang} as dtitle, slug FROM mod_digishop WHERE active = 1 ORDER BY created DESC";
		  $row = $db->fetch_all($sql);
		  
		  return ($row) ? $row : 0;

	  }

	  /**
	   * Content::getPortfolioSitemap()
	   * 
	   * @return
	   */
	  public function getPortfolioSitemap()
	  {
		  global $db, $core;
		  
		  $sql = "SELECT title{$core->dblang} as ptitle, slug FROM mod_portfolio ORDER BY created DESC";
		  $row = $db->fetch_all($sql);
		  
		  return ($row) ? $row : 0;

	  }
	  
	  /**
	   * Content::processPage()
	   * 
	   * @return
	   */
	  public function processPage()
	  {
		  global $db, $core, $wojosec;
		  if (empty($_POST['title'.$core->dblang]))
			  $core->msgs['title'] = _PG_TITLE_R;

		  if ($_POST['access'] == "Membership" && !isset($_POST['membership_id']))
			  $core->msgs['access'] = _PG_MEMBERSHIP_R;
			  
		  if (empty($core->msgs)) {
			  $data = array(
				  'title'.$core->dblang => sanitize($_POST['title'.$core->dblang]), 
				  'keywords'.$core->dblang => sanitize($_POST['keywords'.$core->dblang]),
				  'description'.$core->dblang => sanitize($_POST['description'.$core->dblang]),
				  'body'.$core->dblang => sanitize($_POST['body'.$core->dblang]),
				  'slug' => (empty($_POST['slug'])) ? paranoia($_POST['title'.$core->dblang]) : paranoia($_POST['slug']),
				  'module_id' => intval($_POST['module_id']),
				  'module_data' => (isset($_POST['module_data'])) ? intval($_POST['module_data']) : 0,
				  'module_name' => getValue("modalias","modules","id='".intval($_POST['module_id'])."'"),
				  'contact_form' => intval($_POST['contact_form']),
				  'access' => sanitize($_POST['access'])
			  );

			  if (isset($_POST['membership_id'])) {
				  $mids = $_POST['membership_id'];
				  $total = count($mids);
				  $i = 1;
				  if (is_array($mids)) {
					  $midata = '';
					  foreach ($mids as $mid) {
						  if ($i == $total) {
							  $midata .= $mid;
						  } else
							  $midata .= $mid . ",";
						  $i++;
					  }
				  }
				  $data['membership_id'] = $midata;
			  } else
				  $data['membership_id'] = 0;
				  
			  if ($data['contact_form'] == 1) {
				  $contactform['contact_form'] = "DEFAULT(contact_form)";
				  $db->update("pages", $contactform);
			  }
			  
			  if (!$this->pageid) {
				  $data['created'] = "NOW()";
			  }
			  
			  if ($this->pageid) {
				  $sdata['page_slug'] = $data['slug'];
				  $db->update("layout", $sdata, "page_id='" . (int)$this->pageid . "'");
				  $db->update("menus", $sdata, "page_id='" . (int)$this->pageid . "'");
				  $db->update("posts", $sdata, "page_id='" . (int)$this->pageid . "'");
			  }
			  
			  ($this->pageid) ? $db->update("pages", $data, "id='" . (int)$this->pageid . "'") : $db->insert("pages", $data);
			  $message = ($this->pageid) ? _PG_UPDATED : _PG_ADDED;
			  
			  ($db->affected()) ? $wojosec->writeLog($message, "", "no", "content") . $core->msgOk($message) :  $core->msgAlert(_SYSTEM_PROCCESS);
		  } else
			  print $core->msgStatus();
	  }

	  /**
	   * Content::getPosts()
	   * 
	   * @return
	   */
	  public function getPosts()
	  {
		  global $db;
		  
		  $where = ($this->pageid) ? "WHERE page_id = '".$this->pageid."'" : null ;
		  $sql = "SELECT * FROM posts"
		  . "\n {$where}"
		  . "\n ORDER BY position";
          $row = $db->fetch_all($sql);
          
		  return ($row) ? $row : 0;
	  }

	  /**
	   * Content::getPagePost()
	   * 
	   * @return
	   */
	  public function getPagePost()
	  {
		  global $db, $core, $pager;

		  require_once(WOJOLITE . "lib/class_paginate.php");
          $pager = new Paginator();

          $counter = countEntries("posts");
          $pager->items_total = $counter;
          $pager->default_ipp = $core->perpage;
          $pager->paginate();
          
          if ($counter == 0) {
              $pager->limit = null;
          }
		  
		  $where = ($this->pageid) ? "WHERE page_id = '".$this->pageid."'" : NULL ;
		  $sql = "SELECT pt.*, pt.id as id, pg.id as pageid, pg.title".$core->dblang." as pagetitle, pg.slug as pgslug"
		  . "\n FROM posts AS pt"
		  . "\n LEFT JOIN pages AS pg ON pg.id = pt.page_id"
		  . "\n $where"
		  . "\n ORDER BY pt.page_id, pt.position". $pager->limit;
          $row = $db->fetch_all($sql);
          
		  return ($row) ? $row : 0;
	  }

	  /**
	   * Content::processPost()
	   * 
	   * @return
	   */
	  public function processPost()
	  {
		  global $db, $core, $wojosec;
		  
		  if (empty($_POST['title'.$core->dblang]))
			  $core->msgs['title'] =  _PO_TITLE_R;
		  
		  if (empty($core->msgs)) {
				  $data = array(
				  'title'.$core->dblang => sanitize($_POST['title'.$core->dblang]), 
				  'page_id' => intval($_POST['page_id']), 
				  'page_slug' => getValue("slug", "pages","id = '".intval($_POST['page_id'])."'"), 
				  'show_title' => intval($_POST['show_title']),
				  'body'.$core->dblang => $core->in_url($_POST['body'.$core->dblang]),
				  'jscode' => $_POST['jscode'],
				  'active' => intval($_POST['active'])
			  );
			  
			  ($this->postid) ? $db->update("posts", $data, "id='" . (int)$this->postid . "'") : $db->insert("posts", $data);
			  $message = ($this->postid) ? _PO_UPDATED : _PO_ADDED;
			  
			  ($db->affected()) ? $wojosec->writeLog($message, "", "no", "content") . $core->msgOk($message) : $core->msgAlert(_SYSTEM_PROCCESS);
		  } else
			  print $core->msgStatus();
	  }

	  /**
	   * Content::getPagePlugins()
	   * 
	   * @return
	   */
	  public function getPagePlugins()
	  {
		  global $db, $core, $pager;
		  
		  require_once(WOJOLITE . "lib/class_paginate.php");
          $pager = new Paginator();

          $counter = countEntries("plugins");
          $pager->items_total = $counter;
          $pager->default_ipp = $core->perpage;
          $pager->paginate();
          
          if ($counter == 0) {
              $pager->limit = null;
          }
		  
		  $sql = "SELECT *, DATE_FORMAT(created, '" . $core->long_date . "') as date"
		  . "\n FROM plugins"
		  . "\n ORDER BY hasconfig DESC, title".$core->dblang . $pager->limit;;
          $row = $db->fetch_all($sql);
          
		  return ($row) ? $row : 0;
	  }	 

	  /**
	   * Content::processPlugin()
	   * 
	   * @return
	   */
	  public function processPlugin()
	  {
		  global $db, $core, $wojosec;
		  
		  if (empty($_POST['title'.$core->dblang]))
			  $core->msgs['title'] = _PL_TITLE_R;
		  
		  if (empty($core->msgs)) {
			  $data = array(
				  'title'.$core->dblang => sanitize($_POST['title'.$core->dblang]), 
				  'show_title' => intval($_POST['show_title']),
				  'alt_class' => sanitize($_POST['alt_class']),
				  'body'.$core->dblang => $core->in_url($_POST['body'.$core->dblang]),
				  'info'.$core->dblang => sanitize($_POST['info'.$core->dblang]),
				  'jscode' => isset($_POST['jscode']) ? $_POST['jscode'] : "NULL",
				  'active' => intval($_POST['active'])
			  );
			  
			  if (!$this->id) {
				  $data['created'] = "NOW()";
			  }
			  
			  ($this->id) ? $db->update("plugins", $data, "id='" . (int)$this->id . "'") : $db->insert("plugins", $data);
			  $message = ($this->id) ? _PL_UPDATED : _PL_ADDED;
			  
			  ($db->affected()) ? $wojosec->writeLog($message, "", "no", "plugin") . $core->msgOk($message) :  $core->msgAlert(_SYSTEM_PROCCESS);
		  } else
			  print $core->msgStatus();
	  }

	  /**
	   * Content::getPageModules()
	   * 
	   * @return
	   */
	  public function getPageModules()
	  {
		  global $db, $core, $pager;
		  
		  require_once(WOJOLITE . "lib/class_paginate.php");
          $pager = new Paginator();

          $counter = countEntries("modules");
          $pager->items_total = $counter;
          $pager->default_ipp = $core->perpage;
          $pager->paginate();
          
          if ($counter == 0) {
              $pager->limit = null;
          }
		  
		  $sql = "SELECT *, DATE_FORMAT(created, '" . $core->long_date . "') as date"
		  . "\n FROM modules"
		  . "\n ORDER BY title".$core->dblang . $pager->limit;
          $row = $db->fetch_all($sql);
          
		  return ($row) ? $row : 0;
	  }	 	  

	  /**
	   * Content::processModule()
	   * 
	   * @return
	   */
	  public function processModule()
	  {
		  global $db, $core, $wojosec;
		  
		  if (empty($_POST['title'.$core->dblang]))
			  $core->msgs['title'] = _MO_TITLE_R;
		  
		  if (empty($core->msgs)) {
			  $data = array(
				  'title'.$core->dblang => sanitize($_POST['title'.$core->dblang]), 
				  'info'.$core->dblang => sanitize($_POST['info'.$core->dblang]),
				  'theme' => (isset($_POST['theme']) and !empty($_POST['theme'])) ? sanitize($_POST['theme']) : 'NULL',
				  'metakey'.$core->dblang => sanitize($_POST['metakey'.$core->dblang]), 
				  'metadesc'.$core->dblang => sanitize($_POST['metadesc'.$core->dblang])
			  );

			  $db->update("modules", $data, "id='" . (int)$this->id . "'");
			  ($db->affected()) ? $wojosec->writeLog(_MO_UPDATED, "", "no", "module") . $core->msgOk(_MO_UPDATED) :  $core->msgAlert(_SYSTEM_PROCCESS);
		  } else
			  print $core->msgStatus();
	  }

      /**
       * Content::getAvailablePlugins()
       * 
       * @return
       */
      public function getAvailablePlugins()
	  {
          global $db;
		  $pageid = ($this->pageid) ? "page_id='".$this->pageid."'" : "page_id='".$this->homeid."'";
		  $data = (isset($_GET['modid'])) ? "mod_id='".intval($_GET['modid'])."'" : $pageid;
		  
          $sql = "SELECT * FROM plugins" 
		  . "\n WHERE id NOT IN (SELECT plug_id FROM layout"
		  . "\n WHERE $data)";
          $row = $db->fetch_all($sql);
          
		  return ($row) ? $row : 0;
      }

      /**
       * Content::getPluginName()
       * 
       * @param mixed $name
       * @return
       */
      public function getPluginName($name)
	  {
          global $db, $core;
		  $name = sanitize($name);
          $sql = "SELECT title{$core->dblang} FROM plugins" 
		  . "\n WHERE plugalias = '" . $db->escape($name) . "'";
          $row = $db->first($sql);
          
		  return ($row) ? $row['title'.$core->dblang] : "NA";
      }

      /**
       * Content::getModuleName()
       * 
       * @param mixed $name
       * @return
       */
      public function getModuleName($name)
	  {
          global $db, $core;
		  $name = sanitize($name);
          $sql = "SELECT title{$core->dblang} FROM modules" 
		  . "\n WHERE modalias = '" . $db->escape($name) . "'";
          $row = $db->first($sql);
          
		  return ($row) ? $row['title'.$core->dblang] : "NA";
      }

      /**
       * Content::getModuleMetaData()
       * 
       * @return
       */
      public function getModuleMetaData()
	  {
          global $db, $core;
		  
          $sql = "SELECT * FROM modules" 
		  . "\n WHERE modalias = '" . $this->modalias . "'"
		  . "\n AND active = 1 AND system = 1";
          $row = $db->first($sql);
          
		  return $this->moduledata = $row;
      }

      /**
       * Content::getLayoutOptions()
       * 
       * @return
       */
      public function getLayoutOptions()
      {
          global $db, $core;

		  $pageid = ($this->pageid) ? "l.page_id='".$this->pageid."'" : "l.page_id='".$this->homeid."'";
		  $data = (isset($_GET['modid'])) ? "l.mod_id='".intval($_GET['modid'])."'" : $pageid;
		  
          $sql = "SELECT l.*, p.id as plid, p.title{$core->dblang}" 
		  . "\n FROM layout AS l" 
		  . "\n INNER JOIN plugins AS p ON p.id = l.plug_id" 
		  . "\n WHERE $data"
		  . "\n ORDER BY l.position ASC, p.title{$core->dblang} ASC";
          $row = $db->fetch_all($sql);
          
		  return ($row) ? $row : 0;
      }

      /**
       * Content::getPluginLayout()
       * 
       * @param mixed $place
       * @param bool $modalias
       * @return
       */
      public function getPluginLayout($place, $modalias = false)
      {
          global $db, $core;
		  
		  //$pageid = ($this->slug) ? "l.page_slug = '".$this->slug."'" : "l.page_slug = '".$this->homeslug."'";
		  if($this->slug) {
			  $pageid = "l.page_slug = '".$this->slug."'";
		  } elseif($this->homeid == 0 and $this->is_homemod and preg_match('/index.php/', $_SERVER['PHP_SELF'])) {
			  $pageid = "l.modalias = '".$this->homeslug."'";
		  } else {
			  $pageid = "l.page_slug = '".$this->homeslug."'";
		  }
		  $data = ($modalias) ? "l.modalias = '".$this->modalias."'" : $pageid;
		  
          $sql = "SELECT l.*, p.id as plid, p.title{$core->dblang}, p.body{$core->dblang}, p.plugalias, p.hasconfig, p.system, p.show_title, p.alt_class, p.jscode" 
		  . "\n FROM layout AS l" 
		  . "\n LEFT JOIN plugins AS p ON p.id = l.plug_id" 
		  . "\n WHERE l.place = '".$place."'"
		  . "\n AND {$data}"
		  . "\n AND p.active = '1'"
		  . "\n ORDER BY l.position ASC";
          $row = $db->fetch_all($sql);
          
		  return ($row) ? $row : null;

      }
  
      /**
       * Content::getPluginAssets()
       * 
       * @return
       */
	  public function getPluginAssets()
	  {
		  global $db, $core;
		  
		  $pageid = ($this->slug) ? "l.page_slug = '" . $this->slug . "'" : "l.page_slug = '" . $this->homeslug . "'";
		  $data = ($this->modalias) ? "l.modalias = '" . $this->modalias . "'" : $pageid;
	
		  $sql = "SELECT l.*,  p.plugalias" 
		  . "\n FROM layout AS l" 
		  . "\n LEFT JOIN plugins AS p ON p.id = l.plug_id" 
		  . "\n WHERE {$data}" 
		  . "\n AND p.system = '1'" 
		  . "\n AND p.active = '1'";
		  $result = $db->fetch_all($sql);
	
		  if ($result) {
			  foreach ($result as $row) {
				  $tcssfile = PLUGDIR . $row['plugalias'] . "/theme/" . $core->theme . "/style.css";
				  $tjsfile = PLUGDIR . $row['plugalias'] . "/theme/" . $core->theme . "/script.js";
	
				  $cssfile = PLUGDIR . $row['plugalias'] . "/style.css";
				  $jsfile = PLUGDIR . $row['plugalias'] . "/script.js";
	
				  if (is_file($tcssfile)) {
					  print "<link href=\"" . SITEURL . "/plugins/" . $row['plugalias'] . "/theme/" . $core->theme . "/style.css\" rel=\"stylesheet\" type=\"text/css\" />\n";
				  } elseif (is_file($cssfile)) {
					  print "<link href=\"" . SITEURL . "/plugins/" . $row['plugalias'] . "/style.css\" rel=\"stylesheet\" type=\"text/css\" />\n";
	
				  }
	
				  if (is_file($tjsfile)) {
					  print "<script type=\"text/javascript\" src=\"" . SITEURL . "/plugins/" . $row['plugalias'] . "/theme/" . $core->theme . "/script.js\"></script>\n";
				  } elseif (is_file($jsfile)) {
					  print "<script type=\"text/javascript\" src=\"" . SITEURL . "/plugins/" . $row['plugalias'] . "/script.js\"></script>\n";
				  }
	
			  }
		  }
	  }

	  /**
	   * Content::getModuleAssets()
	   * 
	   * @return
	   */
	  public function getModuleAssets()
	  {
		  global $core;
		  
		  if ($this->modalias) {
			  $tcssfile = MODDIR . $this->modalias . "/theme/" . $core->theme . "/style.css";
			  $jsfile = MODDIR . $this->modalias . "/script.js";

			  if (is_file($tcssfile))
				  print "<link href=\"" . SITEURL . "/modules/" . $this->modalias . "/theme/" . $core->theme . "/style.css\" rel=\"stylesheet\" type=\"text/css\" />\n";
			  
			  if (file_exists($jsfile))
				  print "<script type=\"text/javascript\" src=\"" . SITEURL . "/modules/" . $this->modalias . "/script.js\"></script>\n";
		  
		  } elseif ($this->module_name != '' or $this->module_id <> 0) {
			  $tcssfile = MODDIR . $this->module_name . "/theme/" . $core->theme . "/style.css";
			  $tjsfile = MODDIR . $this->module_name . "/theme/" . $core->theme . "/script.js";
			  
			  $cssfile = MODDIR . $this->module_name . "/style.css";
			  $jsfile = MODDIR . $this->module_name . "/script.js";

			  if (is_file($tcssfile)) {
				  print "<link href=\"" . SITEURL . "/modules/" . $this->module_name . "/theme/" . $core->theme . "/style.css\" rel=\"stylesheet\" type=\"text/css\" />\n";
			  } elseif (is_file($cssfile)) {
				  print "<link href=\"" . SITEURL . "/modules/" . $this->module_name . "/style.css\" rel=\"stylesheet\" type=\"text/css\" />\n";

			  }

			  if (is_file($tjsfile)) {
				  print "<script type=\"text/javascript\" src=\"" . SITEURL . "/plugins/" . $this->module_name . "/theme/" . $core->theme . "/script.js\"></script>\n";
			  } elseif (is_file($jsfile)) {
				  print "<script type=\"text/javascript\" src=\"" . SITEURL . "/modules/" . $this->module_name . "/script.js\"></script>\n";
			  }
		  }
	  }

	  /**
	   * Content:::getStyle()
	   * 
	   * @return
	   */
	  public function getThemeStyle()
	  {
		  global $core;
	      
		  $themevar = THEMEDIR . "/skins/" . $core->theme_var . ".css";
		  if ($core->lang_dir == "rtl") {
			  $css = THEMEDIR . "/css/style_rtl.css";
			  if (is_file($css)) {
				  print "<link href=\"" . SITEURL . "/theme/" . $core->theme . "/css/style_rtl.css\" rel=\"stylesheet\" type=\"text/css\" />\n";
			  } else {
				  print "<link href=\"" . SITEURL . "/theme/" . $core->theme . "/css/style.css\" rel=\"stylesheet\" type=\"text/css\" />\n";
			  }
		  } else {
			  print "<link href=\"" . SITEURL . "/theme/" . $core->theme . "/css/style.css\" rel=\"stylesheet\" type=\"text/css\" />\n";
		  }
		  
		  if($core->theme_var and is_file($themevar)) {
			  print "<link href=\"" . SITEURL . "/theme/" . $core->theme . "/skins/" . $core->theme_var . ".css\" rel=\"stylesheet\" type=\"text/css\" />\n";
		  }
	
	  }
	  
      /**
       * Content::getMenuTree()
       * 
       * @return
       */
      protected function getMenuTree()
	  {
		  global $db, $core;
		  $query = $db->query('SELECT * FROM menus ORDER BY parent_id, position');
		  
		  while ($row = $db->fetch($query)) {
			  $this->menutree[$row['id']] = array(
			        'id' => $row['id'],
					'name'.$core->dblang => $row['name'.$core->dblang], 
					'parent_id' => $row['parent_id']
			  );
		  }
		  return $this->menutree;
	  }

      /**
       * Content::getMenuList()
       * 
       * @return
       */
      public function getMenuList()
	  {
		  global $db, $core;
		  $query = $db->query("SELECT *"
		  . "\n FROM menus " 
		  . "\n WHERE active = '1'"
		  . "\n ORDER BY parent_id, position");
          
		  $res = $db->numrows($query);
		  while ($row = $db->fetch($query)) {
			  $menulist[$row['id']] = array(
			        'id' => $row['id'],
					'name'.$core->dblang => $row['name'.$core->dblang], 
					'parent_id' => $row['parent_id'],
					'page_id' => $row['page_id'],
					'mod_id' => $row['mod_id'],
					'content_type' => $row['content_type'],
					'link' => $row['link'],
					'home_page' => $row['home_page'],
					'active' => $row['active'],
					'target' => $row['target'],
					'icon' => $row['icon'],
					'pslug' => $row['page_slug'],
			  );
			  
		  }
		  return ($res) ? $menulist : 0;
	  }

      /**
       * Content::getSortMenuList()
       * 
       * @param integer $parent_id
       * @return
       */
      public function getSortMenuList($parent_id = 0)
	  {
		  global $core;
		  
		  $submenu = false;
		  $class = ($parent_id == 0) ? "parent" : "child";

		  foreach ($this->menutree as $key => $row) {
			  if ($row['parent_id'] == $parent_id) {
				  if ($submenu === false) {
					  $submenu = true;
					  print "<ul>\n";
				  }
				  
				  print '<li id="list_' . $row['id'] . '">'
				  .'<div><a href="javascript:void(0)" id="item_'.$row['id'].'" data-title="' . $row['name'.$core->dblang] . '" class="delete">'
				  .'<img src="images/del.png" alt="" class="tooltip" title="'._DELETE.'"/></a>'
				  .'<a href="index.php?do=menus&amp;action=edit&amp;id=' . $row['id'] . '" class="'.$class.'">' . $row['name'.$core->dblang] . '</a></div>';
				  $this->getSortMenuList($key);
				  print "</li>\n";
			  }
		  }
		  unset($row);
		  
		  if ($submenu === true)
			  print "</ul>\n";
	  }
 

	  /**
	   * Content::getMenu()
	   * 
	   * @param mixed $array
	   * @param integer $parent_id
	   * @return
	   */
	  public function getMenu($array, $parent_id = 0, $menuid = 'topmenu')
	  {
		  global $core, $user;
		  
		  if(is_array($array) && count($array) > 0) {
				  
			  $submenu = false;
			  
			  $attr = (!$parent_id) ? ' class="menu-parent" id="' . $menuid . '"' : ' class="menu-submenu"';
			  foreach ($array as $key => $row) {
				
				  if ($row['parent_id'] == $parent_id) {
					  if($row['name' . $core->dblang] == 'Train' && !$user->logged_in) continue;
            if($row['name' . $core->dblang] == 'Demo' && !$user->logged_in) continue;
					  
					  if ($submenu === false) {
						  $submenu = true;	
						  print "<ul" . $attr . ">\n";
					  }
					  
					  $url = ($core->seo == 1) ? $core->site_url . '/' . sanitize($row['pslug'], 50) . '.html' : $url = $core->site_url . '/content.php?pagename=' . sanitize($row['pslug'], 50);
					  $active = ($row['pslug'] == $this->slug) ? " class=\"active\"" : "";
					  $mactive = ($row['pslug'] == $this->modalias) ? " class=\"active\"" : "";
					  $homeactive = (preg_match('/index.php/', $_SERVER['PHP_SELF'])) ? "active" : "";
					  $home = ($row['home_page']) ? " homepage" : "";
					  $icon = ($row['icon']) ? '<img src="' . UPLOADURL . 'menuicons/' . $row['icon'] . '" alt="" class="menuicon" />' : "";
					  
					  
					  switch ($row['content_type']) {
						  case 'module':
							  $murl = ($core->seo == 1) ? $core->site_url . '/content/' . sanitize($row['pslug'], 50) . '/' : $murl = $core->site_url . '/modules.php?module=' . $row['pslug'];
							  $murl2 = $row['home_page'] ? SITEURL . '/index.php' : $murl;
							  $link = '<li' . $mactive . '><a href="' . $murl2 . '"><span>' . $icon . $row['name' . $core->dblang] . '</span></a>';
							  break;
							  
						  case 'page':
							  ($row['home_page'] == 1) ? $link = '<li class="' . $homeactive . $home . '"><a href="' . SITEURL . '/index.php"><span>' . $icon . $row['name' . $core->dblang] . '</span></a>' : 
							  $link = '<li' . $active . '><a href="' . $url . '"><span>' . $icon . $row['name' . $core->dblang] . '</span></a>';
							  
							  break;
							  
						  case 'web':
							  $link = '<li><a href="' . $row['link'] . '" target="' . $row['target'] . '"><span>' . $icon . $row['name' . $core->dblang] . '</span></a>';
							  break;
					  }
					  
					  print $link;
					  $this->getMenu($array, $key);
					  print "</li>\n";
				  }
			  }
			  unset($row);
			  
			  if ($submenu === true)
				  print "</ul>\n";
		  }	  
	  }

	  /**
	   * Content::getMenuDropList()
	   * 
	   * @param mixed $parent_id
	   * @param integer $level
	   * @param mixed $spacer
	   * @param bool $selected
	   * @return
	   */
	  public function getMenuDropList($parent_id, $level = 0, $spacer, $selected = false)
	  {
		  global $core;
		  foreach ($this->menutree as $key => $row) {
			  $sel = ($row['id'] == $selected) ? " selected=\"selected\"" : "" ;
			  if ($parent_id == $row['parent_id']) {
				  print "<option value=\"" . $row['id'] . "\"".$sel.">";
				  
				  for ($i = 0; $i < $level; $i++)
					  print $spacer;
				  
				  print $row['name'.$core->dblang] . "</option>\n";
				  $level++;
				  $this->getMenuDropList($key, $level, $spacer, $selected);
				  $level--;
			  }
		  }
		  unset($row);
	  }

	  /**
	   * Content::processMenu()
	   * 
	   * @return
	   */
	  public function processMenu()
	  {
		  global $db, $core, $wojosec;
		  if (empty($_POST['name'.$core->dblang]))
			  $core->msgs['name'] = _MU_NAME_R;
		  
		  if ($_POST['content_type'] == "NA")
			  $core->msgs['content_type'] = _MU_TYPE_R;
		  
		  if (empty($core->msgs)) {
			  $data = array(
				  'name'.$core->dblang => sanitize($_POST['name'.$core->dblang]), 
				  'parent_id' => intval($_POST['parent_id']), 
				  'page_id' => (isset($_POST['page_id'])) ? intval($_POST['page_id']) : "DEFAULT(page_id)",
				  'page_slug' => (isset($_POST['page_id'])) ? getValue("slug", "pages","id = '".intval($_POST['page_id'])."'") : getValue("modalias", "modules","id = '".intval($_POST['mod_id'])."'"), 
				  'mod_id' => (isset($_POST['mod_id'])) ? intval($_POST['mod_id']) : "DEFAULT(mod_id)",
				  'slug' => paranoia($_POST['name'.$core->dblang]),
				  'content_type' => sanitize($_POST['content_type']),
				  'link' => (isset($_POST['web'])) ? sanitize($_POST['web']) : "NULL",
				  'target' => (isset($_POST['target'])) ? sanitize($_POST['target']) : "DEFAULT(target)",
				  'icon' => (isset($_POST['icon'])) ? sanitize($_POST['icon']) : "NULL",
				  'home_page' => intval($_POST['home_page']),
				  'active' => intval($_POST['active'])
			  );

			  if ($data['home_page'] == 1) {
				  $home['home_page'] = "DEFAULT(home_page)";
				  $db->update("menus", $home);
			  }
			  
			  ($this->id) ? $db->update("menus", $data, "id='" . (int)$this->id . "'") : $db->insert("menus", $data);
			  $message = ($this->id) ? _MU_UPDATED : _MU_ADDED;
			  
			  ($db->affected()) ? $wojosec->writeLog($message, "", "no", "content") . $core->msgOk($message) :  $core->msgAlert(_SYSTEM_PROCCESS);
			
		  } else
			  print $core->msgStatus();
	  }

	  /**
	   * Content::getMenuIcons()
	   * 
	   * @return
	   */
	  function getMenuIcons($selected = false)
	  {
		  $path = UPLOADS . 'menuicons/';
		  checkDir($path);
		  $res = '';
		  $handle = opendir($path);
		  $class = 'odd';
		  while (false !== ($file = readdir($handle))) {
			  $class = ($class == 'even' ? 'odd' : 'even');
			  if ($file != "." && $file != ".." && $file != "_notes" && $file != "index.php" && $file != "blank.png") {
				  $sel =  ($selected == $file) ? ' sel' : '';
				  $res .= "<div class=\"".$class.$sel."\">";
				  if ($selected == $file) {
					  $res .= "<input type=\"radio\" name=\"icon\" value=\"" . $file . "\" checked=\"checked\" />" 
					          . " <img src=\"".UPLOADURL . "/menuicons/" . $file."\" alt=\"\"/> ".$file;
				  } else {
					  $res .= "<input type=\"radio\" name=\"icon\" value=\"" . $file . "\" />" 
					           . " <img src=\"".UPLOADURL . "/menuicons/" . $file."\" alt=\"\"/> ".$file;
				  }
				  $res .= "</div>\n";
			  }
		  }
		  closedir($handle);
		  return $res;
	  }

	  /**
	   * Content::createSiteMap()
	   * 
	   * @return
	   */
	  private function createSiteMap()
	  {
		  global $db, $core;
  
		  $psql = "SELECT slug FROM pages ORDER BY created DESC";
		  $pages = $db->query($psql);
		  
		  $smap = "";
		  
		  @header('<?phpxml version="1.0" encoding="UTF-8"?>');
		  $smap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">\r\n';
		  $smap .= "<url>\r\n";
		  $smap .= "<loc>" . SITEURL . "/index.php</loc>\r\n";
		  $smap .= "<lastmod>" . date('Y-m-d') . "</lastmod>\r\n";
		  $smap .= "</url>\r\n";

		  while ($row = $db->fetch($pages)) {
			 $url = ($core->seo == 1) ? SITEURL . '/' . $row['slug'] . '.html' : SITEURL . '/content.php?pagename=' . $row['slug'];
			  
			  $smap .= "<url>\r\n";
			  $smap .= "<loc>" . $url . "</loc>\r\n";
			  $smap .= "<lastmod>" . date('Y-m-d') . "</lastmod>\r\n";
			  $smap .= "<changefreq>weekly</changefreq>\r\n";
			  $smap .= "</url>\r\n";
		  }
          unset($row);
		  if(isset($_POST['am'])) {
		  $amsql = "SELECT slug FROM mod_articles WHERE active = 1 ORDER BY created DESC";
		  $articles = $db->query($amsql);
			  
			while ($row = $db->fetch($articles)) {
				$url = ($core->seo == 1) ? SITEURL . '/article/' . $row['slug'] . '.html' : SITEURL . '/modules.php?module=articles&amp;do=article&amp;artname=' . $row['slug'];
				
				$smap .= "<url>\r\n";
				$smap .= "<loc>" . $url . "</loc>\r\n";
				$smap .= "<lastmod>" . date('Y-m-d') . "</lastmod>\r\n";
				$smap .= "<changefreq>weekly</changefreq>\r\n";
				$smap .= "</url>\r\n";
			}
			unset($row);
		  }
		  if(isset($_POST['ds'])) {
		  $dssql = "SELECT slug FROM mod_digishop WHERE active = 1 ORDER BY created DESC";
		  $digishop = $db->query($dssql);
			  
			while ($row = $db->fetch($digishop)) {
				$url = ($core->seo == 1) ? SITEURL . '/digishop/' . $row['slug'] . '.html' : SITEURL . '/modules.php?module=digishop&amp;do=digishop&amp;productname=' . $row['slug'];
				
				$smap .= "<url>\r\n";
				$smap .= "<loc>" . $url . "</loc>\r\n";
				$smap .= "<lastmod>" . date('Y-m-d') . "</lastmod>\r\n";
				$smap .= "<changefreq>weekly</changefreq>\r\n";
				$smap .= "</url>\r\n";
			}
			unset($row);
		  }
		  if(isset($_POST['pf'])) {
		  $pfsql = "SELECT slug FROM mod_portfolio ORDER BY created DESC";
		  $portfolio = $db->query($pfsql);
			  
			while ($row = $db->fetch($portfolio)) {
				$url = ($core->seo == 1) ? SITEURL . '/portfolio/' . $row['slug'] . '.html' : SITEURL . '/modules.php?module=portfolio&amp;do=digishop&amp;productname=' . $row['slug'];
				
				$smap .= "<url>\r\n";
				$smap .= "<loc>" . $url . "</loc>\r\n";
				$smap .= "<lastmod>" . date('Y-m-d') . "</lastmod>\r\n";
				$smap .= "<changefreq>weekly</changefreq>\r\n";
				$smap .= "</url>\r\n";
			}
			unset($row);
		  }
		  $smap .= "</urlset>";
		  
		  return $smap;
	  }
	  
      /**
       * Content::writeSiteMap()
       * 
       * @return
       */
	  public function writeSiteMap()
	  {
		  global $core;
		  
		  $filename = WOJOLITE . 'sitemap.xml';

		  if (is_writable($filename)) {
			  $handle = fopen($filename, 'w');
			  fwrite($handle, $this->createSiteMap());
			  fclose($handle);
			  $core->msgOk(_SM_SMAPOK);
		  } else
			  $core->msgError(str_replace("[FILENAME]", $filename, _SM_SMERROR),false);
	  }
	  
      /**
       * Content::getContentType()
       * 
       * @param bool $selected
       * @return
       */
      public function getContentType($selected = false)
	  {
		  $modlist = $this->displayMenuModule();
          if($modlist) {
			  $arr = array(
					'page' => _CON_PAGE,
					'module' => _MODULE,
					'web' => _EXT_LINK
			  );
		  } else {
			  $arr = array(
					'page' => _CON_PAGE,
					'web' => _EXT_LINK
			  );  
		  }
		  
		  $contenttype = '';
		  foreach ($arr as $key => $val) {
              if ($key == $selected) {
                  $contenttype .= "<option selected=\"selected\" value=\"" . $key . "\">" . $val . "</option>\n";
              } else
                  $contenttype .= "<option value=\"" . $key . "\">" . $val . "</option>\n";
          }
          unset($val);
          return $contenttype;
      }
	    
	  /**
	   * Content::getHomePageMeta()
	   * 
	   * @return
	   */
	  private function getHomePageMeta()
	  {
		  global $db, $core;
		  
		  $sql = "SELECT p.title{$core->dblang}, p.description{$core->dblang}, p.keywords{$core->dblang}" 
		  . "\n FROM pages AS p" 
		  . "\n LEFT JOIN menus AS m ON p.id = m.page_id" 
		  . "\n WHERE m.home_page = '1'";
		  $row = $db->first($sql);
		  
		  return $row;
	  }

	  /**
	   * Content::getPageMeta()
	   * 
	   * @return
	   */
	  private function getPageMeta()
	  {
		global $core;
		
		$meta = "<title>" . $core->site_name . "  |  ";
		if ($this->slug) {
			$meta .= $this->title;
		} else {
			if (isset($_GET['mode'])) {
				$meta .= "Sitemap of " . $core->site_name;
			} else {
				$home = $this->getHomePageMeta();
				$meta .= $home['title'.$core->dblang];
			}
		}
		$meta .= "</title>\n";
		
		$meta .= "<meta name=\"description\" content=\"";
		if ($this->slug) {
			if ($this->description) {
				$meta .= $this->description;
			} else
				$meta .= $core->metadesc;
		} else {
			$home = $this->getHomePageMeta();
			$meta .= $home['description'.$core->dblang];
		}
		$meta .= "\" />\n";
		
		$meta .= "<meta name=\"keywords\" content=\"";
		if ($this->slug) {
			if ($this->keywords) {
				$meta .= $this->keywords;
			} else
				$meta .= $core->metakeys;
		} else {
			$home = $this->getHomePageMeta();
			$meta .= $home['keywords'.$core->dblang];
		}
		$meta .= "\" />\n";
		return $meta;
	  }

	  /**
	   * Content::getModuleMeta()
	   * 
	   * @return
	   */
	  private function getModuleMeta()
	  {
		  global $core;
          
		  $modmeta = WOJOLITE . 'admin/modules/'.$this->modalias.'/meta.php';
		  if (file_exists($modmeta))
			 include($modmeta);  
	  }

	  /**
	   * Content::getMeta()
	   * 
	   * @return
	   */
	  public function getMeta()
	  {
		  global $core;
		  
		  $meta = '';
		  $meta = "<meta charset=\"utf-8\">\n";
		  if ($this->modalias) {
			  $meta .= $this->getModuleMeta();
		  } else {
			  $meta .= $this->getPageMeta();
		  }
		  $meta .= "<link rel=\"shortcut icon\" type=\"image/x-icon\" href=\"" .SITEURL ."/assets/favicon.ico\" />\n";
		  $meta .= "<meta name=\"publisher\" content=\"" . $core->site_name . "\" />\n";
		  $meta .= "<meta name=\"dcterms.rights\" content=\"" . $core->site_name . " &copy; All Rights Reserved\" >\n";
		  $meta .= "<meta name=\"robots\" content=\"index\" />\n";
		  $meta .= "<meta name=\"robots\" content=\"follow\" />\n";
		  $meta .= "<meta name=\"revisit-after\" content=\"1 day\" />\n";
		  $meta .= "<meta name=\"generator\" content=\"Powered by CMS pro! v" . $core->version . "\" />\n";
		  $meta .= "<meta name=\"viewport\" content=\"width=device-width, initial-scale=1, maximum-scale=1\" />\n";
		  return $meta;
	  }
  }
?>