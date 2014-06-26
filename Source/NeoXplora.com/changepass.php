<?php
include 'includes/changepass.db.php';
if (!UserSessionManager::LoggedIn())
    header('location: login.php');
$pagetitle = "Change Password";
include_once 'includes/header.php';
?>

<?php
$errorflag = false;
if (isset($_SESSION['cpError']) && $_SESSION['cpError'] !== NULL) {
    $errorflag = true;
    ?>

    <script type="text/javascript">
        show_notify_message('Error', '<?php echo $_SESSION['cpError']; ?>', 'error', 'toast-top-right');
    </script>
    <?php
    unset($_SESSION['cpError']);
}
if (isset($_SESSION['cpMessage']) && $_SESSION['cpMessage'] !== NULL) {
    ?>

    <script type="text/javascript">
        show_notify_message('Success', '<?php echo $_SESSION['cpMessage']; ?>', 'success', 'toast-top-right');
    </script>
    <?php
    unset($_SESSION['cpMessage']);
}
?>
<div id="content">
    <div class="container relative">
        <form name="cpForm" method="post" action="">
            <div class="box changepass_box" id="login_box">
                <div class="logo"><img src="images/logo.png" alt="" border="0" /></div>
                <div class="form">
                    <input type="password" class="inpt_bx" placeholder="Old Password" type="password" id="pass" name="pass" value="<?php echo isset($_POST['pass']) ? $_POST['pass'] : ''; ?>" />
                    <input type="password" class="inpt_bx" placeholder="New Password" type="password" id="pass1" name="pass1" value="<?php echo isset($_POST['pass1']) ? $_POST['pass1'] : ''; ?>" />
                    <input type="password" class="inpt_bx" placeholder="Confirm Password" type="password" id="pass2" name="pass2" value="<?php echo isset($_POST['pass2']) ? $_POST['pass2'] : ''; ?>" />
                    <div style="display: block;">
                        <input type="button" value="" class="sbt_btn hover cancel" style="margin-left: 5px;" onclick="window.location.href='index.php';" />
                        <input type="submit" value="" class="sbt_btn hover save" />
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
