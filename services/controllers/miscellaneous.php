<?php

namespace services\controllers;

/**
 * @author Bhaumik Patel | bhaumik.patel@infostretch.com
 * brief  This class will contain all the the database actions related to miscellaneous
 */
class miscellaneousController extends servicesGlobalController {

    /**
     * this action is used to get the countries list
     */
    function getCountryListAction() {

        if ($response = $this->getModel("miscellaneous")->getCountryList()) {
            $this->generateResponse($response);
        } else {
            $this->generateResponse(array(array("code" => "1", "message" => _l("Error while retriving countries.", "services"))), "error");
        }
    }

    /**
     * this action is used to get the currency list
     */
    function getCurrenciesListAction() {

        if ($response = $this->getModel("miscellaneous")->getCurrenciesList()) {
            $this->generateResponse($response);
        } else {
            $this->generateResponse(array(array("code" => "1", "message" => _l("Error while retriving currencies.", "services"))), "error");
        }
    }

    /**
     * this action is used to get the security question list
     */
    function getSecurityQuestionAction() {

        if ($response = $this->getModel("miscellaneous")->getSecurityQuestionList()) {
            $this->generateResponse($response);
        } else {
            $this->generateResponse(array(array("code" => "1", "message" => _l("Error while security questions.", "services"))), "error");
        }
    }

    /**
     * this action is used to get default data
     */
    function getDefaultDataAction() {
        if ($response = $this->getModel("miscellaneous")->getDefaultData()) {
            $this->generateResponse($response);
        } else {
            $this->generateResponse(array(array("code" => "1", "message" => _l("Error while retriving data.", "services"))), "error");
        }
    }

    /**
     * this action is used to get the currenct rates
     */
    function exchangeCurrencyRateAction() {
        if ($response = $this->getModel("miscellaneous")->changeCurrency($this->params)) {
            $this->generateResponse($response);
        } else {
            $this->generateResponse(array(array("code" => "1", "message" => _l("Error while retriving data.", "services"))), "error");
        }
    }

    /**
     * this action is used to add images for the testing purposet to upload image
     */
    function addImagesAction() {
        if (list($valid, $response) = $this->getModel("miscellaneous")->saveAllImages($this->params) and !$valid) {

            $this->generateResponse($response, "error");
        } else {
            $this->generateResponse($response);
        }
    }

    /**
     * this action is used to get all images
     */
    function getAllImagesAction() {
        $this->params["user_id"] = $this->user["user_id"];
        if (list($valid, $response) = $this->getModel("miscellaneous")->getAllImages($this->params) and !$valid) {
            $this->generateResponse($response, "error");
        } else {
            if (isset($this->params['timestamp']) and !empty($this->params['timestamp'])) {
                $deleted_expenses = $this->getModel("miscellaneous")->getDeletedImages($this->user["user_id"], $this->params['timestamp']);
                $this->setServiceHeader("deleted", $deleted_expenses);
            }
            $this->generateResponse($response);
        }
    }

    /**
     * this action is used to update tables wrt LUIDs
     */
    function updateLuidsAction() {
        if (list($valid, $response) = $this->getModel("miscellaneous")->updateServerIDs($this->params) and !$valid) {

            $this->generateResponse($response, "error");
        } else {
            $this->generateResponse($response);
        }
    }

    /**
     * this action is to chech rate exchange is available or not
     */
    function checkCurrencyRateStatusAction() {

        $arrData = $this->getModel("miscellaneous")->checkExchangeRateAvailability($this->params['currency_code']);

        if (isset($arrData['currency']) and !empty($arrData['currency'])) {
            $this->generateResponse(array("status" => "yes"));
        } else {
            $this->generateResponse(array("status" => "no"));
        }
    }

}

