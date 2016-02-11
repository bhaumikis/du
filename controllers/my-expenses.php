<?php

namespace userportal\controllers;


/**
 * @author Bhaumik Patel | bhaumik.patel@infostretch.com
 * My (User) Expenses Controller Class
 */
class myExpensesController extends globalController {

    /**
     * This action is used to check access.
     */
    function init() {
        if (isset($_REQUEST['id']) and !empty($_REQUEST['id'])) {
            if (!$this->getModel("user-expenses")->checkExpenseAccess($_REQUEST['id'])) {
                $_SESSION[$this->session_prefix]["error_message"] = _l("Error_Invalid_Access", 'common');
                \generalFunctions::redirectToLocation($this->getModuleURL() . "/dashboard");
            }
        }
    }

    /**
     *  index action to get all expenses
     */
    function indexAction() {
    	if (isset($_REQUEST['t']) and strlen(trim($_REQUEST['t']))) {
            $arrTripDates = $this->getModel('user-expenses')->getUserTripExpenseDates(); //get Local Date
            $this->view->fromDate = date("F j, Y", strtotime($arrTripDates['trip_date_from'].' 00:00:00'));
            $this->view->toDate = date("F j, Y", strtotime($arrTripDates['trip_date_to'].' 23:59:59'));
        }elseif (isset($_REQUEST['v']) and strlen(trim($_REQUEST['v']))) {
        	$arrExpenseDates = $this->getModel('user-expenses')->getUserVendorExpenseDates(); //get Local Date
        	$this->view->fromDate = date("F j, Y", strtotime($arrExpenseDates['trip_date_from'].' 00:00:00'));
            $this->view->toDate = date("F j, Y", strtotime($arrExpenseDates['trip_date_to'].' 23:59:59'));
        } else {
            if (isset($_REQUEST['fromDate']) and strlen(trim($_REQUEST['fromDate']))) {
                $this->view->fromDate = date("F j, Y", strtotime($_REQUEST['fromDate'])); //as per local date
            } else {
                $this->view->fromDate = date("F j, Y",getNowLocal("MYSQL_TIMESTAMP","-30 day")); //date("F j, Y", strtotime('-30 day')); 
            }

            if (isset($_REQUEST['toDate']) and strlen(trim($_REQUEST['toDate']))) {
                $this->view->toDate = date("F j, Y", strtotime($_REQUEST['toDate'])); //as per local date
            } else {
                $this->view->toDate = date("F j, Y", getNowLocal("MYSQL_TIMESTAMP","")); // get Local todate
            }
        }
        $arrUserExpenses = $this->getModel('user-expenses')->getUserExpenses();
        $this->view->arrExpenses = $arrUserExpenses['data'];
        $this->view->addExtraJS(array("path" => APPLICATION_URL . "/js/bootstrap-datepicker.js"));
        $this->view->arrTotal = $arrUserExpenses['total'];
    }
   
    /**
     *  funtion to get data with applied filters
     */
    function getFilterDataAction() {
        $arrSearch = array();
        $arrSearch['fromDate'] = $_REQUEST['from_date'];
        $arrSearch['toDate'] = $_REQUEST['to_date'];
        $arrSearch['donotshowjs'] = true;

        $strHTML = $this->action("my-expenses", "index", "default", $arrSearch);
        $strHTML = strstr($strHTML, '<!--AJAXPAGESTART-->');
        $strHTML = strstr($strHTML, '<!--AJAXPAGEEND-->', true);
        $strHTML = str_replace('<!--AJAXPAGESTART-->', "", $strHTML);
        $strHTML = str_replace('id="get-html-ajax"', "", $strHTML);

        $arrResponse['HTML'] = ($strHTML);
        echo json_encode($arrResponse);
        exit();
    }

