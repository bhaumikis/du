<?php

namespace admin\controllers;

class emailtemplatesController extends adminGlobalController {

    /**
     * indexAction is used to display list of email template.
     */
    function indexAction() {
        $this->view->emailtemplates = $this->getModel("emailtemplates")->listEmailTemplates();
        $this->view->header_title = _l("Text_Header_Title",'email_templates');
    }

    /**
     * indexAction is used to display list of email template.
     */
    function defaultAction() {
        $this->setView('index');
        $this->view->emailtemplates = $this->getModel("emailtemplates")->listEmailTemplates($type='0');
        $this->view->header_title = _l("Text_Header_Title",'email_templates');
    }
    
    /**
     * indexAction is used to display list of email template.
     */
    function promotionalAction() {
        $this->setView('index');
        $this->view->emailtemplates = $this->getModel("emailtemplates")->listEmailTemplates($type=1);
        $this->view->header_title = _l("Text_Header_Title",'email_templates');
    }
    
    /**
     * addeditAction is used to add/edit email template.
     */
    function addeditAction() {
        if ($_POST) {
            if ($this->getModel("emailtemplates")->_validateEmailTemplateForm()) {
                $result = $this->getModel("emailtemplates")->addEditEmailTemplate();
                if($result['type']==0) \generalFunctions::redirectToLocation($this->getModuleURL() . "/emailtemplates/default");
                else \generalFunctions::redirectToLocation($this->getModuleURL() . "/emailtemplates/promotional");
            }
        }

        $this->view->email_template_id = 0;
        $this->view->emailtemplatedetails = array();
        $this->view->header_title = _l("Text_Header_Title",'email_templates');

        if (isset($_REQUEST["email_template_id"])) {
            $this->view->email_template_id = $_REQUEST["email_template_id"];
            $this->view->emailtemplatedetails = \generalFunctions::htmlsplchs($this->getModel("emailtemplates")->getEmailTemplateDetails($this->view->email_template_id), array("htmltext"));
        }
    }

    /**
     * deleteAdminAction is used to delete admin user.
     */
    function deleteTemplateAction() {
        if (isset($_GET['type']) and $_GET['type'] == '0') {
            $_SESSION[$this->session_prefix]["error_message"] = _l('Error_Delete_Default_Template', 'email_templates');
            generalFunctions::redirectToLocation($this->getModuleURL() . '/emailtemplates/default');
        }
        $this->getModel("emailtemplates")->deleteEmailTemplate($_GET["email_template_id"], $_GET['type']);
        \generalFunctions::redirectToLocation($this->getModuleURL() . '/emailtemplates/promotional');
    }

    /**
     * sendMailAction is used to send mails to user.
     */
    function sendMailAction() {
        if ($_POST and $this->getModel("emailtemplates")->_validateSendMailForm()) {
            $result = $this->getModel("emailtemplates")->getEmailTemplateDetails($_POST["hid_email_template_id"]);
            if($result and $result["type"]==0) $type = "default";
            else $type = "promotional";
            $this->getModel("emailtemplates")->sendMailToBulkUsers();
            \generalFunctions::redirectToLocation($this->getModuleURL() . "/emailtemplates/$type");
        }
        $this->view->emailtemplatedetails = $this->getModel("emailtemplates")->getEmailTemplateDetails($_GET["email_template_id"]);
        $this->view->email_template_id = $_GET["email_template_id"];
        $this->view->header_title = _l("Text_Header_Title","email_templates");
    }

    /**
     * getUserListAction is used to get list of users.
     */
    function userListAction() {
        $this->view->userlist = $this->getModel("emailtemplates")->getUserList($_GET["usertypeid"]);
        $this->setTemplate("popup");
    }

    /**
     * showSentEmailLogAction is used to get list of email log.
     */
    function showSentEmailLogAction() {
        $this->view->emaillog = $this->getModel("emailtemplates")->getEmailLog($_GET["email_template_id"]);
        $this->setTemplate("popup");
    }

}