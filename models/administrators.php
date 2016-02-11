<?php

namespace model;

/**
 * @author Bhaumik Patel | bhaumik.patel@infostretch.com
 * administratorsModel Class
 * brief Administrators Model contains application logic for various functions and database operations of Administrators Module.
 */
class administratorsModel extends globalModel {

    public $cookie_life_time = 8640000; /* 60 * 60 * 24 * 100 */

    /**
     * This function is used to get the list of administrators
     * @param $order_by
     * @param $sortby
     * @return $administrators
     */
    function listAdministrators($order_by, $sortby) {

        $sql = "SELECT a.*,u.title AS utitle FROM `administrators` AS `a` LEFT JOIN `usertypes` AS `u` ON (a.usertype_id = u.usertype_id) ORDER BY " . $order_by;
        $this->view->pager = new PS_Pagination($this->database->link, $sql, \generalFunctions::getConfValue("rows_per_page"), \generalFunctions::getConfValue("links_per_page"), "sortby=" . $sortby);
        $result = $this->view->pager->paginate();

        $administrators = array();
        if ($result) {
            while ($row = $this->database->fetchAssoc($result)) {
                $administrators[] = $row;
            }
        }

        return $administrators;
    }

    /**
     * DU - This function is used to check the validation for the logn based on the module (admin portal/customers portal)
     * @param $module
     * @return boolean
     */
    function checkValidLogin($module = "default") {

        if ($module == "default") {
            if (!isset($_POST["mobile_number"]) or !strlen($_POST["mobile_number"]) or !isset($_POST["password"]) or !strlen($_POST["password"])) {
                $_SESSION[$this->session_prefix]["error_message"] = _l("Required_Fields", "users");
                return false;
            }
            $sql = "SELECT u.* FROM users AS u INNER JOIN usertypes AS ut ON (u.usertype_id = ut.usertype_id) WHERE u.mobile_number = :mobile_number AND u.password = :password AND u.status = :status";

            if (!$userResult = $this->database->queryOne($sql, array(':mobile_number' => trim($_POST["mobile_number"]), ':password' => md5($_POST["password"]), ':status' => '1'))) {
                $_SESSION[$this->session_prefix]["error_message"] = _l("Mobile_Pass_Not_Match", "users");
                return false;
            }
            // Check if user has activated his account.
            if (!$this->getDBTable("user-account-activation")->fetchRow(array("where" => "user_id = :user_id AND activation_flag = :activation_flag", "params" => array(':user_id' => $userResult['user_id'], ':activation_flag' => '1')))) {
                $_SESSION[$this->session_prefix]["error_message"] = _l("Account_Inactive", "users");
                return false;
            }
        } else {
            if (!isset($_POST["email"]) or !strlen($_POST["email"]) or !isset($_POST["password"]) or !strlen($_POST["password"])) {
                $_SESSION[$this->session_prefix]["error_message"] = _l("Required_Fields", "users");
                return false;
            }
            if (!$userResult = $this->getDBTable("users")->fetchRow(array("where" => "`email` = :email AND  `password` = :password AND `status` = :status AND usertype_id <> :usertype_id", "params" => array(':email' => trim($_POST["email"]), ':password' => md5($_POST["password"]), ':status' => '1', ":usertype_id" => 2)))) {
                $_SESSION[$this->session_prefix]["error_message"] = _l("Email_Pass_Not_Match", "users");
                return false;
            }
        }

        $usertype_details = $this->getDBTable("usertypes")->fetchRow(array("where" => "`usertype_id` = :usertype_id", "params" => array(':usertype_id' => $userResult["usertype_id"])));

        $assignmentRights = $this->getDBTable("ticket-assignment-details")->fetchRow(array("where" => "user_id = :user_id AND is_deleted = :is_deleted AND (CURDATE() BETWEEN date_from AND date_to)", "params" => array(':user_id' => $userResult["user_id"], ":is_deleted" => "0")));
        $rightsFlag = '0';
        if (isset($assignmentRights) and !empty($assignmentRights)) {
            $rightsFlag = '1';
        }

        $_SESSION[$this->session_prefix]["user"] = array("user_id" => $userResult["user_id"],
            "email" => $userResult["email"],
            "customer_id" => $userResult["customer_id"],
            "type" => $userResult["type"],
            "usertype_id" => $userResult["usertype_id"],
            "usertype_type" => $usertype_details["type"],
            "first_name" => $userResult["first_name"],
            "last_name" => $userResult["last_name"],
            "mobile_number" => $userResult["mobile_number"],
            "user_image" => $userResult["user_image"],
            "base_currency" => $userResult["base_currency"],
            "base_currency_id" => $userResult["base_currency_id"],
            "base_currency_code" => $this->getModel('miscellaneous')->getCurrencyCodeById($userResult["base_currency_id"]),
            "user_date_format" => $this->getModel('users')->getUserDateFormat($userResult["user_id"],"date_format"),
            "assign_tickets" => $rightsFlag,
            "city" => $userResult["city"],
            "country" => $userResult["country"],
            "customer_logo_set" => 0,
            "customer_logo" => "");
        if(isset($_POST["is_locale_set"]) && isset($_POST["client_date"]) && isset($_POST["client_timezone"])) {
        	$_SESSION[$this->session_prefix]['user']['client_locale'] = array ("date"=>$_POST["client_date"], "timezone"=>$_POST["client_timezone"]);
        }
        if ($module == "admin") {
            $_SESSION[$this->session_prefix]['user']['notification_count'] = $this->getModel("tickets")->getHeaderNotificationCount();
            if($userResult['password_flag'] == '1' and empty($userResult['security_question_id']) and empty($userResult['security_answer'])) {
                $_SESSION[$this->session_prefix]['user']["is_security_question_set"] = '1';
            }
        }
        
        if (isset($_POST['remember']) == '1') {
            setcookie("cookie_du_username", $_POST["mobile_number"], time() + $this->cookie_life_time, "/");
            setcookie("cookie_du_pass", $_POST["password"], time() + $this->cookie_life_time, "/");
        } else {
            setcookie("cookie_du_username", "", time() + $this->cookie_life_time, "/");
            setcookie("cookie_du_pass", "", time() + $this->cookie_life_time, "/");
        }

        if (isset($_POST['admin_remember']) == '1') {
            setcookie("cookie_admin_username", $_POST["email"], time() + $this->cookie_life_time, "/");
            setcookie("cookie_admin_pass", $_POST["password"], time() + $this->cookie_life_time, "/");
        } else {
            setcookie("cookie_admin_username", "", time() + $this->cookie_life_time, "/");
            setcookie("cookie_admin_pass", "", time() + $this->cookie_life_time, "/");
        }


        $this->setUserAccess($userResult["usertype_id"], $module);

        return true;
    }

