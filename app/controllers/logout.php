<?php

class controllers_logout extends Controller {

    function indexAction() {

        $logoutM = $this->loadModel("logout");
        $logout = $logoutM->indexModel();
       
    }
}

?>
