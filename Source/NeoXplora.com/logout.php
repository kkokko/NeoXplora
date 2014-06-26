<?php

include 'includes/config.php';

//$url = str_replace(UserSessionManager::GetUserCompanyAccessName().".", "", FULLBASE) . 'index.php';
UserSessionManager::Logout();

//require_once('blog/wp-blog-header.php');
wp_logout();

header('location: ' . FULLBASE);
?>