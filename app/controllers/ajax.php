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
        
        echo $return; // maybe to make view for this one echo ?
        
    }
    function inscription2Action (){
        $indexM = $this->loadModel("ajax_inscription");
        $return = $indexM->inscription2();
        
        echo $return; // maybe to make view for this one echo ?
    }
    
}

?>
