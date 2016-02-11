<?php

namespace services\controllers;

/**
 * @author Bhaumik Patel | bhaumik.patel@infostretch.com
 * brief  This class will contain all the the database actions related to user trips table
 */
class userTripsController extends servicesGlobalController {

    /**
     * this action is to add trip
     */
    function addTripAction() {
        $this->params["user_id"] = $this->user["user_id"];
        if (list($valid, $response) = $this->getModel("user-trips")->validateTripForm($this->params) and !$valid) {
            $this->generateResponse($response, "error");
        }
        if (list($valid, $response) = $this->getModel("user-trips")->addEditTrip($this->params) and !$valid) {

            $this->generateResponse($response, "error");
        } else {
            $this->generateResponse($response);
        }
    }

    /**
     * this action is to add trips
     */
    function addTripsAction() {

        foreach ($this->params['request_data'] as $intKey => $inputData) {
            $inputData['user_trip_id'] = $inputData['server_id'];
            if (list($valid, $response) = $this->getModel("user-trips")->validateTripForm($inputData) and !$valid) {
                $arrResponse[$intKey] = "Error";
            }

            $inputData["user_id"] = $this->user["user_id"];

            if (list($valid, $response) = $this->getModel("user-trips")->addEditTrip($inputData) and !$valid) {
                $arrResponse[$intKey] = "Error";
            } else {
                $arrResponse[$intKey] = $response;
            }
        }

        if (is_array($arrResponse)) {
            $this->generateResponse($arrResponse);
        }
    }

    /**
     * this action is to delete trips
     */
    function deleteTripsAction() {


        foreach ($this->params['request_data'] as $intKey => $inputData) {
            $inputData['user_trip_id'] = $inputData['server_id'];
            if (list($valid, $response) = $this->getModel("user-trips")->validateTripId($inputData) and !$valid) {
                $arrResponse[$intKey] = "Error";
            }

            $inputData["user_id"] = $this->user["user_id"];

            if (list($valid, $response) = $this->getModel("user-trips")->deleteTrip($inputData) and !$valid) {
                $arrResponse[$intKey] = "Error";
            } else {
                $arrResponse[$intKey] = $response;
            }
        }

        if (is_array($arrResponse)) {
            $this->generateResponse($arrResponse);
        }
    }

    /**
     * this action is to update trips
     */
    function updateTripsAction() {

        foreach ($this->params['request_data'] as $intKey => $inputData) {
            $inputData['user_trip_id'] = $inputData['server_id'];
            if (list($valid, $response) = $this->getModel("user-trips")->validateTripForm($inputData) and !$valid) {
                $arrResponse[$intKey] = "Error";
            }

            $inputData["user_id"] = $this->user["user_id"];

            if (list($valid, $response) = $this->getModel("user-trips")->addEditTrip($inputData) and !$valid) {
                $arrResponse[$intKey] = "Error";
            } else {
                $arrResponse[$intKey] = $response;
            }
        }

        if (is_array($arrResponse)) {
            $this->generateResponse($arrResponse);
        }
    }

    /**
     * this action is to save trip images
     */
    function saveTripImagesAction() {

        $inputData["user_id"] = $this->user["user_id"];

        foreach ($this->params['image_data'] as $intParentKey => $arrImgData) {
            unset($inputData["user_trip_id"]);
            $inputData["user_trip_id"] = $arrImgData["server_id"];

            foreach ($arrImgData["request_data"] as $intKey => $arrImageData) {
                if (list($valid, $response) = $this->getModel("user-trips")->saveTripImage($arrImageData, $inputData) and !$valid) {

                    //$arrResponse[$intKey] = $response;
                } else {
                    //$arrResponse[$intParentKey]['server_id'] = $arrImgData["server_id"];
                    //$arrResponse[$intParentKey][$intKey] = $response;
					$arrResponse[$intKey] = $response;
                }
            }
        }

        if (is_array($arrResponse)) {
            $this->generateResponse($arrResponse);
        }
    }

    /**
     * this action is to save trip images
     */
    function saveTripImageAction() {

        $inputData["user_id"] = $this->user["user_id"];
        $inputData["user_trip_id"] = $this->user["server_id"];
        if (list($valid, $response) = $this->getModel("user-trips")->saveTripImage($this->params, $inputData) and !$valid) {
            $this->generateResponse($response, "error");
        } else {
            $this->generateResponse($response);
        }
    }

    /*
     * this action is to delete trip images
     */

    function deleteTripImagesAction() {

        foreach ($this->params['request_data'] as $intKey => $inputData) {
            if (list($valid, $response) = $this->getModel("user-trips")->deleteTripImages($inputData) and !$valid) {
                //$arrResponse[$intKey] = $response;
            } else {
                $arrResponse[$intKey] = $response;
            }
        }
        if (is_array($arrResponse)) {
            $this->generateResponse($arrResponse);
        }
    }

    /**
     * this action is to get user trips
     */
    function getUserTripsAction() {
    	$response = $this->getModel("user-trips")->getUserTrips($this->user["user_id"], $this->params['timestamp']);

        if (isset($this->params['timestamp']) and !empty($this->params['timestamp'])) {
            list($deleted_trips) = $this->getModel("user-trips")->getDeletedUserTrips($this->user["user_id"], $this->params['timestamp']);
            $this->setServiceHeader("deleted", array("server_ids" => $deleted_trips));
        }

        if (isset($response)) {
            $this->generateResponse($response);
        } else {
            $this->generateResponse($response, "error");
        }
    }

    /**
     * this action is to export trips
     */
    function exportTripsAction() {
        if ($this->params['type'] == "single") {
            $viewSection = $this->actionWithTemplate("user-trips", "view-detail-section", "services", $this->params['user_trip_id'], "view-pdf");
        } else {
            $viewSection = $this->actionWithTemplate("user-trips", "view-section", "services", $this->params['user_trip_id'], "view-pdf");
        }
        $response = $this->getModel('user-trips')->generateTripPdf($viewSection,'services',$this->params);
        $this->generateResponse($response);
        exit;
    }

    /**
     * this action is to view section
     */
    function viewSectionAction() {
        $this->view->usertrips = $this->getModel("user-trips")->getUserTripsByTripIds($this->params['user_trip_id']);
    }

    /**
     * this action is to view details
     */
    function viewDetailSectionAction() {
        $this->view->arrTripData = $this->getModel("user-trips")->getTripDataByTripId($this->params['user_trip_id']);
        $this->view->arrTripReference = $this->getModel('user-trips')->getReferenceByTripId($this->view->arrTripData['user_trip_id']);
        $this->view->arrTripExpense = $this->getModel('user-trips')->getTripTotalExpense($this->view->arrTripData['user_trip_id']);
        $this->view->arrTripData['trip_currency'] = $this->getModel('user-trips')->getCurrencySymbolById($this->view->arrTripData['trip_currency']);
    }

}
