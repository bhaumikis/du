<?php

namespace model;

/**
 * @author Bhaumik Patel | bhaumik.patel@infostretch.com
 * globalModel Class - Master class of the all business models class.
 */
abstract class globalModel extends \superGlobal {

    public $database = null;
    public $used_db_tables_by_request = array();

    function __construct() {
        $this->database = \configurations::getDBObject();
        parent::__construct();
    }

    /**
     * Get Db Table's objject 
     * @param string $db_table
     * @param string $require_new_object
     * @return \dbtable $db_table_obj
     */
    function getDBTable($db_table = "", $require_new_object = false) {

        if ($require_new_object == true or !isset($this->used_db_tables_by_request[$db_table])) {
            include_once(APPLICATION_PATH . "/models/dbtables/" . $db_table . ".php");
            $db_table_class_name = \generalFunctions::convertToActionName($db_table);
            $db_table_class_name = "dbtable\\" . $db_table_class_name;
            $db_table_obj = new $db_table_class_name;
            if (method_exists($db_table_obj, "init")) {
                $db_table_obj->init();
            }
            $this->used_db_tables_by_request[$db_table] = $db_table_obj;
        } else {
            $db_table_obj = $this->used_db_tables_by_request[$db_table];
        }

        return $db_table_obj;
    }

    /**
     * Get Email Content by parameters
     * @param string $template
     * @param string $field
     * @param unknown $data
     * @return mixed
     */
    function getEmailContentByParams($template = "index", $field = "", $data = array()) {
        $email_template = $this->getDBTable("email-templates")->fetchRow("name = '" . $template . "'");

        $s_array = array();
        $d_array = array();

        foreach ($data as $k => $v) {
            $s_array[] = "{" . $k . "}";
            $d_array[] = $v;
        }

        foreach ($email_template as $k => $v) {
            $s_array[] = "{" . $k . "}";
            $d_array[] = $v;
        }
        return str_replace($s_array, $d_array, $field);
    }

    /**
     * Send Email function
     * @param string $template
     * @param unknown $data
     * @param unknown $attachments
     * @param string $smtp
     */
    function sendEmail($template = "index", $data = array(), $attachments = array(), $smtp = false) {

        $email_template = $this->getDBTable("email-templates")->fetchRow("name = '" . $template . "'");
        require_once(APPLICATION_PATH . "/lib/phpmailer/class.phpmailer.php");

        $mail = new \PHPMailer();

        if (\generalFunctions::getConfValue("email_option") == "smtp") {
            $mail->IsSMTP();
            $mail->Host = \generalFunctions::getConfValue("smtp_host");
            $mail->SMTPAuth = true;
            $mail->Username = \generalFunctions::getConfValue("smtp_username");
            $mail->Password = \generalFunctions::getConfValue("smtp_password");

            if (\generalFunctions::getConfValue("smtp_secure_connection") == "TLS") {
                $mail->Port = \generalFunctions::getConfValue("smtp_port");
                $mail->SMTPSecure = "tls";
                $data["from_email"] = \generalFunctions::getConfValue("smtp_from_email");
                $data["from_name"] = \generalFunctions::getConfValue("smtp_from_name");
            } elseif (\generalFunctions::getConfValue("smtp_secure_connection") == "SSL") {
                $mail->Port = \generalFunctions::getConfValue("smtp_port");
                $mail->SMTPSecure = "ssl";
                $data["from_email"] = \generalFunctions::getConfValue("smtp_from_email");
                $data["from_name"] = \generalFunctions::getConfValue("smtp_from_name");
            }
        } else {
            $mail->IsSendmail();
            $mail->Sendmail = \generalFunctions::getConfValue("sendmail");
        }

        if ($email_template["format"] == 'html') {
            $mail->IsHTML(true);
            $mail->Body = $email_template["htmltext"];
        } else {
            $mail->Body = $email_template["text"];
        }

        if (isset($attachments) and count($attachments)) {
            foreach ($attachments as $k => $atcmnt) {
                $mail->AddAttachment($atcmnt);
            }
        }

        $s_array = array();
        $d_array = array();

        foreach ($data as $k => $v) {
            $s_array[] = "{" . $k . "}";
            $d_array[] = $v;
        }

        foreach ($email_template as $k => $v) {
            $s_array[] = "{" . $k . "}";
            $d_array[] = $v;
        }

        $from_email = (isset($data["from_email"]) and strlen($data["from_email"])) ? $data["from_email"] : $email_template["from_email"];
        $from_name = (isset($data["from_name"]) and strlen($data["from_name"])) ? $data["from_name"] : $email_template["from_name"];
        $to_email = (isset($data["to_email"]) and strlen($data["to_email"])) ? $data["to_email"] : $email_template["to_email"];
        $to_name = (isset($data["to_name"]) and strlen($data["to_name"])) ? $data["to_name"] : $email_template["to_name"];

        $mail->Subject = str_replace($s_array, $d_array, $email_template["subject"]);
        $mail->Body = str_replace($s_array, $d_array, $mail->Body);
        $mail->SetFrom($from_email, $from_name);

        if ($to_name) {
            $mail->AddAddress($to_email, $to_name);
        } else {
            $mail->AddAddress($to_email);
        }

        $mail->AddAddress($to_email);
        $mail->Send();
    }

