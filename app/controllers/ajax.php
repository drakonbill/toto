<?php

/**
 * Description of ajax
 *
 * @author Miki
 */
class controllers_ajax extends Controller {

    public function init() {
        
    }
    
    public function indexAction() {
        //if you wish to call siteurl/ajax only
    }

    /* *******************************************************************
     *  Inscription
     * ***************************************************************** */

    function inscriptionAction() {
        $indexM = $this->loadModel("ajax_inscription");
        $return = $indexM->inscription();

        $this->loadView("ajax", $return);
    }

    function inscription2Action() {
        $indexM = $this->loadModel("ajax_inscription");
        $return = $indexM->inscription2();

        $this->loadView("ajax", $return);
    }

    /* *******************************************************************
     *  Member
     * ***************************************************************** */

    function verifMemberAction() {
        $indexM = $this->loadModel("ajax_member");
        $return = $indexM->verifMember();

        $this->loadView("ajax", $return);
    }

    function registerImageMemberAction() {
        $indexM = $this->loadModel("ajax_member");
        $return = $indexM->registerImage();

        $this->loadView("ajax", $return);
    }

    function registerImageProfilMemberAction() {
        $indexM = $this->loadModel("ajax_member");
        $return = $indexM->registerImageProfil();

        $this->loadView("ajax", $return);
    }
    
    function addContactProfilAction() {
        $indexM = $this->loadModel("ajax_member");
        $return = $indexM->addContact();

        $this->loadView("ajax", $return);
    }

    /* *******************************************************************
     *  Passion
     * ***************************************************************** */

    function choicePassionAction() {
        $indexM = $this->loadModel("ajax_passion");
        $return = $indexM->choicePassion();

        $this->loadView("ajax", $return);
    }

    function registerPassionAction() {
        $indexM = $this->loadModel("ajax_passion");
        $return = $indexM->registerPassion();

        $this->loadView("ajax", $return);
    }

    function categoryPassionAction() {
        $indexM = $this->loadModel("ajax_passion");
        $return = $indexM->categoryPassion();

        $this->loadView("ajax", $return);
    }

    function registerImagePassionAction() {
        $indexM = $this->loadModel("ajax_passion");
        $return = $indexM->registerImage();

        $this->loadView("ajax", $return);
    }

    function deletePassionAction() {
        $indexM = $this->loadModel("ajax_passion");
        $return = $indexM->deletePassion();

        $this->loadView("ajax", $return);
    }

    function registerPassionProfilAction() {
        $indexM = $this->loadModel("ajax_passion");
        $return = $indexM->registerPassionProfil();

        $this->loadView("ajax", $return);
    }

    /* *******************************************************************
     *  Event
     * ***************************************************************** */

    function addEventAction() {
        $indexM = $this->loadModel("ajax_event");
        $return = $indexM->addEvent();

        $this->loadView("ajax", $return);
    }
    
    function modifyEventAction() {
        $indexM = $this->loadModel("ajax_event");
        $return = $indexM->modifyEvent();

        $this->loadView("ajax", $return);
    }

    function registerImageEventAction() {
        $indexM = $this->loadModel("ajax_event");
        $return = $indexM->registerImage();

        $this->loadView("ajax", $return);
    }

    function addCommentEventAction() {
        $indexM = $this->loadModel("ajax_event");
        $return = $indexM->addComment();

        $this->loadView("ajax", $return);
    }

    function fanEventAction() {
        $indexM = $this->loadModel("ajax_event");
        $return = $indexM->fan();

        $this->loadView("ajax", $return);
    }

    function participateEventAction() {
        $indexM = $this->loadModel("ajax_event");
        $return = $indexM->participate();

        $this->loadView("ajax", $return);
    }

     /* *******************************************************************
     *  Photo
     * ***************************************************************** */

    

}

?>
