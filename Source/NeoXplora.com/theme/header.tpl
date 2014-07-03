<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $this->pageTitle; ?> - Neo Xplora</title>
    <link href="<?php echo $this->site_url; ?>style/style.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $this->site_url; ?>style/responsive.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $this->site_url; ?>fonts/font.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $this->site_url; ?>style/toastr.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo $this->site_url; ?>favicon.ico" rel="SHORTCUT ICON" />
    <script src="<?php echo $this->site_url; ?>js/jquery-1.9.1.min.js"></script>
    <script src="<?php echo $this->site_url; ?>js/jquery-ui-1.10.4.min.js"></script>
    <script src="<?php echo $this->site_url; ?>NeoShared/SkyJs/skyjs.js"></script>
    <script src="<?php echo $this->site_url; ?>js/toastr.min.js"></script>
    <script src="<?php echo $this->site_url; ?>js/main.js"></script>
    <?php echo $this->headerinclude; ?>
  </head>

  <body>
    <div id="header">
      <div class="container-holder" style="position: relative;">
        <?php echo $this->fetch("nav", "header"); ?>
        <?php echo $this->fetch("sign_in", "header"); ?>
        <?php echo $this->fetch("right_box", "header"); ?>
      </div>
    </div>
    <div id="content">
      <div class="container relative <?php echo $this->extra_classes; ?>">
        