    /**
     * DU - This function sets access for user
     * @param unknown $usertype_id
     * @param unknown $module
     */
    function setUserAccess($usertype_id, $module) {
        $_SESSION[$this->session_prefix]["user"]["access"] = array();

        if ($module == "admin" and !$this->checkLoggedInAsSuperAdmin()) {
            $resources = $this->database->queryData("SELECT m.module, m.option, m.action FROM resources AS m INNER JOIN `privileges` AS p ON (m.resource_id = p.resource_id) WHERE p.usertype_id = '" . $usertype_id . "' AND m.status = 1 AND (m.module IN ('','" . $module . "'))");

            switch ($module) {
                case "admin":
                    $resources[] = array("module" => "admin", "option" => "index", "action" => "*");
                    $resources[] = array("module" => "admin", "option" => "home", "action" => "*");
                    break;
                case "default":
                    $resources[] = array("module" => "default", "option" => "index", "action" => "*");
                    $resources[] = array("module" => "default", "option" => "home", "action" => "*");
                default:
                    break;
            }


            if (count($resources)) {
                for ($i = 0; $i < count($resources); $i++) {

                    if (strlen($resources[$i]["module"] . $resources[$i]["option"] . $resources[$i]["action"]) != 0) {
                        $_SESSION[$this->session_prefix]["user"]["access"][] = md5($resources[$i]["module"] . $resources[$i]["option"] . $resources[$i]["action"]);
                    } else {

                        $_SESSION[$this->session_prefix]["user"]["access"][] = md5($resources[$i]["title"]);
                    }
                }
            }

            $_SESSION[$this->session_prefix]["user"]["access"] = serialize($_SESSION[$this->session_prefix]["user"]["access"]);
        }
    }

