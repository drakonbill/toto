<?php

class controllers_login extends Controller {

    function indexAction() {

        $loginM = $this->loadModel("login");
        $login = $loginM->indexModel();
        $this->reg->login = $login; // <<<NEED TO CHANGE(temp. save data to registry for later use)

        if (!isset($_SESSION['id_member'])) {
            $this->loadView("header", "");
            $this->loadView("login", $login);
            $this->loadView("footer", "");
        }
        else
            $this->redirect("welcome")->indexAction();
    }

}

?>
