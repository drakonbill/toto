<?php

class controllers_api_friends extends Controller {

    public function init() {
        
    }

    public function indexAction() {
        global $_URL;
        $id = array_keys($_URL);
        $id = $id[0];
       if(isset($id) && $id != "")
           echo "USER ID IS ". $id;
       else
           //throw new UnexpectedValueException("NEED FOR USER ID");
           Echo "miss user id";
    }

   public function indexGetAction(){
       $this->indexAction();
   }

}

?>
