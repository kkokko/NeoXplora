<?php
namespace SkyCore;

class TSessionManager {

    private $db;

    public function __construct($db) {
      $this->db = $db;
    }

    public function login($email, $password, $isRemember = false) {
        $sql = "SELECT * FROM old_users where email=\"$email\"";
        $rs_login = $this->db->query($sql);

        if ($rs_login->num_rows == 1) {
            $rw_login_data = $rs_login->fetch_array();
            if (strcmp(trim(md5($password)), trim($rw_login_data['password'])) == 0) {
                $_SESSION[session_id()]['id'] = $rw_login_data['id'];
                $_SESSION[session_id()]['email'] = $rw_login_data['email'];
                $email_part = explode("@", $rw_login_data['email']);
                $_SESSION[session_id()]['username'] = $email_part[0];
                $_SESSION[session_id()]['wp_u_id'] = $rw_login_data['wp_u_id'];
                $_SESSION[session_id()]['level'] = $rw_login_data['level'];

                // Set cookie
                if ($isRemember) {
                    $expire = time() + 60 * 60 * 24 * 14;
                    //ini_set("session.cookie_domain", COOKIEDOMAIN);
                    setcookie('UserPassword', base64_encode($password), $expire);
                    setcookie('UserEmail', $rw_login_data['email'], $expire);
                } else {
                    setcookie("UserEmail", "", time() - 3600);
                    setcookie("UserPassword", "", time() - 3600);
                    unset($_COOKIE['UserPassword'], $_COOKIE['UserEmail']);
                }

                $credentials['user_login'] = $email_part[0];
                $credentials['user_password'] = $password;
                $credentials['remember'] = $isRemember;

                wp_signon($credentials);

                return true;
            } else {
                return "Password is not valid. Please try again.";
            }
        } else {
            return "User not found. Please try again.";
        }
    }

    public function loggedIn() {
        $loggedIn = isset($_SESSION, $_SESSION[session_id()]);
        return $loggedIn;
    }

    public function getUser() {
        if ($this->loggedIn())
            return $_SESSION[session_id()];
        else
            return null;
    }

    public function getUserId() {
        if ($this->loggedIn())
            return $_SESSION[session_id()]['id'];
        else
            return null;
    }

    public function getWPUserId() {
        if ($this->loggedIn())
            return $_SESSION[session_id()]['wp_u_id'];
        else
            return null;
    }

    public function getUserEmail() {
        if ($this->loggedIn())
            return $_SESSION[session_id()]['email'];
        else
            return null;
    }
    
    public function getUserLevel() {
        if ($this->loggedIn())
            return $_SESSION[session_id()]['level'];
        else
            return 0;
    }

    public function logout() {
        unset($_SESSION[session_id()]);
        $_SESSION = array();
        $this->startSession();
    }

    public function startSession() {
        session_set_cookie_params(0, '/', '.' . str_replace('www.', '', $_SERVER['HTTP_HOST']));
        session_start();
    }

    public function getUserName() {
        if ($this->loggedIn())
            return $_SESSION[session_id()]['username'];
        else
            return null;
    }

    public function updateUserSession() {
        $rs_user = $this->db->query("SELECT * FROM old_users WHERE id='" . $this->getUserId() . "'");
        $user = $rs_user->fetch_array();
        $_SESSION[session_id()]['email'] = $user['email'];
        $email_part = explode("@", $user['email']);
        $_SESSION[session_id()]['username'] = $email_part[0];
    }
    
    public function isAdmin() {
      if($this->loggedIn() && $_SESSION[session_id()]['level'] == 9)
        return true;
      else 
        return false;
    }

}

?>