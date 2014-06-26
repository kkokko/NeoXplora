<?php
include 'includes/forgotpass.db.php';
if (UserSessionManager::LoggedIn())
    header('location: index.php');
$pagetitle = "Forgot Password";
include_once 'includes/header.php';
?>

<?php
if (isset($_SESSION['fpError']) && $_SESSION['fpError'] !== NULL) {
    ?>

    <script type="text/javascript">
        show_notify_message('Error', '<?php echo $_SESSION['fpError']; ?>', 'error', 'toast-top-right');
    </script>
    <?php
    unset($_SESSION['fpError']);
}
if (isset($_SESSION['fpMessage']) && $_SESSION['fpMessage'] !== NULL) {
    ?>

    <script type="text/javascript">
        show_notify_message('Success', '<?php echo $_SESSION['fpMessage']; ?>', 'success', 'toast-top-right');
    </script>
    <?php
    unset($_SESSION['fpMessage']);
}
?>
<div id="content">
    <div class="container relative">
        <form name="fpForm" method="post" action="">
            <div class="box forgotpass_box" id="login_box">
                <div class="logo"><img src="images/logo.png" alt="" border="0" /></div>
                <div class="form">
                    <input type="text" class="inpt_bx" placeholder="Email" id="txtEmail" name="txtEmail" value="<?php echo isset($_POST['txtEmail']) ? $_POST['txtEmail'] : ''; ?>" />
                    <input type="submit" value="" class="sbt_btn hover send" />

                    <div style="clear: both;"></div>
                    <div class="link">
                        <a href="login.php">Login</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<?php
include_once 'includes/footer.php';
?>