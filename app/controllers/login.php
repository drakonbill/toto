<?php

class controllers_login extends Controller {

    function indexAction() {

        $loginM = $this->loadModel("login");
        $login = $loginM->indexModel();
        $this->reg->login = $login; // <<<NEED TO CHANGE(temp. save data to registry for later use)
       
        if (!isset($_SESSION['id_member'])) {
            $this->redirect ("index")->indexAction();
//            $controller = new controllers_index();
//            $controller->indexAction();
//            $this->reg->controler = $controller;
        }
        else
            $this->redirect ("welcome")->indexAction();
//            $controller = new controllers_welcome();
//              $controller->indexAction();
//              $this->reg->controler = $controller;
            
     //        header('Location: /welcome');
            
    }

}

?>
