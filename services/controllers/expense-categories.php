<?php

namespace services\controllers;

/**
 * @author Bhaumik Patel | bhaumik.patel@infostretch.com
 * brief  This class will contain all the the database actions related to users table
 */
class expenseCategoriesController extends servicesGlobalController {

    /**
     * this action is to add category
     */
    function addCategoryAction() {
        $this->params["user_id"] = $this->user["user_id"];
        if (list($valid, $response) = $this->getModel("expense-categories")->validateCategoryForm($this->params) and !$valid) {
            $this->generateResponse($response, "error");
        }
        if (list($valid, $response) = $this->getModel("expense-categories")->addEditCategory($this->params) and !$valid) {

            $this->generateResponse($response, "error");
        } else {
            $this->generateResponse($response);
        }
    }

    /**
     * this action is to add categories
     */
    function addCategoriesAction() {
        foreach ($this->params['request_data'] as $intKey => $inputData) {
            $inputData['expense_category_id'] = $inputData['server_id'];
            $inputData["user_id"] = $this->user["user_id"];
            if (list($valid, $response) = $this->getModel("expense-categories")->validateCategoryForm($inputData) and !$valid) {
                $arrResponse[$intKey] = "Error";
            }

            if (list($valid, $response) = $this->getModel("expense-categories")->addEditCategory($inputData) and !$valid) {
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
     * this action is to delete categories
     */
    function deleteCategoriesAction() {

        foreach ($this->params['request_data'] as $intKey => $inputData) {
            $inputData['expense_category_id'] = $inputData['server_id'];
            if (list($valid, $response) = $this->getModel("expense-categories")->validateCategoryId($inputData) and !$valid) {
                $arrResponse[$intKey] = "Error";
            }

            $inputData["user_id"] = $this->user["user_id"];

            if (list($valid, $response) = $this->getModel("expense-categories")->deleteCategory($inputData) and !$valid) {
                //$arrResponse[$intKey] = "Error";
            } else {
                $arrResponse[$intKey] = $response;
            }
        }

        if (is_array($arrResponse)) {
            $this->generateResponse($arrResponse);
        }
        die;
    }

    /**
     * this action is to update category
     */
    function updateCategoriesAction() {
        $inputData['expense_category_id'] = $inputData['server_id'];
        foreach ($this->params['request_data'] as $intKey => $inputData) {
            if (list($valid, $response) = $this->getModel("expense-categories")->validateCategoryForm($inputData) and !$valid) {
                $arrResponse[$intKey] = "Error";
            }

            $inputData["user_id"] = $this->user["user_id"];

            if (list($valid, $response) = $this->getModel("expense-categories")->addEditCategory($inputData) and !$valid) {
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
     * this action is to get categories
     */
    function getUserCategoriesAction() {
        $response = $this->getModel("expense-categories")->getUserCategories($this->user["user_id"], $this->params['timestamp']);

        if (isset($this->params['timestamp']) and !empty($this->params['timestamp'])) {
            list($deleted_categories) = $this->getModel("expense-categories")->getDeletedUserCategories($this->user["user_id"], $this->params['timestamp']);
            $this->setServiceHeader("deleted", array("server_ids" => $deleted_categories));
        }

        if (isset($response)) {
            $this->generateResponse($response);
        } else {
            $this->generateResponse($response, "error");
        }
    }

}
