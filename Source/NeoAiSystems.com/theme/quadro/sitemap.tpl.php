<?php
  /**
   * Sitemap Template
   *
   * @version $Id: sitemap.tpl.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');

  $sitemap = $content->getSitemap();
?>
<?php ?>
<!-- Full Layout -->
<div class="row">
  <div id="page">
    <div class="box clearfix">
      <h1><span><?php echo $core->site_name."  - "._SM_SITE_MAP;?></span></h1>
      <p><?php print $core->site_name." "._SM_SITE_MAP_TITLE;?></p>
      <div class="col grid_8">
        <h3><?php echo _N_PAGES;?></h3>
        <ul>
          <?php foreach($sitemap as $row):?>
          <?php $url = ($core->seo)  ? SITEURL . '/' . $row['slug'].'.html' : SITEURL . '/content.php?pagename=' . $row['slug'];?>
          <li><a href="<?php echo $url;?>"><?php echo $row['pgtitle'];?></a></li>
          <?php endforeach;?>
          <?php unset($row);?>
        </ul>
      </div>
      <?php if($core->checkTable("mod_articles")):?>
      <?php $artrow = $content->getArticleSitemap();?>
      <div class="col grid_8">
        <h3><?php echo getValue("title" . $core->dblang, "modules", "modalias = 'articles'");?></h3>
        <ul>
          <?php foreach($artrow as $row):?>
          <?php $url = ($core->seo == 1) ? SITEURL . '/article/' . $row['slug'] . '.html' : SITEURL . '/modules.php?module=articles&amp;do=article&amp;artname=' . $row['slug'];?>
          <li><a href="<?php echo $url;?>"><?php echo $row['atitle'];?></a></li>
          <?php endforeach;?>
          <?php unset($row);?>
        </ul>
      </div>
      <?php endif;?>
      <?php if($core->checkTable("mod_digishop")):?>
      <?php $digirow = $content->getDigishopSitemap();?>
      <div class="col grid_8">
        <hr />
        <h3><?php echo getValue("title" . $core->dblang, "modules", "modalias = 'digishop'");?></h3>
        <ul>
          <?php foreach($digirow as $row):?>
          <?php $url = ($core->seo == 1) ? SITEURL . '/digishop/' . $row['slug'] . '.html' : SITEURL . '/modules.php?module=digishop&amp;do=digishop&amp;productname=' . $row['slug'];?>
          <li><a href="<?php echo $url;?>"><?php echo $row['dtitle'];?></a></li>
          <?php endforeach;?>
          <?php unset($row);?>
        </ul>
      </div>
      <?php endif;?>
      <?php if($core->checkTable("mod_portfolio")):?>
      <?php $digirow = $content->getPortfolioSitemap();?>
      <div class="col grid_8">
        <hr />
        <h3><?php echo getValue("title" . $core->dblang, "modules", "modalias = 'portfolio'");?></h3>
        <ul>
          <?php foreach($digirow as $row):?>
          <?php $url = ($core->seo == 1) ? SITEURL . '/portfolio/' . $row['slug'] . '.html' : SITEURL . '/modules.php?module=portfolio&amp;do=digishop&amp;productname=' . $row['slug'];?>
          <li><a href="<?php echo $url;?>"><?php echo $row['ptitle'];?></a></li>
          <?php endforeach;?>
          <?php unset($row);?>
        </ul>
      </div>
      <?php endif;?>
    </div>
  </div>
</div>
<!-- Full Layout /--> 