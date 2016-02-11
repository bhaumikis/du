<?php

namespace admin\controllers;

class resourcesController extends adminGlobalController {

    function updateResourceListAction() {

        $this->getModel("resources")->updateResourceList();
        $_SESSION[$this->session_prefix]["action_message"] = "Crawled successfully";
        \generalFunctions::redirectToLocation($this->getModuleURL() . "/resources");
    }

    function indexAction() {

        $this->view->fields = array(1 => array("field" => "resource_id", "title" => "ID"),
            2 => array("field" => "title", "title" => "Title"),
            3 => array("field" => "module", "title" => "Module"),
            4 => array("field" => "option", "title" => "Controller"),
            5 => array("field" => "action", "title" => "Action"),
            6 => array("field" => "action", "title" => "Action", 'enable_sort' => false));

        list($this->view->sortby, $order_by) = $this->getOrderBy("resources", "`title` ASC", $this->view->fields);

        $this->setPage("resources");

        //list($this->view->pager, $this->view->resources) = $this->getModel("resources")->listModules($order_by, $this->view->sortby);
        $this->view->resources = $this->getModel("resources")->listResources($order_by, $this->view->sortby);
    }

    function addeditAction() {
        // If Form is posted then make add/edit operation
        if ($_POST) {
            if ($this->getModel("resources")->_validateResourceForm()) {
                $this->getModel("resources")->addEditResource();
                \generalFunctions::redirectToLocation($this->getModuleURL() . "/resources");
            }
        }

        $this->view->resource_id = 0;
        $this->view->resourcedetails = array();

        if (isset($_REQUEST["resource_id"])) {
            $this->view->resource_id = $_REQUEST["resource_id"];
            $this->view->resourcedetails = \generalFunctions::htmlsplchs($this->getModel("resources")->getResourceDetails($this->view->resource_id), array("htmltext"));
        }
    }

}