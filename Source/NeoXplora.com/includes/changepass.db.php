<?php

if (stripos($_SERVER["PHP_SELF"], "/changepass.db.php") > 0)
    die("Restricted access");

include_once dirname(__FILE__) . '/config.php';
include_once 'blog/wp-includes/user.php';

if (count($_POST) > 0) {
    if (isset($_POST['pass']) && trim($_POST['pass']) == '') {
        $_SESSION['cpError'] = "Please enter old password";
        return;
    }else if (isset($_POST['pass1']) && trim($_POST['pass1']) == '') {
        $_SESSION['cpError'] = "Please enter new password";
        return;
    } else if (isset($_POST['pass2']) && trim($_POST['pass2']) == '') {
        $_SESSION['cpError'] = "Please enter confirm new password";
        return;
    } else if ($_POST['pass1'] != $_POST['pass2']) {
        $_SESSION['cpError'] = "Confirm password is not match";
        return;
    } else if ($_POST['pass'] == $_POST['pass1']) {
        $_SESSION['cpError'] = "Old password and new password is same";
        return;
    }
    
    $id = UserSessionManager::GetUserId();
    $user = UserUtils::GetUserDetail($id);
    
    if ($user['password'] != md5($_POST['pass'])){
        $_SESSION['cpError'] = "Old password is not match";
        return;
    }
    $pass = md5($_POST['pass1']);
    if (mysql_query("UPDATE old_users SET password=\"$pass\" WHERE id=$id")) {
        $_SESSION['cpMessage'] = "Your password has been changes";
        wp_set_password( $_POST['pass1'], UserSessionManager::GetWPUserId() );
    }
    
    if (isset($_COOKIE['UserPassword']) && isset($_COOKIE['UserEmail'])) {
        $expire = time() + 60 * 60 * 24 * 14;
        setcookie('UserPassword', base64_encode($_POST['pass1']), $expire);
    }
}
?>