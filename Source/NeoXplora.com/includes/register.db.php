<?php

if (stripos($_SERVER["PHP_SELF"], "/register.db.php") > 0)
    die("Restricted access");

include_once dirname(__FILE__) . '/config.php';
if (count($_POST) > 0) {
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

    if (isset($_POST['txtEmail']) && isset($_POST['password'])) {
        $isReg = UserUtils::Register($_POST['txtEmail'], $_POST['password']);

        if ($isReg === true) {
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
                    $isLogin = UserSessionManager::Login($_POST['txtEmail'], $_POST['password'], 0);

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