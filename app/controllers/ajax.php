<?php
/**
 * Description of ajax
 *
 * @author Miki
 */
class controllers_ajax extends Controller{
    
    /*********************************************************************
     *  Inscription 
     *******************************************************************/
    
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
    
    /*********************************************************************
     *  Member 
     *******************************************************************/
    
    function verifMemberAction (){
        $indexM = $this->loadModel("ajax_member");
        $return = $indexM->verifMember();
        
       $this->loadView("ajax", $return); 
    }
    
    /*********************************************************************
     *  Passion 
     *******************************************************************/
    
    function choicePassionAction (){
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
    
    
    /*********************************************************************
     *  Event 
     *******************************************************************/
    
    function addEventAction() {
        $indexM = $this->loadModel("ajax_event");
        $return = $indexM->addEvent();
        
        $this->loadView("ajax", $return); 
    }
    
    function registerImageEventAction() {
        $indexM = $this->loadModel("ajax_event");
        $return = $indexM->registerImage();
        
        $this->loadView("ajax", $return); 
    }
    
    public function indexAction() {
        //if you wish to call siteurl/ajax only
    }
    
}

?>
