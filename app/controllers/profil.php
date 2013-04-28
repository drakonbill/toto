<?php

class controllers_profil extends Controller {

// No IndexAction
    function indexAction() {
        
    }
    
    function seePassionAction() {
        
        global $_URL;
        
        $indexM = $this->loadModel("profil");
        
        if(isset($_URL['pseudo']))
            $return = $indexM->seePassion($pseudo);
        else
            $return = $indexM->seePassion("");
        
        $this->loadView("header_co", ""); 
        $this->loadView("seePassion", $return); 
        $this->loadView("footer", ""); 
    }
    
}

?>