<?php

namespace model;

/**
 * @author Bhaumik Patel | bhaumik.patel@infostretch.com
 * securityQuestionsModel 
 * brief Security Questions Model contains application logic for various functions and database operations of Security Questions..
 */
class securityQuestionsModel extends globalModel {
    
    /**
     * DU - This function is used to get security questions
     * @param string $status
     * @return Array $dataArr
     */
    function getSecurityQuestions($status = '') {
        if (isset($status) and !empty($status)) {
            $data = $this->getDBTable("security-questions")->fetchAll(array("where" => "status = :status", "params" => array(":status" => '1')));
        } else {
            $data = $this->getDBTable("security-questions")->fetchAll();
        }
        $dataArr = array();

        if (!empty($data)) {
            $dataArr = $data;
        }
        return $dataArr;
    }

    /**
     * DU - This function is used to check if security question matches with the users entered question.
     * @return boolean
     */
    function validateSecurityQuestion() {
        $errors = array();

        if (!isset($_POST["security_question_id"]) or !\generalFunctions::valueSet($_POST["security_question_id"])) {
            $errors[] = _l("Select_Question", "forgot_password");
        }
        if (!isset($_POST["security_answer"]) or !\generalFunctions::valueSet($_POST["security_answer"])) {
            $errors[] = _l("Enter_Answer", "forgot_password");
        }

        if ((!isset($_POST["email"]) or !\generalFunctions::valueSet($_POST["email"])) AND (!isset($_POST["mobile_number"]) or !\generalFunctions::valueSet($_POST["mobile_number"]))) {
            $errors[] = _l("Enter_Email_Or_Mobile", "forgot_password");
        }

        if (count($errors)) {
            $_SESSION[$this->session_prefix]["error_message"] = $errors;
            return false;
        } else {
            $data = $this->getDBTable("users")->fetchAll(array("where" => "security_question_id = :security_question_id AND security_answer = :security_answer AND (mobile_number = :mobile_number OR email = :email) AND status = :status AND deleted = :deleted", "params" => array(":security_question_id" => $_POST['security_question_id'], ":security_answer" => $_POST['security_answer'], ":mobile_number" => $_POST['mobile_number'], ":email" => $_POST['email'], ":status" => '1', ":deleted" => '0')));

            if (empty($data)) {
                $errors = array();
                $errors[] = _l("QA_Not_Match", "forgot_password");
                $_SESSION[$this->session_prefix]["error_message"] = $errors;
                return false;
            }
        }
        if ($_POST['mobile_number'] != '') {
            // Write code to send random password in sms.
        }

        if ($_POST['email'] != '' and isset($data)) {
            if (\generalFunctions::isValidEmail($_POST['email'])) {
                $empData["firstname"] = $data[0]["first_name"];
                $empData["lastname"] = $data[0]["last_name"];
                $empData["mobile_number"] = $data[0]["mobile_number"];
                //Generate string/password with 10 character capital letters, digit, no special characters and small letters.
                $empData["random_pass"] = \generalFunctions::genRandomPass(10, true, true, false, true);
                $empData["to_email"] = $data[0]["email"];
                $userData = array();
                $userData['password_flag'] = '1';
                $userData['password'] = md5($empData["random_pass"]);
                $this->getDBTable("users")->update($userData, array("where" => "user_id = :user_id", "params" => array(":user_id" => $data[0]["user_id"])));

                $this->sendEmail("forgot_password_mail", $empData);
                $_SESSION[$this->session_prefix]["action_message"] = _l("Mail_Sent", "forgot_password");
            }
        }
        return true;
    }

    /**
     * DU - This function is used to validate security question while adding.
     * @return boolean
     */
    function validateSecurityForm() {
        $errors = array();

        if (!isset($_POST["question"]) or !\generalFunctions::valueSet($_POST["question"])) {
            $errors[] = _l('Error_Enter_Question', 'change_security_question');
        }
        if (count($errors)) {
            $_SESSION[$this->session_prefix]["error_message"] = $errors;
            return false;
        } else {
            $data = $this->getDBTable("security-questions")->fetchRow(array("where" => "question = :question AND status = :status", "params" => array(":question" => $_POST['question'], ":status" => '1')));

            if (isset($data) and !empty($data)) {
                $errors = array();
                $errors[] = _l('Error_Question_Exists', 'change_security_question');
                $_SESSION[$this->session_prefix]["error_message"] = $errors;
                return false;
            }
        }
        return true;
    }

    /**
     * DU - This function is used to add security question 
     */
    function addSecurityQuestion() {

        $data = $_POST;
        unset($data['submit']);
        $data['created_date'] = date('Y-m-d H:i:s');
        $data['updated_date'] = date('Y-m-d H:i:s');

        $this->getDBTable("security-questions")->insert($data);
        $_SESSION[$this->session_prefix]["action_message"] = _l('Text_Question_Add_Success', 'change_security_question');
    }

    /**
     * DU - This function is used to get list of security question
     */
    function getSecurityQuestionsList() {
        return $this->getDBTable("security-questions")->fetchAll("", "security_question_id DESC");
    }

}
