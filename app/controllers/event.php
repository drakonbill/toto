  <?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of event
 *
 * @author Deixonne
 */
class controllers_event extends Controller {
    
    function indexAction() {
        $user = $this->user;
        if($user->loggedin()) {
            $indexM = $this->loadModel("event");
            $index = $indexM->addEventModel();


            //load widgets data ( from models, if they need it)
            //$this->loadmWidgets(array("hello","last10"));
            //pack some data
            //$index["token"] = @$reg->token;

            //load views

            $this->loadView("header_co", "");
            $this->loadView("addevent", "");
            $this->loadView("footer", "");
        }
        else 
              $this->redirect ("index")->indexAction();
 
    }
    
    
    
}

?>
