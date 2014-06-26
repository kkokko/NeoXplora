<?php
  /**
   * Vertical Menu
   *
   * @version $Id: main.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<!-- Start Vertical Menu -->
<div id="vmenunav"><?php $content->getMenu($mainmenu,0, "vmenu");?></div>
<!-- End Vertical Menu /-->

<script type="text/javascript"> 
// <![CDATA[
$(document).ready(function () {
	$("ul#vmenu").find('ul.menu-submenu').parent().addClass('li-submenu').append('<span class="li-sub-arrow"></span>');
	$('#vmenu span.li-sub-arrow').click(function () {
		$(this).siblings('#vmenu ul.menu-submenu').slideToggle();
	});
});
// ]]>
</script>