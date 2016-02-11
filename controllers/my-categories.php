<?php

namespace userportal\controllers;

/**
 * Expense Category Controller
 */
class myCategoriesController extends globalController {

    /**
     * This action is used to check access.
     */
    function init() {
        if (isset($_REQUEST['id']) and !empty($_REQUEST['id'])) {
            if (!$this->getModel("expense-categories")->checkCategoryAccess($_REQUEST['id'])) {
                $_SESSION[$this->session_prefix]["error_message"] = _l("Error_Invalid_Access", 'common');
                \generalFunctions::redirectToLocation($this->getModuleURL() . "/dashboard");
            }
        }
    }

    /**
     *  index actiob to get all expenses
     */
    function indexAction() {

        if ($_POST) {
            $this->getModel('expense-categories')->updateExpenseCategories();
            echo '<script type="text/javascript">window.parent.closeModelBox();window.parent.location.reload();</script>';
            exit;
        }


        if ($_REQUEST['popup'] == 'yes') {
            $this->setTemplate("popup");
        }
        $this->view->arrCategoryData = $this->getModel('expense-categories')->getAllCategories();
        $this->view->arrDefaultCategories = $this->getModel('expense-categories')->getDefaultCategories();
        //$this->view->addExtraJS(array("path" => APPLICATION_URL . "/js/jquery.editinplace.js"));
        $this->view->addExtraJS(array("path" => APPLICATION_URL . "/js/bootstrap-editable.min.js"));
        $this->view->addExtraCSS(array("path" => APPLICATION_URL . "/css/bootstrap-editable.css"));
    }

    /**
     *  list action to get all expenses categories
     */
    function listAction() {

        if ($_POST) {
            $this->getModel('expense-categories')->updateExpenseCategories();
            echo '<script type="text/javascript">window.parent.closeModelBox();window.parent.location.reload();</script>';
            exit;
        }


        if ($_REQUEST['popup'] == 'yes') {
            $this->setTemplate("popup");
        }

        $this->view->arrCategoryData = $this->getModel('expense-categories')->getAllCategories();
    }

    /**
     * action add edit category action
     */
    function addEditAction() {
        $this->view->header_title = _l("ADD_CATEGORY", 'my-categories');
        $this->view->blnPopUp = false;
        if (isset($_REQUEST["id"]) and $_REQUEST["id"] > 0) {
            $this->view->header_title = _l("EDIT_CATEGORY", 'my-categories');
            $this->view->userCategoryId = $_REQUEST["id"];
            $this->view->arrCategoryDetails = $this->getModel("expense-categories")->getCategoryDataById($_REQUEST["id"]);
        }

        if ($_POST) {
            $this->getModel('expense-categories')->addEditUserCategory();
            if ($_GET['popup'] == 'yes') {
                echo '<script type="text/javascript">window.parent.closeModelBox();window.parent.location.reload();</script>';
            } else {
                \generalFunctions::redirectToLocation($this->getModuleURL() . "/my-categories");
            }
        }
        $this->view->arrBaseCategoryData = $this->getModel('expense-categories')->getBaseCategoryList();

        if ($_REQUEST['popup'] == 'yes') {
            $this->setTemplate("popup");
            $this->view->blnPopUp = True;
            $this->view->addExtraCSS(array("path" => APPLICATION_URL . "/css/msdropdown/dd.css"));
            $this->view->addExtraJS(array("path" => APPLICATION_URL . "/js/msdropdown/jquery.dd.min.js"));
        }
        
    }

    /**
     * Action to get category
     */
    function getCategoryDataAction() {

        $arrData = $this->getModel('expense-categories')->getSubCategoryOfBaseCategory();

        echo json_encode($arrData);
        die;
    }

    /**
     *  Action to delete category
     */
    function deleteCategoryAction() {
        echo $arrData = $this->getModel('expense-categories')->deleteUserCategory();
        die;
    }

    /**
     * Action to Add Quick Category 
     */
    function addQuickCategoryAction() {
        $arrData = $this->getModel('expense-categories')->AddQuickUserCategory();
        echo json_encode($arrData);
        die;
    }
    
    /**
     * Action to save inline category 
     */
    function saveInlineCategoryAction() {
        $arrData = $this->getModel('expense-categories')->quickUpdateUserCategory();
        echo json_encode($arrData);
        die;
    }
}