    /**
     * DU - This function is used to perform the logout operation and unset all the session parameter for the portal
     * @return void
     */
    function logout() {
        $_SESSION[$this->session_prefix] = array();
        unset($_SESSION[$this->session_prefix]);
    }

    /**
     * DU - This function is used to validate admin user form
     * @return boolean
     */
    function _validateAdminUserForm() {
        $errors = array();

        if (!isset($_POST["first_name"]) or !\generalFunctions::valueSet($_POST["first_name"])) {
            $errors[] = _l("Enter_Firstname", "administrators");
        }
        if (!isset($_POST["last_name"]) or !\generalFunctions::valueSet($_POST["last_name"])) {
            $errors[] = _l("Enter_Lastname", "administrators");
        }
        if (!isset($_POST["country_id"]) or !\generalFunctions::valueSet($_POST["country_id"])) {
            $errors[] = _l("Enter_Country", "administrators");
        }
        if (!isset($_POST["email"]) or !\generalFunctions::valueSet($_POST["email"])) {
            $errors[] = _l("Enter_Email", "administrators");
        }
        if ($_POST["birth_date"] != "") {
            /* if (date("Y") - date("Y", strtotime($_POST["birth_date"])) < 15) {
              $errors[] = _l("User_Age", "users");
              } */
            if (time() < strtotime('+15 years', strtotime($_POST["birth_date"]))) {
                $errors[] = _l("User_Age", "users");
            }
        }
        if (empty($_POST['user_id'])) {
            if (!isset($_POST["password"]) or !\generalFunctions::valueSet($_POST["password"])) {
                $errors[] = _l("Enter_Password", "administrators");
            }
            if (!preg_match("/\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\W])(?=\S*[\d])\S*/", trim($_POST['password']))) {
                $errors[] = _l("Invalid_Password_String", "administrators");
            } else if ((isset($_POST["mobile_number"]) and !empty($_POST["mobile_number"])) and strpos($_POST["password"], $_POST["mobile_number"]) !== false) {
                $errors[] = _l("Password_Contains_Mobile_No", "administrators");
            }
        }

        if (count($errors)) {
            $_SESSION[$this->session_prefix]["error_message"] = $errors;
            return false;
        } else {
            $data = $this->getDBTable("users")->fetchRow(array("where" => "email = :email AND deleted = :deleted", "params" => array(":email" => $_POST['email'], ":deleted" => '0')));
            if (!empty($data) and (isset($_POST['user_id']) and !empty($_POST['user_id']) and $data['user_id'] != $_POST['user_id'])) {
                $errors = array();
                $errors[] = _l("Email_Exists", "administrators");
                $_SESSION[$this->session_prefix]["error_message"] = $errors;
                return false;
            }
        }
        return true;
    }

