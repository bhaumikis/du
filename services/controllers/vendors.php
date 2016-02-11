<?php

namespace services\controllers;

/**
 * @author Bhaumik Patel | bhaumik.patel@infostretch.com
 * brief  This class will contain all the the database actions related to vendor table
 */
class vendorsController extends servicesGlobalController {

    /**
     * this action is to add vendor
     */
    function addVendorAction() {
        $this->params["user_id"] = $this->user["user_id"];
        if (list($valid, $response) = $this->getModel("vendors")->validateVendorForm($this->params) and !$valid) {
            $this->generateResponse($response, "error");
        }
        if (list($valid, $response) = $this->getModel("vendors")->addEditVendor($this->params) and !$valid) {

            $this->generateResponse($response, "error");
        } else {
            $this->generateResponse($response);
        }
    }

    /**
     * this action is to add vendors
     */
    function addVendorsAction() {

        foreach ($this->params['request_data'] as $intKey => $inputData) {
            $inputData['expense_vendor_id'] = $inputData['server_id'];
            if (list($valid, $response) = $this->getModel("vendors")->validateVendorForm($inputData) and !$valid) {
                $arrResponse[$intKey] = "Error";
            }

            $inputData["user_id"] = $this->user["user_id"];

            if (list($valid, $response) = $this->getModel("vendors")->addEditVendor($inputData) and !$valid) {
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
     * this action is to delete action
     */
    function deleteVendorsAction() {

        foreach ($this->params['request_data'] as $intKey => $inputData) {
            $inputData['expense_vendor_id'] = $inputData['server_id'];
            if (list($valid, $response) = $this->getModel("vendors")->validateVendorId($inputData) and !$valid) {
                $arrResponse[$intKey] = "Error";
            }

            $inputData["user_id"] = $this->user["user_id"];

            if (list($valid, $response) = $this->getModel("vendors")->deleteVendor($inputData) and !$valid) {
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
     * this action is to update vendor
     */
    function updateVendorsAction() {

        foreach ($this->params['request_data'] as $intKey => $inputData) {
            $inputData['expense_vendor_id'] = $inputData['server_id'];
            if (list($valid, $response) = $this->getModel("vendors")->validateVendorForm($inputData) and !$valid) {
                $arrResponse[$intKey] = "Error";
            }

            $inputData["user_id"] = $this->user["user_id"];

            if (list($valid, $response) = $this->getModel("vendors")->addEditVendor($inputData) and !$valid) {
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
     * this action is to get vendor
     */
    function getUserVendorAction() {
        $response = $this->getModel("vendors")->getVendorList($this->user["user_id"], $this->params['timestamp']);

        if (isset($this->params['timestamp']) and !empty($this->params['timestamp'])) {
            list($deleted_vendors) = $this->getModel("vendors")->getDeletedVendors($this->user["user_id"], $this->params['timestamp']);
            $this->setServiceHeader("deleted", array("server_ids" => $deleted_vendors));
        }

        if (isset($response)) {
            $this->generateResponse($response);
        } else {
            $this->generateResponse($response, "error");
        }
    }

}
