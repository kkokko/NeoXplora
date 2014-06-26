<?php
  /**
   * Header
   *
   * @version $Id: header.php, v2.00 2011-04-20 10:12:05 gewa Exp $
   */
  
  if (!defined("_VALID_PHP"))
      die('Direct access to this location is not allowed.');
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $core->company;?></title>
<script type="text/javascript">
var IMGURL = "<?php echo ADMINURL; ?>/images";
var ADMINURL = "<?php echo ADMINURL; ?>";
</script>
<link href="assets/style.css" rel="stylesheet" type="text/css" />
<link href="../assets/redmond/jquery-ui.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../assets/jquery.js"></script>
<script type="text/javascript" src="../assets/jquery-ui.js"></script>
<script type="text/javascript" src="../assets/tooltip.js"></script>
<script type="text/javascript" src="../assets/global.js"></script>
<script type="text/javascript" src="assets/master.js"></script>
<script type="text/javascript" src="editor/scripts/innovaeditor.js"></script>
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/fancybox/jquery.fancybox.pack.js"></script>
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/fancybox/helpers/jquery.fancybox-media.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITEURL;?>/assets/fancybox/jquery.fancybox.css" media="screen" />
<link rel="stylesheet" href="assets/tree.css" type="text/css" media="screen" />
<script type="text/javascript" src="assets/jquery.tree.js"></script>
<?php 
if(file_exists("plugins/".sanitize(get("plug"))."/style.css"))
echo "<link href=\"plugins/".sanitize(get("plug"))."/style.css\" rel=\"stylesheet\" type=\"text/css\" />\n";
if(file_exists("plugins/".sanitize(get("plug"))."/script.js"))
echo "<script type=\"text/javascript\" src=\"plugins/".sanitize(get("plug"))."/script.js\"></script>\n";
if(file_exists("modules/".sanitize(get("mod"))."/style.css"))
echo "<link href=\"modules/".sanitize(get("mod"))."/style.css\" rel=\"stylesheet\" type=\"text/css\" />\n";
if(file_exists("modules/".sanitize(get("mod"))."/script.js"))
echo "<script type=\"text/javascript\" src=\"modules/".sanitize(get("mod"))."/script.js\"></script>\n";
?>
</head>
<body>
<div class="container">
<!-- Header -->
  <header id="header" class="clearfix">
    <div class="logo"><a href="index.php"><?php echo ($core->logo) ? '<img src="'.SITEURL.'/uploads/'.$core->logo.'" alt="'.$core->company.'" />': $core->company;?></a></div>
    <div class="top-menu">
      <div class="usermenu">
        <ul>
          <li class="welcome"><strong><?php echo _WELCOME.' '.$user->username;?>!</strong></li>
          <li><a href="../index.php" title="<?php echo _N_VIEWS;?>" class="gohome"><?php echo _N_VIEWS;?></a></li>
          <?php if($user->getAcl("Backup")):?>
          <!--<li><a href="index.php?do=backup" title="<?php echo _N_BACK;?>" class="backup"><?php echo _N_BACK;?></a></li>-->
          <?php endif;?>
          <?php if($user->getAcl("FM")):?>
       <!--   <li><a href="index.php?do=filemanager" title="<?php echo _N_FM;?>" class="fm"><?php echo _N_FM;?></a></li>-->
          <?php endif;?>
          <?php if($user->getAcl("events")):?>
          <!--<li><a href="index.php?do=modules&amp;action=config&amp;mod=events" title="<?php echo $core->countEvents() . ' ' . _N_EVENTS;?>" class="events"><?php echo $core->countEvents();?></a></li>-->
          <?php endif;?>
          <?php if($user->getAcl("System")):?>
          <!--<li><a href="index.php?do=system" title="<?php echo _N_SYSTEM;?>" class="system"><?php echo _N_FM;?></a></li>-->
          <?php endif;?>
         <!-- <li class="langswitch"><a href="javascript:void(0);" onClick="$('.dolang').slideToggle('fast')"><img src="<?php echo SITEURL;?>/lang/<?php echo $core->language;?>.png" alt="" /></a>
          <span class="dolang">
			<?php foreach($core->langList() as $lang):?>
            <?php if($core->language == $lang['flag']):?>
            <a href="javascript:void(0);" class="lang-active"><img src="<?php echo SITEURL;?>/lang/<?php echo $lang['flag'];?>.png" alt="" title="<?php echo $lang['name'];?>"/></a>
            <?php else:?>
            <a href="index.php?<?php echo $_SERVER['QUERY_STRING'];?>" data-lang="<?php echo $lang['flag'];?>" class="langchange"><img src="<?php echo SITEURL;?>/lang/<?php echo $lang['flag'];?>.png" alt="" title="<?php echo $lang['name'];?>"/></a>
            <?php endif?>
            <?php endforeach;?>
          </span>
          </li>-->
          <li>
            <?php if($user->fbid):?>
            <a href="https://www.facebook.com/logout.php?next=<?php echo SITEURL;?>/logout.php&amp;access_token=<?php echo $_SESSION['fb_token'];?>" class="logout"><?php echo _N_LOGOUT;?></a>
            <?php else:?>
            <a href="logout.php" title="<?php echo _N_LOGOUT;?>" class="logout"><?php echo _N_LOGOUT;?></a>
            <?php endif;?>
          </li>
        </ul>
      </div>
    </div>
  </header>
