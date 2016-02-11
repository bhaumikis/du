<?php

namespace admin\controllers;

include_once($this->module_path . "/controllers/users.php");
include(APPLICATION_PATH . "/lib/recaptcha/recaptchalib.php");

class forgotpasswordController extends usersController {

    function preActionDispatch() {

    }

    /**
     * indexAction is used for forgot password.
     */
    function indexAction() {
        if ($_POST and $this->getModel("users")->validateForgotPasswordForm()) {
            $this->getModel("users")->sendForgotPasswordMail();
            \generalFunctions::redirectToLocation($this->getModuleURL());
        }
        $this->view->captcha_public_key = \generalFunctions::getConfValue('captcha_public_key');
        $this->view->securityquestions = $this->getModel("security-questions")->getSecurityQuestions();
        $this->setTemplate("plain");
    }

    /**
     * resetPasswordAction is used to reset user password.
     */
    function resetPasswordAction() {
        if (!$this->getModel("users")->checkForgotPasswordToken($_GET['i'])) {
            \generalFunctions::redirectToLocation($this->getModuleURL());
        }

        if ($_POST and $this->getModel("users")->validateResetPasswordForm()) {
            $this->getModel("users")->resetAdminPassword($_GET['i']);
            \generalFunctions::redirectToLocation($this->getModuleURL());
        }
        $this->setTemplate("plain");
    }

}
