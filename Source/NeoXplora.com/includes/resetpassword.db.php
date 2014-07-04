<?php

if (stripos($_SERVER["PHP_SELF"], "/resetpassword.db.php") > 0)
    die("Restricted access");

include_once dirname(__FILE__) . '/config.php';

if (count($_POST) > 0) {
    if ($_SERVER['QUERY_STRING'] != '') {
        $rs_auth = mysql_query("SELECT userid from old_auth WHERE code=\"$_SERVER[QUERY_STRING]\"");
        if (mysql_num_rows($rs_auth) == 0) {
            $_SESSION['rpError'] = "Reset password link is not valid";
            return;
        } else {
            if (isset($_POST['pass1']) && trim($_POST['pass1']) == '') {
                $_SESSION['rpError'] = "Please enter password";
                return;
            } else if (isset($_POST['pass2']) && trim($_POST['pass2']) == '') {
                $_SESSION['rpError'] = "Please enter confirm password";
                return;
            } else if ($_POST['pass1'] != $_POST['pass2']) {
                $_SESSION['rpError'] = "Confirm password is not match";
                return;
            } else if ($_POST['pass'] == $_POST['pass1']) {
                $_SESSION['cpError'] = "Old password and new password is same";
                return;
            }
            $rw_auth = mysql_fetch_array($rs_auth);
            $pass = md5($_POST['pass1']);
            if (mysql_query("UPDATE old_users SET password=\"$pass\" WHERE id=$rw_auth[userid]")) {
                mysql_query("DELETE FROM old_auth WHERE code=\"$_SERVER[QUERY_STRING]\"");
                $_SESSION['message'] = "Your password has been changes";
                header('location: ' . FULLBASE . 'index.php');
                die;
            }
        }
    } else {
        $_SESSION['rpError'] = "Reset password link is not valid";
        return;
    }
}
?>