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

    public function init() {
        
    }

    function addAction() {

        if ($this->user->loggedin()) {
            $indexM = $this->loadModel("event");
            $index = $indexM->addEventModel();

            $this->loadView("header_co", "");
            $this->loadView("addevent", "");
            $this->loadView("footer", "");
        }
        else
            $this->redirect("index")->indexAction();
    }

    function displayAction() {
        global $_URL;

        if ($this->user->loggedin()) {

            $indexM = $this->loadModel("event");
            $index = $indexM->displayModel();

            $this->loadView("header_co", "");
            $this->loadView("displayevent", $index);
            $this->loadView("footer", "");
        }
        else
            $this->redirect("index")->indexAction();
    }

    function indexAction() {

//        $model = $this->loadModel("event");
//        $allEvents =  $model->allUserEvents();
//        
//        foreach ($allEvents as $idEvent) {
//            $events[$idEvent] = $model ->displayModel($idEvent);
//             
//        }
//         $this->loadView("displayevent", $events); 
    }

}

?>
