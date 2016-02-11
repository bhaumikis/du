<?php

namespace admin\controllers;

class configurationsController extends adminGlobalController {

    function indexAction() {
        // If Form is posted then make add/edit operation
        if ($_POST) {

            $this->getModel("configurations")->saveConfigurations();

            \generalFunctions::redirectToLocation($this->getModuleURL() . "/configurations");
        }


        $this->view->fields = array(1 => array("field" => "title", "title" => "Title"),
            2 => array("field" => "value", "title" => "Value"),
            3 => array("field" => "parameter", "title" => "Parameter")
        );

        list($this->view->sortby, $order_by) = $this->getOrderBy("administrator", "`group` ASC,`ordering` ASC", $this->view->fields);

        $this->view->configurationdetails = $this->getModel("configurations")->listConfigurations($order_by);
    }

}
