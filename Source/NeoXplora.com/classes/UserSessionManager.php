<?php

class UserSessionManager {

    public static function Login($email, $password, $isRemember = false) {
        $sql = "SELECT * FROM users where email=\"$email\"";
        $rs_login = mysql_query($sql);

        if (mysql_num_rows($rs_login) == 1) {
            $rw_login_data = mysql_fetch_array($rs_login, 1);
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

    public static function LoggedIn() {
        $loggedIn = isset($_SESSION, $_SESSION[session_id()]);
        return $loggedIn;
    }

    public static function GetUser() {
        if (self::LoggedIn())
            return $_SESSION[session_id()];
        else
            return null;
    }

    public static function GetUserId() {
        if (UserSessionManager::LoggedIn())
            return $_SESSION[session_id()]['id'];
        else
            return null;
    }

    public static function GetWPUserId() {
        if (UserSessionManager::LoggedIn())
            return $_SESSION[session_id()]['wp_u_id'];
        else
            return null;
    }

    public static function GetUserEmail() {
        if (UserSessionManager::LoggedIn())
            return $_SESSION[session_id()]['email'];
        else
            return null;
    }

    public static function Logout() {
        // Destroy cookie
        //ini_set("session.cookie_domain", COOKIEDOMAIN);
        /* setcookie("UserEmail", "", time() - 3600);
          setcookie("UserPassword", "", time() - 3600);
          unset($_COOKIE['UserPassword'], $_COOKIE['UserEmail']); */

        unset($_SESSION[session_id()]);
        //session_unregister('neoxplora');
        $_SESSION = array();
        self::StartSession();
    }

    public static function StartSession() {
        session_set_cookie_params(0, '/', '.' . str_replace('www.', '', $_SERVER['HTTP_HOST']));
        //session_set_cookie_params(0, '/', '.localhost');
        //session_name('neoxplora');
        session_start();
        //session_register('neoxplora');
    }

    public static function GetUserName() {
        if (UserSessionManager::LoggedIn())
            return $_SESSION[session_id()]['username'];
        else
            return null;
    }

    public static function UpdateUserSession() {
        $user = UserUtils::GetUserDetail(UserSessionManager::GetUserId());
        $_SESSION[session_id()]['email'] = $user['email'];
        $email_part = explode("@", $user['email']);
        $_SESSION[session_id()]['username'] = $email_part[0];
    }
    
    public static function IsAdmin() {
      if(self::LoggedIn() && $_SESSION[session_id()]['level'] == 9)
        return true;
      else 
        return false;
    }

}

?>