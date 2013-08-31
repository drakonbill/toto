<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Widget
 *
 * @author Miki
 */
class Widget extends Core {

    
    protected $model;
    protected $method;
    protected $params;
    protected $view;

    public function __construct($view, $model = "", $method = "", $params = "") {
parent::__construct("");
        $this->view = $view;
        $this->model = $model;
        $this->method = $method;
        $this->params = $params;
    }

    public function setModel($model) {
        $this->model = $model;
    }

    public function setView($view) {
        $this->view = $view;
    }

    public function setMethod($method) {
        $this->method = $method;
    }

    public function setParams($params) {
        $this->params = $params;
    }

    public function load() {
        global $reg;
        if ($this->model == "")
            $data = $this->params;
        else {

            $wm = $reg->contr->loadModel("widgets_" . $this->model, $this->params);

            if (method_exists($wm, $this->method)) {
                $method = $this->method;
                $data = $wm->$method($this->params);
            }
            else
                $data = $this->params;
        }
        $options = array('name' => "widgets/" . $this->view, 'data' => $data);

        return new View($options);
    }

}

?>
