<?php

class controllers_account extends Controller {

// No IndexAction
    function indexAction() {
        header('Location: /index'); // meetoparty/account will redirect on index
    }

// Login method
    function loginAction() {

        $loginM = $this->loadModel("account");
        $login = $loginM->login();
        $this->reg->login = $login;

        if (!isset($_SESSION['id_member'])) {
            $this->loadView("header", "");
            $this->loadView("login", $login);
            $this->loadView("footer", "");
        }
        else
            header('Location: /welcome');
    }

// Logout method 
    function logoutAction() {

        $logoutM = $this->loadModel("account");
        $logout = $logoutM->logout();

        header('Location: /index');
    }

// Lost password method

    function lostpasswordAction() {

//$user = $this->reg->user;
//load model and fill data with
        $indexM = $this->loadModel("account");
        $index = $indexM->lostpassword();

//load views
        $this->loadView("header", "");
        $this->loadView("lostpassword", $index);
        $this->loadView("footer", "");
    }

    // Validation of the registration 
    function validationregistrationAction() {
        
        $indexM = $this->loadModel("account");
        $index = $indexM->validationregistration();
        
        $this->loadView("header", "");
        $this->loadView("validationregistration", $index);
        $this->loadView("footer", "");
    }

}

?>
