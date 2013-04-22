<?php

class controllers_lostpassword extends Controller {

    function indexAction() {

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
