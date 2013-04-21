<?php

/**
 * Description of logout Model
 * Logout of the website : destroy the id_member in Session.
 * @author Quentin
 */
class models_logout extends Model {

    public function indexModel() {

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

}

?>