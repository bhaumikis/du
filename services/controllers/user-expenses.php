<?php

namespace services\controllers;

/**
 * @author Bhaumik Patel | bhaumik.patel@infostretch.com
 * brief  This class will contain all the the database actions related to user expense table
 */
class userExpensesController extends servicesGlobalController {

    /**
     * this action is to add expense
     */
    function addExpenseAction() {

        if (list($valid, $response) = $this->getModel("user-expenses")->validateExpenseForm($this->params) and !$valid) {
            $this->generateResponse($response, "error");
        }
        $inputData["user_id"] = $this->user["user_id"];
        if (list($valid, $response) = $this->getModel("user-expenses")->addEditExpense($this->params) and !$valid) {

            $this->generateResponse($response, "error");
        } else {
            $this->generateResponse($response);
        }
    }

    /**
     * this action is to add expenses
     */
    function addExpensesAction() {
        foreach ($this->params['request_data'] as $intKey => $inputData) {
            $inputData['user_expense_id'] = $inputData['server_id'];
            if (list($valid, $response) = $this->getModel("user-expenses")->validateExpenseForm($inputData) and !$valid) {
                $arrResponse[$intKey] = "Error";
            }

            $inputData["user_id"] = $this->user["user_id"];

            if (list($valid, $response) = $this->getModel("user-expenses")->addEditExpense($inputData) and !$valid) {
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
     * this action is to save expense images
     */
    function saveExpenseImageAction() {
        $inputData["user_id"] = $this->user["user_id"];

        foreach ($this->params['image_data'] as $intParentKey => $arrImgData) {
            unset($inputData["user_expense_id"]);
            $inputData["user_expense_id"] = $arrImgData["server_id"];

            foreach ($arrImgData["request_data"] as $intKey => $arrImageData) {
                if (list($valid, $response) = $this->getModel("user-expenses")->saveExpenseImage($arrImageData, $inputData) and !$valid) {

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
     * this action is to save expense images
     */
    function saveExpenseImagesAction() {
        $inputData["user_id"] = $this->user["user_id"];

        foreach ($this->params['image_data'] as $intParentKey => $arrImgData) {
            unset($inputData["user_expense_id"]);
            $inputData["user_expense_id"] = $arrImgData["server_id"];

            foreach ($arrImgData["request_data"] as $intKey => $arrImageData) {
                if (list($valid, $response) = $this->getModel("user-expenses")->saveExpenseImage($arrImageData, $inputData) and !$valid) {

                    //$arrResponse[$intKey] = $response;
                } else {
                    //$arrResponse[$intParentKey]['server_id'] = (int)$arrImgData["server_id"];
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
     * this action is to delete expense
     */
    function deleteExpensesAction() {

        foreach ($this->params['request_data'] as $intKey => $inputData) {
            $inputData['user_expense_id'] = $inputData['server_id'];
            if (list($valid, $response) = $this->getModel("user-expenses")->validateExpenseId($inputData) and !$valid) {
                $arrResponse[$intKey] = "Error";
            }

            $inputData["user_id"] = $this->user["user_id"];

            if (list($valid, $response) = $this->getModel("user-expenses")->deleteExpense($inputData) and !$valid) {
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
     * this action is to update expense
     */
    function updateExpensesAction() {

        foreach ($this->params['request_data'] as $intKey => $inputData) {
            $inputData['user_expense_id'] = $inputData['expense_id'];
            if (list($valid, $response) = $this->getModel("user-expenses")->validateExpenseForm($inputData) and !$valid) {
                $arrResponse[$intKey] = "Error";
            }

            $inputData["user_id"] = $this->user["user_id"];

            if (list($valid, $response) = $this->getModel("user-expenses")->addEditExpense($inputData) and !$valid) {
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
     * this action is to delete expense images
     */
    function deleteExpenseImagesAction() {

        foreach ($this->params['request_data'] as $intKey => $inputData) {
            if (list($valid, $response) = $this->getModel("user-expenses")->deleteExpenseImages($inputData) and !$valid) {
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
     * this action is to get user expenses
     */
    function getUserExpensesAction() {
        $response = $this->getModel("user-expenses")->getAllUserExpenses($this->user["user_id"], $this->params['timestamp']);

        if (isset($this->params['timestamp']) and !empty($this->params['timestamp'])) {
            list($deleted_expenses) = $this->getModel("user-expenses")->getDeletedUserExpenses($this->user["user_id"], $this->params['timestamp']);
            $this->setServiceHeader("deleted", array("server_ids" => $deleted_expenses));
        }

        if (isset($response)) {
            $this->generateResponse($response);
        } else {
            $this->generateResponse($response, "error");
        }
    }

    /**
     * this action is to export report
     */
    function exportExpensesAction() {
        if ($this->params['type'] == "single") {
            $viewSection = $this->actionWithTemplate("user-expenses", "view-detail-section", "services", $this->params['user_expense_id'], "view-pdf");
        } else {
            $viewSection = $this->actionWithTemplate("user-expenses", "view-section", "services", $this->params['user_expense_id'], "view-pdf");
        }
        $response = $this->getModel('user-expenses')->generateExpensePdf($viewSection,'services',$this->params);
        $this->generateResponse($response);
        exit;
    }

    /**
     * this action is to view section
     */
    function viewSectionAction() {
        $this->view->userexpenses = $this->getModel("user-expenses")->getUserExpenseByExpenseIds($this->params['user_expense_id']);
    }

    /**
     * this action is to view section
     */
    function viewDetailSectionAction() {
        $this->view->expense_id = $this->params['user_expense_id'];
        $this->view->arrUserExpense = $this->getModel('user-expenses')->getUserExpenseById($this->params['user_expense_id']);
        $this->view->arrExpenseReference = $this->getModel('user-expenses')->getReferenceByExpenseId($this->view->arrUserExpense['user_expense_id']);
        $this->view->arrExpenseCategory = $this->getModel('user-expenses')->getCategoryNameByExpenseId($this->view->arrUserExpense['expense_category_id']);
    }

}
