<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of login
 *
 * @author Miki
 */
class models_login extends Model {

    public function indexModel() {


// Variable definition : Email, Password, and Token
        $loginEmail = $this->reg->clean->POST('email');
        $loginPassword = md5($this->reg->clean->POST('password'));
        $loginToken = $this->reg->clean->POST('token');

// If the token is not empty
        if (isset($_SESSION['token']) && isset($_SESSION['token_time']) && isset($loginToken)) {
// If the token in session, and sent with the connexion form are equal
            if ($_SESSION['token'] == $loginToken) {
// During of the token
                $timestamp_old = time() - (10 * 60);
// If the token has expiered 
                if ($_SESSION['token_time'] >= $timestamp_old) {
// If the cookie is not empty
                    if (!isset($_COOKIE['email']) OR !isset($_COOKIE['password'])) {

// Email selection 
                        $query = mysql_query("SELECT * from member WHERE mail = '$loginEmail'") or die("Impossible de sélectionner le pseudo : " . mysql_error());
                        if (mysql_num_rows($query) != 1) {
                            $data["error"]["email"] = "wrong email";
                            //return $data;
                        }


// Password selection with the good email, to see if the email OR the password is false. To know which one is false.
                        $query2 = mysql_query("SELECT iddumembre from member WHERE email_member = '$loginEmail' AND password_member = '$loginPassword'") or die("Impossible de sélectionner le pseudo : " . mysql_error());
                        if (mysql_num_rows($query2) == 1) {

                            $row = mysql_fetch_assoc($query2);

                            $data = $row;
                            // ID of the member in session 
                            $_SESSION['idmember'] = $data['iddumembre'];



                            $expire = 5 * 24 * 3600; //durée du cookie à 5 jours
                            $tempo_email = $loginEmail;
                            $tempo_password = $loginPassword;
                            setcookie('email', $tempo_email, time() + $expire);
                            setcookie('password', $tempo_password, time() + $expire);
                        } else {
                            $data["error"]["password"] = "wrong password";
                           // return $data;
                        }
                    } else {
                        $cookieEmail = $_COOKIE['email'];
                        $cookiePassword = $_COOKIE['password'];

                        $query = mysql_query("SELECT iddumembre from member WHERE email_member = '$cookieEmail' AND password_member = '$cookiePassword'") or die("Impossible de sélectionner le pseudo : " . mysql_error());
                        if (mysql_num_rows($query) == 1) {

                            $row = mysql_fetch_assoc($query);

                            $data = $row;
                            // ID of the member in session 
                            $_SESSION['idmember'] = $data['iddumembre'];
                        }
                       else {
                            $data["error"]["cookie"] = "wrong cookie";
                             setcookie('email', "", time() - 1000);
                            setcookie('password', "", time() - 1000);
                           // return $data;
                        }
                    }
                } else {
// Error in the global variable data
                    $data['error']['token'] = "D&eacute;lai insatisfaisant.";
                }
            } else {
                $data['error']['token'] = "Mauvais jeton d\'acc&egrave;s";
            }
        } else {
            $data['error']['token'] = "Jeton d\'acc&egrave;s inexistant";
        }
        return $data;
    }

}
?>