<?php
  /**
   * Rss Parser
   *
   * @version $Id: main.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
  
  require_once(WOJOLITE . "admin/plugins/rss/admin_class.php");
  $rss = new Rss();
  require_once('lib/rss_fetch.inc');
  define("MAGPIE_CACHE_DIR", WOJOLITE . "plugins/rss/cache");
  $dataurl = fetch_rss($rss->url);
  
?>
<!-- Start Rss Parser -->
<?php
	$i = 0;
	foreach ($dataurl->items as $item) :
		$i++;
		$title = $item['title'];
		$title = str_replace('&#34;', "'", $title);
		$title = ($rss->title_trim <> 0) ? sanitize($title,$rss->title_trim) : $title;
		$url = $item['link'];
		$pubdate = dodate($rss->dateformat, $item['published']);
		$summary = $item['summary'];
		$summary = ($rss->body_trim <> 0) ? sanitize($summary,$rss->body_trim) : $summary;
		?>
     <div class="rss-title">
		<?php if($rss->show_date):?>
        <span><?php echo $pubdate;?></span>
        <?php endif;?>
        <a href="<?php echo $url;?>"><?php echo $title;?></a>
        <?php if($rss->show_body):?>
        <p><?php echo $summary;?></p>
        <?php endif;?>
     </div>
<?php if ($i >= $rss->perpage) break;?>
<?php endforeach;?>
<!-- End Rss Parser /-->