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
<head>
<?php echo $content->getMeta(); ?>
<script type="text/javascript">
var THEMEURL = "<?php echo MODTHEMEURL;?>";
var SITEURL = "<?php echo SITEURL;?>";
</script>
<?php $content->getThemeStyle();?>
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/jquery.js"></script>
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/tables.js"></script>
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/global.js"></script>
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/cycle.js"></script>
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/flex.js"></script>
<script type="text/javascript" src="<?php echo THEMEURL;?>/master.js"></script>
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/fancybox/jquery.fancybox.pack.js"></script>
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/fancybox/helpers/jquery.fancybox-media.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo SITEURL;?>/assets/fancybox/jquery.fancybox.css" media="screen" />
<?php if($core->eucookie):?>
<script type="text/javascript" src="<?php echo SITEURL;?>/assets/eu_cookies.js"></script>
<script type="text/javascript"> 
$(document).ready(function () {
    $("body").acceptCookies({
        position: 'top',
        notice: '<?php echo EU_NOTICE;?>',
        accept: '<?php echo EU_ACCEPT;?>',
        decline: '<?php echo EU_DECLINE;?>',
        decline_t: '<?php echo EU_DECLINE_T;?>',
        whatc: '<?php echo EU_W_COOKIES;?>'
    })
});
</script> 
<?php endif;?>
<?php $content->getPluginAssets();?>
<?php $content->getModuleAssets();?>
</head>
<body<?php $core->renderThemeBg();?>>
<div class="container"> 
  <!-- Header -->
  <header id="header" class="clearfix">
    <div class="row grid_24">
      <div class="top-menu clearfix">
        <?php if($core->show_lang):?>
        <div class="col grid_6"> 
          <!-- Langswitcher Start -->
          <div id="langswitch">
            <ul>
              <?php foreach($core->langList() as $lang):?>
              <?php if($core->language == $lang['flag']):?>
              <li class="lang-active"><a href="javascript:void(0);"><img src="<?php echo SITEURL;?>/lang/<?php echo $lang['flag'];?>.png" title="<?php echo $lang['name'];?>" alt=""/></a></li>
              <?php else:?>
              <li><a href="<?php echo SITEURL;?>" data-lang="<?php echo $lang['flag'];?>" class="langchange" title="<?php echo $lang['name'];?>"><img src="<?php echo SITEURL;?>/lang/<?php echo $lang['flag'];?>.png" alt=""/></a></li>
              <?php endif?>
              <?php endforeach;?>
            </ul>
          </div>
          <!--/ Langswitcher End --> 
        </div>
        <?php endif;?>
        <div class="col grid_<?php echo ($core->show_lang and $core->showlogin) ? 18 : 24;?>"> 
        <?php if($core->showlogin):?>
          <!-- Login Start -->
          <div class="login-menu flright">
          <?php if($user->logged_in):?>
          <strong><?php echo _WELCOME;?>:</strong>&nbsp; <a href="<?php echo SITEURL;?>/account.php"><?php echo $user->username;?></a>
          <?php if ($user->is_Admin()):?>
          &bull; <a href="<?php echo SITEURL;?>/admin">Admin Panel</a>
          <?php endif;?>
          &bull;
          <?php if($user->fbid):?>
          <a href="https://www.facebook.com/logout.php?next=<?php echo SITEURL;?>/logout.php&amp;access_token=<?php echo $_SESSION['fb_token'];?>"><?php echo _N_LOGOUT;?></a>
          <?php else:?>
          <a href="<?php echo SITEURL;?>/logout.php"><?php echo _N_LOGOUT;?></a>
          <?php endif;?>
          <?php else:?>
          <strong><?php echo _WELCOME;?> <?php echo $user->username;?></strong> &nbsp; &nbsp; <a href="<?php echo SITEURL;?>/register.php"><?php echo _UA_REGISTER;?></a> &bull; <a href="<?php echo SITEURL;?>/login.php"><?php echo _UA_LOGIN;?></a> &bull; <a href="<?php echo SITEURL;?>/login.php"><?php echo _UA_TITLE3;?></a>
          <?php endif;?>
          </div>
          <!--/ Login End --> 
         <?php endif;?>
        </div>
      </div>
    </div>

    <div class="row grid_24">
      <div class="sub-header clearfix">
        <div class="col grid_<?php echo ($core->showsearch) ? 16 : 24;?>">
          <div class="logo">
          <a href="<?php echo SITEURL;?>/index.php"><?php echo ($core->logo) ? '<img src="'.SITEURL.'/uploads/'.$core->logo.'" alt="'.$core->company.'" />': $core->company;?></a> </div>
        </div>
      
      <?php if($core->showsearch):?>
        <!-- Livesearch Start -->
        <div class="col grid_8">
          <div id="search-box" class="flright">
            <form action="<?php echo SITEURL;?>/search.php" method="post" name="search-form">
              <input name="keywords" type="text" id="inputString" onclick="disAutoComplete(this);" />
            </form>
            <div id="suggestions"></div>
          </div>
        </div>
        <!--/ Livesearch End --> 
        <?php endif;?>
      </div>
    </div>
    
    <!-- Main Menu -->
    <div class="row clearfix">
      <nav id="mainmenu" class="col grid_24">
      <div class="menu-mobile-wrapper">
          <a id="menu-mobile-trigger"><span></span></a>
      </div>
        <?php $mainmenu = $content->getMenuList(); $content->getMenu($mainmenu,0);?>
      </nav>
      <!-- Main Menu  /--> 
    </div>
    
  </header>
  <!-- Header /--> 
  </div>