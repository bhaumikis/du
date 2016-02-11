<?php

namespace userportal\controllers;

/**
 * @author Bhaumik Patel | bhaumik.patel@infostretch.com
 *
 */
class errorController extends globalController {

    /*
     * Error Page
     */
    function indexAction() {

        $this->setTemplate("error");

        if ($this->checkSessionKeyExists("errors")) {
            $this->view->errors = $this->getSession("errors");
            $this->unsetSession("errors");
        }
    }

}