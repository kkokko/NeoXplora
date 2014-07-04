<?php

class UserUtils {

    public static function IsEmailExist($email) {
        $rs_email = mysql_query("SELECT * FROM old_users WHERE email=\"$email\"");
        if (mysql_num_rows($rs_email) == 1) {
            return true;
        }
        return false;
    }

    public static function GetUserDetail($id) {
        $rs_user = mysql_query("SELECT * FROM old_users WHERE id=\"$id\"");

        return mysql_fetch_array($rs_user, 1);
    }
    
    public static function Register($email, $password){
        $pass = md5($password);
        if (mysql_query("INSERT INTO old_users (`email`, `password`) VALUES ('$email', '$pass')"))
            return array('msg' => true, 'last_id' => mysql_insert_id());
        else 
            return "Error in register new user process.";
    }

}

?>