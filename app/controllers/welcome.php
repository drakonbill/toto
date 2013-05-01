<?php

/**
 * Description of welcome
 *
 * @author Miki
 */
class controllers_welcome extends Controller {

    public function init() {
        
    }

    function indexAction() {
        //load welcome model/view
        // echo " THIS IS WELCOME PAGE!!!! <br/> ";
        $welcomeM = $this->loadModel("welcome");
        $welcome = $welcomeM->indexModel();

        $this->loadView("header_co", "");
        $this->loadView("welcome", $welcome);
        $this->loadView("footer", "");
    }

}

?>
