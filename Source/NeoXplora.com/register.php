<?php
header('location: login.php');
die;
include 'includes/register.db.php';
if (UserSessionManager::LoggedIn())
    header('location: index.php');
$pagetitle = "Register";
include_once 'includes/header.php';
?>

<?php
if (isset($_SESSION['regError']) && $_SESSION['regError'] !== NULL) {
    ?>

    <script type="text/javascript">
        show_notify_message('Error', '<?php echo $_SESSION['regError']; ?>', 'error', 'toast-top-right');
    </script>
    <?php
    unset($_SESSION['regError']);
}
?>
<div id="content">
    <div class="container relative">
        <form name="regForm" method="post" action="">
            <div class="login_box" id="login_box">
                <div class="logo"><img src="images/logo.png" alt="" border="0" /></div>
                <div class="form">
                    <input type="text" class="inpt_bx" placeholder="Email" id="txtEmail" name="txtEmail" value="<?php echo isset($_POST['txtEmail']) ? $_POST['txtEmail'] : ''; ?>" />
                    <input type="password" class="inpt_bx" placeholder="Password" type="password" id="password" name="password" value="" />
                    <input type="password" class="inpt_bx" placeholder="Password" type="password" id="password2" name="password2" value="" />
                    <div style="display: block;">
                        <input type="submit" value="" class="sbt_btn singup" />
                        <div style="clear: both;"></div>
                    </div>
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

