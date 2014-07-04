<?php
include 'includes/resetpassword.db.php';
if (UserSessionManager::LoggedIn())
    header('location: index.php');
$title = "Reset Password";
if ($_SERVER['QUERY_STRING'] != '') {
    $rs_auth = mysql_query("SELECT userid from old_auth WHERE code=\"$_SERVER[QUERY_STRING]\"");
    if (mysql_num_rows($rs_auth) == 0) {
        $_SESSION['rpError'] = "Reset password link is not valid";
    }
} else {
    $_SESSION['rpError'] = "Reset password link is not valid";
}
include_once 'includes/header.php';
?>

<?php
if (isset($_SESSION['rpError']) && $_SESSION['rpError'] !== NULL) {
    ?>

    <script type="text/javascript">
        show_notify_message('Error', '<?php echo $_SESSION['rpError']; ?>', 'error', 'toast-top-right');
    </script>
    <?php
    unset($_SESSION['rpError']);
}
?>
<div id="content">
    <div class="container relative">
        <form name="rpForm" method="post" action="">
            <div class="login_box" id="login_box">
                <div class="logo"><img src="images/logo.png" alt="" border="0" /></div>
                <div class="form">
                    <input type="password" class="inpt_bx" placeholder="New Password" type="password" id="pass1" name="pass1" value="<?php echo isset($_POST['pass1']) ? $_POST['pass1'] : ''; ?>" />
                    <input type="password" class="inpt_bx" placeholder="Confirm Password" type="password" id="pass2" name="pass2" value="<?php echo isset($_POST['pass2']) ? $_POST['pass2'] : ''; ?>" />
                    <div style="display: block;">
                        <input type="submit" value="" class="sbt_btn save" />
                        <div style="clear: both;"></div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<?php
include_once 'includes/footer.php';
?>