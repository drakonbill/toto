<?php

/**
 * Base View class
 * initialize view data and load view
 * @author Miki
 */
class View {

    protected $reg, $data;

    public function __construct($name, $data) {
        global $reg;
        $this->reg = $reg;
        $this->data = $data;
        debug($data);
        $this->includeView($name);
        //
    }

    //this function load view and pass local copy for all data needed inside it
    function includeView($name) {
        //load all data needed in view
        $data = $this->data; //data got form model        
        $reg = $this->reg;  // registry, for special cases
        $widgets = $this->reg->mWidget; //widget models -- this need to be changed!!!!
        $applibs = $this->reg->applib; //app libs data -- MUST MAKE app lib loader!!!
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
