<?php

class controllers_index extends Controller {

    public function init() {
        
    }

    function indexAction() {

        $user = $this->reg->user;

        // To clean cookies 
        $user->clean_cookie();

        if ($user->loggedin()) {
            header('Location: /actuality');
        } else {
            //load model and fill data with
            $indexM = $this->loadModel("index");
            $index = $indexM->indexModel();

            //load views
            $this->loadView("header", "");
            $this->loadView("index", $index);
            $this->loadView("footer", "");
        }
    }

}

?>
