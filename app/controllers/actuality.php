<?php

/**
 * Description of welcome
 *
 * @author Miki
 */
class controllers_actuality extends Controller {

    public function init() {
        $this->setLevel('indexAction', 1);
        // $this->role->UnVerif->add('indexAction');
    }

    function indexAction() {

        $welcomeM = $this->loadModel("actuality");
        $welcome = $welcomeM->indexModel();

        $this->loadView("header_co", "");
        $this->loadView("actuality", $welcome);
        $this->loadView("footer", "");
    }

}

?>
