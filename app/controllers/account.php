<?php

class controllers_account extends Controller {

// No IndexAction
    function indexAction() {
        
    }

// Login method
    function login() {

        $loginM = $this->loadModel("login");
        $login = $loginM->indexModel();
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
    function logout() {

        $logoutM = $this->loadModel("logout");
        $logout = $logoutM->indexModel();

        header('Location: /index');
    }

// Lost password method

    function lostpassword() {

//$user = $this->reg->user;
//load model and fill data with
        $indexM = $this->loadModel("lostpassword");
        $index = $indexM->indexModel();

//load views
        $this->loadView("header", "");
        $this->loadView("lostpassword", $index);
        $this->loadView("footer", "");
    }

}

?>
