<?php

namespace model;

/**
 * @author Bhaumik Patel | bhaumik.patel@infostretch.com
 * brief Configurations Model contains application logic for various functions and database operations of Configurations Module.
*/
class configurationsModel extends globalModel {

    /**
     * This function will get the list of the configurations/settings
     * @param type $order_by
     * @return array
     */
    function listConfigurations($order_by) {
        $sql = "SELECT * FROM `configurations` WHERE  status = '1' ORDER BY " . $order_by;
        return $this->database->queryData($sql);
    }

    /**
     * This function willsave the coniguration/settings in the database for the portal
     * @return void
     */
    function saveConfigurations($post = array()) {
        foreach ($post as $k => $v) {

            $save = true;
            $type = $this->getDBTable("admconfigurations")->fetchAllByFields(array("type", "title"), "configuration_id = '" . $k . "'");

            switch ($type[0]["type"]) {
                case "int":
                    if (!\generalFunctions::checkInt($v)) {
                        $_SESSION[$this->session_prefix]["error_message"][] = _l("INVALID_VALUE", "common") . " \"" . $type[0]["title"] . "\"";
                        $save = false;
                    }
                    break;
                case "url":
                    if (!\generalFunctions::checkURL($v)) {
                        $_SESSION[$this->session_prefix]["error_message"][] = _l("INVALID_VALUE", "common") . " \"" . $type[0]["title"] . "\"";
                        $save = false;
                    }
                    break;
                case "email":
                    if (!\generalFunctions::isValidEmail($v)) {
                        $_SESSION[$this->session_prefix]["error_message"][] = _l("INVALID_VALUE", "common") . " \"" . $type[0]["title"] . "\"";
                        $save = false;
                    }
                    break;
                case "csv":
                    $v = preg_replace("/\s/", "", $v);
                    break;
                case "text":
                default:
                    break;
            }

            $v = trim($v);

            if ($save) {
                $this->getDBTable("admconfigurations")->update(array("value" => $v), "configuration_id = '" . $k . "'");
            }
        }

        $status = true;
        if (count($_SESSION[$this->session_prefix]["error_message"])) {
            $status = false;
        }

        return $status;
    }

    /**
     * This function is used to get application configuration details
     * @param $application_id
     * @return $app_conf_values
     */
    function getGoogeAnalyticsConfig($application_id = 0) {

        if (!$this->getModel("modules")->checkGoogleAnalyticExists($application_id)) {
            return array();
        }

        $sql = "SELECT acv.application_configuration_id,acv.value,ac.configuration_type FROM application_configuration_values AS acv "
                . "INNER JOIN application_configurations AS ac ON (acv.application_configuration_id = ac.application_configuration_id)"
                . " WHERE acv.application_id = '" . $application_id . "' AND ac.group = 'Google Analytics' AND ac.configuration_type = '1'";

        $tmp_app_conf_values = $this->database->queryData($sql);

        foreach ($tmp_app_conf_values as $v) {
            $tmp_all_conf_details[$v["application_configuration_id"]] = $v["value"];
        }

        $where = "status = '1' AND `group` = 'Google Analytics' AND configuration_type = '1'";

        $all_confs = $this->getDBTable("application-configurations")->fetchAll($where, "ordering ASC");

        $app_conf_values = array();
        foreach ($all_confs as $k => $v) {
            $app_conf_values[$k]["title"] = $v["title"];
            $app_conf_values[$k]["parameter"] = $v["parameter"];
            $app_conf_values[$k]["value"] = $all_confs[$k]["default_value"];

            if (strlen($tmp_all_conf_details[$v["application_configuration_id"]])) {
                $app_conf_values[$k]["value"] = $tmp_all_conf_details[$v["application_configuration_id"]];
            }
        }

        return $app_conf_values;
    }

    /**
     * This function is used to get application level configurations for crittercism
     * @param $application_id
     * @return $app_conf_values
     */
    function getCrittercismConfig($application_id = 0) {

        if (!$this->getModel("modules")->checkCrittercismExists($application_id)) {
            return array();
        }

        $sql = "SELECT acv.application_configuration_id,acv.value FROM application_configuration_values AS acv"
                . " INNER JOIN application_configurations AS ac ON (acv.application_configuration_id = ac.application_configuration_id) "
                . " WHERE acv.application_id = '" . $application_id . "' AND ac.group = 'Crittercism' AND ac.configuration_type = '1'";
        $tmp_app_conf_values = $this->database->queryData($sql);

        foreach ($tmp_app_conf_values as $v) {
            $tmp_all_conf_details[$v["application_configuration_id"]] = $v["value"];
        }

        $where = "status = '1' AND `group` = 'crittercism' AND configuration_type = 1";

        $all_confs = $this->getDBTable("application-configurations")->fetchAll($where, "ordering ASC");

        $app_conf_values = array();
        foreach ($all_confs as $k => $v) {
            $app_conf_values[$k]["title"] = $v["title"];
            $app_conf_values[$k]["parameter"] = $v["parameter"];
            $app_conf_values[$k]["value"] = $all_confs[$k]["default_value"];

            if (strlen($tmp_all_conf_details[$v["application_configuration_id"]])) {
                $app_conf_values[$k]["value"] = $tmp_all_conf_details[$v["application_configuration_id"]];
            }
        }

        return $app_conf_values;
    }

}
