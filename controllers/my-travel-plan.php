<?php

namespace userportal\controllers;

/**
 * My (User) Trip / Travel plan controller
 */
class myTravelPlanController extends globalController {

    /**
     * This action is used to check access.
     */
    function init() {
        if (isset($_REQUEST['id']) and !empty($_REQUEST['id'])) {
            if (!$this->getModel("user-trips")->checkTripAccess($_REQUEST['id'])) {
                $_SESSION[$this->session_prefix]["error_message"] = _l("Error_Invalid_Access", 'common');
                \generalFunctions::redirectToLocation($this->getModuleURL() . "/dashboard");
            }
        }
    }

    /**
     *  add edit trips
     */
    function addEditAction() {
        if ($_POST and $this->getModel("user-trips")->validateUserTrips()) {
                $intTripId = $this->getModel("user-trips")->addEditUserTrip();
                \generalFunctions::redirectToLocation($this->getModuleURL() . "/my-travel-plan");
            }

        $this->view->header_title = _l("ADD_TRIP", 'my-travel-plan');
        if (isset($_REQUEST["id"]) and $_REQUEST["id"] > 0) {
            $this->view->header_title = _l("EDIT_TRIP", 'my-travel-plan');
            $this->view->userTripId = $_REQUEST["id"];
            $this->view->arrTripDetails = $this->getModel("user-trips")->getTripDataByTripId($_REQUEST["id"]);
            $this->view->arrTripReferenceDetails = $this->getModel("user-trips")->getReferenceByTripId($_REQUEST["id"]);
            $this->view->arrDisableFile = $this->getModel("user-trips")->getDisableFileList($this->view->arrTripReferenceDetails);
        }

        if ($_REQUEST['popup'] == 'yes') {
            $this->setTemplate("popup");
        }

        $this->view->currencies = $this->getModel("miscellaneous")->getCurrenciesList();
        $this->view->countries = $this->getModel("miscellaneous")->getCountries();
        $this->view->arrBaseCategory = $this->getModel('expense-categories')->getBaseCategoryList();
        $this->view->addExtraJS(array("path" => APPLICATION_URL . "/js/html5lightbox.js"));
        $this->view->addExtraJS(array("path" => APPLICATION_URL . "/js/bootstrap-filestyle.js"));
        $this->view->addExtraJS(array("path" => APPLICATION_URL . "/js/jquery.mask.min.js"));
        $this->view->addExtraJS(array("path" => APPLICATION_URL . "/js/select2.js"));
        $this->view->addExtraCSS(array("path" => APPLICATION_URL . "/css/select2.css"));
        $this->view->addExtraJS(array("path" => APPLICATION_URL . "/js/bootstrap-timepicker.js"));
        $this->view->addExtraCSS(array("path" => APPLICATION_URL . "/css/bootstrap-timepicker.css"));
    }

    /**
     * action to get all data of trip
     */
    function indexAction() {
        if (isset($_REQUEST['fromDate']) and strlen(trim($_REQUEST['fromDate']))) {
            $this->view->fromDate = date("F j, Y", strtotime($_REQUEST['fromDate']));
        } else {
            $this->view->fromDate = date("F j, Y", strtotime('-3 month'));
        }

        if (isset($_REQUEST['toDate']) and strlen(trim($_REQUEST['toDate']))) {
            $this->view->toDate = date("F j, Y", strtotime($_REQUEST['toDate']));
        } else {
            $this->view->toDate = date("F j, Y", strtotime('+3 month'));
        }

        $this->view->arrUserTrips = $this->getModel('user-trips')->getUserTripData();
        $this->view->arrOngoingData = $this->getModel('user-trips')->extractOngoingDetails($this->view->arrUserTrips);
        $this->view->arrTripTotal['Ongoing'] = $this->getModel('user-trips')->getTotalTripExpense($this->view->arrUserTrips, 'ONGOING');
        $this->view->arrTripTotal['Upcoming'] = $this->getModel('user-trips')->getTotalTripExpense($this->view->arrUserTrips, 'UPCOMING');
        $this->view->arrTripTotal['Previous'] = $this->getModel('user-trips')->getTotalTripExpense($this->view->arrUserTrips, 'PREVIOUS');
        $this->view->userBaseCurrency = $this->getModel('users')->getUserBaseCurrencySymbol();
    }

