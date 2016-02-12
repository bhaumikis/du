<?php

namespace userportal\controllers;

/*
 * User Controller - handle variouse user actions
 */
class usersController extends globalController {

    /**
     * resetPasswordAction is used to reset user password.
     */
    function resetPasswordAction() {
        if ($_POST and $this->getModel("users")->validatePassword()) {
            $this->getModel("users")->updatePassword();
            \generalFunctions::redirectToLocation($this->getModuleURL());
        }
        $this->setTemplate("plain");
    }

    /**
     * myProfileAction is used to display user information.
     */
    function myProfileAction() {
        if (!isset($_SESSION[$this->session_prefix]['profile_access'])) {
            \generalFunctions::redirectToLocation($this->getModuleURL() . "/users/check-credentials");
        }

        $this->view->userDetails = $this->getModel("users")->getUserDetails($_SESSION[$this->session_prefix]['user']['user_id']);
        $this->view->countries = $this->getModel("miscellaneous")->getCountries();

        if ($_POST and $this->getModel("users")->validateUserDetails()) {
            $this->getModel("users")->updateUserDetails();
            \generalFunctions::redirectToLocation($this->getModuleURL() . '/users/my-profile');
        }
    }

    /**
     * checkCredentialsAction is used to validate user password to access my profile section.
     */
    function checkCredentialsAction() {
        if ($_POST and $this->getModel("users")->validateCredentials()) {
            $_SESSION[$this->session_prefix]['profile_access'] = 'true';
            \generalFunctions::redirectToLocation($this->getModuleURL() . '/users/my-profile');
        }
        $this->view->userDetails = $this->getModel("users")->getUserDetails($_SESSION[$this->session_prefix]['user']['user_id']);
    }

    /**
     * changePasswordAction is used to change user password.
     */
    function changePasswordAction() {
        if ($_POST and $this->getModel("users")->validateChangePassword()) {
            $this->getModel("users")->updatePassword();
            \generalFunctions::redirectToLocation($this->getModuleURL() . '/users/my-profile');
        }
        $this->view->userDetails = $this->getModel("users")->getUserDetails($_SESSION[$this->session_prefix]['user']['user_id']);
    }

    /**
     * changeSecurityQuestionAction is used to change user security question.
     */
    function changeSecurityQuestionAction() {
        if ($_POST and $this->getModel("users")->_validateSecurityQuestion()) {
            $this->getModel("users")->updateSecurityQuestion();
            \generalFunctions::redirectToLocation($this->getModuleURL() . '/users/my-profile');
        }
        $this->view->userDetails = $this->getModel("users")->getUserDetails($_SESSION[$this->session_prefix]['user']['user_id']);
        $this->view->securityquestions = $this->getModel("security-questions")->getSecurityQuestions();
    }

    /**
     * appSettings is used to change user settings.
     */
    function appSettingsAction() {
        if ($_POST and $this->getModel("users")->_validateAppSettings()) {
            $this->getModel("users")->updateAppSettings();
            \generalFunctions::redirectToLocation($this->getModuleURL() . '/users/app-settings');
        }
        $this->view->userDetails = $this->getModel("users")->getUserDetails($_SESSION[$this->session_prefix]['user']['user_id']);

        $arrLanguages = $this->getModel("miscellaneous")->getLanguages(0);
        $this->view->dropdown['language'] = $this->getModel("miscellaneous")->getDropdownFormat($arrLanguages, "code", "title");

        $arrCountries = $this->getModel("miscellaneous")->getCountryList(0);
        $this->view->dropdown['country'] = $this->getModel("miscellaneous")->getDropdownFormat($arrCountries, "country_id", "name"); //1=>india

        $this->view->appSettings['default'] = $this->getModel("users")->getDefaultAppSettings();
        $this->view->appSettingsvalues = $this->getModel("users")->getUserAppSettings();
    }

    /**
     * editProfileAction is used to update user profile.
     */
    function editProfileAction() {
        if (!isset($_SESSION[$this->session_prefix]['profile_access'])) {
            \generalFunctions::redirectToLocation($this->getModuleURL() . "/users/check-credentials");
        }

        $this->view->userDetails = $this->getModel("users")->getUserDetails($_SESSION[$this->session_prefix]['user']['user_id']);
        $this->view->countries = $this->getModel("miscellaneous")->getCountries();
        $this->view->currencies = $this->getModel("miscellaneous")->getCurrenciesList();
        
        if ($_POST and $this->getModel("users")->validateUserDetails()) {
            $this->getModel("users")->updateUserDetails();
            \generalFunctions::redirectToLocation($this->getModuleURL() . '/users/my-profile');
        }
        $this->view->addExtraJS(array("path" => APPLICATION_URL . "/js/jquery.confirm.min.js"));
    }
    
    /**
     * uploadTemporaryImageAction is used to upload temporary image for user.
     */
    function uploadTemporaryImageAction() {
        if (isset($_FILES) and !empty($_FILES['user_image']['name'])) {
            $ret = $this->getModel("users")->uploadTemporaryImage();
            $this->view->uploadfile["uploadfile"] = $ret;
            $this->view->uploadfile["uploadfileOrig"] = str_replace("-", "_", $_FILES['user_image']['name']);
        }
        $this->setTemplate("imagetemplate");
    }
	
	function testAction()
	{
		//@todo: testing git commit
	}
}
