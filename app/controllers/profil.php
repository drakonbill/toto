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
        
        //<?php if (isset($contr)) $contr->loadvWidget("footer_quotation"); 
        $this->Widget["footer_quotation"] = new Widget("footer_quotation");
        //<?php if (isset($contr)) $contr->loadvWidget("mosaic_pictures"); 
         $this->Widget["mosaic_pictures"] = new Widget("mosaic_pictures");
        //<?php if (isset($contr)) $contr->loadvWidget("passionCategory", "passionCategory", $data->id_member); 
         $this->Widget["passionCategory"] = new Widget("passionCategory","passionCategory","passionCategory","");
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
        $id = array_keys($_URL);

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
