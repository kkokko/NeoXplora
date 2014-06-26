<div class="sign_in">
  <?php if ($this->logged_in) { ?>
    <a href="<?php echo $this->site_url; ?>changepass.php"><?php echo $this->username; ?></a> <span style="color: #fff;">|</span> 
    <?php if($this->userlevel == 'admin') { ?>
      <a href="<?php echo $this->site_url; ?>panel.php">Admin Panel</a> <span style="color: #fff;">|</span>
    <?php } ?>
    <a href="<?php echo $this->site_url; ?>logout.php">Sign out</a>
  <?php } else { ?>
    <a href="<?php echo $this->site_url; ?>login.php">Sign in</a>
  <?php } ?>
</div>