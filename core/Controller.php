<?php

/**
 * Description of Controller
 *
 * @author Miki
 */
class Controller {

    protected $reg;

    public function __construct() {

        global $reg;
        $this->reg = $reg;
    }

    ## owerride this function in Your controller

    public function indexAction() {
        
    }

    ## make model object and add them in registry

    public function loadModel($name) {

        return $this->loader("models_" . $name);
    }

    ## make view object and add them in registry

    public function loadView($name, $data) {


        return new View($name, $data);
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

        return new View("widgets/" . $name, $data);
    }

    ## create new object and return reference ( call autoloader to include class file )

    private function loader($load) {

        return new $load;
    }

}

?>
