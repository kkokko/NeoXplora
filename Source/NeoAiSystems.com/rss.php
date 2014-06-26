<?php
  /**
   * Rss
   *
   * @version $Id: rss.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  define("_VALID_PHP", true);
  require_once("init.php");
  
  
  header("Content-Type: text/xml");
  header('Pragma: no-cache');
  echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
  echo "<rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\">\n\n";
  echo "<channel>\n";
  echo "<title><![CDATA[".$core->site_name."]]></title>\n";
  echo "<link><![CDATA[".$core->site_url."]]></link>\n";
  echo "<description><![CDATA[Latest 20 Rss Feeds - ".$core->company."]]></description>\n";
  echo "<generator>".$core->company."</generator>\n";

  $sql = "SELECT pt.*, pt.id as id, pg.id as pageid, pg.title{$core->dblang} as pagetitle, pg.slug,"
  . "\n DATE_FORMAT(pg.created, '%a, %d %b %Y %T GMT') as created" 
  . "\n FROM posts AS pt"
  . "\n LEFT JOIN pages AS pg ON pg.id = pt.page_id"
  . "\n WHERE pt.active = 1"
  . "\n ORDER BY pg.id LIMIT 20";
  
  $data = $db->fetch_all($sql);
  foreach ($data as $row) {
      $pageid = $row['pageid'];
      $title = $row['title'.$core->dblang];
	  $text = $row['body'.$core->dblang];
      $body = cleanSanitize($text,400);
      $date = $row['created'];
      $slug = $row['slug'];
      
      if ($core->seo == 1) {
          $url = $core->site_url . '/' . sanitize($slug,50) . '.html';
      } else
          $url = $core->site_url . '/content.php?pagename=' . sanitize($slug,50);
      
      echo "<item>\n";
      echo "<title><![CDATA[$title]]></title>\n";
      echo "<link><![CDATA[$url]]></link>\n";
      echo "<guid isPermaLink=\"true\"><![CDATA[$url]]></guid>\n";
      echo "<description><![CDATA[$body]]></description>\n";
      echo "<pubDate><![CDATA[$date]]></pubDate>\n";
      echo "</item>\n";
  }
  unset($row);
  echo "</channel>\n";
  echo "</rss>";
?>