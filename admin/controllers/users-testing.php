<?php

namespace admin\controllers;

class usersTestingController extends adminGlobalController {

    function indexAction() {

        //echo $this->action("users-testing", "get-my-name", "admin", array("name" => "jitesh"));
        //return $this->forward("index", "index", "default", array("name" => "jitesh"));
    }

    function getMyNameAction() {
        //return $this->forward("index", "index", "default", array("name" => "jitesh"));
    }

}
