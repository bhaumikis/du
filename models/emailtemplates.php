<?php

namespace model;

/**
 * @author Bhaumik Patel | bhaumik.patel@infostretch.com
 * brief Email Templates Model contains application logic for various functions and database operations of Email TemplatesModule.
 */
class emailtemplatesModel extends globalModel {

    /**
     * DU - This function will get the list of all email templates
     * @param $order_by
     * @param $sortby
     * @return array
     */
    function listEmailTemplates($type=NULL) {
        if($type!=NULL) {
            return $this->getDBTable("email-templates")->fetchAll(array("where" => "type=:type", "params" => array(":type" => $type)), "email_template_id DESC");
        }
        return $this->getDBTable("email-templates")->fetchAll("", "email_template_id DESC");
    }

    /**
     * DU - This function will get email template details based on name
     * @param $name
     * @return array
     */
    function getEmailTemplateByName($name = "") {
        return $this->getDBTable("email-templates")->fetchRow(array("where" => "name=:name", "params" => array(":name" => $name)));
    }

    /**
     * DU -This function will get details of the email template based on primary key
     * @param $email_template_id
     * @return array
     */
    function getEmailTemplateDetails($email_template_id = 0) {
        return $this->getDBTable("email-templates")->fetchRow(array("where" => "email_template_id=:email_template_id", "params" => array(":email_template_id" => $email_template_id)));
    }

    /**
     * DU - This function will add and edit the data in the email templates database
     * @return void
     */
    function addEditEmailTemplate() {
        $data = $_POST;
        unset($data["submit"]);
        unset($data["cancel"]);

        if ($data["email_template_id"]) { // Update Record
            $data["updated_date"] = date("Y-m-d H:i:s");
            $this->getDBTable("email-templates")->update($data, array("where" => "email_template_id=:email_template_id", "params" => array(":email_template_id" => $data["email_template_id"])));
            $_SESSION[$this->session_prefix]["action_message"] = _l("Msg_Edit_Success", "common");
        } else { // Add Record
            unset($data["email_template_id"]);

            if ($_POST['name'] == "") {
                $data['name'] = str_replace(" ", "_", $_POST['title']);
            }
            $data["created_date"] = date("Y-m-d H:i:s");
            $data["updated_date"] = date("Y-m-d H:i:s");
            $data["email_template_id"] = $this->getDBTable("email-templates")->insert($data);
            $_SESSION[$this->session_prefix]["action_message"] = _l("Msg_Add_Success", "common");
        }
        $result =  $this->getDBTable("email-templates")->find($data["email_template_id"]);
        return $result;
    }

    /**
     * DU - This function will get check the validations in the email template page while performing add and edit operation
     * @return boolean
     */
    function _validateEmailTemplateForm() {
        $errors = array();

        if (!isset($_POST["title"]) or !\generalFunctions::valueSet($_POST["title"])) {
            $errors[] = _l("Enter_Title", "email_templates");
        }
        if (isset($_POST["from_email"]) and !empty($_POST["from_email"]) and !\generalFunctions::isValidEmail($_POST["from_email"])) {
            $errors[] = _l("Enter_From_Email", "email_templates");
        }
        if (isset($_POST["to_email"]) and !empty($_POST["to_email"]) and !\generalFunctions::isValidEmail($_POST["to_email"])) {
            $errors[] = _l("Enter_To_Email", "email_templates");
        }
        if (!isset($_POST["subject"]) or !\generalFunctions::valueSet($_POST["subject"])) {
            $errors[] = _l("Enter_Subject", "email_templates");
        }
        if (count($errors)) {
            $_SESSION[$this->session_prefix]["error_message"] = $errors;
            return false;
        }
        return true;
    }

    /**
     * DU - This function is used to delete email templates
     */
    function deleteEmailTemplate($email_template_id = 0, $type = 1) {
        // Delete admin user.
        $this->getDBTable("email-templates")->delete(array("where" => "email_template_id = :email_template_id AND type = :type", "params" => array(":email_template_id" => $email_template_id, ":type" => $type)));
    }

    /**
     * DU - This function is used to get user list.
     */
    function getUserList($usertypeid = 0) {
        if ($usertypeid == "2") {
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
        } else if ($usertypeid == "3") {
            return $this->getDBTable("users")->fetchAllByFields(array("user_id,email,CONCAT_WS(' ',first_name,last_name) AS uname"), "usertype_id='3'", "user_id ASC");
        }
    }

    /**
     * DU - This function is used to validate send mail form.
     * @return boolean
     */
    function _validateSendMailForm() {
        $errors = array();

        if (!isset($_POST["sendto"]) or !\generalFunctions::valueSet($_POST["sendto"])) {
            $errors[] = _l("Enter_Sendto_Email", "email_templates");
        }
        if (!isset($_POST["subject"]) or !\generalFunctions::valueSet($_POST["subject"])) {
            $errors[] = _l("Enter_Send_Subject", "email_templates");
        }
        if (count($errors)) {
            $_SESSION[$this->session_prefix]["error_message"] = $errors;
            return false;
        }
        return true;
    }

    /**
     * DU - This function is used to send mail to bulk user.
     * @return boolean
     */
    function sendMailToBulkUsers() {
        $data = $_POST;
        if (isset($data['sendto']) and !empty($data['sendto'])) {
            $toemails = explode(',', $data['sendto']);
            $user_id = explode(',', $data['sendtoids']);
            $i = 0;
            foreach ($toemails as $toemail) {
                if (\generalFunctions::isValidEmail($toemail)) {
                    $this->sendBulkEmail($toemail, $data['subject'], $data['htmltext'], "support@dailyuse.com");
                    $logdata = array();
                    $logdata["created_date"] = date("Y-m-d H:i:s");
                    $logdata["status"] = "1";
                    $logdata["email"] = $toemail;
                    $logdata["user_id"] = $user_id[$i];
                    $logdata["subject"] = $data['subject'];
                    $logdata["email_template_id"] = $data['hid_email_template_id'];
                    $this->getDBTable("send-email-logs")->insert($logdata);
                }
                $i++;
            }
        }
    }

    /**
     * DU - This function is used to get email log.
     * @return boolean
     */
    function getEmailLog($email_template_id) {
        $sql = "select el.email, et.title, el.created_date, CONCAT_WS(' ',first_name,last_name) AS uname FROM `send_email_logs` AS el
                INNER JOIN email_templates AS et ON (et.email_template_id = el.email_template_id)
                INNER JOIN users AS u ON (u.user_id = el.user_id)
                WHERE el.email_template_id = :email_template_id";
        return $this->database->queryData($sql, array(":email_template_id" => $email_template_id));
    }

}