    /**
     * function to add or update expenses
     */
    function addEditAction() {
        if ($_POST) {
        	//p($_POST);
            $this->getModel('user-expenses')->addEditUserExpense($_POST);
            \generalFunctions::redirectToLocation($this->getModuleURL() . "/my-expenses");
        }

        $this->view->header_title = _l("ADD_EXPENSE", 'my-expenses');

        if (isset($_REQUEST["id"]) and $_REQUEST["id"] > 0) {
            $this->view->userExpensesId = $_REQUEST["id"];
            $this->view->arrExpensesDetails = $this->getModel("user-expenses")->getUserExpenseById($_REQUEST["id"]);
            $this->view->arrExpensesReferenceDetails = $this->getModel("user-expenses")->getReferenceByExpenseId($_REQUEST["id"]);
            $this->view->header_title = _l("EDIT_EXPENSE", 'my-expenses');
            $this->view->arrDisableFile = $this->getModel("user-expenses")->getDisableFileList($this->view->arrExpensesReferenceDetails);
            $this->view->strExpenseCategoriesLable = $this->getModel("user-expenses")->getAllCategoryByExpenseId($_REQUEST["id"]);
        }

        $this->view->addExtraJS(array("path" => APPLICATION_URL . "/js/bootstrap-filestyle.js"));
        $this->view->addExtraJS(array("path" => APPLICATION_URL . "/js/jquery.mask.min.js"));
        $this->view->addExtraJS(array("path" => APPLICATION_URL . "/js/select2.js"));
        $this->view->addExtraJS(array("path" => APPLICATION_URL . "/js/html5lightbox.js"));
        $this->view->addExtraCSS(array("path" => APPLICATION_URL . "/css/select2.css"));
        $this->view->addExtraJS(array("path" => APPLICATION_URL . "/js/jquery.confirm.min.js"));

        $this->view->addExtraJS(array("path" => APPLICATION_URL . "/js/bootstrap-timepicker.js"));
        $this->view->addExtraCSS(array("path" => APPLICATION_URL . "/css/bootstrap-timepicker.css"));

        $this->view->arrCategoryData = $this->getModel('expense-categories')->getCategoryListForUser();
        $this->view->arrVendorData = $this->getModel('vendors')->getVendorListByUserId();
        $this->view->arrTripData = $this->getModel('user-trips')->getTripListByUserId();
        $this->view->arrCardData = $this->getModel('cards')->getCardListByUserId();
        $this->view->currencies = $this->getModel("miscellaneous")->getCurrenciesList();
    }

    /**
     * Action to delete expense
     */
    function deleteExpenseAction() {
        $strStatus = 'FAIL';
        if ($_REQUEST['selected_expense']) {
            $strStatus = $this->getModel('user-expenses')->deleteUserExpense($_REQUEST['selected_expense']);
        }
        echo $strStatus;
        exit;
    }

    /**
     *  Action to view individual expense details
     */
    function viewDetailAction() {
        $this->view->expense_id = $_REQUEST['id'];
        $this->view->arrUserExpense = $this->getModel('user-expenses')->getUserExpenseById($_REQUEST['id']);
        $this->view->arrExpenseReference = $this->getModel('user-expenses')->getReferenceByExpenseId($this->view->arrUserExpense['user_expense_id']);
        $this->view->arrExpenseCategory = $this->getModel('user-expenses')->getCategoryNameByExpenseId($this->view->arrUserExpense['expense_category_id']);
        $this->view->addExtraJS(array("path" => APPLICATION_URL . "/js/html5lightbox.js"));
    }

    /**
     *   action to delete expense by id
     */
    function deleteExpenseByIdAction() {
        $str = $this->getModel('user-expenses')->deleteUserExpenseById($_POST['id']);
        echo $str;
        exit;
    }

    /**
     *   action to export user expense as xls
     */
    function exportUserExpensesAction() {
        $this->getModel('user-expenses')->exportUserExpenses($_GET['expid']);
    }

    /**
     *   action to remove trip from expense
     */
    function removeExpenseTripAction() {
        echo $this->getModel('user-expenses')->removeExpenseTrip();
        die;
    }

    /**
     *   action to export pdf
     */
    function exportPdfUserExpenseAction() {
        $params = array("expense_id" => $_REQUEST['id']);
        $viewSection = $this->actionWithTemplate("my-expenses", "view-section", "default", $params, "view-pdf");
        $this->getModel('user-expenses')->generateExpensePdf($viewSection,'default');
        exit;
    }

    /**
     *   action to view section
     */
    function viewSectionAction() {
        $this->view->expense_id = $_REQUEST['id'];
        $this->view->arrUserExpense = $this->getModel('user-expenses')->getUserExpenseById($_REQUEST['id']);
        $this->view->arrExpenseReference = $this->getModel('user-expenses')->getReferenceByExpenseId($this->view->arrUserExpense['user_expense_id']);
        $this->view->arrExpenseCategory = $this->getModel('user-expenses')->getCategoryNameByExpenseId($this->view->arrUserExpense['expense_category_id']);
        $this->setTemplate("view-pdf");
    }

    /**
     *   action to download expense as pdf
     */
    function downloadExpensePdfAction() {
        header('Content-type: application/pdf');
        header('Content-Disposition: attachment; filename="' . PDF_FILE_PATH . '"');
        readfile(PDF_FILE_PATH);
        exit;
    }

    /**
     *  action to remove expense file
     */
    function removeExpenseFileAction() {
        echo $this->getModel('user-expenses')->deleteUserExpenseImages();
        exit;
    }

}