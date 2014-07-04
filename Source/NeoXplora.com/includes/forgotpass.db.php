<?php

if (stripos($_SERVER["PHP_SELF"], "/forgotpass.db.php") > 0)
    die("Restricted access");

include_once dirname(__FILE__) . '/config.php';

if (count($_POST) > 0) {
    $email_class = new EmailAddressValidator();
    if (isset($_POST['txtEmail']) && $_POST['txtEmail'] == '') {
        $_SESSION['fpError'] = "Please enter email";
        return;
    } else if ($email_class->check_email_address($_POST['txtEmail']) === false) {
        $_SESSION['fpError'] = "Email is not valid";
        return;
    }

    if (isset($_POST['txtEmail'])) {
        $rs_email = mysql_query("SELECT * FROM old_users WHERE email=\"$_POST[txtEmail]\"");
        if (mysql_num_rows($rs_email)) {
            $rw_email = mysql_fetch_array($rs_email, 1);

            $random_chars = "";
            $characters = array(
                "A", "B", "C", "D", "E", "F", "G", "H", "J", "K", "L", "M",
                "N", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z",
                "1", "2", "3", "4", "5", "6", "7", "8", "9");

            $keys = array();

            while (count($keys) < 10) {
                $x = mt_rand(0, count($characters) - 1);
                if (!in_array($x, $keys)) {
                    $keys[] = $x;
                }
            }

            foreach ($keys as $key) {
                $random_chars .= $characters[$key];
            }
            $code = md5($random_chars);

            $rs_auth = mysql_query("SELECT * FROM `auth` WHERE userid=$rw_email[id]");
            if (mysql_num_rows($rs_auth) > 0) {
                mysql_query("UPDATE `auth` SET code='$code' WHERE userid=$rw_email[id]");
            } else {
                mysql_query("INSERT INTO `auth` (userid, code) values ($rw_email[id], '$code')");
            }
            if (file_exists(ROOT . 'email_tpl/forgotpassword.html')) {
                $handle = fopen(ROOT . 'email_tpl/forgotpassword.html', "r");
                $body = fread($handle, filesize(ROOT . 'email_tpl/forgotpassword.html'));
                fclose($handle);
                $to = "$rw_email[email]";
                $subject = "Password Reset";
                $email_part = explode("@", $rw_email['email']);
                $body = str_replace("{{USERNAME}}", $email_part[0], $body);
                $body = str_replace("{{RESETLINK}}", FULLBASE . 'resetpassword.php?' . $code, $body);
                $body = str_replace("{{SITEADMINEMAIL}}", MAILER_EMAIL, $body);
                $body = str_replace("{{FULLBASE}}", FULLBASE, $body);
                $body = str_replace("{{IP}}", $_SERVER['REMOTE_ADDR'], $body);

                // To send HTML mail, the Content-type header must be set
                $headers = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                // Additional headers
                $headers .= 'From: ' . MAILER_EMAIL . "\r\n";
                $headers .= "Reply-To:  " . MAILER_REPLY_TO . "\r\n";
                if (mail($to, $subject, $body, $headers)) {
                    $_SESSION['message'] = "Reset password link has been sent in mail.";
                    header('location: ' . FULLBASE . 'index.php');
                    die;
                } else {
                    $_SESSION['fpError'] = "Mail is not send";
                    return;
                }
            } else {
                $_SESSION['fpError'] = "Reset password template is not found.";
                return;
            }
        } else {
            $_SESSION['fpError'] = "User is not found";
            return;
        }
    }
}
?>