<div id="footer">
    <?php
    if (strpos($_SERVER['PHP_SELF'],'admin') === false) {
	?>
    <div class="chat_box" style="display:none;">
        <textarea class="textarea" name="chat_text" id="chat_text"></textarea>
        <input type="button" id="chat_button" class="replay" value="" disabled="true" />    
    </div>
    <div id="open_chat"><img src="<?php echo FULLBASE; ?>images/chat_icon.png" /></div>
    <div id="close_chat"><img src="<?php echo FULLBASE; ?>images/close.png" /></div>
    <?php } ?>
    <div class="copy"> &copy; Neo AI Systems P/L 2014 All rights reserved.</div>
</div>
</body>
</html>
