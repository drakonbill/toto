<?php

/**
 * Description of welcome
 *
 * @author Miki
 */
class controllers_news extends Controller {

    public function init() {
        
    }

    function indexAction() {
        //load welcome model/view
        // echo " THIS IS WELCOME PAGE!!!! <br/> ";
        $welcomeM = $this->loadModel("news");
        $welcome = $welcomeM->indexModel();

        $this->loadView("header_co", "");
        $this->loadView("news", $welcome);
        $this->loadView("footer", "");
    }

}

?>