    /**
     * Translate values from the translate table.
     * @param string $lang
     * @param unknown $data
     * @param string $reference_table
     * @param number $reference_id
     * @return Ambigous <multitype:, unknown>
     */
    function translateValues($lang = "en_US", $data = array(), $reference_table = "", $reference_id = 0) {

        $sql = "SELECT * FROM translations WHERE reference_table = '" . $reference_table . "' AND language = '" . $lang . "' AND reference_id = '" . $reference_id . "'";

        $result = $this->database->queryData($sql);

        $trans_values = array();

        foreach ($result as $k => $v) {
            $trans_values[$v["reference_field"]] = $v["value"];
        }

        foreach ($trans_values as $field => $value) {
            if (isset($value) and !empty($value)) {
                $data[$field] = $value;
            }
        }
        return $data;
    }

    /**
     * This function is used to get status for all modules
     * @return array
     */
    function getFieldsStatus() {

        $status = array();
        $status[] = array("value" => "1", "text" => _l("Active", "common"));
        $status[] = array("value" => "0", "text" => _l("Inactive", "common"));

        return $status;
    }

    /**
     * Check user type is Admin or not.
     * @return boolean
     */
    function isUserCustomerAdmin() {

        if (isset($_SESSION[$this->session_prefix]["user"]["usertype_type"]) and
                $_SESSION[$this->session_prefix]["user"]["usertype_type"] == 3 and
                $_SESSION[$this->session_prefix]["user"]["usertype_id"] == CUSTOMER_ADMIN) {
            return true;
        }

        return false;
    }

    /**
     * Check User is customer or not.
     * @return boolean
     */
    function isUserCustomerOther() {

        if (isset($_SESSION[$this->session_prefix]["user"]["usertype_type"]) and
                $_SESSION[$this->session_prefix]["user"]["usertype_type"] == 3 and
                $_SESSION[$this->session_prefix]["user"]["usertype_id"] != CUSTOMER_ADMIN) {
            return true;
        }

        return false;
    }

    /**
     * Check User is solution admin or not.
     * @return boolean
     */
    function isUserSolutionAdmin() {

        if ($_SESSION[$this->session_prefix]["user"]["usertype_id"] == SOLUTION_ADMIN) {
            return true;
        }

        return false;
    }

    /**
     * Update the table's updated date
     * @param string $table
     * @param string $field
     * @param string $updated_date
     * @param string $where
     */
    function updateModifiedDate($table = "", $field = "", $updated_date = "", $where = "1") {
        $this->database->update($table, $field . " = '" . $updated_date . "'", $where);
    }

    /**
     * Type casting of the fields
     * @param unknown $castarray
     * @param unknown $result
     * @param string $level
     */
    function typeCastFields($castarray = array(), &$result = array(), $level = "1") {

        switch ($level) {
            case "2":
                foreach ($result as $indx => $record) {
                    foreach ($castarray as $type => $fields) {
                        foreach ($fields as $field) {
                            settype($result[$indx][$field], $type);
                        }
                    }
                }
                break;
            case "1":
            default:
                foreach ($castarray as $type => $fields) {
                    foreach ($fields as $field) {
                        settype($result[$field], $type);
                    }
                }
                break;
        }
    }

    /**
     * Send Bulk Emails to users.
     * @param string $to_email
     * @param string $subject
     * @param string $body
     * @param string $from_email
     * @param unknown $attachments
     * @param string $smtp
     */
    function sendBulkEmail($to_email = "", $subject = "", $body = "", $from_email = "", $attachments = array(), $smtp = false) {

        require_once(APPLICATION_PATH . "/lib/phpmailer/class.phpmailer.php");
        $data = array();

        $mail = new \PHPMailer();

        if (\generalFunctions::getConfValue("email_option") == "smtp") {
            $mail->IsSMTP();
            $mail->Host = \generalFunctions::getConfValue("smtp_host");
            $mail->SMTPAuth = true;
            $mail->Username = \generalFunctions::getConfValue("smtp_username");
            $mail->Password = \generalFunctions::getConfValue("smtp_password");

            if (\generalFunctions::getConfValue("smtp_secure_connection") == "TLS") {
                $mail->Port = \generalFunctions::getConfValue("smtp_port");
                $mail->SMTPSecure = "tls";
                $data["from_email"] = \generalFunctions::getConfValue("smtp_from_email");
                $data["from_name"] = \generalFunctions::getConfValue("smtp_from_name");
            } elseif (\generalFunctions::getConfValue("smtp_secure_connection") == "SSL") {
                $mail->Port = \generalFunctions::getConfValue("smtp_port");
                $mail->SMTPSecure = "ssl";
                $data["from_email"] = \generalFunctions::getConfValue("smtp_from_email");
                $data["from_name"] = \generalFunctions::getConfValue("smtp_from_name");
            }
        } else {
            $mail->IsSendmail();
            $mail->Sendmail = \generalFunctions::getConfValue("sendmail");
        }

        if (isset($attachments) and count($attachments)) {
            foreach ($attachments as $k => $atcmnt) {
                $mail->AddAttachment($atcmnt);
            }
        }

        $from_email = (isset($data["from_email"]) and strlen($data["from_email"])) ? $data["from_email"] : $from_email;
        $from_name = (isset($data["from_name"]) and strlen($data["from_name"])) ? $data["from_name"] : $from_name;
        $to_email = (isset($data["to_email"]) and strlen($data["to_email"])) ? $data["to_email"] : $to_email;
        $to_name = (isset($data["to_name"]) and strlen($data["to_name"])) ? $data["to_name"] : $to_name;

        $mail->Subject = $subject;
        $mail->IsHTML(true);
        $mail->Body = $body;
        $mail->SetFrom($from_email, $from_name);

        if ($to_name) {
            $mail->AddAddress($to_email, $to_name);
        } else {
            $mail->AddAddress($to_email);
        }

        $mail->Send();
    }

}
