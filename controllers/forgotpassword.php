<?php

namespace userportal\controllers;

include_once($this->module_path . "/controllers/users.php");

/**
 * @author Bhaumik Patel | bhaumik.patel@infostretch.com
 * Forgot password Controller class
 */
class forgotpasswordController extends usersController {

    function preActionDispatch() {

    }

    /**
     * forgotPasswordAction is used to get new password for user.
     */
    function indexAction() {
        if ($_POST) {
            if ($this->getModel("security-questions")->validateSecurityQuestion()) {
                \generalFunctions::redirectToLocation($this->getModuleURL());
            }
        }
        $this->view->securityquestions = $this->getModel("security-questions")->getSecurityQuestions();
        $this->setTemplate("plain");
    }

}
