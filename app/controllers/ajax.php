<?php
/**
 * Description of ajax
 *
 * @author Miki
 */
class controllers_ajax extends Controller{
    
    function inscriptionAction (){
        $indexM = $this->loadModel("ajax_inscription");
        $return = $indexM->inscription();
        
        $this->loadView("ajax", $return); 
        
    }
    function inscription2Action (){
        $indexM = $this->loadModel("ajax_inscription");
        $return = $indexM->inscription2();
        
       $this->loadView("ajax", $return); 
    }
    
    function verifMemberAction (){
        $indexM = $this->loadModel("ajax_member");
        $return = $indexM->verifMember();
        
       $this->loadView("ajax", $return); 
    }
    
    function choicePassionAction (){
        $indexM = $this->loadModel("ajax_passion");
        $return = $indexM->choicePassion();
        
        $this->loadView("ajax", $return); 
    }

    public function indexAction() {
        //if you wish to call siteurl/ajax only
    }
    
}

?>
