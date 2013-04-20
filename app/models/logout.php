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
        setcookie("email_member", "", time() - 3600);
        setcookie("password_member", "", time() - 3600);
        
        
    }

}

?>