<div class="nav">
  <a href="#" class="menu"><span></span><span></span><span></span></a>
  <ul>
    <li>
      <a href="<?php echo $this->site_url; ?>index.php" class="search <?php echo (strtolower($this->page) == 'search') ? 'active' : '' ?>">
        <span>&nbsp;</span>
      </a>
    </li>
    <li>
      <a href="<?php echo $this->site_url; ?>news.php" class="news <?php echo (strtolower($this->page) == 'news') ? 'active' : '' ?>">
        <span>&nbsp;</span>
      </a>
    </li>
    <?php if($this->logged_in && (strtolower($this->page) == 'train')) { ?>
    <li>
      <a href="<?php echo $this->site_url; ?>train.php" class="train <?php echo (strtolower($this->page) == 'train') ? 'active' : '' ?>">
        <span>&nbsp;</span>
      </a>
    </li>                        
    <?php } ?>
    <?php /*
    <li>
      <a href="<?php echo FULLBASE; ?>learn.php" class="learn <?php echo (strtolower($title) == 'learn') ? 'active' : '' ?>">
        <span>&nbsp;</span>
      </a>
    </li>
    <li>
      <a href="<?php echo FULLBASE; ?>desktop.php" class="desktop <?php echo (strtolower($title) == 'desktop') ? 'active' : '' ?>">
        <span>&nbsp;</span>
      </a>
    </li>
    */ ?>
  </ul>
</div>