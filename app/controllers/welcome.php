<?php

/**
 * Description of welcome
 *
 * @author Miki
 */
class controllers_welcome extends Controller{
    //put your code here
     function indexAction() {
        //load welcome model/view
        // echo " THIS IS WELCOME PAGE!!!! <br/> ";
        $welcomeM = $this->loadModel("welcome");
        $welcome = $welcomeM->indexModel();
        
        $this->loadView("header", "Welcome");
        $this->loadView("welcome", $welcome);
        $this->loadView("footer", "");
    }
}

?>
