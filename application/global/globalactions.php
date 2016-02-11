<?php

/**
 * Strip slashes
 * @param unknown $value
 * @return multitype:
 */
function stripslashes_deep($value) {
    $value = is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
    return $value;
}

if (get_magic_quotes_gpc()) {
    $_POST = array_map('stripslashes_deep', $_POST);
    $_GET = array_map('stripslashes_deep', $_GET);
    $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
    $_REQUEST = array_map('stripslashes_deep', $_REQUEST);
}

/**
 * Translate the message from the source language to destination language.
 * @param unknown $param
 * @param unknown $section
 * @return unknown|Ambigous <>
 */
function _l($param, $section) {

    static $values;

    $language = DEFAULT_LANGUAGE;

    if (isset($values[$section]) and !empty($values[$section])) {

        if (isset($values[$section][$param]) and !empty($values[$section][$param])) {
            return $values[$section][$param];
        } else {
            return $param;
        }
    } else {

        $path = APPLICATION_PATH . "/languages/" . $language . "/" . $section . ".ini";
        $sectionvalues = parse_ini_file($path, false);

        $values[$section] = $sectionvalues;

        if (isset($sectionvalues[$param]) and !empty($sectionvalues[$param])) {
            return $sectionvalues[$param];
        } else {
            return $param;
        }
    }

    return $param;
}

require_once 'localefunctions.php';