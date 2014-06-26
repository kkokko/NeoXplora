<?php
include 'includes/login.db.php';
if (UserSessionManager::LoggedIn()){
  if(isset($_GET['action'])){
    header('location: '.$_GET['action'].'.php');
  } else { 
    header('location: index.php');
  }
}
$pagetitle = "Login";
include_once 'includes/header.php';
if (isset($_SESSION['loginError']) && $_SESSION['loginError'] !== NULL) {
    ?>
    <script type="text/javascript">
        show_notify_message('Error', '<?php echo $_SESSION['loginError']; ?>', 'error', 'toast-top-right');
    </script>
    <?php
    unset($_SESSION['loginError']);
}
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
        <form name="loginForm" method="post" action="login.php<?php if(isset($_GET['action'])) { echo '?action='.$_GET['action']; }?>">
            <div class="box login_box" id="login_box">
                <div class="logo"><img src="images/NeoXploraLOGO.png" alt="" border="0" /></div>
                <div class="form">
                    <input type="text" class="inpt_bx" placeholder="Email" id="txtEmail" name="txtEmail" value="<?php echo isset($_POST['txtEmail']) ? $_POST['txtEmail'] : (isset($_COOKIE['UserEmail']) ? $_COOKIE['UserEmail'] : ''); ?>" />
                    <input type="password" class="inpt_bx" placeholder="Password" type="password" id="password" name="password" value="<?php echo isset($_COOKIE['UserPassword']) ? base64_decode($_COOKIE['UserPassword']) : ""; ?>" />
                    <input type="submit" value="" id="login_btn"  name="login_btn" class="sbt_btn login" disabled="true"/>
                    <div style="clear: both;"></div>
                    <label style="margin-top: 10px;"><input class="fancy-checkbox" type="checkbox" name="chkRememberMe" value="1" /> Remember me</label>
                    <div class="link">
                        <a href="forgotpass.php">Forgot Password</a>
                    </div>
                    <hr style="margin: 15px 0 10px 0;" />
                    <div style=" margin-bottom: 10px; margin-left: 72%; text-align: center;">
                        <label style="width: 115px;"> OR </label>
                    </div>
                    <input type="password" class="inpt_bx" placeholder="Confirm password" type="password" id="password2" name="password2" value="" />
                    <input type="submit" value="" id="register_btn" name="register_btn" class="sbt_btn singup" disabled="true" />
                    <div style="clear: both;"></div>
                </div>
            </div>
        </form>
    </div>
</div>
<?php
include_once 'includes/footer.php';
?>