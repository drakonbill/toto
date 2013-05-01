<?php

/**
 * Description of Controller
 *
 * @author Miki
 */
abstract class Controller extends Core {

    public function __construct($option = array()) {
        parent::__construct($option);
        $this->init();
        $this->loadInit();
    }

    ## owerride this functions in Your controller
    abstract public function init();

    abstract public function indexAction();

    private function loadInit(){
        
    }
    ## make model object and add them in registry

    public function loadModel($name, $opt = "") {

        return $this->loader("models_" . $name, $opt);
    }

    ## make view object

    public function loadView($name, $data) {

        $options = array('name' => $name, 'data' => $data);
        return new View($options);
//        return new View($name, $data);
    }

    public function loadHView($name, $data) {

       

        if ($user->loggedin()) {
            $this->loadmWidgets(array("news"));
            $this->loadView("header-co", "");
        } else {
            
            $this->loadView("header","");
        }

        $this->loadView($name, $data);
        $this->loadView("footer", "");

//        return new View($name, $data);
    }

    ## make widgets objects for models and add them in registry 

    public function loadmWidgets($widgets) {

        foreach ($widgets as $name) {

            $mWidget[$name] = $this->loadModel("widgets_" . $name);

            //$vWidget[$name] = $this->loadView("widgets_" . $name);
        }
        $this->reg->mWidget = $mWidget;
        //$this->reg->vWidget = $vWidget;
        return array("mWidget" => $mWidget);
    }

    public function loadvWidget($name) {


        @$mWidget = $this->reg->mWidget;
        if (isset($mWidget[$name]))
            $data = $mWidget[$name];
        else
            $data = "";

        $options = array('name' => "widgets/" . $name, 'data' => $data);
        return new View($options);
    }

    ## create new object and return reference ( call autoloader to include class file )

    private function loader($load, $opt) {

        return new $load($opt);
    }

    public function redirect($name) {
        $clasName = "controllers_" . $name;
        $controller = new $clasName();

        $this->reg->controler = $controller;
        return $controller;
    }

}

?>
