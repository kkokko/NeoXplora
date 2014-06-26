<?php
  /**
   * Mainmenu
   *
   * @version $Id: mainmenu.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<ul id="nav">
  <?php if($user->getAcl("Menus")):?>
  <li><a href="index.php?do=menus" class="<?php if ($core->do == 'menus') echo "active";?>"><img src="images/icons/menus-ico.png" alt="" /><?php echo _N_MENUS;?></a></li>
  <?php endif;?>
  <?php if($user->getAcl("Pages")):?>
  <li><a href="index.php?do=pages" class="<?php if ($core->do == 'pages') echo "active";?>"><img src="images/icons/pages-ico.png" alt="" /><?php echo _N_PAGES;?></a></li>
  <?php endif;?>
  <?php /*if($user->getAcl("Posts")):?>
  <li><a href="index.php?do=posts" class="<?php if ($core->do == 'posts') echo "active";?>"><img src="images/icons/posts-ico.png" alt="" /><?php echo _N_POSTS;?></a> </li>
  <?php endif;*/?>
  <?php /*if($user->getAcl("Modules")):?>
  <li><a href="index.php?do=modules" class="<?php if ($core->do == 'modules') echo "active";?>"><img src="images/icons/modules-ico.png" alt="" /><?php echo _N_MODS;?></a></li>
  <?php endif;?>
  <?php if($user->getAcl("Plugins")):?>
  <li><a href="index.php?do=plugins" class="<?php if ($core->do == 'plugins') echo "active";?>"><img src="images/icons/plugins-ico.png" alt="" /><?php echo _N_PLUGS;?></a></li>
  <?php endif;*/?>
  <?php /*if($user->getAcl("Memberships")):?>
  <li><a href="javascript:void(0);" class="<?php echo ($core->do == 'memberships' or $core->do == 'gateways' or $core->do == 'transactions') ? "expanded" : "collapsed";?>"><img src="images/icons/membership-ico.png" alt="" /><?php echo _N_MEMBS;?><span>...</span></a>
    <ul class="subnav">
      <?php if($user->getAcl("Memberships")):?>
      <li><a href="index.php?do=memberships" class="<?php if ($core->do == 'memberships') echo "active";?>"><?php echo _N_MEMBSET;?></a></li>
      <?php endif;?>
      <?php if($user->getAcl("Gateways")):?>
      <li><a href="index.php?do=gateways" class="<?php if ($core->do == 'gateways') echo "active";?>"><?php echo _N_GATES;?></a></li>
      <?php endif;?>
      <?php if($user->getAcl("Transactions")):?>
      <li><a href="index.php?do=transactions" class="<?php if ($core->do == 'transactions') echo "active";?>"><?php echo _N_TRANS;?></a></li>
      <?php endif;?>
    </ul>
  </li>
  <?php endif;*/?>
  <?php /*if($user->getAcl("Layout")):?>
  <li><a href="index.php?do=layout" class="<?php if ($core->do == 'layout') echo "active";?>"><img src="images/icons/layout-ico.png" alt="" /><?php echo _N_LAYS;?></a></li>
  <?php endif;*/?>
  <?php if($user->getAcl("Users")):?>
  <li><a href="index.php?do=users" class="<?php if ($core->do == 'users') echo "active";?>"><img src="images/icons/user-ico.png" alt="" /><?php echo _N_USERS;?></a></li>
  <?php endif;?>
  <?php /*if($user->getAcl("Configuration")):?>
  <li><a href="javascript:void(0);" class="<?php echo ($core->do == 'config' or $core->do == 'templates' or $core->do == 'newsletter' or $core->do == 'language' or $core->do == 'maintenance' or $core->do == 'logs') ? "expanded" : "collapsed";?>"><img src="images/icons/config-ico.png" alt="" /><span>...</span><?php echo _N_CONF;?></a>
    <ul class="subnav">
      <?php if($user->getAcl("Configuration")):?>
      <li><a href="index.php?do=config" class="<?php if ($core->do == 'config') echo "active";?>"><?php echo _CG_TITLE1;?></a></li>
      <?php endif;?>
      <?php if($user->getAcl("Templates")):?>
      <li><a href="index.php?do=templates" class="<?php if ($core->do == 'templates') echo "active";?>"><?php echo _N_EMAILS;?></a></li>
      <?php endif;?>
      <?php if($user->getAcl("Newsletter")):?>
      <li><a href="index.php?do=newsletter" class="<?php if ($core->do == 'newsletter') echo "active";?>"><?php echo _N_NEWSL;?></a></li>
      <?php endif;?>
      <?php if($user->getAcl("Language")):?>
      <li><a href="index.php?do=language" class="<?php if ($core->do == 'language') echo "active";?>"><?php echo _N_LANGS;?></a></li>
      <?php endif;?>
      <?php if($user->getAcl("Maintenance")):?>
      <li><a href="index.php?do=maintenance" class="<?php if ($core->do == 'maintenance') echo "active";?>"><?php echo _N_SMTCN;?></a></li>
      <?php endif;?>
      <?php if($user->getAcl("Logs")):?>
      <li><a href="index.php?do=logs" class="<?php if ($core->do == 'logs') echo "active";?>"><?php echo _N_LOGS;?></a></li>
      <?php endif;?>
    </ul>
  </li>
  <?php endif;*/?>
</ul>