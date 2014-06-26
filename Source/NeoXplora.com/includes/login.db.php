<?php

if (stripos($_SERVER["PHP_SELF"], "/login.db.php") > 0)
    die("Restricted access");

include_once dirname(__FILE__) . '/config.php';

//require_once('blog/wp-blog-header.php');
require_once('blog/wp-includes/registration.php');

// Cookie set
/*if (!UserSessionManager::LoggedIn() && isset($_COOKIE['UserPassword']) && isset($_COOKIE['UserEmail'])) {
    $isLogin = UserSessionManager::Login($_COOKIE['UserEmail'], base64_decode($_COOKIE['UserPassword']), 1);
    if ($isLogin === true) {
        header('location: ' . FULLBASE . 'index.php');
        die;
    }
}*/

if (count($_POST) > 0 && isset($_POST['login_btn'])) {
    if (isset($_POST['txtEmail']) && $_POST['txtEmail'] == '') {
        $_SESSION['loginError'] = "Please enter email";
        return;
    } else if (isset($_POST['password']) && $_POST['password'] == '') {
        $_SESSION['loginError'] = "Please enter password";
        return;
    }

    if (isset($_POST['txtEmail']) && isset($_POST['password'])) {
        $isLogin = UserSessionManager::Login($_POST['txtEmail'], $_POST['password'], isset($_POST['chkRememberMe']) ? $_POST['chkRememberMe'] : 0);

        if ($isLogin === true) {
            if(isset($_GET['action'])){
              header('location: '.FULLBASE.$_GET['action'].'.php');
            } else { 
              header('location: ' . FULLBASE . 'index.php');
            }
            die;
        } else {
            $_SESSION['loginError'] = $isLogin;
            return;
        }
    }
}

if (count($_POST) > 0 && isset($_POST['register_btn'])) {
    $email_class = new EmailAddressValidator();
    if (isset($_POST['txtEmail']) && $_POST['txtEmail'] == '') {
        $_SESSION['regError'] = "Please enter email";
        return;
    } else if (isset($_POST['password']) && trim($_POST['password']) == '') {
        $_SESSION['regError'] = "Please enter password";
        return;
    } else if (isset($_POST['password2']) && trim($_POST['password2']) == '') {
        $_SESSION['regError'] = "Please enter confirm password";
        return;
    } else if ($_POST['password'] != $_POST['password2']) {
        $_SESSION['regError'] = "Confirm password is not match";
        return;
    } else if ($email_class->check_email_address($_POST['txtEmail']) === false) {
        $_SESSION['regError'] = "Email is not valid";
        return;
    } else if (UserUtils::IsEmailExist($_POST['txtEmail']) === true) {
        $_SESSION['regError'] = "Email already exist";
        return;
    }
    
    if(email_exists($_POST['txtEmail'])){
        $_SESSION['regError'] = "Email already exist";
        return;
    }

    if (isset($_POST['txtEmail']) && isset($_POST['password'])) {
        $isReg = UserUtils::Register($_POST['txtEmail'], $_POST['password']);

        if (is_array($isReg) && $isReg['msg'] === true) {
            if (file_exists(ROOT . 'email_tpl/register.html')) {
                $handle = fopen(ROOT . 'email_tpl/register.html', "r");
                $body = fread($handle, filesize(ROOT . 'email_tpl/register.html'));
                fclose($handle);
                $to = "$_POST[txtEmail]";
                $subject = "Welcome to NeoXplora";
                $email_part = explode("@", $_POST['txtEmail']);
                $body = str_replace("{{USERNAME}}", $email_part[0], $body);
                $body = str_replace("{{SITEADMINEMAIL}}", MAILER_EMAIL, $body);
                $body = str_replace("{{FULLBASE}}", FULLBASE, $body);
                $body = str_replace("{{EMAIL}}", $_POST['txtEmail'], $body);
                $body = str_replace("{{PASSWORD}}", $_POST['password'], $body);

                // To send HTML mail, the Content-type header must be set
                $headers = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                // Additional headers
                $headers .= 'From: ' . MAILER_EMAIL . "\r\n";
                $headers .= "Reply-To:  " . MAILER_REPLY_TO . "\r\n";
                if (mail($to, $subject, $body, $headers)) {
                    $_SESSION['message'] = "Your account has been create successfully";

                    $email_part = explode("@", $_POST['txtEmail']);
                    $newusername = $email_part[0];
                    $user_id = wp_create_user($newusername, $_POST['password'], $_POST['txtEmail']);
                    if (is_int($user_id)) {
                        $wp_user_object = new WP_User($user_id);
                        $wp_user_object->set_role('subscriber');
                        mysql_query("UPDATE users SET wp_u_id=$user_id WHERE id=$isReg[last_id]");
                    }

                    $isLogin = UserSessionManager::Login($_POST['txtEmail'], $_POST['password'], isset($_POST['chkRememberMe']) ? $_POST['chkRememberMe'] : 0);
                    if ($isLogin === true) {
                        header('location: ' . FULLBASE . 'index.php');
                        die;
                    } else {
                        $_SESSION['regError'] = $isLogin;
                        return;
                    }
                    die;
                } else {
                    $_SESSION['regError'] = "Mail is not send";
                    return;
                }
            } else {
                $_SESSION['regError'] = "Your account has been create successfully.";
                return;
            }
        } else {
            $_SESSION['regError'] = $isReg;
            return;
        }
    }
}
?>