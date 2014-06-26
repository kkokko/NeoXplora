<?php
  /**
   * Latest Twitts
   *
   * @version $Id: main.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
  
  require_once(WOJOLITE . "admin/plugins/twitts/admin_class.php");
  $twitt = new latestTwitts();
?>
<!-- Start Latest Twitts -->
<?php if($twitt->username):?>
<div id="twitt"> <img src="<?php echo SITEURL;?>/plugins/twitts/images/loading.gif" alt="Loading" class="loading" /> </div>
<div id="twitt-nav">
  <div class="next"></div>
  <div class="prev"></div>
  <br class="clear" />
</div>
<script type="text/javascript">
// <![CDATA[
    getTwitters('twitt', {
        id: '<?php echo $twitt->username;?>',
        callback: cycleTwitts,
        clearContents: true,
        count: <?php echo $twitt->counter;?>,
        withFriends: false,
        ignoreReplies: false,
        template: '<span class="status"><?php if($twitt->show_image):?><img src="%user_profile_image_url%" align="left" class="avatar"/><?php endif;?>"%text%"</span> <span class="time"><a href="http://twitter.com/%user_screen_name%/statuses/%id_str%">%time%</a></span>'
    });

    function cycleTwitts() {
        $("#twitt ul").cycle({
            fx: 'scrollDown',
            speed: <?php echo $twitt->speed;?>,
            timeout: <?php echo $twitt->timeout;?>,
            next: '#twitt-nav .next',
            prev: '#twitt-nav .prev'
        });
    }
// ]]>
</script>
<?php endif;?>
<!-- End Latest Twitts /-->