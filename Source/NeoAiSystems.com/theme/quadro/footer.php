<?php
  /**
   * Footer
   *
   * @version $Id: footer.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>	
<!-- Footer -->
<footer id="footer" class="clearfix">
  <div class="row">
    <div class="fotter-wrap clearfix">
      <div class="col grid_16">
        <div class="copyright top10">Copyright &copy;<?php echo date('Y').' <a href="'.SITEURL.'/index.php">'.$core->site_name.'</a>';?> All Rights Reserved. | Powered by: CMS Pro! v <?php echo $core->version;?></div>
      </div>
      <div class="col grid_8">
        <div class="ficons top10 hide-phone"><a href="http://validator.w3.org/check/referer" target="_blank"><img src="<?php echo THEMEURL;?>/images/valid-xhtml.png" alt="Our website is valid XHTML 1.0 Transitional" /></a> <a href="<?php echo SITEURL;?>/index.php"><img src="<?php echo THEMEURL;?>/images/btn-home.png" alt="Home Page" /></a> <a href="<?php echo SITEURL;?>/sitemap.php"><img src="<?php echo THEMEURL;?>/images/btn-sitemap.png" alt="Sitemap" /></a> <a href="<?php echo SITEURL;?>/rss.php"><img src="<?php echo THEMEURL;?>/images/btn-atom.png" alt="Rss" /></a></div>
      </div>
    </div>
  </div>
</footer>
</div>
<!-- Footer /-->
<?php if($core->analytics):?>
<!-- Google Analytics --> 
<?php echo cleanOut($core->analytics);?> 
<!-- Google Analytics /-->
<?php endif;?>
</body></html>