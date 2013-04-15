<?php

class controllers_index extends Controller {

    function indexAction() {

        $user = $this->reg->user;
        if ($user->loggedin()) {
            $this->logedAction();
        } else {
            //load model and fill data with
            $indexM = $this->loadModel("index");
            $index = $indexM->indexModel();


            //load widgets data ( from models, if they need it)
            //$this->loadmWidgets(array("hello","last10"));
            //pack some data
            //$index["token"] = @$reg->token;
            //load views
            $this->loadView("index", $index);
        }
    }

    function logedAction() {
        //load welcome model/view
        echo " THIS IS WELCOME PAGE!!!! <br/> ";
    }

    function loginAction() {
        $loginM = $this->loadModel("login");
        $login = $loginM->indexModel();
        //print_r($login);
        if (!isset($login['iddumembre'])) {
            $this->reg->login = $login;
            $this->indexAction();
        }
        else
            $this->logedAction();
    }

}

?>
