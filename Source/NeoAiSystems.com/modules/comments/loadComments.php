<?php
  /**
   * loadComments
   *
   * @version $Id: loadComments.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  define("_VALID_PHP", true);
  require_once("../../init.php");
  
  require_once(WOJOLITE . "admin/modules/comments/admin_class.php");
  require_once(WOJOLITE . "admin/modules/comments/lang/" . $core->language . ".lang.php");
  $com = new Comments();
  
  $page = sanitize($_GET['pg']);
  $page = (int)$page;

  $page_id = sanitize($_GET['pageid']);
  $page_id = (int)$page_id;
  
  $commentTree = array();

  /**
   * getCommentTree()
   *
   * @param integer $page
   * @param integer $page_id
   * @return
   */
  function getCommentTree($page, $page_id)
  {
      global $db, $com;

      $start = ($page - 1) * $com->perpage;
	  $limit = $start.','.$com->perpage;

      $sql = "SELECT c.*, DATE_FORMAT(c.created, '" . $com->dateformat . "') as cdate, u.avatar, u.fbid" 
	  . "\n FROM mod_comments as c" 
	  . "\n LEFT JOIN users as u ON u.id = c.user_id" 
	  . "\n WHERE page_id = " . $db->escape($page_id)
	  . "\n AND c.active = '1'" 
	  . "\n ORDER BY c.created " . $com->sorting . " LIMIT  ". $limit;
      $query = $db->fetch_all($sql);

      return $query ? $query : 0;
  }
 
  $data = getCommentTree($page, $page_id);
  if ($data) {
	  print "<ul>\n";
	  foreach ($data as $key => $row) {
		  $child = ($row['parent_id'] <> 0) ? ' child' : null;
			  ob_start();
			  include(MODDIR . "/comments/template.tpl.php");
			  $html = ob_get_contents();
			  ob_end_clean();
			  
			  print '<li class="comment' . $child . '" id="comment-' . $row['id'] . '">';
			  print $html;
			  print "</li>\n";
	  }
	  unset($row);
	  
	print "</ul>\n";
  }
?>