<?php
  /**
   * Live Search
   *
   * @version $Id: livesearch.php,v 1.1.5 2010-12-28 10:12:05 gewa Exp $
   */
  define("_VALID_PHP", true);
  
  require_once("../init.php");

?>
<?php
  if (isset($_POST['liveSearch']))
      : $string = sanitize($_POST['liveSearch'],15);

  if (strlen($string) > 3)
      : $sql = $db->query("SELECT pt.*, pt.id as id, pg.id as pageid, pg.title{$core->dblang} as pagetitle, pg.slug" 
	  . "\n FROM posts AS pt"
	  . "\n LEFT JOIN pages AS pg ON pg.id = pt.page_id"
	  . "\n WHERE pt.title{$core->dblang} LIKE '%" . $db->escape($string) . "%' or pt.body{$core->dblang} LIKE '%" . $db->escape($string) . "%'"
	  . "\n ORDER BY pg.id LIMIT 10");
	  
  $display = '';	  
  $display .= '<div id="searchresults"><div class="searchresults-wrapper">';
  $i = 0; $color1 = "search-even"; $color2 = "search-odd";
  
  while ($row = $db->fetch($sql)): 
  $i++;
  
  if ($core->seo == 1) :
	$link = $core->site_url . '/' . sanitize($row['slug'],50) . '.html';
  else:
	$link = $core->site_url . '/content.php?pagename=' . sanitize($row['slug'],50);
  endif;
  
  $title = $row['title'.$core->dblang];
  $body = cleanOut($row['body'.$core->dblang]);
  $content = sanitize($body, 100);
  if (strlen($title) > 65)
      $title = substr($title, 0, 50) . "...";
  $display .= '<div class="'.(($i % 2 == 0) ? $color1 : $color2).'"><a href="'.$link.'">'.$title.'<small>'.$content.'...</small></a></div>';
  endwhile;
  
  $display .= '</div></div>';
  print $display;
  endif;
  endif;
?>