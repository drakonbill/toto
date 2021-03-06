<?php

/*
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

    /*     * ******************************************************************
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

    /*     * ******************************************************************
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

    /*     * ******************************************************************
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

    /*     * ******************************************************************
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

    function searchEventAction() {
        $indexM = $this->loadModel("ajax_event");
        $return = $indexM->searchEvent();

        $this->loadView("ajax", $return);
    }

    /* FOR THE PROFILE PART */

    function modifyFirstNameAction() {
        $indexM = $this->loadModel("ajax_profile");
        $return = $indexM->ModifyFirstName();

        $this->loadView("ajax", $return);
    }

    function modifyPseudoAction() {
        $indexM = $this->loadModel("ajax_profile");
        $return = $indexM->ModifyPseudo();

        $this->loadView("ajax", $return);
    }

    function modifySituationAction() {
        $indexM = $this->loadModel("ajax_profile");
        $return = $indexM->ModifySituation();

        $this->loadView("ajax", $return);
    }

    function modifyPreferanceAction() {
        $indexM = $this->loadModel("ajax_profile");
        $return = $indexM->ModifyPreferance();

        $this->loadView("ajax", $return);
    }

    function modifySexeAction() {
        $indexM = $this->loadModel("ajax_profile");
        $return = $indexM->ModifySexe();

        $this->loadView("ajax", $return);
    }

    function modifyBirthAction() {
        $indexM = $this->loadModel("ajax_profile");
        $return = $indexM->ModifyBirth();

        $this->loadView("ajax", $return);
    }

    function modifySituationproAction() {
        $indexM = $this->loadModel("ajax_profile");
        $return = $indexM->ModifySituationpro();

        $this->loadView("ajax", $return);
    }

    function modifyCodepostalAction() {
        $indexM = $this->loadModel("ajax_profile");
        $return = $indexM->ModifyCodepostal();

        $this->loadView("ajax", $return);
    }

    function modifyVilleAction() {
        $indexM = $this->loadModel("ajax_profile");
        $return = $indexM->ModifyVille();

        $this->loadView("ajax", $return);
    }

    function modifyPortableAction() {
        $indexM = $this->loadModel("ajax_profile");
        $return = $indexM->ModifyPortable();

        $this->loadView("ajax", $return);
    }

    function modifyRechercheAction() {
        $indexM = $this->loadModel("ajax_profile");
        $return = $indexM->ModifyRecherche();

        $this->loadView("ajax", $return);
    }

    function modifyDescriptionAction() {
        $indexM = $this->loadModel("ajax_profile");
        $return = $indexM->ModifyDescription();

        $this->loadView("ajax", $return);
    }

    function modifyFacebookAction() {
        $indexM = $this->loadModel("ajax_profile");
        $return = $indexM->ModifyFacebook();

        $this->loadView("ajax", $return);
    }

    function modifyTwitterAction() {
        $indexM = $this->loadModel("ajax_profile");
        $return = $indexM->ModifyTwitter();

        $this->loadView("ajax", $return);
    }

    function modifySkypeAction() {
        $indexM = $this->loadModel("ajax_profile");
        $return = $indexM->ModifySkype();

        $this->loadView("ajax", $return);
    }

    function refreshPassionAction() {
        $indexM = $this->loadModel("ajax_passion");
        $return = $indexM->RefreshPassion();

        $this->loadView("ajax", $return);
    }
    
    /* PHOTO */
    
    function registerImagePhotoAction() {
        $indexM = $this->loadModel("ajax_photo");
        $return = $indexM->registerImage();

        $this->loadView("ajax", $return);
    }
    
    function registerPhotoAction() {
        $indexM = $this->loadModel("ajax_photo");
        $return = $indexM->registerPhoto();

        $this->loadView("ajax", $return);
    }

}

?>
