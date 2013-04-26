<?php

class models_account extends Model {

// No need for the moment
    public function indexModel() {
        
    }

// Login Model
    public function login() {

// Variable definition : Email, Password, and Token
        $loginEmail = $this->reg->clean->POST('email');
// I want to include the hacher function in the user lib ... But don't know how to do that 
        $loginPassword = $this->reg->clean->POST('password');
        $password = $this->reg->user->hacher($loginPassword); //md5(sha1(PREFIXE) . $loginPassword  . sha1(SUFFIXE));
        $loginToken = $this->reg->clean->POST('token');

// For the coockie : var memoriser, if it's check : cookies on, if not, no cookies.

        $logincookie = $this->reg->clean->POST('memoriser');

// If the token is not empty
        if (isset($_SESSION['token']) && isset($_SESSION['token_time']) && isset($loginToken)) {
// If the token in session, and sent with the connexion form are equal
            if ($_SESSION['token'] == $loginToken) {
// During of the token
                $timestamp_old = time() - (10 * 60);
// If the token has expiered 
                if ($_SESSION['token_time'] >= $timestamp_old) {
// If the cookie is not empty
                    if (!isset($_COOKIE['email_member']) OR !isset($_COOKIE['password_member'])) {

// Email selection 
                        $query = mysql_query("SELECT * from member WHERE email_member = '$loginEmail'") or die("Impossible de sélectionner le pseudo : " . mysql_error());
                        if (mysql_num_rows($query) != 1) {
                            $data["error"]["email"] = "L'adresse email que vous avez entré est inconnue.";
                        }

// Password selection with the good email, to see if the email OR the password is false. To know which one is false.
                        $query2 = mysql_query("SELECT id_member from member WHERE email_member = '$loginEmail' AND password_member = '$password'") or die("Impossible de sélectionner le pseudo : " . mysql_error());
                        if (mysql_num_rows($query2) == 1) {

                            $row = mysql_fetch_assoc($query2);

                            $data = $row;

                            if ($_SESSION['account_locked'] < 10) {
// ID of the member in session 
                                $_SESSION['id_member'] = $data['id_member'];
                            } else {
                                $data["error"]["locked"] = "A cause d'un grand nombre de tentatives, votre session est temporairement bloquée.";
                            }
// cookies 
                            if ($logincookie == 1) {
                                $expire = 7 * 24 * 3600; //durée du cookie à 7 jours
                                $tempo_email = $loginEmail;
                                $tempo_password = $password;
                                setcookie('email_member', $tempo_email, time() + $expire);
                                setcookie('password_member', $tempo_password, time() + $expire);
                                setcookie('logout', 1, time() + $expire);
                            }
                        } else {
                            $data["error"]["password"] = "Le mot de passe que vous avez entré n'est pas correct.";
                            // Installation of locked account with sessions if the member aptent 10 times
                            if (!isset($_SESSION['account_locked'])) {
                                $_SESSION['account_locked'] = 1;
                            }

                            if ((isset($_SESSION['account_locked'])) && ($_SESSION['account_locked'] < 10)) {
                                $_SESSION['account_locked'] = $_SESSION['account_locked'] + 1;
                            }
                            if ((isset($_SESSION['account_locked'])) && ($_SESSION['account_locked'] == 10)) {
                                $data["error"]["locked"] = "A cause d'un grand nombre de tentatives, votre session est temporairement bloquée.";
                            }
                        }
                    } else {
                        $cookieEmail = $_COOKIE['email_member'];
                        $cookiePassword = $_COOKIE['password_member'];

                        $query = mysql_query("SELECT id_member from member WHERE email_member = '$cookieEmail' AND password_member = '$cookiePassword'") or die("Impossible de sélectionner le pseudo : " . mysql_error());
                        if (mysql_num_rows($query) == 1) {

                            $row = mysql_fetch_assoc($query);

                            $data = $row;
// ID of the member in session 
                            $_SESSION['id_member'] = $data['id_member'];
                        } else {
                            $data["error"]["cookie"] = "wrong cookie";
                            setcookie('email_member', "", time() - 1000);
                            setcookie('password_member', "", time() - 1000);

                            if (!isset($_SESSION['account_locked'])) {
                                $_SESSION['account_locked'] = 1;
                            }

                            if ((isset($_SESSION['account_locked'])) && ($_SESSION['account_locked'] < 10)) {
                                $_SESSION['account_locked'] = $_SESSION['account_locked'] + 1;
                            }
                            if ((isset($_SESSION['account_locked'])) && ($_SESSION['account_locked'] == 10)) {
                                $data["error"]["locked"] = "A cause d'un grand nombre de tentatives, votre session est temporairement bloquée.";
                            }
                        }
                    }
                } else {
// Error in the global variable data
                    $data['error']['token'] = "La connexion a expirée. Veuillez vous identifier de nouveau.";
                }
            } else {
                $data['error']['token'] = "Un problème est arrivé durant la connexion. Veuillez réessayer.";
                header('Location: /index');
            }
        } else {
            $data['error']['token'] = "Jeton d'accès inexistant.";
            header('Location: /index');
        }
        return $data;
    }

    // Logout Model

    public function logout() {

        // Unset id of the member in session
        unset($_SESSION['id_member']);

        // Destroying the cookie
        if ((!empty($_COOKIE['email_member'])) && (!empty($_COOKIE['password_member']))) {
            setcookie('email_member', null, time() - 3600, '/');
            setcookie('password_member', null, time() - 3600, '/');
            // unset($_COOKIE['email_member']);
            // unset($_COOKIE['password_member']);
        }
    }

    public function lostpassword() {
        
    }

}

?>