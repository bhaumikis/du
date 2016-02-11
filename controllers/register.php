<?php

namespace userportal\controllers;

include_once($this->module_path . "/controllers/users.php");

/*
 * User Registration Controller
 */
class registerController extends usersController {

    function preActionDispatch() {
        
    }
    
    /**
     * indexAction is used to register new user.
     */
    function indexAction() {
        if ($_POST) {            
            if ($this->getModel("users")->validateRegistrationForm()) {
                $this->getModel("users")->registerUser();
                \generalFunctions::redirectToLocation($this->getModuleURL());
            }
        }
        $this->view->securityquestions = $this->getModel("security-questions")->getSecurityQuestions('1');
        $this->view->countries = $this->getModel("miscellaneous")->getCountries();
        $this->view->currencies = $this->getModel("miscellaneous")->getCurrenciesList();
        $this->setTemplate("fullscreen");
        $this->view->addExtraJS(array("path" => APPLICATION_URL . "/js/common.js"));
        $this->view->addExtraJS(array("path" => APPLICATION_URL . "/js/jquery.confirm.min.js"));
    }
    
    /**
     * activationAction is used to activate new users.
     */
    function activationAction() {
        $this->getModel("users")->getUserTokenData($_GET['i']);
        $this->setTemplate("fullscreen");
    }
    
    /* (non-PHPdoc)
     * @see \userportal\controllers\usersController::uploadTemporaryImageAction()
     */
    function uploadTemporaryImageAction() {
        if (isset($_FILES) and !empty($_FILES['user_image']['name'])) {
            $ret = $this->getModel("users")->uploadTemporaryImage();
            $this->view->uploadfile["uploadfile"] = $ret;
            $this->view->uploadfile["uploadfileOrig"] = str_replace("-", "_", $_FILES['user_image']['name']);
        }
        $this->setTemplate("imagetemplate");
    }
    
    /**
     * Action to check exchange rate availability
     * Print 1 if available else print 0. 
     */
    function checkExchangeRateAvailabilityAction() {
        if(isset($_POST['cur_code'])) {
          $data = $this->getModel("miscellaneous")->checkExchangeRateAvailabilityById($_POST['cur_code']);
        }else {
            $data = $this->getModel("miscellaneous")->checkExchangeRateAvailabilityById($_POST['cur_id']);
        }
        if(isset($data['currency']) and !empty($data['currency'])) {
            echo '1';
        } else {
            echo '0';
        }exit;
    }
}