<!-- Header /--> 
  <div id="topmenu">
    <ul id="topnav">
      <?php if($user->getAcl("Menus")):?><li><a href="index.php?do=menus"><?php echo _N_MENUS;?></a></li><?php endif;?>
      <?php if($user->getAcl("Pages")):?><li><a href="index.php?do=pages"><?php echo _N_PAGES;?></a></li><?php endif;?>
     <!-- <?php if($user->getAcl("Posts")):?><li><a href="index.php?do=posts"><?php echo _N_POSTS;?></a> </li><?php endif;?>
      <?php if($user->getAcl("Modules")):?><li><a href="index.php?do=modules"><?php echo _N_MODS;?></a></li><?php endif;?>
      <?php if($user->getAcl("Plugins")):?><li><a href="index.php?do=plugins"><?php echo _N_PLUGS;?></a></li><?php endif;?>
      <?php if($user->getAcl("Memberships")):?>
      <li><a href="javascript:void(0);"><?php echo _N_MEMBS;?></a>
        <ul>
          <?php if($user->getAcl("Memberships")):?><li><a href="index.php?do=memberships"><?php echo _N_MEMBSET;?></a></li><?php endif;?>
          <?php if($user->getAcl("Gateways")):?><li><a href="index.php?do=gateways"><?php echo _N_GATES;?></a></li><?php endif;?>
          <?php if($user->getAcl("Transactions")):?><li><a href="index.php?do=transactions"><?php echo _N_TRANS;?></a></li><?php endif;?>
        </ul>
      </li>
      <?php endif;?>
      <?php if($user->getAcl("Layout")):?><li><a href="index.php?do=layout"><?php echo _N_LAYS;?></a></li><?php endif;?>-->
      <?php if($user->getAcl("Users")):?><li><a href="index.php?do=users"><?php echo _N_USERS;?></a></li><?php endif;?>
      <!--<?php if($user->getAcl("Configuration")):?>
      <li><a href="javascript:void(0);"><?php echo _N_CONF;?></a>
        <ul>
          <?php if($user->getAcl("Configuration")):?><li><a href="index.php?do=config"><?php echo _CG_TITLE1;?></a></li><?php endif;?>
          <?php if($user->getAcl("Templates")):?> <li><a href="index.php?do=templates"><?php echo _N_EMAILS;?></a></li><?php endif;?>
          <?php if($user->getAcl("Newsletter")):?><li><a href="index.php?do=newsletter"><?php echo _N_NEWSL;?></a></li><?php endif;?>
          <?php if($user->getAcl("Language")):?><li><a href="index.php?do=language"><?php echo _N_LANGS;?></a></li><?php endif;?>
          <?php if($user->getAcl("Maintenance")):?><li><a href="index.php?do=maintenance"><?php echo _N_SMTCN;?></a></li><?php endif;?>
          <?php if($user->getAcl("Logs")):?><li><a href="index.php?do=logs"><?php echo _N_LOGS;?></a></li><?php endif;?>
        </ul>
      </li>-->
      <?php endif;?>
    </ul>
    <div class="clear"></div>
  </div>