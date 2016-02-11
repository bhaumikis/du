<?php

namespace model;

/**
 * @author Bhaumik Patel | bhaumik.patel@infostretch.com
 * brief Resources Model contains application logic for various functions and database operations of resources..
 */
class resourcesModel extends globalModel {

    /**
     * This function will update the resources databy using crawl function and it will search for the controller and its action which are created but not inserted as a resource entry.
     * so if the new action found which is not there in the resource list then it will insert the resource for that controller and action
     * It will ignore the duplication
     * @global type $application_modules
     * @return void
     */
    function updateResourceList() {
        global $application_modules;

        $modules = array_merge(array("default"), $application_modules);

        foreach ($modules as $module) {
            $contoller_path = APPLICATION_PATH . (($module != "default") ? "/" . $module : "") . "/controllers";

            $files = glob($contoller_path . "/*.php");

            foreach ($files as $file) {

                $controller = basename($file, ".php");

                $lines = file($file);

                $lines[] = "function *Action(";

                foreach ($lines as $line) {
                    $line = trim($line);

                    if (preg_match("/function\s(.*)Action\(/", $line, $matches)) {

                        $action = $matches[1];

                        preg_match_all('/((?:^|[A-Z])[a-z]+)/', $action, $matches_words);

                        if ($action == "*") {
                            $title = "All Actions - " . ucwords(str_replace("-", " ", $controller));
                        } else {
                            $title = ucwords(implode(" ", $matches_words[1])) . " - " . ucwords(str_replace("-", " ", $controller));
                        }

                        $data = array();
                        $data["title"] = $title;
                        $data["module"] = $module;
                        $data["option"] = $controller;
                        $data["action"] = \generalFunctions::revertToActionName($action);
                        $data["status"] = 1;

                        $resource_id = $this->getDBTable("resources")->insertignore($data);
                    }
                }
            }
        }
    }

    /**
     * This function will return the list of resources
     * @return array
     */
    function getResourceList() {
        $sql = "SELECT * FROM `resources` ORDER BY `module` ASC,`option` ASC";
        return $this->database->queryData($sql);
    }

    /**
     * This function will return the list of resources for solution
     * @return array
     */
    function getResourceListForSolution() {
        $sql = "SELECT * FROM `resources` WHERE module = 'default' ORDER BY `module` ASC,`option` ASC";
        return $this->database->queryData($sql);
    }

    /**
     * This function will get list of the resources based on parameters,it will return the data based on the pagination
     * @param $order_by
     * @param $sortby
     * @return array
     */
    function listResources($order_by, $sortby) {

        $sql = "SELECT * FROM `resources` ORDER BY `module` ASC,`option` ASC," . $order_by;
        return $this->database->queryData($sql);

        $pager = new PS_Pagination($this->database->link, $sql, generalFunctions :: getConfValue("rows_per_page"), generalFunctions :: getConfValue("links_per_page"), "sortby=" . $sortby);
        $result = $pager->paginate();

        $resources = array();
        if ($result) {
            while ($row = $this->database->fetchAssoc($result)) {
                $resources[] = $row;
            }
        }

        return array($pager, $resources);
    }

    /**
     * This function will get the resource detail based on name
     * @param $name
     * @return array
     */
    function getResourceByName($name = "") {
        return $this->getDBTable("resources")->fetchRow("name = '" . $name . "'");
    }

    /**
     * This function will get all the details related to resource based on resource_id
     * @param $resource_id
     * @return array
     */
    function getResourceDetails($resource_id = 0) {
        return $this->getDBTable("resources")->fetchRow("resource_id = '" . $resource_id . "'");
    }

    /**
     * This function contains the logic for the add and edit operation in the resources table
     * @return void
     */
    function addEditResource() {
        $data = $_POST;
        unset($data["submit"]);
        unset($data["cancel"]);

        if ($data["resource_id"]) { // Update Record
            $data["updated_date"] = date("Y-m-d H:i:s");
            $this->getDBTable("resources")->update($data, "resource_id = '" . $data["resource_id"] . "'");
            $_SESSION[$this->session_prefix]["action_message"] = _l("Resource updated successfully.", "common");
        } else { // Add Record
            unset($data["resource_id"]);
            $data["created_date"] = date("Y-m-d H:i:s");
            $data["updated_date"] = date("Y-m-d H:i:s");
            $data["resource_id"] = $this->getDBTable("resources")->insertignore($data);
            $_SESSION[$this->session_prefix]["action_message"] = _l("Resource added successfully.", "common");
        }
    }

    /**
     * This function will check the validation from the resource form while doing add and edit operation
     * @return boolean
     */
    function _validateResourceForm() {
        $errors = array();

        if (!isset($_POST["title"]) or !\generalFunctions::valueSet($_POST["title"])) {
            $errors[] = _l("Please enter title.", "resources");
        }
        if (count($errors)) {
            $_SESSION[$this->session_prefix]["error_message"] = $errors;
            return false;
        }
        return true;
    }

}
