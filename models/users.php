<?php

namespace model;

/**
 * @author Bhaumik Patel | bhaumik.patel@infostretch.com
 * brief Users Model contains application logic for various functions and database operations of users Module.
 */
class usersModel extends globalModel {

    /**
     * DU - This function is used to  get image/photo of the user from the database and physical location
     * @param $user_id
     * @return void
     */
    function deleteImage($user_id = 0) {
        $user = $this->getDBTable("users")->fetchRow("user_id = '" . $user_id . "'");
        if (file_exists(APPLICATION_PATH . "/images/user_images/" . $user["user_image"])) {
            @unlink(APPLICATION_PATH . "/images/user_images/" . $user["user_image"]);
        }
        $this->getDBTable("users")->update(array("user_image" => ""), "user_id = '" . $user_id . "'");
    }

    /**
     * DU - This function get the details of the user based on user_id
     * @param $user_id
     * @return array
     */
    function getUserDetails($user_id = 0) {
        $sql = "SELECT *,cur.currency_name,cur.currency_code from users AS u
                LEFT JOIN countries AS c ON (c.country_id = u.country_id)
                LEFT JOIN currencies AS cur ON (u.base_currency_id = cur.currency_id)
                WHERE user_id = :user_id";
        return $this->database->queryOne($sql, array(":user_id" => $user_id));
    }

    /**
     * DU - This function get the specific details of the user based on user_id
     * @param $user_id
     * @return array
     */
    function getUserDetailsByField($user_id = 0) {
        return $this->getDBTable("users")->fetchRowByFields(array("security_question_id", "security_answer"), array("where" => "user_id = :user_id", "params" => array(":user_id" => $user_id)));
    }

    /**
     * DU - This function is used to check the validation for login parameters
     * @param $params
     * @return $errors
     */
    function validateLoginParams($params) {
        $errors = array();
        $username = trim($params['mobile_number']);
        $password = trim($params['password']);

        if (!isset($username) or !\generalFunctions::valueSet($username)) {
            $errors[] = array("code" => "115", "message" => _l("Please enter mobile number.", "services"));
        } elseif (!\generalFunctions::isValidMobile($username)) {
            $errors[] = array("code" => "114", "message" => _l("Please enter valid mobile number.", "services"));
        } elseif (!$this->checkUserActivated($username)) {
            $errors[] = array("code" => "113", "message" => _l("You have not activated your account.", "services"));
        }

        if (!isset($username) or $password == '') {
            $errors[] = array("code" => "116", "message" => _l("Please enter Password.", "services"));
        }
        if (!empty($errors)) {
            return array(false, $errors);
        } else {
            return array(true, array());
        }
    }

