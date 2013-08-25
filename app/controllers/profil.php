<?php

/**
 * Controller : Profil : all functions relatives to the profil of the member
 * @author Nicolas D, Quentin L
 * @todo 
 */
class controllers_profil extends Controller {
    /*
     * Function of initialisation 
     * @todo : Checking the using of this function ?
     */

    
    public function init() {
        
//ADD PAGE WIDGET -- this one you use on all variants of profile page
$pWidget = $this->Widget["profileWidget"] = new Widget("news","news","",array("p1"=>1,"p2"=>2));
$pWidget->setMethod("blabla");
        
    }

    /*
     * Default function when you go : http://meetoparty/profil/
     * @default : Go to see the profil of the member connected
     */

    function indexAction() {
        $this->seeProfilAction();
    }

    /*
     * Function : Checking profil depending of the pseudo : http://meetoparty/profil/pseudo/powereborn
     */

    function pseudoAction() {

        $this->seeProfilAction();
    }

    /*
     * Function : Checking profil depending of the id of the member : http://meetoparty/profil/id/63
     */

    function idAction() {

//WIDGET EXAMPLE --- reuse page widget, reset view:
$this->Widget["profileWidget"]->setView("profilNavigation");
//NEW WIDGET -- ONLY for idAction
$pWidget2 = $this->Widget["profileWidget2"] = new Widget("news");
        
        global $_URL;
        $pseudo = array_keys($_URL);

        $indexM = $this->loadModel("profil");

        $return = $indexM->seeProfilID($pseudo[0]);

        $this->loadView("header_co", $return->id_member);
        $this->loadView("profil", $return);
        $this->loadView("footer", "");
    }

// Fonction to see profile of member or of another member
    function seeProfilAction() {

        global $_URL;
        $pseudo = array_keys($_URL);

        $indexM = $this->loadModel("profil");

        $return = $indexM->seeProfil(@$pseudo[0]);

        $this->loadView("header_co", "");
        $this->loadView("profil", $return);
        $this->loadView("footer", "");
    }

    function seePassionAction() {

        global $_URL;

        $indexM = $this->loadModel("profil");

        if (isset($_URL['pseudo']))
            $return = $indexM->seePassion($pseudo);
        else
            $return = $indexM->seePassion("");

        $this->loadView("header_co", "");
        $this->loadView("seePassion", $return);
        $this->loadView("footer", "");
    }

    function seeContactsAction() {

        global $_URL;

        $indexM = $this->loadModel("profil");

        if (isset($_URL['id']))
            $return = $indexM->seeContacts($id);
        else
            $return = $indexM->seeContacts("");

        $this->loadView("header_co", "");
        $this->loadView("seeContacts", $return);
        $this->loadView("footer", "");
    }

}

?>
