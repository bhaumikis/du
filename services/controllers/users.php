<?php

namespace services\controllers;

/**
 * @author Bhaumik Patel | bhaumik.patel@infostretch.com
 * brief  This class will contain all the database actions related to users table and user operation
 */
class usersController extends servicesGlobalController {

    /**
     * DU - login action is used to check the user authorization web service using login parameters
     */
    function loginAction() {
        if (list($valid, $response) = $this->getModel("users")->validateLoginParams($this->params) and !$valid) {
            $this->generateResponse($response, "error");
        }
        if (list($valid, $response) = $this->getModel("users")->authenticateUser($this->params) and !$valid) {
            $this->generateResponse($response, "error");
        } else {
            $this->generateResponse($response);
        }
    }

    /**
     * DU - register Action is used to register the user for particular application for the portal
     */
    function registerAction() {

        if (list($valid, $response) = $this->getModel("users")->validateUserForm($this->params) and !$valid) {
            $this->generateResponse($response, "error");
        }
        if (list($valid, $response) = $this->getModel("users")->register($this->params) and !$valid) {

            $this->generateResponse($response, "error");
        } else {
            $this->generateResponse($response);
        }
    }

    /**
     * ForogotPassword Action is used to set the new password and send email with the new password to the user from the application
     */
    function forgotpasswordAction() {
        if (list($valid, $response) = $this->getModel("users")->forgotPassword($this->params) and !$valid) {
            $this->generateResponse($response, "error");
        } else {
            $this->generateResponse($response);
        }die;
    }

    /**
     * updateProfile action is used to update the profile details of the user from the application
     */
    function updateProfileAction() {
        $user_id = $this->user["user_id"];
        //$userDetails = $this->getModel("users")->getUserDetails($user_id);
        //$this->params['user_id'] = $user_id;

        $response = $this->getModel("users")->updateUserProfile($this->params, $user_id);
        $this->generateResponse($response);
    }

    /**
     * updateSyncTime action is used to update sync time for newly added user
     */
    function updateSyncTimeAction() {
        $user_id = $this->user["user_id"];

        if (!$this->getModel("users")->getUserSyncStatus($user_id)) {
            $this->getModel("users")->setFirstUserSyncTime($user_id);
        }

        $response = $this->getModel("users")->updateUserSyncTime($user_id);
        $this->generateResponse($response);
    }

    /**
     * change Password Action is used to change the password for existing user from application
     */
    function changePasswordAction() {
        $user_id = $this->user["user_id"];
        if (list($valid, $response) = $this->getModel("users")->validateChangePasswordService($this->params, $user_id) and !$valid) {
            $this->generateResponse($response, "error");
        } else {
            $response = $this->getModel("users")->changePasswordForAppUser($this->params, $user_id);
            $this->generateResponse(array("user_id" => $user_id));
        }
    }

    /**
     * DU - Logout Action is used to remove the token of the loggedin user.
     */
    function logoutAction() {
        if (list($valid, $response) = $this->getModel("users")->logout($this->params['token']) and !$valid) {
            $this->generateResponse($response, "error");
        } else {
            $this->generateResponse($response);
        }
    }

    /**
     * setDeviceToken is used to set the device token and platform for the users from which he has logged in for the last application.
     */
    function setDeviceTokenAction() {
        if (list($valid, $response) = $this->getModel("users")->validateSetDeviceTokenService($this->params) and !$valid) {
            $this->generateResponse($response, "error");
        }
        $user_id = $this->user["user_id"];
        $this->getModel("users")->updateDevideTokenAndPlatform($this->params, $user_id);
        $response = array('code' => 200, 'message' => _l("success", "services"));
        $this->generateResponse($response);
    }

    /**
     * This action is defined to get list of users on application id using token of a user
     */
    function getUsersAction() {

        $user_id = $this->user["user_id"];

        $response = $this->getModel("users")->getApplicationUsersList($user_id, $this->params);

        if (isset($this->params['timestamp']) and !empty($this->params['timestamp'])) {
            list($deleted_users) = $this->getModel("users")->getDeletedUsers($this->params['timestamp']);
            $this->setServiceHeader("deleted", array("user_id" => $deleted_users));
        }

        if (isset($response)) {
            $this->generateResponse($response);
        } else {
            $this->generateResponse($response, "error");
        }
    }