    /**
     *  action calls  when filter is selected, call from AJAx
     */
    function getFilterDataAction() {
        $arrSearch = array();
        $arrSearch['fromDate'] = $_REQUEST['from_date'];
        $arrSearch['toDate'] = $_REQUEST['to_date'];
        $arrSearch['donotshowjs'] = true;

        $strHTML = $this->action("my-travel-plan", "index", "default", $arrSearch);
        $strHTML = strstr($strHTML, '<!--AJAXPAGESTART-->');
        $strHTML = strstr($strHTML, '<!--AJAXPAGEEND-->', true);
        $strHTML = str_replace('<!--AJAXPAGESTART-->', "", $strHTML);
        $strHTML = str_replace('id="get-html-ajax"', "", $strHTML);
        $arrResponse['HTML'] = ($strHTML);
        echo json_encode($arrResponse);
        exit();
    }

    /**
     *  get trip list
     */
    function getTripListAction() {
        if ($_POST) {
            $result = $this->getModel("user-trips")->updateExpenseForTripId();
            if($result['success']==0) {
                echo "<script type=\"text/javascript\">window.parent.closeModelBox(); alert('".$result['msg']."');</script>";
                exit;
            }
            echo '<script type="text/javascript">window.parent.closeModelBox();window.parent.location.reload();</script>';
            exit;
        }

        $this->view->arrTripData = $this->getModel("user-trips")->getUserTripDataById();
        $this->setTemplate("popup");
    }

    /**
     * get detailed view
     */
    function viewDetailAction() {
        $this->view->arrTripData = $this->getModel("user-trips")->getTripDataByTripId($_REQUEST['id']);
        $this->view->arrTripReference = $this->getModel('user-trips')->getReferenceByTripId($this->view->arrTripData['user_trip_id']);
        $this->view->arrTripExpense = $this->getModel('user-trips')->getTripTotalExpense($this->view->arrTripData['user_trip_id']);
        $this->view->arrTripData['trip_currency'] = $this->getModel('user-trips')->getCurrencySymbolById($this->view->arrTripData['trip_currency']);
        $this->view->addExtraJS(array("path" => APPLICATION_URL . "/js/html5lightbox.js"));
    }

    /**
     *  delete trip
     */
    function deleteTravelByIdAction() {
        $str = $this->getModel('user-trips')->deleteUserTravelById($_POST['id']);
        echo $str;
        exit;
    }

    /**
     *  delete multiple trips
     */
    function deleteTripsAction() {
        $strStatus = 'FAIL';
        if ($_REQUEST['selected_trip']) {
            $strStatus = $this->getModel('user-trips')->deleteUserTrip($_REQUEST['selected_trip']);
        }
        echo $strStatus;
        exit;
    }

    /**
     * export XLS action
     */
    function exportUserTripsAction() {
    	$this->getModel('user-trips')->exportUserTrips($_GET['tripid']);
    }

    /**
     * export PDF action
     */
    function exportPdfUserTripAction() {
        $params = array("trip_id" => $_REQUEST['id']);
        $viewSection = $this->actionWithTemplate("my-travel-plan", "view-section", "default", $params, "view-pdf");
        $this->getModel('user-trips')->generateTripPdf($viewSection, 'default');
        exit;
    }

    /**
     *
     */
    function viewSectionAction() {
        $this->view->arrTripData = $this->getModel("user-trips")->getTripDataByTripId($_REQUEST['id']);
        $this->view->arrTripReference = $this->getModel('user-trips')->getReferenceByTripId($this->view->arrTripData['user_trip_id']);
        $this->view->arrTripExpense = $this->getModel('user-trips')->getTripTotalExpense($this->view->arrTripData['user_trip_id']);
        $this->view->arrTripData['trip_currency'] = $this->getModel('user-trips')->getCurrencySymbolById($this->view->arrTripData['trip_currency']);
    }

    /**
     * download pdf
     */
    function downloadTripPdfAction() {
        header('Content-type: application/pdf');
        header('Content-Disposition: attachment; filename="' . PDF_FILE_PATH . '"');
        readfile(PDF_FILE_PATH);
        exit;
    }

    /**
     * action to remove file.
     */
    function removeTripFileAction() {
        echo $this->getModel('user-trips')->deleteUserTripImages();
        exit;
    }

}