<?php

class controllers_profil extends Controller {

    public function init() {
        
    }
    
    function indexAction() {
        
    }
    
// Fonction to see profile of member or of another member
    function seeProfilAction() {

        global $_URL;

        $indexM = $this->loadModel("profil");

        if (isset($_URL['pseudo']))
            $return = $indexM->seeProfil($_URL['pseudo']);
        else
            $return = $indexM->seeProfil("");

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