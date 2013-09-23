<?php

class controllers_photo extends Controller {

    public function init() {
        
    }
    
    public function indexAction() {
        global $_URL;
        $this->displayAlbumAction();
    }

    public function displayAlbumAction() {
        global $_URL;
        
        $pseudo = array_keys($_URL);

        $indexM = $this->loadModel("profil");
        
        $photoM = $this->loadModel("photo");
        
        $return = array();
        
        $return[] = $indexM->seeProfil($pseudo[0]);
        $return[] = $photoM->loadAlbum();
        
    
            /* $indexM = $this->loadModel("ajax_event");
              $return = $indexM->registerImage(); */
            $this->loadView("header_co", "");
            $this->loadView("photo", $return);
            $this->loadView("footer", "");
 
    }

}

?>
