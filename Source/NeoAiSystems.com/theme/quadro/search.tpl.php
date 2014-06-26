<?php
  /**
   * Search Template
   *
   * @version $Id: login.tpl.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<?php
  $keywords = post('keywords');
  $keywords = str_replace("%", '', $keywords);
  $keywords = sanitize($keywords,15,false);
  $keywords = $db->escape($keywords);

  $contentdata = $db->fetch_all("SELECT pt.*, pt.id as id, pg.id as pageid, pg.title{$core->dblang} as pagetitle, pg.slug" 
  . "\n FROM posts AS pt"
  . "\n LEFT JOIN pages AS pg ON pg.id = pt.page_id"
  . "\n WHERE pt.title{$core->dblang} LIKE '%" . $keywords . "%' or pt.body{$core->dblang} LIKE '%" . $keywords . "%'"
  . "\n ORDER BY pg.id LIMIT 10");

  if ($db->numrows($db->query("SHOW TABLES LIKE 'mod_articles'"))) {
		 $articledata = $db->fetch_all("SELECT *" 
		. "\n FROM mod_articles"
		. "\n WHERE title{$core->dblang} LIKE '%" . $keywords . "%' or body{$core->dblang} LIKE '%" . $keywords . "%'"
		. "\n ORDER BY id LIMIT 10");
  } else {
	  $articledata = false;
  }
  
  if ($db->numrows($db->query("SHOW TABLES LIKE 'mod_digishop'"))) {
		 $digishopdata = $db->fetch_all("SELECT *" 
		. "\n FROM mod_digishop"
		. "\n WHERE title{$core->dblang} LIKE '%" . $keywords . "%' or body{$core->dblang} LIKE '%" . $keywords . "%'"
		. "\n ORDER BY id LIMIT 10");
  } else {
	  $digishopdata = false;
  }
  
  if ($db->numrows($db->query("SHOW TABLES LIKE 'mod_portfolio'"))) {
		 $portadata = $db->fetch_all("SELECT *" 
		. "\n FROM mod_portfolio"
		. "\n WHERE title{$core->dblang} LIKE '%" . $keywords . "%' or body{$core->dblang} LIKE '%" . $keywords . "%'"
		. "\n ORDER BY id LIMIT 10");
  } else {
	  $portadata = false;
  }

?>
<!-- Full Layout -->
<div class="row grid_24">
  <div id="page">
    <div id="searchdata">
      <?php if (!$keywords || strlen($keywords = trim($keywords)) == 0 || strlen($keywords) < 3):?>
      <h1><span><?php echo _SR_SEARCH;?></span></h1>
      <?php $core->msgAlert(_SR_SEARCH_EMPTY2,false);?>
      <?php elseif(!$contentdata and !$articledata and !$digishopdata and !$portadata):?>
      <h1><span><?php echo _SR_SEARCH;?></span></h1>
      <?php $core->msgAlert(_SR_SEARCH_EMPTY . $keywords . _SR_SEARCH_EMPTY1,false);?>
      <?php else:?>
      <h1><span><?php echo _SR_SEARCH2 . $keywords;?></span></h1>
      <?php $i = 0; $color1 = "search-even"; $color2 = "search-odd";;?>
      <?php foreach($contentdata as $cdata):?>
      <?php $link =($core->seo == 1) ? $core->site_url . '/' . sanitize($cdata['slug'],50) . '.html' : $link = $core->site_url . '/content.php?pagename=' . sanitize($cdata['slug'],50);?>
      <?php $i++;?>
      <div class="<?php echo(($i % 2 == 0) ? $color1 : $color2);?>"><a href="<?php echo $link;?>"><strong><?php echo $i.'. '.$cdata['title'.$core->dblang];?></strong></a>
        <div style="padding-left:10px"><?php echo cleanSanitize($cdata['body'.$core->dblang],300);?></div>
        <hr />
      </div>
      <?php endforeach;?>
      <?php unset($cdata,$link,$i,$contentdata);?>
      <?php if($articledata):?>
      <h1><span><?php echo getValue('title'.$core->dblang, 'modules', 'modalias = "articles"');?></span></h1>
      <?php $i = 0; $color1 = "search-even"; $color2 = "search-odd";;?>
      <?php foreach($articledata as $adata):?>
      <?php $link = ($core->seo == 1) ? $core->site_url . '/article/' . $adata['slug'] . '.html' : $core->site_url . '/modules.php?module=articles&amp;do=article&amp;artname=' . sanitize($adata['slug'],150);?>
      <?php $i++;?>
      <div class="<?php echo(($i % 2 == 0) ? $color1 : $color2);?>"><a href="<?php echo $link;?>"><strong><?php echo $i.'. '.$adata['title'.$core->dblang];?></strong></a>
        <div style="padding-left:10px"><?php echo cleanSanitize($adata['body'.$core->dblang],300);?></div>
        <hr />
      </div>
      <?php endforeach;?>
      <?php unset($adata,$link,$i,$articledata);?>
      <?php endif;?>
      <?php if($digishopdata):?>
      <h1><span><?php echo getValue('title'.$core->dblang, 'modules', 'modalias = "digishop"');?></span></h1>
      <?php $i = 0; $color1 = "search-even"; $color2 = "search-odd";;?>
      <?php foreach($digishopdata as $sdata):?>
      <?php $link = ($core->seo == 1) ? $core->site_url . '/digishop/' . $sdata['slug'] . '.html' : $core->site_url . '/modules.php?module=digishop&amp;do=digishop&amp;productname=' . sanitize($sdata['slug'],150);?>
      <?php $i++;?>
      <div class="<?php echo(($i % 2 == 0) ? $color1 : $color2);?>"><a href="<?php echo $link;?>"><strong><?php echo $i.'. '.$sdata['title'.$core->dblang];?></strong></a>
        <div style="padding-left:10px"><?php echo cleanSanitize($sdata['body'.$core->dblang],300);?></div>
        <hr />
      </div>
      <?php endforeach;?>
      <?php unset($sdata,$link,$i,$digishopdata);?>
      <?php endif;?>
      <?php if($portadata):?>
      <h1><span><?php echo getValue('title'.$core->dblang, 'modules', 'modalias = "portfolio"');?></span></h1>
      <?php $i = 0; $color1 = "search-even"; $color2 = "search-odd";;?>
      <?php foreach($portadata as $pdata):?>
      <?php $link = ($core->seo == 1) ? $core->site_url . '/portfolio/' . $pdata['slug'] . '.html' : $core->site_url . '/modules.php?module=portfolio&amp;do=portfolio&amp;productname=' . $pdata['slug'];?>
      <?php $i++;?>
      <div class="<?php echo(($i % 2 == 0) ? $color1 : $color2);?>"><a href="<?php echo $link;?>"><strong><?php echo $i.'. '.$pdata['title'.$core->dblang];?></strong></a>
        <div style="padding-left:10px"><?php echo cleanSanitize($pdata['body'.$core->dblang],300);?></div>
        <hr />
      </div>
      <?php endforeach;?>
      <?php unset($pdata,$link,$i,$portadata);?>
      <?php endif;?>
      <?php endif;?>
    </div>
  </div>
</div>
<!-- Full Layout /--> 