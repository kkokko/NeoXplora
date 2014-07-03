<?php
$title = isset($pagetitle) ? $pagetitle : "";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Neo Xplora | Smart web search</title>
        <link href="<?php echo FULLBASE; ?>style/style.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo FULLBASE; ?>style/responsive.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo FULLBASE; ?>fonts/font.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo FULLBASE; ?>style/toastr.min.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo FULLBASE; ?>favicon.ico" rel="SHORTCUT ICON" />
        <script src="<?php echo FULLBASE; ?>js/jquery-1.9.1.min.js"></script>
        <script src="<?php echo FULLBASE; ?>js/toastr.min.js"></script>
        <script src="<?php echo FULLBASE; ?>js/main.js"></script>
    </head>

    <body>
        <div id="header">
            <div class="container-holder" style="position: relative;">
                <div class="nav">
                    <a href="#" class="menu"><span></span><span></span><span></span></a>
                    <ul>
                        <li>
                            <a href="<?php echo FULLBASE; ?>index.php" class="search <?php echo (strtolower($title) == 'search') ? 'active' : '' ?>">
                                <span>&nbsp;</span>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo FULLBASE; ?>news.php" class="news <?php echo (strtolower($title) == 'news') ? 'active' : '' ?>">
                                <span>&nbsp;</span>
                            </a>
                        </li>
                        <?php if( (UserSessionManager::LoggedIn() || is_user_logged_in()) && (strtolower($title) == 'train') ){ ?>
                        <li>
                            <a href="<?php echo FULLBASE; ?>train.php" class="train <?php echo (strtolower($title) == 'train') ? 'active' : '' ?>">
                                <span>&nbsp;</span>
                            </a>
                        </li>                        
                        <?php } ?>
                     <?php /*   <li>
                            <a href="<?php echo FULLBASE; ?>learn.php" class="learn <?php echo (strtolower($title) == 'learn') ? 'active' : '' ?>">
                                <span>&nbsp;</span>
                            </a>
                        </li>
						<li>
                            <a href="<?php echo FULLBASE; ?>desktop.php" class="desktop <?php echo (strtolower($title) == 'desktop') ? 'active' : '' ?>">
                                <span>&nbsp;</span>
                            </a>
                        </li>
*/						
?>
                     
                    </ul>
                </div>
                <div class="sign_in">
                    <?php
                    if (UserSessionManager::LoggedIn() || is_user_logged_in()) {
                         $current_user = wp_get_current_user();
                         if (isset($current_user->data) && isset($current_user->data->user_login)){
                             $username = $current_user->data->user_login;
                         }else{
                             $username = UserSessionManager::GetUserName();
                         }
                        ?>
                        <a href="<?php echo FULLBASE; ?>changepass.php"><?php echo $username; ?></a> <span style="color: #fff;">|</span> 
                        <?php if(UserSessionManager::IsAdmin()) { ?>
                          <a href="<?php echo FULLBASE; ?>panel.php">Admin Panel</a> <span style="color: #fff;">|</span>
                        <?php } ?>
                        <a href="<?php echo FULLBASE; ?>logout.php">Sign out</a>
                        <?php
                    } else {
                        ?>
                        <a href="<?php echo FULLBASE; ?>login.php">Sign in</a>
                    <?php } ?>
                </div>
                <?php 
				if(strtolower($title) != 'news')

				if (RIGHT_BOX) { ?>
                    <div class="help">
                        <a href="<?php echo FULLBASE; ?>blog/">Blog</a><br />
                        <a href="<?php echo FULLBASE; ?>blog/?p=8">Welcome message</a><br />
                        <a href="<?php echo FULLBASE; ?>train.php">Help us train Neo</a><br />
                        <?php if (UserSessionManager::LoggedIn()) { ?> 	 	
	                        <a href="<?php echo FULLBASE; ?>admin/newsParser.php">News parsers</a> 	 	
	                        <?php } ?> 
                    </div>
                <?php } ?>
            </div>
        </div>