    /**
     * DU - This function is used to add admin users.
     */
    function addAdminUser() {
        $data = $_POST;
        unset($data['submit'], $data['assign_country'], $data['employee_id']);

        if (isset($data['birth_date']) and !empty($data['birth_date'])) {
            $data['birth_date'] = date('Y-m-d', strtotime($_POST['birth_date']));
        } else {
            $data['birth_date'] = '';
        }

        if ($data["user_id"]) {

            $this->adminCountryMapping($_POST['assign_country'], $data["user_id"]);

            $this->getDBTable("users")->update($data, array("where" => "user_id = :user_id", "params" => array(":user_id" => $data["user_id"])));
            $_SESSION[$this->session_prefix]["action_message"] = _l("Update_Success", "administrators");
        } else {
            $data['password'] = md5($_POST["password"]);
            $data['created_date'] = date('Y-m-d H:i:s');
            $data['updated_date'] = date('Y-m-d H:i:s');
            $data['status'] = '1';
            $data['deleted'] = '0';
            $data['usertype_id'] = '3';
            $data['password_flag'] = '1';

            $user_id = $this->database->insert("users", $data);

            $this->adminCountryMapping($_POST['assign_country'], $user_id);

            if ($_POST['email'] != '') {
                if (\generalFunctions::isValidEmail($_POST['email'])) {
                    $adminData = array();
                    $adminData["to_email"] = $_POST['email'];
                    $adminData["firstname"] = $_POST['first_name'];
                    $adminData["lastname"] = $_POST["last_name"];
                    $adminData["username"] = $_POST["email"];
                    $adminData["password"] = $_POST["password"];
                    $adminData["link"] = "<a href=" . APPLICATION_URL . "/admin>" . _l("Click Here", "common") . "</a>";

                    $this->sendEmail("admin_activation_mail", $adminData);
                    $_SESSION[$this->session_prefix]["action_message"] = _l("Activation_Mail_Sent", "administrators");
                }
            }

            $_SESSION[$this->session_prefix]["action_message"] = _l("Add_Success", "administrators");
        }
    }

    
    /**
     * DU - This function is used to update admin/user query mapping tables.
     * @param unknown $updatedCountry
     * @param unknown $admin_id
     */
    function adminCountryMapping($updatedCountry, $admin_id) {

        if (!isset($updatedCountry)) {
            $updatedCountry = array();
        }
        // Get list of countries assigned to admin for user queries.
        $assignedCountry = $this->getDBTable("user-area-mappings")->fetchAll(array("where" => "user_id = :user_id", "params" => array(":user_id" => $admin_id)));
        $countryIds = array();
        foreach ($assignedCountry as $d) {
            $countryIds[] = $d['country_id'];
        }
        // Get deleted country array.
        $removedCountries = array_diff($countryIds, $updatedCountry);

        if (isset($removedCountries) and !empty($removedCountries)) {
            foreach ($removedCountries as $countryid) {
                // Get users who belongs to deleted country.
                $userCountryData = array();
                $userCountryData = $this->getDBTable("users")->fetchAllByFields(array("user_id", "country_id"), array("where" => "country_id = :country_id", "params" => array(":country_id" => $countryid)));

                if (isset($userCountryData) and !empty($userCountryData)) {
                    foreach ($userCountryData as $udata) {
                        // Remove admin user relation.
                        $this->getDBTable("user-admin-relations")->delete(array("where" => "user_id = :user_id AND admin_id = :admin_id", "params" => array(":user_id" => $udata["user_id"], ":admin_id" => $admin_id)));
                    }
                }
            }
        }

        // Delete admin-country entry from mapping table.
        $this->getDBTable("user-area-mappings")->delete(array("where" => "user_id = :user_id", "params" => array(":user_id" => $admin_id)));

        // Insert new country assigned to admin in mapping table.
        if (isset($updatedCountry) and !empty($updatedCountry)) {
            foreach ($updatedCountry as $assign_country) {
                $mapData = array();
                $mapData['user_id'] = $admin_id;
                $mapData['country_id'] = $assign_country;
                $this->getDBTable("user-area-mappings")->insert($mapData);
                // Get users who belong to country assigned to admin.
                $userCountryData = array();
                $userCountryData = $this->getDBTable("users")->fetchAllByFields(array("user_id", "country_id"), array("where" => "country_id = :country_id AND usertype_id != :usertype_id", "params" => array(":country_id" => $assign_country, ":usertype_id" => '1')));

                if (isset($userCountryData) and !empty($userCountryData)) {
                    foreach ($userCountryData as $udata) {
                        // Insert into  user_admin_relations table.
                        $uamdata = array();
                        $uamdata['user_id'] = $udata['user_id'];
                        $uamdata['admin_id'] = $admin_id;
                        $this->getDBTable("user-admin-relations")->insert($uamdata);
                    }
                }
            }
        }
    }

