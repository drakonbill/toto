<?php

/**
 * Base View class
 * initialize view data and load view
 * @author Miki
 */
class View extends Core{

    protected $data;

    public function __construct($option) {
        parent::__construct($option);
        
        $this->data = $option['data'];
        $this->includeView($option['name']);
    }

    //this function load view and pass local copy for all data needed inside it
    function includeView($name) {
        //load all data needed in view
        $data = $this->data; //data got form model        
        $reg = $this->reg;  // registry, for special cases
        $widgets = $this->reg->mWidget; //widget models -- this need to be changed!!!!
        $applibs = $this->reg->applib; //app libs data
        $contr = $this->reg->controller;
        $conf = $this->reg->appconf;
        $login = $this->reg->login;
        
        include("../" . APPDIR . "views/" . $name . ".phtml");
    }
    
//    function loadView($name, $data){
//        $this->control->loadView($name, $data);
//    }

}

?>
