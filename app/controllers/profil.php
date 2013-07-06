<?php

class controllers_profil extends Controller {

    public function init() {
        
    }
    
    function indexAction() {
        $this->seeProfilAction();
    }
    
    function pseudoAction(){
        
        $this->seeProfilAction();
    }

     function idAction(){
        
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

}

?>