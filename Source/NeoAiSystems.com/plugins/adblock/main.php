<?php
  /**
   * AdBlock Plugin
   *
   * @package CMS Pro
   * @author wojoscripts.com
   * @copyright 2012
   * @version $Id: main.php, v1.00 2012-12-24 12:12:12 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');

  if (class_exists('AdBlock')) {
	$adblock = new AdBlock();	
  } else {
	  require(WOJOLITE . "admin/modules/adblock/admin_class.php");
	  $adblock = new AdBlock();	
  }
  
  $adblock->adblockid = ###ADBLOCKID###;
  $ad = $adblock->getSingle();
  
  $ad_content = '';
  $fname = 'na';
 
  if($ad)
  {
	$fname = '';
  	$memberlevels = explode(',',$ad['memberlevels']);
  	
  	//check credentials
  	if(is_array($memberlevels) && count($memberlevels))
  	{
  	  if(in_array($user->userlevel, $memberlevels))
  	  {
  	  	if($adblock->isOnline($ad))
  	  	{
  	  		$fname = 'f_' . sha1(md5(rand() . time())).md5(sha1($adblock->adblockid));
  	  		$href=(strpos($ad['banner_image_link'],'http://') === 0)?$ad['banner_image_link']:'http://' . $ad['banner_image_link'];
  	  		$ad_content = ($ad['banner_image'])? ('<a href="' . $href . '" onclick="' . $fname . '()" title="' . $ad['banner_image_alt'] . '"><img src="' . SITEURL . '/' . $adblock->imagepath . $ad['banner_image'] . '" alt="' . $ad['banner_image_alt'] . '" /></a>'):cleanOut($ad['banner_html']);
  	  		$adblock->incrementViewsNumber();
  	  	}	
  	  }			
  	}	
  	
  }	
?>
<?php if($ad):?>
<!-- Start AdBlock Campaign -->
<div class="adblock-campaign" style="text-align:center"><?php echo $ad_content?></div>
<script type="text/javascript">
var <?php echo $fname?>_clicked = false;

function <?php echo $fname?>()
{
	if(<?php echo $fname?>_clicked) return;
	$.ajax({
		  type: 'POST',
		  url: '<?php echo SITEURL?>/modules/adblock/controller.php?adC=<?php echo $adblock->adblockid?>&f=<?php echo $fname?>',
		});
	<?php echo $fname?>_clicked = true;
}			
</script>
<!-- End AdBlock Campaign /-->
<?php endif;?>