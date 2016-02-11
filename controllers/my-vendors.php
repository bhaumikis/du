<?php

namespace userportal\controllers;

/**
 * (User) myVendorController - Manage User's Vender list
 */
class myVendorsController extends globalController {

    /**
     * This action is used to check access.
     */
    function init() {
        if (isset($_REQUEST['expense_vendor_id']) and !empty($_REQUEST['expense_vendor_id'])) {
            if (!$this->getModel("vendors")->checkVendorAccess($_REQUEST['expense_vendor_id'])) {
                $_SESSION[$this->session_prefix]["error_message"] = _l("Error_Invalid_Access", 'common');
                \generalFunctions::redirectToLocation($this->getModuleURL() . "/dashboard");
            }
        }
    }

    /**
     *  add edit vendor
     */
    function addEditAction() {

        $this->view->chk = $_REQUEST['popup'];
        $this->view->expense_vendor_id = 0;
        $this->view->header_title = _l("ADD_VENDOR", 'my-vendors');
        if (isset($_REQUEST['expense_vendor_id']) and !empty($_REQUEST['expense_vendor_id'])) {
            $this->view->expense_vendor_id = $_REQUEST['expense_vendor_id'];
            $this->view->header_title = _l("EDIT_VENDOR", 'my-vendors');
        }

        $this->view->vendorDetails = $this->getModel("vendors")->getVendorDetailsId($_REQUEST['expense_vendor_id']);

        if ($_POST) {
            $intVendorId = $this->getModel("vendors")->addEditUserVendor();
            if ($_POST['hid_chk'] == "yes") {
                $_SESSION[$this->session_prefix]["action_message"] = "";
                echo '<script type="text/javascript">window.parent.closeModelBox();window.parent.getUpdatedVendors("' . $intVendorId . '","' . $_POST['name'] . '");</script>';
                exit;
            } else {
                \generalFunctions::redirectToLocation($this->getModuleURL() . "/my-vendors");
            }
        }

        if ($_REQUEST['popup'] == 'yes') {
            $this->setTemplate("popup");
        }
    }

    /**
     * index action to get all vendor
     */
    function indexAction() {
        $this->view->vendors = $this->getModel("vendors")->getMyVendorList();
//        $this->view->fields = array(
//            1 => array("field" => "name", "title" => "Name"),
//            2 => array("field" => "description", "title" => "Description"),
//            3 => array("field" => "status", "title" => "Status"),
//            4 => array("field" => "created_date", "title" => "Created Date"),
//            5 => array("field" => "updated_date", "title" => "Updated Date"),
//            6 => array("field" => "action", "title" => "Action", 'enable_sort' => false));
//
//        list($this->view->sortby, $order_by) = $this->getOrderBy("vendors", "expense_vendor_id ASC", $this->view->fields);
//        $this->setPage("vendors");
//        list($this->view->pager, $this->view->vendors) = $this->getModel("vendors")->getMyVendorList($order_by, $this->view->sortby);
    }

    /**
     *  delete vendor
     */
    function deleteVendorAction() {
        $this->getModel("vendors")->deleteVendorById($_REQUEST['expense_vendor_id']);
        \generalFunctions::redirectToLocation($this->getModuleURL() . "/my-vendors");
    }
    
    /**
     *  delete multiple vendor
     */
    function deleteVendorsAction() {
        $this->getModel("vendors")->deleteMultipleVendors($_REQUEST['selected_vendors']);
        echo 'SUCCESS';
        exit;
    }

}