    /**
     * DU - This function is used to get list of all admin users
     */
    function getAdminUsersList() {
        return $this->database->queryData("SELECT u.*, CONCAT(first_name,' ',last_name) as uname,c.name FROM `users` AS u
                                            LEFT JOIN countries AS c ON (c.country_id = u.country_id)
                                            WHERE usertype_id = :usertype_id ORDER BY user_id ASC", array(":usertype_id" => "3"));
    }

    /**
     * DU - This function get the details of the admin based on user_id
     */
    function getAdminDetails($user_id = 0) {
        return $this->getDBTable("users")->fetchRow(array("where" => "user_id = :user_id", "params" => array(":user_id" => $user_id)));
    }

   /**
    * DU - This function is used to validate security form when admin first time sets security information.
    * @return boolean
    */
   function _validateSecurityForm() {
        $errors = array();

        if (!isset($_POST["security_question_id"]) or !\generalFunctions::valueSet($_POST["security_question_id"])) {
            $errors[] = _l("Select_Security_Question", "administrators");
        }
        if (!isset($_POST["security_answer"]) or !\generalFunctions::valueSet($_POST["security_answer"])) {
            $errors[] = _l("Enter_Security_Answer", "administrators");
        }

        if (count($errors)) {
            $_SESSION[$this->session_prefix]["error_message"] = $errors;
            return false;
        }
        return true;
    }

    /**
     * DU - This function is used to set security question and answer of first time logged in admin user.
     */
    function setSecurityQuestion() {
        $data = $_POST;
        unset($data['submit']);
        $data['password_flag'] = '0';
        $this->getDBTable("users")->update($data, array("where" => "user_id = :user_id", "params" => array(":user_id" => $_SESSION[$this->session_prefix]['user']['user_id'])));
        $_SESSION[$this->session_prefix]["action_message"] = _l("Txt_Change_Password", "administrators");
        unset($_SESSION[$this->session_prefix]['user']['is_security_question_set']);
    }

    /**
     * DU - This function get assigned country to particular admin.
     * @param number $user_id
     * @return multitype:unknown 
     */
    function getAssignedCountries($user_id = 0) {
        $data = $this->getDBTable("user-area-mappings")->fetchAll(array("where" => "user_id = :user_id", "params" => array(":user_id" => $user_id)));
        $country_ids = array();
        if (isset($data) and !empty($data)) {
            foreach ($data as $d) {
                $country_ids[] = $d['country_id'];
            }
        }
        return $country_ids;
    }

    /**
     * DU - This function get assigned country list to all admin.
     * @param number $user_id
     * @return Ambigous <multitype:, unknown>
     */
    function getAllAssignedCountries($user_id = 0) {
        if ($user_id == 0 OR empty($user_id)) {
            $data = $this->database->queryData("SELECT CONCAT_WS(' ',u.first_name,u.last_name) AS aname, u.user_id, c.country_id FROM users AS u
                    INNER JOIN user_area_mappings AS c ON (c.user_id = u.user_id)");
        } else {
            $data = $this->database->queryData("SELECT CONCAT_WS(' ',u.first_name,u.last_name) AS aname, u.user_id, c.country_id FROM users AS u
                    INNER JOIN user_area_mappings AS c ON (c.user_id = u.user_id)
                    WHERE u.user_id!=:user_id", array(':user_id' => $user_id));
        }
        $country_ids = array();
        if (isset($data) and !empty($data)) {
            foreach ($data as $d) {
                $country_ids[$d['country_id']]['user_id'] = $d['user_id'];
                $country_ids[$d['country_id']]['name'] = $d['aname'];
            }
        }
        return $country_ids;
    }

    /**
     * DU - This function get the details of the admin based on user_id
     * @param number $user_id
     */
    function deleteAdminUser($user_id = 0) {
        // delete all information regarding user mapping for user queries.
        $this->adminCountryMapping(array(), $user_id);
        // Delete admin user.
        $this->getDBTable("users")->delete(array("where" => "user_id = :user_id", "params" => array(":user_id" => $user_id)));
    }

    /**
     * DU - This function get the details of the admin and its related country name.
     * @return multitype:string 
     */
    function getAdminCountryList() {
        $data = $this->database->queryData("SELECT CONCAT_WS(' ',u.first_name,u.last_name) AS aname,u.user_id, c.name AS cname FROM users AS u
                    LEFT JOIN countries AS c ON (c.country_id = u.country_id)
                    WHERE usertype_id=:usertype_id", array(':usertype_id' => '3'));

        $adminArr = array();
        if (isset($data) and !empty($data)) {
            foreach ($data as $d) {
                $adminArr[$d['user_id']] = $d['aname'] . ' - ' . $d['cname'];
            }
        }
        return $adminArr;
    }

    /**
     * DU - This function is used to get assigned admin for a particular user.
     * @param unknown $user_id
     */
    function getAssignedAdmin($user_id) {
        return $this->getDBTable("user-admin-relations")->fetchRow(array("where" => "user_id = :user_id", "params" => array(":user_id" => $user_id)));
    }

    /**
     * DU - This function is used to validate admin assignment for user.
     * @return boolean
     */
    function _validateAssignAdminForm() {
        $errors = array();

        if (!isset($_POST["admin_id"]) or !\generalFunctions::valueSet($_POST["admin_id"])) {
            $errors[] = _l("Select_Admin_To_Assign", "administrators");
        }

        if (count($errors)) {
            $_SESSION[$this->session_prefix]["error_message"] = $errors;
            return false;
        }
        return true;
    }

    /**
     * DU - This function is used to set admin for end user.
     */
    function setAdmin() {
        $data = $_POST;
        unset($data['submit'], $data['assigned_admin_id']);

        if (isset($_POST['assigned_admin_id']) and !empty($_POST['assigned_admin_id'])) {
            $this->getDBTable("user-admin-relations")->delete(array("where" => "user_id = :user_id AND admin_id = :admin_id", "params" => array(":user_id" => $data["user_id"], ":admin_id" => $_POST['assigned_admin_id'])));
        }
        $this->getDBTable("user-admin-relations")->insert($data);
    }

    /**
     * DU - This function get country wise user count.
     * @param number $country_ids
     * @return string
     */
    function getCountryUserCount($country_ids = 0) {
        $data = $this->database->queryData("SELECT COUNT(u.user_id) as total, u.country_id,c.name FROM users AS u
                                            INNER JOIN countries AS c ON (u.country_id = c.country_id)
                                            WHERE u.country_id IN (" . $country_ids . ") GROUP BY u.country_id");
        $country_details = "";
        if (isset($data) and !empty($data)) {
            foreach ($data as $d) {
                $country_details .= $d['name'] . ' - ' . $d['total'] . ', ';
            }
            $country_details = rtrim($country_details, ", ");
        }
        return $country_details;
    }

    /**
     * DU - This function is used to set admin rights for ticket assignment module.
     */
    function setTicketAdmin() {
        $assignmentData = array();
        $assignmentData['user_id'] = $_POST['hid_user_id'];
        $assignmentData['date_from'] = date('Y-m-d', strtotime($_POST['date_from']));
        $assignmentData['date_to'] = date('Y-m-d', strtotime($_POST['date_to']));
        $assignmentData['assigned_by'] = $_SESSION[$this->session_prefix]['user']['user_id'];
        $assignmentData['is_deleted'] = '0';
        $assignmentData['created_date'] = date('Y-m-d H:i:s');
        $assignmentData['updated_date'] = date('Y-m-d H:i:s');
        $this->getDBTable("ticket-assignment-details")->insert($assignmentData);
        $_SESSION[$this->session_prefix]["action_message"] = _l("Msg_Rights_Assign_Success", "administrators");
    }

    /**
     * DU - This function is used to unset admin rights for ticket assignment module.
     */
    function unsetTicketAdmin($user_id = 0) {
        $this->getDBTable("ticket-assignment-details")->update(array("is_deleted" => '1', "updated_date" => date('Y-m-d H:i:s')), array("where" => "user_id=:user_id AND is_deleted=:ais_deleted", "params" => array(":user_id" => $user_id, "ais_deleted" => "0")));
        $_SESSION[$this->session_prefix]["action_message"] = _l("Msg_Rights_Remove_Success", "administrators");
    }

    
    /**
     * DU - This function is used to validate profile details.
     * @return boolean
     */
    function validateProfileDetails() {
        $errors = array();

        if (!isset($_POST["first_name"]) or !\generalFunctions::valueSet($_POST["first_name"])) {
            $errors[] = _l("Enter_Firstname", "users");
        }
        if (!isset($_POST["last_name"]) or !\generalFunctions::valueSet($_POST["last_name"])) {
            $errors[] = _l("Enter_Lastname", "users");
        }
        if (!isset($_POST["country_id"]) or !\generalFunctions::valueSet($_POST["country_id"])) {
            $errors[] = _l("Enter_Country", "users");
        }
        if (!isset($_POST["email"]) or !\generalFunctions::valueSet($_POST["email"])) {
            $errors[] = _l("Enter_Email", "users");
        }

        if (count($errors)) {
            $_SESSION[$this->session_prefix]["error_message"] = $errors;
            return false;
        }
        return true;
    }

}
