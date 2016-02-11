<?php

/**
 * @author Bhaumik Patel | bhaumik.patel@infostretch.com
 * View Class
 */
class view {

    private $extra_js = array();
    private $extra_css = array();
    private $used_helpers = array();

    /**
     * Add extra js file in the view
     * @param string $jspath
     */
    function addExtraJS($jspath = "") {
        $this->extra_js[] = $jspath;
    }

    /* e.g. - array("path"=>"http://www.domain.com/css/style.css","params"=>array("media"=>"print")); */

    /**
     * Add extra css file in the view
     * @param string $csspath
     */
    function addExtraCSS($csspath = "") {
        $this->extra_css[] = $csspath;
    }

    /**
     * Remove extra js file in the view
     * @param unknown $key
     */
    function removeExtraJS($key = -1) {
        unset($this->extra_js[$key]);
    }

    /**
     * Remove extra css file in the view 
     * @param unknown $key
     */
    function removeExtraCSS($key = -1) {
        unset($this->extra_css[$key]);
    }

    /**
     * Render extra Js file
     * @return string
     */
    function renderExtraJS() {
        $str = "";
        foreach ($this->extra_js as $e_j) {
            $params_str = "";
            if (isset($e_j["params"]) and is_array($e_j["params"]) and !empty($e_j["params"])) {
                $params_tmp = array();
                foreach ($e_j["params"] as $p => $v) {
                    $params_tmp[] = $p . '="' . $v . '"';
                }
                $params_str = " " . implode(" ", $params_tmp);
            }
            $str .= '<script type="text/javascript" language="javascript" src="' . $e_j["path"] . '"' . $params_str . '></script>';
            $str .= "\n";
        }

        return $str;
    }

    /**
     * Render Extra Css file
     * @return string
     */
    function renderExtraCSS() {
        $str = "";
        foreach ($this->extra_css as $e_c) {
            $params_str = "";
            if (isset($e_c["params"]) and is_array($e_c["params"]) and !empty($e_c["params"])) {
                $params_tmp = array();
                foreach ($e_c["params"] as $p => $v) {
                    $params_tmp[] = $p . '="' . $v . '"';
                }
                $params_str = " " . implode(" ", $params_tmp);
            }
            $str .= '<link href="' . $e_c["path"] . '" rel="stylesheet" type="text/css"' . $params_str . '/>';
            $str .= "\n";
        }
        return $str;
    }

    /**
     * Set Helper
     * @param unknown $helper_name
     * @param unknown $args
     * @param string $require_new_object
     * @return Ambigous <unknown, multitype:>
     */
    function helper($helper_name, $args = array(), $require_new_object = false) {

        if ($require_new_object == true or !isset($this->used_helpers[$helper_name])) {
            include_once(APPLICATION_PATH . "/application/view/helpers/" . $helper_name . ".php");
            $helper_obj_name = '\helper\\' . \frontController::convertToActionName($helper_name);
            $helper = new $helper_obj_name($args);
            $this->used_helpers[$helper_name] = $helper;
        } else {
            $helper = $this->used_helpers[$helper_name];
        }

        return $helper;
    }

}