    /**
     * This action is defined to get details of a user, sessions attended by a user and the the users who attending the session with current user
     */
    function getUserDetailsAction() {

        $user_id = $this->user["user_id"];

        $response = $this->getModel("users")->getUserDetailsAndSchedules($user_id, $this->params);

        if (isset($response)) {
            $this->generateResponse($response);
        } else {
            $this->generateResponse($response, "error");
        }
    }

    /**
     * This action is defined to get details of a user, sessions attended by a user and the the users who attending the session with current user
     */
    function getUpdatedUserDetailsAction() {

        $user_id = $this->user["user_id"];
        $application_id = $this->user["application_id"];
        if (list($valid, $response) = $this->getModel("users")->validateTimestampForUserDetails($this->params) and !$valid) {
            $this->generateResponse($response, "error");
        }

        $response = $this->getModel("users")->getUpdatedUserDetails($user_id, $this->params);

        if (isset($this->params['timestamp']) and !empty($this->params['timestamp'])) {
            list($user_notes, $favourite_exhibitors, $schedule_attendee, $deleted_users_attending_session_with_me) = $this->getModel("users")->getDeletedUserDetails($application_id, $user_id, $this->params['timestamp']);
            $this->setServiceHeader("deleted", array("user_note" => $user_notes, "favourite_exhibitors" => $favourite_exhibitors, "schedule_attendee" => $schedule_attendee, "attending_session_with_me" => $deleted_users_attending_session_with_me));
        }

        if (isset($response)) {
            $this->generateResponse($response);
        } else {
            $this->generateResponse($response, "error");
        }
    }

    /**
     * This action is defined to get details of user related data.
     */
    function getUserDataAction() {

        $user_id = $this->user["user_id"];

        $response = $this->getModel("users")->getUserData($user_id, $this->params);

        if (isset($response)) {
            $this->generateResponse($response);
        } else {
            $this->generateResponse($response, "error");
        }
    }

    /**
     * This action is defined to change base currency of user.
     */
    function changeBaseCurrencyAction() {
        $user_id = $this->user["user_id"];

        if (list($valid, $response) = $this->getModel("users")->updateUserDataByField($user_id, "base_currency_id", $this->params['base_currency_id']) and !$valid) {
            $this->generateResponse($response, "error");
        } else {
            $this->generateResponse($response);
        }
    }

    /**
     * This action is defined to change base currency of user.
     */
    function changeSecurityQuestionAction() {
        $user_id = $this->user["user_id"];

        if (list($valid, $response) = $this->getModel("users")->validateSecurityQuestion($this->params, $user_id) and !$valid) {
            $this->generateResponse($response, "error");
        } else {
            $this->generateResponse($response);
        }
    }

    /**
     * This action is to save user images
     */
    function saveUserImageAction() {
        $this->params["user_id"] = $this->user["user_id"];

        if (list($valid, $response) = $this->getModel("users")->saveUserImage($this->params) and !$valid) {

            $this->generateResponse($response, "error");
        } else {
            $this->generateResponse($response);
        }
    }

    /**
     * This action is to delete user images
     */
    function deleteUserImageAction() {
        $this->params["user_id"] = $this->user["user_id"];

        if ($this->getModel("users")->deleteImage($this->user["user_id"])) {

            $this->generateResponse($response, "error");
        } else {
            $this->generateResponse(array("success" => true));
        }
    }

    /**
     * This action is to get user profile
     */
    function getUserProfileAction() {
        $this->params["user_id"] = $this->user["user_id"];

        if (list($valid, $response) = $this->getModel("users")->getUserProfile($this->params["user_id"], 'url') and !$valid) {

            $this->generateResponse($response, "error");
        } else {
            $this->generateResponse($response);
        }
    }

    /**
     * This action is to get user security question
     */
    function getUserSecurityQuestionAction() {
        $this->params["user_id"] = $this->user["user_id"];

        if (list($valid, $response) = $this->getModel("users")->getUserSecurityQuestionId($this->params["user_id"]) and !$valid) {

            $this->generateResponse($response, "error");
        } else {
            $this->generateResponse($response);
        }
    }

    function resetSyncMappingAction() {
        $this->params["user_id"] = $this->user["user_id"];

        if (list($valid, $response) = $this->getModel("users")->resetUserSyncMapping($this->params) and !$valid) {

            $this->generateResponse($response, "error");
        } else {
            $this->generateResponse($response);
        }
    }

}
