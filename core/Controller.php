<?php

/**
 * Description of Controller
 *
 * @author Miki
 */
abstract class Controller extends Core {

    public function __construct($option = array()) {
        parent::__construct($option);
    }

    ## owerride this function in Your controller

    abstract public function indexAction();

    ## make model object and add them in registry

    public function loadModel($name, $opt = "") {

        return $this->loader("models_" . $name, $opt);
    }

    ## make view object and add them in registry

    public function loadView($name, $data) {

        $options = array('name'=>$name, 'data' => $data);
        return new View($options);
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
        
        $options = array('name'=>"widgets/" . $name, 'data' => $data);
        return new View($options);
    }

    ## create new object and return reference ( call autoloader to include class file )

    private function loader($load, $opt) {

        return new $load($opt);
    }

}

?>