    /**
     * DU - Check user is activated or not.
     * @param unknown $mobileNumber
     * @return boolean
     */
    function checkUserActivated($mobileNumber) {
        $active_sql = "SELECT uaa.* FROM users u left join user_account_activation uaa on uaa.user_id=u.user_id WHERE uaa.activation_flag= :activation_flag AND u.mobile_number=:mobile_number";
        if (!$this->database->queryOne($active_sql, array(':mobile_number' => $mobileNumber, ':activation_flag' => '1'))) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * DU - This function is used to check the given login parameter authentication with the database users
     * @param $username
     * @param $pass
     * @param $application_id
     * @return $userResult
     */
    function authenticateUser($params) {

        $result = $this->userAuthentication($params);
        if(!$result[0]) return $result;
        if ($result[1]['user_image'] != '' and file_exists(APPLICATION_PATH . '/images/user_images/' . $result[1]['user_image'])) {
            $result[1]['user_image'] = APPLICATION_URL . '/images/user_images/' . $result[1]['user_image'];
            $imagedata = file_get_contents($result[1]['user_image']);
            $result[1]['user_image_raw'] = base64_encode($imagedata);
        } else {
            $result[1]['user_image_raw'] = "";
            $result[1]['user_image'] = "";
        }

        return $result;
    }

    /**
     * DU - This function authenticate user from elemento database
     * @params $username
     * @params $password
     * @params $application_id
     * @return array
     */
    function userAuthentication($params) {

        if ($userResult = $this->getDBTable("users")->fetchRow("`mobile_number` = '" . trim($params['mobile_number']) . "' AND `password` = '" . md5($params['password']) . "' AND usertype_id = '" . ORDINARY_USER . "' AND status = '1' ")) {
            $token = $this->getNewToken();
            $this->getDBTable("device-tokens")->insert(array("user_id" => $userResult["user_id"], "token" => $token, "last_updated_date" => date("Y-m-d H:i:s")));
            $userResult['token'] = $token;

            if (\generalFunctions::valueSet($userResult['birth_date']) and $userResult['birth_date'] != '0000-00-00') {
                $userResult['birth_date'] = strtotime($userResult['birth_date']);
            } else {
                $userResult['birth_date'] = strtotime(date('Y-m-d'));
            }
            $userResult['birth_date'] = (int) $userResult['birth_date_timestamp'];
            
            unset($userResult['birth_date_timestamp']);
            $userResult['country'] = $this->getModel('miscellaneous')->getCountryNameById($userResult['country_id']);

            $bln_new_user = $this->checkUserLoginInfo($userResult["user_id"]);
            if ($bln_new_user == true) {
                $userResult['force_sync_required'] = false;
                $this->insertUserLoginInfo($userResult["user_id"], $params);
            } else {
                $userResult['force_sync_required'] = $this->checkInsertUserLoginInfo($userResult["user_id"], $params);
            }

            unset($userResult['password'], $userResult['status'], $userResult['deleted'], $userResult['created_date'], $userResult['updated_date']);
            $this->typeCastFields(array("int" => array("user_id", "usertype_id", "security_question_id", 'password_flag','base_currency_id')), $userResult, 1);

            return array(true, $userResult);
        } elseif ($userResult = $this->getDBTable("users")->fetchRow("`mobile_number` = '" . trim($params['mobile_number']) . "' AND password_flag = '1' ")) {
            $errors = array();
            $errors[] = array("code" => "118", "message" => _l("Please check email as your old password is expired.", "services"));
            return array(false, $errors);
        } else {
            $errors = array();
            $errors[] = array("code" => "118", "message" => _l("Invalid Username/Password.", "services"));
            return array(false, $errors);
        }
    }

    /**
     * DU - This function is used to validate User Image
     * @params $xedit
     * @return errors
     */
    function _validateUserImage($xedit = 0) {

        $errors = array();

        $temp_name = ($xedit == 1) ? "xeditfile" : "user_image";

        if (isset($_FILES[$temp_name]["name"]) and !empty($_FILES[$temp_name]["name"]) and !preg_match("/\.(jpg|jpeg|gif|png|bmp|tif|ico)$/", $_FILES[$temp_name]["name"])) {
            $errors = "Invalid image type.";
        }

        return $errors;
    }

    /**
     * DU - This function is used to check all the validation for the users form,while performing add and edit operation from web service
     * @params $params
     * @params $isRegister
     * @return $errors
     */
    function validateUserForm($params = array(), $isRegister = true) {

        $errors = array();
        if ($isRegister && (!isset($params["mobile_number"]) or !\generalFunctions::valueSet($params["mobile_number"]))) {
            $errors[] = array("code" => "1", "message" => _l("Please enter mobile number.", "services"));
        } elseif ($isRegister && (!\generalFunctions::isValidMobile($params["mobile_number"]))) {
            $errors[] = array("code" => "2", "message" => _l("Please enter valid mobile number.", "services"));
        }
        if (!isset($params["first_name"]) or !\generalFunctions::valueSet($params["first_name"])) {
            $errors[] = array("code" => "3", "message" => _l("Please enter firstname.", "services"));
        }
        if (!isset($params["last_name"]) or !\generalFunctions::valueSet($params["last_name"])) {
            $errors[] = array("code" => "4", "message" => _l("Please enter lastname.", "services"));
        }
        if ($isRegister && $params["password"] == "") {
            $errors[] = array("code" => "5", "message" => _l("Please enter password.", "services"));
        } else if (!preg_match("/\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\W])(?=\S*[\d])\S*/", $params["password"])) {
            $errors[] = array("code" => "6", "message" => _l("Password must be atleast 8 characters long, contain one upper case letter, one lower case letter, one digit, one special character.", "services"));
        }

        if ($isRegister && $params["password"] != $params["cpassword"]) {
            $errors[] = array("code" => "7", "message" => _l("Please enter confirm password same as password.", "services"));
        }

        if (strlen($params["password"]) != 0 and trim(strlen($params["password"])) == 0) {
            $errors[] = array("code" => "8", "message" => _l("Invalid input for password.", "services"));
        }

        if (!isset($params["security_question_id"]) or !\generalFunctions::valueSet($params["security_question_id"])) {
            $errors[] = array("code" => "9", "message" => _l("Please enter security question id.", "services"));
        }

        if (!isset($params["security_answer"]) or !\generalFunctions::valueSet($params["security_answer"])) {
            $errors[] = array("code" => "10", "message" => _l("Please enter security answer.", "services"));
        }

        if (!isset($params['email']) and !\generalFunctions::valueSet($params['email'])) {
            $errors[] = array("code" => "11", "message" => _l("Please enter email.", "services"));
        } else if (!\generalFunctions::isValidEmail($params["email"])) {
            $errors[] = array("code" => "12", "message" => _l("Please enter valid email.", "services"));
        }

        if (isset($params['birth_date']) and !\generalFunctions::checkInt($params['birth_date'])) {
            $errors[] = array("code" => "13", "message" => _l("Please enter valid birth date.", "services"));
        }

        if ($params["gender"] != 'Male' && $params["gender"] != 'Female') {
            $errors[] = array("code" => "14", "message" => _l("Please select gender as male or female only.", "services"));
        }

        if ($err = $this->_validateUserImage() and count($err)) {
            $errors[] = $err;
        }

        if ($params["user_id"]) {
            $tmp_wr = "mobile_number = '" . $params["mobile_number"] . "' AND usertype_id = '" . ORDINARY_USER . "' AND user_id != '" . $params["user_id"] . "' ";
        } else {
            $tmp_wr = "mobile_number = '" . $params["mobile_number"] . "' AND usertype_id = '" . ORDINARY_USER . "'";
        }

        if ($eexists = $this->getDBTable("users")->fetchRow($tmp_wr)) {
            $errors[] = array("code" => "123", "message" => _l("Mobile Number already exists.", "services"));
        }
        if (!empty($errors)) {
            return array(false, $errors);
        } else {
            return array(true, array());
        }
    }

    /**
     * DU - This function is used to create the new registration of the user from the web services using the given parameters
     * @param array $data
     * @return $response
     */
    function register($data) {


        $data['usertype_id'] = ORDINARY_USER;

        unset($data["submit"], $data["cancel"]);

        if (\generalFunctions::valueSet($data['birth_date'])) {
            $data['birth_date_timestamp'] = $data['birth_date'];
			$data['birth_date'] = date("Y-m-d H:i:s", $data['birth_date']);
        }

        if ($data["user_id"]) { // Update Record
            if (\generalFunctions::valueSet($data["password"]) and ( $data["password"] == $data["cpassword"])) {
                $data["password"] = md5($data["password"]);
                unset($data["cpassword"]);
            } else {
                unset($data["password"], $data["cpassword"]);
            }

            $data["updated_date"] = date("Y-m-d H:i:s");
            $this->getDBTable("users")->update($data, array("where" => "user_id = :user_id", "params" => array(":user_id" => $data['user_id'])));
        } else { // Add Record
            unset($data["user_id"], $data["cpassword"]);

            $data["password"] = md5($data["password"]);
            $data["created_date"] = date("Y-m-d H:i:s");
            $data["updated_date"] = date("Y-m-d H:i:s");
            if (isset($data["country"]) and !empty($data["country"])) {
                $data["country_id"] = $this->getModel("miscellaneous")->getCountryIdByName($data["country"]);
                unset($data["country"]);
            }
            $data["status"] = 1;
            $data["deleted"] = 0;
            $data["pushNoticationID"] = (isset($data['pushNoticationID'])) ? $data['pushNoticationID'] : 0;

            $data["user_id"] = $this->getDBTable("users")->insert($data);

            // Allocat user to admin for user queries based on country.
            if (isset($data['country_id']) and !empty($data['country_id'])) {
                // get admin who is allocated to this country.
                $mapdata = $this->getDBTable("user-area-mappings")->fetchRow(array("where" => "country_id = :country_id", "params" => array(":country_id" => $data['country_id'])));
                if (isset($mapdata) and !empty($mapdata)) {
                    $umData = array();
                    $umData['user_id'] = $data["user_id"];
                    $umData['admin_id'] = $mapdata['user_id']; // this is admin id.
                    $this->getDBTable("user-admin-relations")->insert($umData);
                } else {
                    // if no admin is allocated to the country then user will be assigned to super admin.
                    $superadmindata = $this->getDBTable("users")->fetchRow(array("where" => "usertype_id = :usertype_id", "params" => array(":usertype_id" => "1")));
                    $umData = array();
                    $umData['user_id'] = $data["user_id"];
                    $umData['admin_id'] = $superadmindata['user_id']; // this is admin id.
                    $this->getDBTable("user-admin-relations")->insert($umData);
                }
            }

            if ($data['email'] != '') {
                if (\generalFunctions::isValidEmail($data['email'])) {

                    $tokenData = array();
                    $tokenData['user_id'] = $data["user_id"];
                    $tokenData['type'] = 'email';
                    $tokenData['token_id'] = \generalFunctions::genRandomPass(20, true, true, false, true);
                    $tokenData['registration_date'] = $data['created_date'];
                    $tokenData['activation_flag'] = '0';

                    $this->getDBTable("user-account-activation")->insert($tokenData);

                    $empData["to_email"] = $data['email'];
                    $empData["firstname"] = $data['first_name'];
                    $empData["lastname"] = $data["last_name"];
                    $empData["link"] = "<a href=" . APPLICATION_URL . "/register/activation/i/" . $tokenData['token_id'] . ">here</a>";

                    $this->sendEmail("user_activation_mail", $empData);
                }
            }


            //$this->setFirstUserSyncTime($data["user_id"]);
        }

        if ($data["user_image_name"] != '' && $data['user_image'] != '') {
            $arrData = pathinfo($data["user_image_name"]);
            $fileName = $data["user_id"] . "." . $arrData['extension'];
            $imagePath = APPLICATION_PATH . "/images/user_images/" . $fileName;

            $this->getModel("miscellaneous")->saveImages($imagePath, $data['user_image']);
            
            // resize image
            // $this->getModel("miscellaneous")->saveUserImage($imagePath,$imagePath,  \configurations::$USER_IMAGE_WIDTH,\configurations::$USER_IMAGE_HEIGHT);    
            $this->getDBTable("users")->update(array("user_image" => $fileName), array("where" => "user_id = :user_id", "params" => array(":user_id" => $data['user_id'])));
        }

        if (!empty($data["user_id"])) {
            $response = array(array("user_id" => $data["user_id"]));
            return array(true, $response);
        } else {
            $response = array(array("code" => "100", "message" => _l("Issue with Database operation.", "services")));
            return array(false, $response);
        }
    }

    /**
     * DU - this function checks if requested user's entry exist in sync status record or not
     * @param type $user_id
     * @return array
     */
    function getUserSyncStatus($user_id) {
        return $this->getDBTable("user-sync-status")->fetchRow("user_id = '" . $user_id . "'");
    }

    /**
     * DU - this function adds current time as last sync time and push notification time for newly register users
     * @param type $user_id
     */
    function setFirstUserSyncTime($user_id = 0) {
        $user_sync_status_data['user_id'] = $user_id;
        $user_sync_status_data["last_sync_time"] = date("Y-m-d H:i:s");
        $user_sync_status_data["push_notification_sent_time"] = date("Y-m-d H:i:s");
        $this->getDBTable("user-sync-status")->insert($user_sync_status_data);
    }

    /**
     * DU - this is validation for security question
     * @param type $data
     * @param type $user_id
     * @return array $errors
     */
    function validateSecurityQuestion($data, $user_id) {
        $errors = array();

        if (!isset($data["old_security_question_id"]) or !\generalFunctions::valueSet($data["old_security_question_id"])) {
            $errors[] = array("code" => "119", "message" => _l("Please enter old security question.", "services"));
        }
        if (!isset($data["old_security_answer"]) or !\generalFunctions::valueSet($data["old_security_answer"])) {
            $errors[] = array("code" => "120", "message" => _l("Please enter old security answer.", "services"));
        }
        if (!isset($data["security_question_id"]) or !\generalFunctions::valueSet($data["security_question_id"])) {
            $errors[] = array("code" => "121", "message" => _l("Please enter new security question.", "services"));
        }
        if (!isset($data["security_answer"]) or !\generalFunctions::valueSet($data["security_answer"])) {
            $errors[] = array("code" => "122", "message" => _l("Please enter new security answer.", "services"));
        }

        if (count($errors)) {
            return array(false, $errors);
        }

        $userDetails = $this->getUserDetailsByField($user_id);

        if ((trim($data['old_security_question_id']) == $userDetails['security_question_id']) and (trim($data['old_security_answer']) == $userDetails['security_answer'])) {
            $arrData = array();
            $arrData['security_question_id'] = $data['security_question_id'];
            $arrData['security_answer'] = $data['security_answer'];

            if ($this->getDBTable("users")->update($arrData, array("where" => "user_id = :user_id", "params" => array(":user_id" => $user_id)))) {
                return array(true, array("security_question_id" => $arrData['security_question_id'], "security_answer" => $arrData['security_answer']));
            } else {
                $errors = array();
                $errors[] = array("code" => "118", "message" => _l("Request not completed.", "services"));
                return array(false, $errors);
            }
        } else {
            $errors[] = array("code" => "1003", "message" => _l("Old question answer not matched", "services"));
        }

        if (count($errors)) {
            return array(false, $errors);
        }
    }

    /**
     * DU - This function is used to check the validation of the username and send the new random password to the user in the email.
     * @param array $params
     * @return $errors
     */
    function forgotPassword($params) {

        $errors = array();

        if (!isset($params["email"]) or !\generalFunctions::valueSet($params["email"])) {
            $errors[] = array("code" => "1000", "message" => _l("Please enter email.", "services"));
        } elseif (!\generalFunctions::isValidEmail($params["email"])) {
            $errors[] = array("code" => "1001", "message" => _l("Please enter valid email.", "services"));
        }

        if (!\generalFunctions::isValidMobile($params["mobile_number"])) {
            $errors[] = array("code" => "1005", "message" => _l("Please enter valid mobile number.", "services"));
        } else {
            if (!$valid_mobile = $this->getDBTable("users")->fetchAll(array("where" => "mobile_number = :mobile_number AND status = :status AND deleted = :deleted", "params" => array(":mobile_number" => $params['mobile_number'], ":status" => '1', ":deleted" => '0')))) {
                $errors[] = array("code" => "1007", "message" => _l("Mobile number does not exist.", "services"));
            }
        }

        if (!$eexists = $this->getDBTable("users")->fetchAll(array("where" => "email = :email AND status = :status AND deleted = :deleted", "params" => array(":email" => $params['email'], ":status" => '1', ":deleted" => '0')))) {
            $errors[] = array("code" => "1002", "message" => _l("Email does not exist.", "services"));
        }

        if (!isset($params["security_question_id"]) or !\generalFunctions::valueSet($params["security_question_id"])) {
            $errors[] = array("code" => "1008", "message" => _l("Please enter security question.", "services"));
        }
        if (!isset($params["security_answer"]) or !\generalFunctions::valueSet($params["security_answer"])) {
            $errors[] = array("code" => "1009", "message" => _l("Please enter security answer.", "services"));
        }

        if (!$valid_qa = $this->getDBTable("users")->fetchAll(array("where" => "security_question_id = :security_question_id AND security_answer = :security_answer", "params" => array(":security_question_id" => $params['security_question_id'], ":security_answer" => $params['security_answer'])))) {
            $errors[] = array("code" => "1003", "message" => _l("Wrong answer, Please enter correct answer for the security question", "services"));
        }

        if (count($errors)) {
            return array(false, $errors);
        } else {
            $data = $this->getDBTable("users")->fetchAll(array("where" => "security_question_id = :security_question_id AND security_answer = :security_answer AND mobile_number = :mobile_number AND email = :email AND status = :status AND deleted = :deleted", "params" => array(":security_question_id" => $params['security_question_id'], ":security_answer" => $params['security_answer'], ":mobile_number" => $params['mobile_number'], ":email" => $params['email'], ":status" => '1', ":deleted" => '0')));

            if (empty($data)) {
                $errors[] = array("code" => "1004", "message" => _l("Entered data does not matched", "forgot_password"));
                $_SESSION[$this->session_prefix]["error_message"] = $errors;
                return array(false, $errors);
            }
        }
        if ($params['mobile_number'] != '') {
            // Write code to send random password in sms.
        }

        if ($params['email'] != '' and isset($data)) {
            if (\generalFunctions::isValidEmail($params['email'])) {
                $empData["firstname"] = $data[0]["first_name"];
                $empData["lastname"] = $data[0]["last_name"];
                $empData["mobile_number"] = $data[0]["mobile_number"];
                $empData["to_email"] = $data[0]["email"];

                //Generate string/password with 10 character capital letters, digit, no special characters and small letters.
                #$empData["random_pass"] = \generalFunctions::genRandomPass(10, true, true, false, true);
                $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
                $empData["random_pass"] = substr(str_shuffle($chars), 0, 6);
                $userData = array();
                $userData['password_flag'] = '1';
                $userData['password'] = md5($empData["random_pass"]);
                $this->getDBTable("users")->update($userData, array("where" => "user_id = :user_id", "params" => array(":user_id" => $data[0]["user_id"])));
                $this->sendEmail("forgot_password_mail", $empData);
                $response = array('code' => 1006, 'message' => _l("New Password sent to your registered email address.", "services"), "data" => array("pass" => $empData["random_pass"]));
                return array(true, $response);
            }
        }
        return true;
    }

    /**
     * DU - This function is used to get all the details related to user based on user_id
     * @param int $user_id
     * @param string $image_type
     * @return array $response
     */
    function getUserProfile($user_id = 0, $image_type = 'raw') {
        if ($dataArr = $this->getDBTable("users")->fetchRow("user_id=$user_id and status=1") and count($dataArr)) {

            if ($image_type == 'raw') {
                if (!empty($dataArr['user_image'])) {
                    if (file_exists(APPLICATION_URL . "/images/user_images/" . $dataArr['user_image'])) {
                        $dataArr['user_image'] = base64_encode(file_get_contents(APPLICATION_URL . "/images/user_images/" . $dataArr['user_image']));
                    }
                } else {
                    $dataArr['user_image'] = base64_encode(file_get_contents(APPLICATION_URL . "/images/user_images/no_image.png"));
                }
            } else {
                if (!empty($dataArr['user_image'])) {
                    if ($imgfiletime = filemtime(APPLICATION_PATH . "/images/user_images/" . $dataArr['user_image'])) {
                        $imgfilemtime = $imgfiletime;
                    }
                    $dataArr['user_image'] = APPLICATION_URL . "/images/user_images/" . $dataArr['user_image'] . "?" . $imgfilemtime;
                } else {
                    $dataArr['user_image'] = APPLICATION_URL . "/images/user_images/no_image.png";
                }
            }
            //$dataArr['birth_date'] = strtotime($dataArr['birth_date']);
            //$dataArr['birth_date'] = $dataArr['birth_date'] * 1000;
            $dataArr['birth_date'] = $dataArr['birth_date_timestamp'];
			
            $dataArr['country'] = $this->getModel('miscellaneous')->getCountryNameById($dataArr['country_id']);
            $this->typeCastFields(array("int" => array("user_id","mobile_number", "usertype_id","country_id","status", "security_question_id", 'password_flag','base_currency_id')), $dataArr, 1);
            return array(true, $dataArr);
        } else {
            $response = array('code' => 1000, 'message' => "No Record found.");
            return array(false, $response);
        }
    }

    /**
     * DU - This function is used to generate a new token which are in md5 form and not used yet.
     * @return $token
     */
    function getNewToken() {
        for ($i = 0; $i < 100; $i++) {
            $token = md5(rand(5, 15) . time());
            if ($this->getDBTable("device-tokens")->fetchRow("token = '" . $token . "'")) {
                continue;
            }
            return $token;
        }
    }

    /**
     * DU - This function is used to do the logout of the user from web service. It will also delete the token from the database.
     * @param string $token
     * @return array $response
     */
    function logout($token = '') {
        if ($this->getDBTable("device-tokens")->delete("token = '" . $token . "'")) {
            $response = array('code' => 200, 'message' => "success");
            return array(true, $response);
        } else {
            $response = array('code' => 1000, 'message' => "No Record found.");
            return array(false, $response);
        }
    }

    /**
     * DU - This function is used to update the platform and device token from the login service
     * @param $params
     * @param $user_id
     * @return void
     */
    function updateDevideTokenAndPlatform($params, $user_id) {

        $platform = trim($params['platform']);
        $device_token = trim($params['device_token']);
        $arrData['updated_date'] = date("Y-m-d H:i:s");
        if ($platform != "") {
            $arrData['platform'] = $platform;
        }
        if ($device_token != "") {
            $arrData['device_token'] = $device_token;
        }

        $this->getDBTable("users")->update($arrData, " user_id='" . $user_id . "' ");
    }

    /**
     * DU - This function is used to validate the set device token web service
     * @param array $params
     * @return array $errors
     */
    function validateSetDeviceTokenService($params) {
        $device_token = trim($params['device_token']);
        $platform = trim($params['platform']);
        $application_id = trim($params['application_id']);

        if (!isset($device_token) or $device_token == '') {
            $errors[] = array("code" => "126", "message" => _l("Please enter device token.", "services"));
        }
        if (!isset($platform) or $platform == '') {
            $errors[] = array("code" => "127", "message" => _l("Please enter platform.", "services"));
        }

        if (!empty($errors)) {
            return array(false, $errors);
        } else {
            return array(true, array());
        }
    }

    /**
     * DU - This function is used to update the profile of the user from application
     * @param array $params
     * @param int $user_id
     * @return array $userdetails
     */
    function updateUserProfile($params, $user_id) {

        $data['user_id'] = $user_id;

        if (isset($params['first_name']) and \generalFunctions::valueSet($params['first_name'])) {
            $data['first_name'] = $params['first_name'];
        }
        if (isset($params['last_name']) and \generalFunctions::valueSet($params['last_name'])) {
            $data['last_name'] = $params['last_name'];
        }
        if (isset($params['address_line1']) and \generalFunctions::valueSet($params['address_line1'])) {
            $data['address_line1'] = $params['address_line1'];
        }
        if (isset($params['address_line2']) and \generalFunctions::valueSet($params['address_line2'])) {
            $data['address_line2'] = $params['address_line2'];
        }
        if (isset($params['email']) and \generalFunctions::valueSet($params['email'])) {
            $data['email'] = $params['email'];
        }
        if (isset($params['country']) and \generalFunctions::valueSet($params['country'])) {
            $data['country'] = $params['country'];
        }
        if (isset($params['city']) and \generalFunctions::valueSet($params['city'])) {
            $data['city'] = $params['city'];
        }
        if (isset($params['zip_code']) and \generalFunctions::valueSet($params['zip_code'])) {
            $data['zip_code'] = $params['zip_code'];
        }
        if (isset($params['state']) and \generalFunctions::valueSet($params['state'])) {
            $data['state'] = $params['state'];
        }

        if (isset($data["country"]) and !empty($data["country"])) {
            $data["country_id"] = $this->getModel("miscellaneous")->getCountryIdByName($data["country"]);
            unset($data["country"]);
        }

        $data['updated_date'] = date("Y-m-d H:i:s");
            
        if (\generalFunctions::valueSet($data['birth_date'])) {
            $data['birth_date_timestamp'] = (int) $data['birth_date'];
            $data['birth_date'] = date("Y-m-d H:i:s", $data['birth_date']);
        }
        
        if ($params["user_image_name"] != '' && $params['user_image_raw'] != '') {
            $arrData = pathinfo($params["user_image_name"]);
            $data['user_image'] = $data["user_id"] . "." . $arrData['extension'];
            $imagePath = APPLICATION_PATH . "/images/user_images/" . $data['user_image'];

            $this->getModel("miscellaneous")->saveImages($imagePath, $params['user_image_raw']);
        }

        $this->getDBTable("users")->update($data, array("where" => "user_id = :user_id", "params" => array(":user_id" => $data['user_id'])));

        $userdetails = $this->getUserProfile($user_id, "url");
        if(isset($userdetails[1]['birth_date_timestamp'])) {
            $userdetails[1]['birth_date_timestamp'] = (int) $userdetails[1]['birth_date_timestamp'];
            $userdetails[1]['birth_date'] = (int) $userdetails[1]['birth_date_timestamp'];
            $userdetails[1]['birth_date_ymd'] = date("Y-m-d", $userdetails[1]['birth_date_timestamp']);
        }
        return $userdetails;
    }

    /**
     * DU - This function is used to update user sync time for new user added
     * @param int $user_id
     * @return array $params
     */
    function updateUserSyncTime($user_id) {

        $data['last_sync_time'] = date("Y-m-d H:i:s");
        $this->getDBTable("user-sync-status")->update($data, "user_id = '" . $user_id . "' ");
        return array('user_id' => $user_id);
    }

    /**
     * DU - this function is used to validate the current password web service parameters
     * @param array $params
     * @param int $user_id
     * @return array $errors
     */
    function validateChangePasswordService($params, $user_id) {
        $userDetails = $this->getUserDetails($user_id);
        if ($params["current_password"] == "") {
            $errors[] = array("code" => "129", "message" => _l("Please enter current password.", "services"));
        } else if ($userDetails["password"] != md5($params["current_password"])) {
            $errors[] = array("code" => "132", "message" => _l("Invalid old password.", "services"));
        }
        if ($params["new_password"] == "") {
            $errors[] = array("code" => "130", "message" => _l("Please enter new password.", "services"));
        }
        if ($params["confirm_password"] == "") {
            $errors[] = array("code" => "131", "message" => _l("Please enter confirm password.", "services"));
        }

        if (!preg_match("/\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\W])(?=\S*[\d])\S*/", $params["new_password"])) {
            $errors[] = array("code" => "121", "message" => _l("Password must be atleast 8 characters long, contain one upper case letter, one lower case letter, one digit, one special character.", "services"));
        }
        if ($params["new_password"] == $params["current_password"]) {
            $errors[] = array("code" => "121", "message" => _l("Please enter new password different from the current password.", "services"));
        }

        if ($params["new_password"] != $params["confirm_password"]) {
            $errors[] = array("code" => "121", "message" => _l("Please enter confirm password same as password.", "services"));
        }



        if (!empty($errors)) {
            return array(false, $errors);
        } else {
            return array(true, array());
        }
    }

    /**
     * DU - This function is used to validate Timestamp
     * @param array $params
     * @return array()
     */
    function validateTimestampForUserDetails($params) {
        $errors = array();

        if (!isset($params['timestamp']) or !\generalFunctions::valueSet($params['timestamp'])) {
            $errors[] = array("code" => "147", "message" => _l("Please enter timestamp."));
        } else if (isset($params['timestamp']) and !\generalFunctions::checkIntPositive($params['timestamp'])) {
            $errors[] = array("code" => "146", "message" => _l("Invalid Timestamp."));
        }
        if (!empty($errors)) {
            return array(false, $errors);
        } else {
            return array(true, array());
        }
    }

    /**
     * DU - This function is used to get user data
     * @param $int user_id
     * @return array $user_data
     */
    function getUserData($user_id) {

        $finalData = array();

        $finalData['cards'] = $this->database->queryData("select * from cards WHERE user_id=:user_id", array(':user_id' => $user_id));

        $finalData['expense_categories'] = $this->database->queryData("select * from expense_categories WHERE user_id=:user_id AND is_default = :is_default", array(':user_id' => $user_id, ":is_default" => "0"));

        foreach ($finalData['expense_categories'] as $intKey => $arrData) {
            if (empty($arrData['parent_expense_category_id'])) {
                $finalData['expense_categories'][$intKey]['parent_expense_category_id'] = -1;
            }
        }


        $finalData['user_expenses'] = $this->database->queryData("select * from user_expenses AS ue LEFT JOIN user_expense_reference AS uer ON uer.user_expense_id = ue.user_expense_id WHERE ue.user_id=:user_id", array(':user_id' => $user_id));

        $finalData['user_trips'] = $this->database->queryData("select * from user_trips WHERE user_id=:user_id", array(':user_id' => $user_id));

        $finalData['user_settings'] = $this->database->queryData("select us.user_setting_id,us.title,us.parameter,us.`type`,us.`comment`,us.`list`,IF(usv.value is null, us.value, usv.value) as value,us.`status` from user_settings AS us LEFT JOIN user_setting_value AS usv ON usv.user_setting_id = us.user_setting_id and usv.user_id=:user_id", array(':user_id' => $user_id));

        return array('user_data' => $finalData);
    }

    /**
     * DU - This function is used to validate user password.
     * @return booloean
     */
    function validatePassword() {
        $errors = array();

        if (!isset($_POST["password"]) or !\generalFunctions::valueSet($_POST["password"])) {
            $errors[] = _l("Enter_Password", "reset_password");
        }
        if (!isset($_POST["cpassword"]) or !\generalFunctions::valueSet($_POST["cpassword"])) {
            $errors[] = _l("Confirm_Password", "reset_password");
        }

        if (!preg_match("/\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\W])(?=\S*[\d])\S*/", $_POST['password'])) {
            $errors[] = _l("Invalid_Password_String", "reset_password");
        } else if ($_POST["cpassword"] != "" and ($_POST["password"] != $_POST["cpassword"])) {
            $errors[] = _l("Password_Confirm_Password_Same", "reset_password");
        }

        if (count($errors)) {
            $_SESSION[$this->session_prefix]["error_message"] = $errors;
            return false;
        }
        return true;
    }

    /**
     * DU - This function is used to update password.
     */
    function updatePassword() {
        $userData = array();
        $userData['password_flag'] = '0';
        $userData['password'] = md5($_POST["password"]);

        $this->database->update("users", $userData, array("where" => "user_id = :user_id", "params" => array(":user_id" => $_SESSION[$this->session_prefix]['user']['user_id'])));
        $_SESSION[$this->session_prefix]["action_message"] = _l("Password_Updated_Success", "reset_password");
    }

    /**
     * DU - This function is used to validate registration form
     * @return boolean
     */
    function validateRegistrationForm() {
        $errors = array();

        if (!isset($_POST["first_name"]) or !\generalFunctions::valueSet($_POST["first_name"])) {
            $errors[] = _l("Enter_Firstname", "users");
        }
        if (!isset($_POST["last_name"]) or !\generalFunctions::valueSet($_POST["last_name"])) {
            $errors[] = _l("Enter_Lastname", "users");
        }
        if (!isset($_POST["mobile_number"]) or !\generalFunctions::valueSet($_POST["mobile_number"])) {
            $errors[] = _l("Enter_Mobile", "users");
        }
        if (!isset($_POST["gender"]) or !\generalFunctions::valueSet($_POST["gender"])) {
            $errors[] = _l("Select_Gender", "users");
        }
        if (!isset($_POST["security_question_id"]) or !\generalFunctions::valueSet($_POST["security_question_id"])) {
            $errors[] = _l("Select_Question", "users");
        }
        if (!isset($_POST["security_answer"]) or !\generalFunctions::valueSet($_POST["security_answer"])) {
            $errors[] = _l("Enter_Answer", "users");
        }
        if (!isset($_POST["birth_date"]) or !\generalFunctions::valueSet($_POST["birth_date"])) {
            $errors[] = _l("Enter_Birthdate", "users");
        }
        if (!isset($_POST["base_currency_id"]) or !\generalFunctions::valueSet($_POST["base_currency_id"])) {
            $errors[] = _l("Enter_Currency", "users");
        }
        if (!isset($_POST["city"]) or !\generalFunctions::valueSet($_POST["city"])) {
            $errors[] = _l("Enter_City", "users");
        }
        if (!isset($_POST["zip_code"]) or !\generalFunctions::valueSet($_POST["zip_code"])) {
            $errors[] = _l("Enter_Zipcode", "users");
        }

        if (!isset($_POST["country_id"]) or !\generalFunctions::valueSet($_POST["country_id"])) {
            $errors[] = _l("Enter_Country", "users");
        }

        if (!isset($_POST["email"]) or !\generalFunctions::valueSet($_POST["email"])) {
            $errors[] = _l("Enter_Email", "users");
        }

        if ($_POST["birth_date"] != "") {
            /* if (date("Y") - date("Y", strtotime($_POST["birth_date"])) < 15) {
              $errors[] = _l("User_Age", "users");
              } */
            if (time() < strtotime('+15 years', strtotime($_POST["birth_date"]))) {
                $errors[] = _l("User_Age", "users");
            }
        }

        if (!isset($_POST["password"]) or !\generalFunctions::valueSet($_POST["password"])) {
            $errors[] = _l("Enter_Password", "users");
        }

        if (!isset($_POST["cpassword"]) or !\generalFunctions::valueSet($_POST["cpassword"])) {
            $errors[] = _l("Confirm_Password", "users");
        }

        if (!preg_match("/\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\W])(?=\S*[\d])\S*/", $_POST['password'])) {
            $errors[] = _l("Invalid_Password_String", "users");
        } else if ($_POST["cpassword"] != "" and ($_POST["password"] != $_POST["cpassword"])) {
            $errors[] = _l("Password_Confirm_Password_Same", "users");
        } else if (strpos($_POST["password"], $_POST["mobile_number"]) !== false) {
            $errors[] = _l("Password_Contains_Mobile_No", "users");
        }

        if (count($errors)) {
            $_SESSION[$this->session_prefix]["error_message"] = $errors;
            return false;
        } else {
            $data = $this->getDBTable("users")->fetchRow(array("where" => "mobile_number = :mobile_number AND deleted = :deleted", "params" => array(":mobile_number" => $_POST['mobile_number'], ":deleted" => '0')));
            if (!empty($data)) {
                $errors = array();
                $errors[] = _l("Mobile_Number_Exists", "users");
                $_SESSION[$this->session_prefix]["error_message"] = $errors;
                return false;
            }
        }
        return true;
    }

    /**
     * DU - This function is used to register user.
     * @return void
     */
    function registerUser() {
        $data = $_POST;
        unset($data['submit'], $data['cpassword'], $data['hid_user_image']);
        $data['birth_date'] = date('Y-m-d', strtotime($_POST['birth_date']));
        $data['birth_date_timestamp'] = strtotime($_POST['birth_date']);
        $data['password'] = md5($_POST["password"]);
        $data['created_date'] = date('Y-m-d H:i:s');
        $data['updated_date'] = date('Y-m-d H:i:s');
        $data['status'] = '1';
        $data['deleted'] = '0';
        $data['usertype_id'] = '2';

        $user_id = $this->database->insert("users", $data);

        // Allocat user to admin for user queries based on country.
        if (isset($_POST['country_id']) and !empty($_POST['country_id'])) {
            // get admin who is allocated to this country.
            $mapdata = $this->getDBTable("user-area-mappings")->fetchRow(array("where" => "country_id = :country_id", "params" => array(":country_id" => $_POST['country_id'])));
            if (isset($mapdata) and !empty($mapdata)) {
                $umData = array();
                $umData['user_id'] = $user_id;
                $umData['admin_id'] = $mapdata['user_id']; // this is admin id.
                $this->database->insert("user_admin_relations", $umData);
            } else {
                // if no admin is allocated to the country then user will be assigned to super admin.
                $superadmindata = $this->getDBTable("users")->fetchRow(array("where" => "usertype_id = :usertype_id", "params" => array(":usertype_id" => "1")));
                $umData = array();
                $umData['user_id'] = $user_id;
                $umData['admin_id'] = $superadmindata['user_id']; // this is admin id.
                $this->database->insert("user_admin_relations", $umData);
            }
        }

        if ($_POST['hid_user_image'] != '') {
            if (preg_match("/\.(png|jpg|gif|jpeg)$/i", $_POST['hid_user_image'])) {

                $filetype_a = explode(".", $_POST['hid_user_image']);
                $filetype = $filetype_a[count($filetype_a) - 1];
                $file_name = $user_id . "." . $filetype;
                $udata['user_image'] = $file_name;

                $source_path = APPLICATION_PATH . "/images/temp_user_images/" . $_POST['hid_user_image'];
                $dest_path = APPLICATION_PATH . "/images/user_images/" . $file_name;
                //copy($source_path, $dest_path);
                if(!$this->getModel("miscellaneous")->saveUserImage($source_path,$dest_path,\configurations::$USER_IMAGE_WIDTH,\configurations::$USER_IMAGE_HEIGHT)) {
                    // @todo: put validation if image not upload 
                }
                $this->database->update("users", $udata, array("where" => "user_id = :user_id", "params" => array(":user_id" => $user_id)));
            }
        }
        if ($_POST['email'] != '') {
            if (\generalFunctions::isValidEmail($_POST['email'])) {

                $tokenData = array();
                $tokenData['user_id'] = $user_id;
                $tokenData['type'] = 'email';
                $tokenData['token_id'] = \generalFunctions::genRandomPass(20, true, true, false, true);
                $tokenData['registration_date'] = $data['created_date'];
                $tokenData['activation_flag'] = '0';

                $this->database->insert("user_account_activation", $tokenData);

                $empData["to_email"] = $_POST['email'];
                $empData["firstname"] = $_POST['first_name'];
                $empData["lastname"] = $_POST["last_name"];
                $empData["link"] = "<a href=" . APPLICATION_URL . "/register/activation/i/" . $tokenData['token_id'] . ">here</a>";

                $this->sendEmail("user_activation_mail", $empData);
                $_SESSION[$this->session_prefix]["action_message"] = _l("Activation_Mail_Sent", "users");
            }
        }

        $_SESSION[$this->session_prefix]["action_message"] = _l("Registered_Success", "users");
    }

    /**
     * Get User Token Data
     * @param unknown $token_id
     */
    function getUserTokenData($token_id) {
        $data = $this->getDBTable("user-account-activation")->fetchRow(array("where" => "token_id = :token_id", "params" => array(":token_id" => $token_id)));

        if (isset($data) and !empty($data)) {
            $userData = array();
            $userData['activation_flag'] = '1';

            $this->database->update("user_account_activation", $userData, array("where" => "token_id = :token_id", "params" => array(":token_id" => $token_id)));

            $_SESSION[$this->session_prefix]["action_message"] = _l("Account_Activated", "users");
        }
    }

    /**
     * DU - This function is used to validate user details.
     * @return boolean
     */
    function validateUserDetails() {
        $errors = array();

        if (!isset($_POST["first_name"]) or !\generalFunctions::valueSet($_POST["first_name"])) {
            $errors[] = _l("Enter_Firstname", "users");
        }
        if (!isset($_POST["last_name"]) or !\generalFunctions::valueSet($_POST["last_name"])) {
            $errors[] = _l("Enter_Lastname", "users");
        }
        if (!isset($_POST["city"]) or !\generalFunctions::valueSet($_POST["city"])) {
            $errors[] = _l("Enter_City", "users");
        }
        if (!isset($_POST["zip_code"]) or !\generalFunctions::valueSet($_POST["zip_code"])) {
            $errors[] = _l("Enter_Zipcode", "users");
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

    /**
     * DU - This function is used to update user details.
     * @return boolean
     */
    function updateUserDetails() {
        $data = $_POST;
        unset($data['submit']);
        $path = APPLICATION_PATH . "/images/user_images/";

        if ($_POST['hid_user_image'] != '') {
            if (preg_match("/\.(png|jpg|gif|jpeg)$/i", $_POST['hid_user_image'])) {

                $filetype_a = explode(".", $_POST['hid_user_image']);
                $filetype = $filetype_a[count($filetype_a) - 1];
                $file_name = $_SESSION[$this->session_prefix]['user']['user_id'] . "." . $filetype;
                $udata['user_image'] = $file_name;

                $source_path = APPLICATION_PATH . "/images/temp_user_images/" . $_POST['hid_user_image'];
                $dest_path = APPLICATION_PATH . "/images/user_images/" . $file_name;
                //copy($source_path, $dest_path);
                if(!$this->getModel("miscellaneous")->saveUserImage($source_path,$dest_path,\configurations::$USER_IMAGE_WIDTH,\configurations::$USER_IMAGE_HEIGHT)) {
                    // @todo: put validation if image not upload 
                }

                $this->database->update("users", $udata, array("where" => "user_id = :user_id", "params" => array(":user_id" => $_SESSION[$this->session_prefix]['user']['user_id'])));
            }
        }

        unset($data['hid_user_image']);

        // if users country is changed then allocated him to different admin for user queries based on country.
        if ($_POST['country_id'] != $_POST['hid_user_country_id']) {
            $this->getDBTable("user-admin-relations")->delete(array("where" => "user_id = :user_id", "params" => array(":user_id" => $_SESSION[$this->session_prefix]['user']['user_id'])));

            $mapdata = $this->getDBTable("user-area-mappings")->fetchRow(array("where" => "country_id = :country_id", "params" => array(":country_id" => $_POST['country_id'])));
            if (isset($mapdata) and !empty($mapdata)) {
                $umData = array();
                $umData['user_id'] = $_SESSION[$this->session_prefix]['user']['user_id'];
                $umData['admin_id'] = $mapdata['user_id']; // this is admin id.
                $this->database->insert("user_admin_relations", $umData);
            }
        }
        $_SESSION[$this->session_prefix]["action_message"] = _l("Profile_Updated_Success", "users");

        if ($data['base_currency_id'] != $_SESSION[$this->session_prefix]['user']['base_currency_id']) {
            $_SESSION[$this->session_prefix]['user']['base_currency_id'] = $data['base_currency_id'];
            $_SESSION[$this->session_prefix]['user']["base_currency_code"] = $this->getModel('miscellaneous')->getCurrencyCodeById($data["base_currency_id"]);
        }

        $this->database->update("users", $data, array("where" => "user_id = :user_id", "params" => array(":user_id" => $_SESSION[$this->session_prefix]['user']['user_id'])));
    }

    /**
     * DU - This function is used to validate user credentials.
     * @return boolean
     */
    function validateCredentials() {
        $errors = array();

        if (!isset($_POST["password"]) or !\generalFunctions::valueSet($_POST["password"])) {
            $errors[] = _l("Enter_Password", "users");
        }

        if (count($errors)) {
            $_SESSION[$this->session_prefix]["error_message"] = $errors;
            return false;
        } else {
            $data = $this->getDBTable("users")->fetchRow(array("where" => "user_id = :user_id AND password = :password AND status = :status AND deleted = :deleted", "params" => array(":user_id" => $_SESSION[$this->session_prefix]['user']['user_id'], ":password" => md5($_POST['password']), ":status" => '1', ":deleted" => '0')));
            if (empty($data)) {
                $errors = array();
                $errors[] = _l("Password_Incorrect", "users");
                $_SESSION[$this->session_prefix]["error_message"] = $errors;
                return false;
            } else {
                $_SESSION[$this->session_prefix]["error_message"] = "";
            }
        }
        return true;
    }

    /**
     * DU - This function is used to update user data by field.
     * @param unknown $user_id
     * @param unknown $field
     * @param unknown $value
     * @return multitype:boolean multitype:unknown  |multitype:boolean multitype:multitype:string Ambigous <unknown, Ambigous>   
     */
    function updateUserDataByField($user_id, $field, $value) {
        $data = array();
        $data[$field] = $value;
        if ($this->database->update("users", $data, array("where" => "user_id = :user_id", "params" => array(":user_id" => $user_id)))) {
            return array(true, $data);
        } else {
            $errors = array();
            $errors[] = array("code" => "118", "message" => _l("Request not completed.", "services"));
            return array(false, $errors);
        }
    }

    /**
     * DU - This function is used to validate user new password when user is changing password.
     * @return booloean
     */
    function validateChangePassword() {
        $errors = array();

        if (!isset($_POST["opassword"]) or !\generalFunctions::valueSet($_POST["opassword"])) {
            $errors[] = _l("Enter_Old_Password", "change_password");
        }

        if (!isset($_POST["password"]) or !\generalFunctions::valueSet($_POST["password"])) {
            $errors[] = _l("Enter_New_Password", "change_password");
        }
        if (!isset($_POST["cpassword"]) or !\generalFunctions::valueSet($_POST["cpassword"])) {
            $errors[] = _l("Confirm_New_Password", "change_password");
        }

        if (!preg_match("/\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\W])(?=\S*[\d])\S*/", $_POST['password'])) {
            $errors[] = _l("Invalid_Password_String", "change_password");
        } else if ($_POST["cpassword"] != "" and ($_POST["password"] != $_POST["cpassword"])) {
            $errors[] = _l("Password_Confirm_New_Password_Same", "change_password");
        }

        if (count($errors)) {
            $_SESSION[$this->session_prefix]["error_message"] = $errors;
            return false;
        } else {
            $data = $this->getDBTable("users")->fetchRow(array("where" => "user_id = :user_id AND password = :password AND status = :status AND deleted = :deleted", "params" => array(":user_id" => $_SESSION[$this->session_prefix]['user']['user_id'], ":password" => md5($_POST['opassword']), ":status" => '1', ":deleted" => '0')));
            if (empty($data)) {
                $errors = array();
                $errors[] = _l("Old_Password_Incorrect", "change_password");
                $_SESSION[$this->session_prefix]["error_message"] = $errors;
                return false;
            } else {
                if ($data['password'] == md5($_POST['password'])) {
                    $errors[] = _l("New_Old_Password_Same", "change_password");
                    $_SESSION[$this->session_prefix]["error_message"] = $errors;
                    return false;
                } else if (strpos($_POST["password"], $data["mobile_number"]) !== false) {
                    $errors[] = _l("Password_Contains_Mobile_No", "change_password");
                    $_SESSION[$this->session_prefix]["error_message"] = $errors;
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * DU - This function is used to validate validate security question when user is changing security question.
     * @return booloean
     */
    function _validateSecurityQuestion() {
        $errors = array();

        if (!isset($_POST["old_security_answer"]) or !\generalFunctions::valueSet($_POST["old_security_answer"])) {
            $errors[] = _l("Enter_Old_Security_Answer", "change_security_question");
        }
        if (!isset($_POST["security_question_id"]) or !\generalFunctions::valueSet($_POST["security_question_id"])) {
            $errors[] = _l("Select_New_Security_Question", "change_security_question");
        }
        if (!isset($_POST["security_answer"]) or !\generalFunctions::valueSet($_POST["security_answer"])) {
            $errors[] = _l("Enter_New_Security_Answer", "change_security_question");
        }

        if (count($errors)) {
            $_SESSION[$this->session_prefix]["error_message"] = $errors;
            return false;
        } else {
            $data = $this->getDBTable("users")->fetchRow(array("where" => "user_id = :user_id AND security_question_id = :security_question_id AND security_answer = :security_answer AND status = :status AND deleted = :deleted", "params" => array(":user_id" => $_SESSION[$this->session_prefix]['user']['user_id'], ":security_question_id" => $_POST['hid_old_sec_que'], ":security_answer" => $_POST['old_security_answer'], ":status" => '1', ":deleted" => '0')));
            if (empty($data)) {
                $errors = array();
                $errors[] = _l("Old_Security_Answer_Wrong", "change_security_question");
                $_SESSION[$this->session_prefix]["error_message"] = $errors;
                return false;
            }
        }
        return true;
    }

    /**
     * DU - This function is used to update users security question.
     * @return boolean
     */
    function updateSecurityQuestion() {
        $data = $_POST;
        unset($data['submit'], $data['hid_old_sec_que'], $data['old_security_answer']);
        $this->database->update("users", $data, array("where" => "user_id = :user_id", "params" => array(":user_id" => $_SESSION[$this->session_prefix]['user']['user_id'])));
        $_SESSION[$this->session_prefix]["action_message"] = _l("Question_Updated_Success", "change_security_question");
    }

    /**
     * DU - This function is used to update users security question.
     * @return boolean
     */
    function getDefaultAppSettings() {
        return $this->database->queryData("SELECT * FROM user_settings WHERE status=:status ORDER BY ordering ASC", array(':status' => '1'));
    }

    /**
     * DU - This function is used to update users security question.
     * @return Ambigous <multitype:, unknown>
     */
    function getUserAppSettings() {
        $data = $this->database->queryData("SELECT * FROM user_setting_value WHERE user_id=:user_id", array(':user_id' => $_SESSION[$this->session_prefix]['user']['user_id']));

        foreach ($data as $d) {
            $dataArr[$d['user_setting_id']] = $d['value'];
        }
        return $dataArr;
    }

    /**
     * This function is used to update the password for user
     * @param array $params
     * @param int $user_id
     * @return void
     */
    function changePasswordForAppUser($params, $user_id) {
        $this->getDBTable("users")->update(array("password" => md5($params["new_password"]), "updated_date" => date("Y-m-d H:i:s"), "password_flag" => "0"), "user_id = '" . $user_id . "'");
    }    
    
    /**
     * DU - This function is used to validate app setting value.
     * @return booloean
     */
    function _validateAppSettings() {
        $errors = array();

        if (count($errors)) {
            $_SESSION[$this->session_prefix]["error_message"] = $errors;
            return false;
        }
        return true;
    }

    /**
     * DU - This function is used to update users app settings
     */
    function updateAppSettings() {
        $data = $_POST;
        unset($data['submit']);
        $this->getDBTable("user-setting-value")->delete(array("where" => "user_id = :user_id", "params" => array(":user_id" => $_SESSION[$this->session_prefix]['user']['user_id'])));

        foreach ($data['configurations'] as $user_setting_id => $configuration) {
            $userData['user_setting_id'] = $user_setting_id;
            $userData['user_id'] = $_SESSION[$this->session_prefix]['user']['user_id'];
            $userData['value'] = $configuration;
            $this->getDBTable("user-setting-value")->insert($userData);
        }

        $_SESSION[$this->session_prefix]['user']['user_date_format'] = $this->getUserDateFormat($_SESSION[$this->session_prefix]['user']['user_id'],"date_format");
        $_SESSION[$this->session_prefix]["action_message"] = _l("Settings_Updated_Success", "users");
    }

    /**
     * DU - This function is used to validate forgot password form for admin/super admin.
     * @return boolean
     */
    function validateForgotPasswordForm() {
        $errors = array();

        if (!isset($_POST["security_question_id"]) or !\generalFunctions::valueSet($_POST["security_question_id"])) {
            $errors[] = _l("Select_Question", "forgot_password");
        }
        if (!isset($_POST["security_answer"]) or !\generalFunctions::valueSet($_POST["security_answer"])) {
            $errors[] = _l("Enter_Answer", "forgot_password");
        }
        if (!isset($_POST["email"]) or !\generalFunctions::valueSet($_POST["email"])) {
            $errors[] = _l("Enter_Email", "forgot_password");
        }
        if (!isset($_POST["recaptcha_response_field"]) or !\generalFunctions::valueSet($_POST["recaptcha_response_field"])) {
            $errors[] = _l("Enter_Captcha", "forgot_password");
        } else {

            $resp = recaptcha_check_answer(\generalFunctions::getConfValue('captcha_private_key'), $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);

            if ($resp->is_valid) {
                
            } else {
                $errors[] = _l("Invalid_Captcha", "forgot_password");
            }
        }
        if (count($errors)) {
            $_SESSION[$this->session_prefix]["error_message"] = $errors;
            return false;
        } else {
            $data = $this->getDBTable("users")->fetchRow(array("where" => "security_question_id = :security_question_id AND security_answer = :security_answer AND email = :email AND status = :status AND deleted = :deleted", "params" => array(":security_question_id" => $_POST['security_question_id'], ":security_answer" => $_POST['security_answer'], ":email" => $_POST['email'], ":status" => '1', ":deleted" => '0')));

            if (empty($data)) {
                $errors = array();
                $errors[] = _l("QA_Not_Match", "forgot_password");
                $_SESSION[$this->session_prefix]["error_message"] = $errors;
                return false;
            }
        }
        return true;
    }

    /**
     * DU - This function is used to send forgot password mail to admin/super admin.
     * @return void
     */
    function sendForgotPasswordMail() {
        $data = $this->getDBTable("users")->fetchRow(array("where" => "security_question_id = :security_question_id AND security_answer = :security_answer AND email = :email AND status = :status AND deleted = :deleted", "params" => array(":security_question_id" => $_POST['security_question_id'], ":security_answer" => $_POST['security_answer'], ":email" => $_POST['email'], ":status" => '1', ":deleted" => '0')));
        if ($_POST['email'] != '' and isset($data)) {
            if (\generalFunctions::isValidEmail($_POST['email'])) {

                $token = \generalFunctions::genRandomPass(20, true, true, false, true);

                $tokenData = array();
                $tokenData['user_id'] = $data["user_id"];
                $tokenData['token'] = $token;
                $tokenData['status'] = '0';
                $tokenData['created_date'] = date('Y-m-d H:i:s');

                $this->database->insert("forgot_password", $tokenData);

                $empData["to_email"] = $_POST['email'];
                $empData["firstname"] = $data["first_name"];
                $empData["lastname"] = $data["last_name"];
                $empData["link"] = "<a href='" . APPLICATION_URL . "/admin/forgotpassword/reset-password/i/" . $token . "'>Click to reset your password</a>";

                $this->sendEmail("admin_forgot_password_mail", $empData);

                $_SESSION[$this->session_prefix]["action_message"] = _l("Mail_Sent", "forgot_password");
            }
        }
    }

    /**
     * DU - This function is used to check forgot password token for admin/super admin.
     * @return boolean
     */
    function checkForgotPasswordToken($token) {
        $data = $this->getDBTable("forgot-password")->fetchRow(array("where" => "token = :token AND status = :status", "params" => array(":token" => $token, ":status" => '0')));
        if (empty($data)) {
            $_SESSION[$this->session_prefix]["error_message"] = _l("Invalid_Token", "forgot_password");
            return false;
        }
        return true;
    }

    /**
     * DU - This function is used to validate reset password form for admin/super admin.
     * @return booloean
     */
    function validateResetPasswordForm() {
        $errors = array();

        if (!isset($_POST["password"]) or !\generalFunctions::valueSet($_POST["password"])) {
            $errors[] = _l("Enter_Password", "reset_password");
        }
        if (!isset($_POST["cpassword"]) or !\generalFunctions::valueSet($_POST["cpassword"])) {
            $errors[] = _l("Confirm_Password", "reset_password");
        }

        if (!preg_match("/\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\W])(?=\S*[\d])\S*/", $_POST['password'])) {
            $errors[] = _l("Invalid_Password_String", "reset_password");
        } else if ($_POST["cpassword"] != "" and ($_POST["password"] != $_POST["cpassword"])) {
            $errors[] = _l("Password_Confirm_Password_Same", "reset_password");
        }

        if (count($errors)) {
            $_SESSION[$this->session_prefix]["error_message"] = $errors;
            return false;
        }
        return true;
    }

    /**
     * DU - This function is used to reset admin/super admin password.
     * @param string $token
     * @return void
     */
    function resetAdminPassword($token) {
        $data = $this->getDBTable("forgot-password")->fetchRow(array("where" => "token = :token AND status = :status", "params" => array(":token" => $token, ":status" => '0')));

        $userData = array();
        $userData['password'] = md5($_POST["password"]);
        $this->database->update("users", $userData, array("where" => "user_id = :user_id", "params" => array(":user_id" => $data['user_id'])));

        $tokenData = array();
        $tokenData['status'] = '1';
        $this->getDBTable("forgot-password")->update($tokenData, array("where" => "user_id = :user_id", "params" => array(":user_id" => $data['user_id'])));
        $_SESSION[$this->session_prefix]["action_message"] = _l("Password_Updated_Success", "reset_password");
    }

    /**
     * DU - save binary images to system
     * @param type $data
     * @return array
     */
    function saveUserImage($data) {
        if (isset($data["filename"]) && !empty($data["filename"])) {
            $arrData = pathinfo($data["filename"]);
            $data['extension'] = $arrData['extension'];
        }
        $fileName = $data["user_id"] . "." . $data['extension'];
        $imagePath = APPLICATION_PATH . "/images/user_images/" . $fileName;

        $this->getModel("miscellaneous")->saveImages($imagePath, $data['binary_content']);

        $this->getDBTable("users")->update(array("user_image" => $fileName), array("where" => "user_id = :user_id", "params" => array(":user_id" => $data['user_id'])));
        return array(true, array("image_path" => APPLICATION_URL . "/images/user_images/" . $fileName));
    }

    /**
     * DU - This function is used to upload temporary image for user.
     * @return string $file_name
     */
    function uploadTemporaryImage() {
        $path = APPLICATION_PATH . "/images/temp_user_images/";

        if (isset($_FILES['user_image']) and !empty($_FILES['user_image'])) {
            if (preg_match("/\.(png|jpg|gif|jpeg|bmp)$/i", $_FILES["user_image"]["name"])) {
                $filetype_a = explode(".", $_FILES['user_image']["name"]);
                $filetype = $filetype_a[count($filetype_a) - 1];
                $file_name = time() . $filetype_a[0] . "." . $filetype;
                move_uploaded_file($_FILES['user_image']["tmp_name"], $path . $file_name);
            }
        }
        return $file_name;
    }

    /**
     * DU - This function is used to get list of all ordinary users
     * @return array $data
     */
    function getUsersList() {
        if (!$this->checkLoggedInAsSuperAdmin()) {
            $data = $this->database->queryData("SELECT u.*, c.name AS country, CONCAT_WS(' ',first_name,last_name) as uname FROM `users` AS u
                    INNER JOIN user_admin_relations AS r ON (r.user_id = u.user_id)
                    LEFT JOIN countries AS c ON (c.country_id = u.country_id)
                    WHERE u.usertype_id = :usertype_id AND r.admin_id = :admin_id ORDER BY u.user_id ASC", array(":usertype_id" => "2", ":admin_id" => $_SESSION[$this->session_prefix]['user']['user_id']));
            return $data;
        } else {
            $data = $this->database->queryData("SELECT u.*, c.name AS country, CONCAT_WS(' ',u.first_name, u.last_name) as uname, CONCAT_WS(' ',au.first_name, au.last_name) as aname FROM `users` AS u
                    LEFT JOIN user_admin_relations AS r ON (r.user_id = u.user_id)
                    LEFT JOIN users as au ON (au.user_id = r.admin_id)
                    LEFT JOIN countries AS c ON (c.country_id = u.country_id)
                    WHERE u.usertype_id = :usertype_id
                    ORDER BY u.user_id ASC", array(":usertype_id" => "2"));
            return $data;
        }
    }

    /**
     * Get User Base  Currency Symbol
     * @return string
     */
    function getUserBaseCurrencySymbol() {
        $arrData = $this->getDBTable("currencies")->fetchRowByFields(array("currency_symbol"), array("where" => "currency_id = :currency_id", "params" => array(":currency_id" => $_SESSION[$this->session_prefix]['user']['base_currency_id'])));
        return $arrData['currency_symbol'];
    }

    /**
     * Get User Base Currency Id
     * @return array
     */
    function getUserBaseCurrency() {
        $arrData = $this->getDBTable("users")->fetchRowByFields(array("base_currency_id"), array("where" => "user_id = :user_id", "params" => array(":user_id" => $_SESSION[$this->session_prefix]['user']['user_id'])));
        return $arrData['base_currency_id'];
    }

    /**
     * Get Profile Image
     * @param type $user_id
     * @return string
     */
    function getProfileImage($user_id) {
        $strWhere = "user_id = :user_id";
        $arrParams[':user_id'] = $user_id;
        $rsResult = $this->getDBTable("users")->fetchRowByFields(array('user_image'), array("where" => $strWhere, "params" => $arrParams));

        if (isset($rsResult['user_image']) and !empty($rsResult['user_image'])) {
            if ($imgfiletime = filemtime(APPLICATION_PATH . "/images/user_images/" . $rsResult['user_image'])) {
                $imgfilemtime = $imgfiletime;
            }
            $rsResult['user_image'] = APPLICATION_URL . "/images/user_images/" . $rsResult['user_image'] . "?" . $imgfilemtime;
        } else {
            $rsResult['user_image'] = "";
        }

        return $rsResult;
    }

    /**
     * function to get security question id of user
     * @param type $userId
     * @return array
     */
    function getUserSecurityQuestionId($userId) {
        $strWhere = "user_id = :user_id";
        $arrParams[':user_id'] = $userId;
        $rsResult = $this->getDBTable("users")->fetchRowByFields(array('security_question_id'), array("where" => $strWhere, "params" => $arrParams));
        return array(true, $rsResult);
    }

    /**
     * Insert user login information
     * @param int $userId
     * @param array $params
     * @return boolean
     */
    function checkInsertUserLoginInfo($userId, $params) {
        $arrParams = array();
        $arrParams[':user_id'] = $userId;
        $arrParams[':device_id'] = (isset($params['device_id']) and !empty($params['device_id'])) ? $params['device_id'] : 0;
        $arrParams[':app_version'] = ((isset($params['app_version']) and !empty($params['app_version']))) ? $params['app_version'] : 0;
        $arrParams[':installation_date'] = ((isset($params['installation_date']) and !empty($params['installation_date']))) ? date("Y-m-d H:i:s", $params['installation_date']) : '0000-00-00 00:00:00';

        $strWhere = "user_id = :user_id AND device_id=:device_id AND app_version=:app_version AND installation_date=:installation_date";

        $rsResult = $this->getDBTable("user-device-info")->fetchRowByFields(array('user_device_info_id'), array("where" => $strWhere, "params" => $arrParams));

        $this->insertUserLoginInfo($userId, $params);

        if ((isset($rsResult['user_device_info_id']) and !empty($rsResult['user_device_info_id']))) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * function to reset user device mapping
     * @param array $params
     * @return array
     */
    function resetUserSyncMapping($params) {
        $this->getDBTable("user-expenses-reference")->update(array("LUID" => 0), array("where" => "user_expense_id in (select user_expense_id from user_expenses where user_id = :user_id)", "params" => array(":user_id" => $params['user_id'])));
        $this->getDBTable("user-trip-reference")->update(array("LUID" => 0), array("where" => "user_trip_id in (select user_trip_id from user_trips where user_id =:user_id)", "params" => array(":user_id" => $params['user_id'])));
        $this->getDBTable("user-trips")->update(array("LUID" => 0), array("where" => "user_id = :user_id", "params" => array(":user_id" => $params['user_id'])));
        $this->getDBTable("user-expenses")->update(array("LUID" => 0), array("where" => "user_id = :user_id", "params" => array(":user_id" => $params['user_id'])));
        $this->getDBTable("expense-categories")->update(array("LUID" => 0), array("where" => "user_id = :user_id", "params" => array(":user_id" => $params['user_id'])));
        $this->getDBTable("expense-vendors")->update(array("LUID" => 0), array("where" => "user_id = :user_id", "params" => array(":user_id" => $params['user_id'])));
        return array(true, array("success" => true));
    }

    /**
     * Check user's login information
     * @param type $userId
     * @return boolean
     */
    function checkUserLoginInfo($userId) {
        $arrParams[':user_id'] = $userId;
        $strWhere = "user_id = :user_id";
        $rsResult = $this->getDBTable("user-device-info")->fetchRowByFields(array('count(0) as cnt_rec'), array("where" => $strWhere, "params" => $arrParams));
        if ($rsResult['cnt_rec'] > 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @param int $userId
     * @param array $params
     */
    function insertUserLoginInfo($userId, $params) {
        $arrData = array();
        $arrData['user_id'] = $userId;
        $arrData['device_id'] = $params['device_id'];
        $arrData['app_version'] = $params['app_version'];
        $arrData['installation_date'] = ((isset($params['installation_date']) and !empty($params['installation_date']))) ? date("Y-m-d H:i:s", $params['installation_date']) : 0;
        $arrData['last_login_on'] = date('Y-m-d H:i:s');

        $this->getDBTable("user-device-info")->replace($arrData);
    }

    /**
     * Get User Date Format
     * @param unknown $user_id
     * @param string $parameter
     * @return string
     */
    function getUserDateFormat($user_id, $parameter = 'date_format') {
        $SQL = "SELECT
                    if(usv.value IS NOT NULL,usv.value,us.value) as value
                    FROM user_settings us
                    LEFT JOIN user_setting_value usv ON usv.user_setting_id=us.user_setting_id and usv.user_id=:user_id
                    where parameter = :parameter";
        $param = $this->database->queryOne($SQL, array(":user_id" => $user_id, ":parameter" => $parameter));

        switch ($param["value"]) {
            case 'MM/DD/YY':
                $strPHPFormat = "m/d/y";
                break;
            case 'DD/MM/YY':
                $strPHPFormat = "d/m/y";
                break;
            default:
                $strPHPFormat = "Y/m/d";
                break;
        }
        return $strPHPFormat;
    }
    
    /**
     * Update Quick User Status
     * @return mixed
     */
    function updateQuickUserStatus()
    {
       $strStatus = ($_POST['status'] == 1)?0:1;
        $blnSucess = $this->getDBTable("users")->update(array("status" => $strStatus), array("where" => "user_id = :user_id", "params" => array(":user_id" => $_POST['user_id'])));
       
       return $blnSucess;
    }

}