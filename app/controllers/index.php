<?php

class controllers_index extends Controller {

    function indexAction() {

        $user = $this->reg->user;
        if ($user->loggedin()) {
            $controller = new controllers_welcome();
            $controller->indexAction();
            $this->reg->controler = $controller;
        } else {
            //load model and fill data with
            $indexM = $this->loadModel("index");
            $index = $indexM->indexModel();


            //load widgets data ( from models, if they need it)
            //$this->loadmWidgets(array("hello","last10"));
            //pack some data
            //$index["token"] = @$reg->token;
            //load views
            $this->loadView("header", "");
            $this->loadView("index", $index);
            $this->loadView("footer", "");
        }
    }

    

    function loginAction() {
        $loginM = $this->loadModel("login");
        $login = $loginM->indexModel();
        //print_r($login);
        if (!isset($login['id_member'])) {
            $this->reg->login = $login;
            $this->indexAction();
        }
        else
            $controller = new controllers_welcome();
            $controller->indexAction();
            $this->reg->controler = $controller;
            //$this->logedAction();
            //header('Location: '.$this->reg->appconf['url']);
    }

}

?>
