<?php

namespace admin\controllers;

class usertypesController extends adminGlobalController {

    function addeditAction() {

        if ($_POST) {
            if ($this->getModel("usertypes")->_validateUserTypeForm()) {
                $this->getModel("usertypes")->addEditUserType();
                \generalFunctions::redirectToLocation(APPLICATION_URL . "/" . $this->getModule() . "/usertypes");
            }
        }

        $this->view->usertype_id = 0;
        $this->view->usertypedetails = array();

        if (isset($_REQUEST["usertype_id"])) {
            $this->view->usertype_id = $_REQUEST["usertype_id"];
            $this->view->usertypedetails = \generalFunctions::htmlsplchs($this->getModel("usertypes")->getUserTypeDetails($this->view->usertype_id));
        }
    }

    function deleteAction() {

        $this->getModel("usertypes")->deleteUserType($_GET["usertype_id"]);
        $_SESSION[$this->session_prefix]["action_message"] = "Record deleted successfully";
        \generalFunctions::redirectToLocation(APPLICATION_URL . "/" . $this->getModule() . "/usertypes");
    }

    function changestatusAction() {
        if (isset($_GET["usertype_id"]) and ($usertypesdetails = $this->getModel("usertypes")->getUserTypeDetails($_GET["usertype_id"]))) {
            $this->getModel("usertypes")->updateUserTypeStatus($_GET["usertype_id"], $_GET["status"]);
        } else {
            $_SESSION[$this->session_prefix]["error_message"] = "Invalid Input.";
        }
        \generalFunctions::redirectToLocation(APPLICATION_URL . "/" . $this->getModule() . "/usertypes");
    }

    function privilegesAction() {
        if ($_POST) {

            $this->getModel("usertypes")->savePreviledges();

            \generalFunctions::redirectToLocation(APPLICATION_URL . "/" . $this->getModule() . "/usertypes");
        } else {

        }

        $this->view->fields = array(1 => array("field" => "", "title" => "", "checkbox" => true),
            2 => array("field" => "title", "title" => "Title"),
            3 => array("field" => "module", "title" => "Module"),
            4 => array("field" => "option", "title" => "Controller"),
            5 => array("field" => "action", "title" => "Action"));

        list($this->view->sortby, $order_by) = $this->getOrderBy("resources", "`title` ASC", $this->view->fields);

        $this->setPage("resources");

        $this->view->resources = $this->getModel("resources")->getResourceList();
        $this->view->usertype = $this->getModel("usertypes")->getUserTypeDetails($_GET["usertype_id"]);
        $this->view->tmp_selected_resources_admin = $this->getModel("usertypes")->getSelectedResourceList($_GET["usertype_id"]);
    }

    function indexAction() {
        $this->view->fields = array(1 => array("field" => "usertype_id", "title" => "ID"),
            2 => array("field" => "title", "title" => "Title"),
            3 => array("field" => "description", "title" => "Description"),
            4 => array("field" => "updated_date", "title" => "Updated On"),
            5 => array("field" => "status", "title" => "Status"),
            6 => array("field" => "action", "title" => "Action", 'enable_sort' => false));

        list($this->view->sortby, $order_by) = $this->getOrderBy("usertypes", "`usertype_id` DESC", $this->view->fields);

        $this->setPage("usertypes");

        $this->view->usertypes = array();

        list($this->view->pager, $this->view->usertypes) = $this->getModel("usertypes")->listUserTypes($order_by, $this->view->sortby);
    }

}

