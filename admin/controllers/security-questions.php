<?php

namespace admin\controllers;

class securityQuestionsController extends adminGlobalController {

    /**
     * indexAction is used to display list of all security questions.
     */
    function indexAction() {        
        $this->view->securityquestions = $this->getModel("security-questions")->getSecurityQuestionsList();
    }

    /**
     * addAction is used to add security questions.
     */
    function addAction() {

        if ($_POST and $this->getModel("security-questions")->validateSecurityForm()) {
            $this->getModel("security-questions")->addSecurityQuestion();
            \generalFunctions::redirectToLocation($this->getModuleURL() . "/security-questions");
        }
    }

}
