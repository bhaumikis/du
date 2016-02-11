<?php

namespace admin\controllers;

class usersController extends adminGlobalController {

    /**
     * manageMyAccountAction is used to update user details.
     */
    function manageMyAccountAction() {

        $this->view->userDetails = $this->getModel("users")->getUserDetails($_SESSION[$this->session_prefix]['user']['user_id']);
        $this->view->countries = $this->getModel("miscellaneous")->getCountries();

        if ($_POST and $this->getModel("administrators")->validateProfileDetails()) {
            $this->getModel("users")->updateUserDetails();
            \generalFunctions::redirectToLocation($this->getModuleURL() . '/users/manage-my-account');
        }
    }

    /**
     * changePasswordAction is used to change user password.
     */
    function changePasswordAction() {
        if ($_POST and $this->getModel("users")->validateChangePassword()) {
            $this->getModel("users")->updatePassword();
            \generalFunctions::redirectToLocation($this->getModuleURL() . '/users/change-password');
        }
    }

    /**
     * changeSecurityQuestionAction is used to change user security question.
     */
    function changeSecurityQuestionAction() {
        if ($_POST and $this->getModel("users")->_validateSecurityQuestion()) {
            $this->getModel("users")->updateSecurityQuestion();
            \generalFunctions::redirectToLocation($this->getModuleURL() . '/users/change-security-question');
        }
        $this->view->userDetails = $this->getModel("users")->getUserDetails($_SESSION[$this->session_prefix]['user']['user_id']);
        $this->view->securityquestions = $this->getModel("security-questions")->getSecurityQuestions();
    }

    /**
     * endUsersAction is used to display list of all end users
     */
    function endUsersAction() {
        $this->view->hideassignedtofield = '0';
        if (!$this->checkLoggedInAsSuperAdmin()) {
            $this->view->hideassignedtofield = '1';
        }
        $this->view->users = $this->getModel("users")->getUsersList();

        $this->view->addExtraJS(array("path" => APPLICATION_URL . "/js/jquery-ui.min.js"));
        $this->view->addExtraCSS(array("path" => APPLICATION_URL . "/css/smoothness/jquery-ui.css"));
    }

    /**
     * adminUsersAction is used to display list of all admin users
     */
    function adminUsersAction() {

        if (!$this->checkLoggedInAsSuperAdmin()) {
            $_SESSION[$this->session_prefix]["error_message"] = _l("Error_Invalid_Access",'common');
            generalFunctions::redirectToLocation($this->getModuleURL() . '/dashboard');
        }
        if ($_POST) {
            $this->getModel("administrators")->setTicketAdmin();
        }        
        $this->view->administrators = $this->getModel("administrators")->getAdminUsersList();
        
        $this->view->addExtraJS(array("path" => APPLICATION_URL . "/js/jquery.confirm.min.js"));
        $this->view->addExtraJS(array("path" => APPLICATION_URL . "/js/bootstrap-datepicker.js"));
    }

    /**
     * addeditAction is used to add admin users.
     */
    function addeditAction() {

        if (!$this->checkLoggedInAsSuperAdmin()) {
            $_SESSION[$this->session_prefix]["error_message"] = _l("Error_Invalid_Access",'common');
            generalFunctions::redirectToLocation($this->getModuleURL() . '/dashboard');
        }

        if ($_POST and $this->getModel("administrators")->_validateAdminUserForm()) {
            $this->getModel("administrators")->addAdminUser();
            \generalFunctions::redirectToLocation($this->getModuleURL() . '/users/admin-users');
        }

        $this->view->user_id = 0;
        $this->view->userdetails = array();

        if (isset($_REQUEST["user_id"])) {
            $this->view->user_id = $_REQUEST["user_id"];
            $this->view->userdetails = $this->getModel("administrators")->getAdminDetails($_REQUEST["user_id"]);
        }
        $this->view->assignedcountries = $this->getModel("administrators")->getAssignedCountries($_REQUEST["user_id"]);
        $this->view->allassignedcountries = $this->getModel("administrators")->getAllAssignedCountries($_REQUEST["user_id"]);
        $this->view->countries = $this->getModel("miscellaneous")->getCountries();
    }

    /**
     * getRandomPasswordAction is used to get random password.
     */
    function getRandomPasswordAction() {
        echo \generalFunctions::genRandomPass(12);
        exit;
    }

    /**
     * setSecurityQuestionAction is used to set security question for admin user for first time.
     */
    function setSecurityQuestionAction() {
        if ($_POST and $this->getModel("administrators")->_validateSecurityForm()) {
            $this->getModel("administrators")->setSecurityQuestion();
            \generalFunctions::redirectToLocation($this->getModuleURL() . '/dashboard');
        }
        $this->view->securityquestions = $this->getModel("security-questions")->getSecurityQuestions('1');
    }

    /**
     * deleteAdminAction is used to delete admin user.
     */
    function deleteAdminAction() {
        if (!$this->checkLoggedInAsSuperAdmin()) {
            $_SESSION[$this->session_prefix]["error_message"] = _l("Error_Invalid_Access",'common');
            generalFunctions::redirectToLocation($this->getModuleURL() . '/dashboard');
        }

        $this->getModel("administrators")->deleteAdminUser($_GET["user_id"]);
        \generalFunctions::redirectToLocation($this->getModuleURL() . '/users/admin-users');
    }

    /**
     * assignAdminAction is used to assign admin to a particular end user.
     */
    function assignAdminAction() {
        if (!$this->checkLoggedInAsSuperAdmin()) {
            $_SESSION[$this->session_prefix]["error_message"] = _l("Error_Invalid_Access",'common');
            generalFunctions::redirectToLocation($this->getModuleURL() . '/dashboard');
        }

        if ($_POST and $this->getModel("administrators")->_validateAssignAdminForm()) {
            $this->getModel("administrators")->setAdmin();
            echo '<script type="text/javascript">window.parent.closeDialogBox();</script>';
            exit;
        }
        $this->view->adminlist = $this->getModel("administrators")->getAdminCountryList();
        $this->view->assignedadmin = $this->getModel("administrators")->getAssignedAdmin($_GET['user_id']);
        $this->view->userid = $_GET['user_id'];
        $this->setTemplate("plain");
    }

    /**
     * getCountryUserGroupAction is used to get country wise user count.
     */
    function getCountryUserCountAction() {
        if (empty($_POST['country_ids'])) {
            $country_ids = '0';
        } else {
            $country_ids = $_POST['country_ids'];
        }
        echo $this->getModel("administrators")->getCountryUserCount($country_ids);
        exit;
    }

    /**
     * setTicketAssignmentAction is used to set ticket assignment module to admin.
     */
    function unsetTicketAssignmentAction() {
        if (!$this->checkLoggedInAsSuperAdmin()) {
            $_SESSION[$this->session_prefix]["error_message"] = _l("Error_Invalid_Access",'common');
            generalFunctions::redirectToLocation($this->getModuleURL() . '/dashboard');
        }
        $this->getModel("administrators")->unsetTicketAdmin($_GET["user_id"]);
        \generalFunctions::redirectToLocation($this->getModuleURL() . '/users/admin-users');
    }
    
    function changeUserStatusAction(){
        if($this->getModel("users")->updateQuickUserStatus()){
            echo "Success";
        }
        else
        {
            echo "failed";
        }
        exit;
    }

}
