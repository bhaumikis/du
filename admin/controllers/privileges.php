<?php

switch ($action) {
    default:
        if ($_POST) {

            $data = $_POST;

            if ($data["usertype_id"] == "1") {
                $_SESSION[$session_prefix]["action_message"] = "Super Administrator has all privileges.";
                \generalFunctions::redirectToLocation($this->getModuleURL() . "/privileges");
            }

            $database->delete("privileges", "usertype_id = '" . $data["usertype_id"] . "'");

            if (isset($data["module_id"]) and is_array($data["module_id"])) {
                foreach ($data["module_id"] as $k => $v) {
                    $database->insert("privileges", array("usertype_id" => $data["usertype_id"], "module_id" => $v));
                }
            }

            $_SESSION[$session_prefix]["action_message"] = "Records saved successfully";
            \generalFunctions::redirectToLocation($this->getModuleURL() . "/usertypes");
        } else {
            $resources = $database->selectData("resources", "status = '1'", "title ASC");
            $usertype = $database->selectOne("usertypes", "usertype_id = '" . $_GET["usertype_id"] . "' AND status = '1'");
        }

        break;
}