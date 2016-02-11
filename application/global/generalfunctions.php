<?php

define("REGEXP_LATLONG", "/^(\-?)[0-9]+(\.[0-9]+)?$/");
define("REGEXP_FLOAT", "/^(\-)?[0-9]+(\.[0-9]+)?$/");
define("REGEXP_INT", "/^(\-)?[0-9]+$/");
define("REGEXP_INT_NULL", "/^\s*\d*\s*$/");
define("REGEXP_INT_POSITIVE", "/^[0-9]+$/");
define("REGEXP_IMAGETYPE", "/\.(jpg|jpeg|png|gif|bmp)/i");
define("REGEXP_AUDIOTYPES", "/\.(mp3)/i");
define("REGEXP_VIDEOTYPES", "/\.(mp4|wmv)/i");
define("CURRENCY_CODE", "USD");
define("CURRENCY_SYMBOL", "$");
define("REGEXP_AUDIOVIDEOTYPES", "/\.(mp3|mp4|wmv)/i");
define("REGEXP_POSTCODE", "/^[0-9a-z ]+$/i");
define("REGEXP_POSTCODE_AUS", "/^[0-9]{4}$/i");
define("REGEXP_PHONE_AUS", "/^\d{2}\s?\d{4}\s?\d{4}$/");
define("REGEXP_PHONE", "/^\[0-9]+(\.[0-9][0-9]?)?$/");
define("REGEXP_MOBILE", "/^(0\d{10,12}|[1-9]\d{9,11})$/");
define("REGEXP_URL", "/^(http(s?):\/\/|ftp:\/\/{1})((\w+\.){1,})\w{2,}$/i");
define("REGEXP_EMAIL", "/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/");
define("REGEXP_DIR_NAME", "/^[a-zAz0-9_]+$/i");
define("REGEXP_SPECIAL_CHARS", "/[\!\@\#\$\%\^\&\*\(\)\_\+\{\}\|\:\"\<\>\?\[\]\\\;\'\,\.\/\/\*\-\+\~\`\-\=]/");
define("REGEXP_DEVICE_TOKEN", "/^(([a-f0-9]){8}\s?){7}([a-f0-9]){8}$/i");
define("DATETIME_FORMAT", "m/d/Y H:i:s");
define("TIME_FORMAT", "H:i:s");
define("TIMEA_FORMAT", "h:i:s A");
define("DEFAULT_TRANSLATION_LANGUAGE", "fr_FR");
define("DEFAULT_LANGUAGE", generalFunctions::getUserConfValue("language"));

/**
 * @author Bhaumik Patel | bhaumik.patel@infostretch.com
 * General Functions of the application
 */
class generalFunctions {

    /**
     * Redirect To Location
     * @param string $location
     */
    public static function redirectToLocation($location = "index.php") {
        header("Location:" . $location);
        exit;
    }

    /**
     * Get Seo (User Friendly) Url
     * @param string $options
     * @param string $action
     * @param string $module
     * @param unknown $params
     * @return string
     */
    public static function getSEOUrl($options = "index", $action = "index", $module = "", $params = array()) {
        $url = "";

        if (isset($options) and !empty($options)) {

            $url = $options;

            if (isset($action) and !empty($action)) {
                $url .= "/" . $action;
            }

            foreach ($params as $k => $v) {
                $url .= "/" . $k . "/" . $v;
            }
        }

        if (isset($module) and !empty($module)) {
            $url = $module . "/" . $url;
        }

        return APPLICATION_URL . "/" . $url;
    }

    /**
     * Convert to Action name
     * @param string $keyword
     * @return Ambigous <string, unknown>
     */
    public static function convertToActionName($keyword = "") {
        $keyword = str_replace("_", "-", $keyword);
        $keywords = explode("-", $keyword);
        $keyword = "";
        for ($i = 0; $i < count($keywords); $i++) {
            if ($i == 0) {
                $keyword .= $keywords[$i];
            } else {
                $keyword .= ucfirst($keywords[$i]);
            }
        }

        return $keyword;
    }

    /**
     * Revert to Action name
     * @param string $keyword
     * @return mixed
     */
    public static function revertToActionName($keyword = "") {
        $keyword = preg_replace_callback("/[A-Z]/", create_function(
                        '$matches', 'return "-".strtolower($matches[0]);'
                ), $keyword);

        return $keyword;
    }

    /**
     * Log Error Messages
     * @param string $message
     */
    function logError($message = "") {
        $file = APPLICATION_PATH . "/application/logs/error.log";
        $fp = fopen($file, "a+");
        fwrite($fp, $message . "\r\n");
    }

    /**
     * Error 404 handler 
     */
    public static function error404() {
        header('HTTP/1.0 404 Not Found');
        echo "<h1>404 Not Found</h1>";
        echo "The page that you have requested could not be found.";
        exit;
    }

    /**
     * Render Messages
     * @param string $type
     */
    public static function showMessages($type = "error_message") {
        global $session_prefix;
        if ($_SESSION[$session_prefix][$type]) {
            if (is_array($_SESSION[$session_prefix][$type])) {
                echo implode("<br>", $_SESSION[$session_prefix][$type]);
            } else {
                echo $_SESSION[$session_prefix][$type];
            }
        }
        unset($_SESSION[$session_prefix][$type]);
    }

    /**
     * Count No. of messages
     * @param string $type
     * @return number
     */
    public static function countMessages($type = "error_message") {
        global $session_prefix;
        if ($_SESSION[$session_prefix][$type]) {
            if (is_array($_SESSION[$session_prefix][$type])) {
                return count($_SESSION[$session_prefix][$type]);
            } else {
                return 1;
            }
        }
        return 0;
    }

    /**
     * Check Access of the request
     * @return boolean
     */
    public static function checkAccess() {
        global $session_prefix;

        if (isset($_REQUEST["option"]) and !in_array($_REQUEST["option"], $_SESSION[$session_prefix]["user"]["modules"])) {
            $_SESSION[$session_prefix]["error_message"] = "Invalid Request or Access denied.";
            generalFunctions::redirectToLocation(ADMIN_APPLICATION_URL . "/index.php?option=message");
        }

        return true;
    }

    /**
     * Get Config Value from the database - configuratin table.
     * @param string $parameter
     * @return Ambigous <>|boolean
     */
    public static function getConfValue($parameter = "") {

        $param = configurations::getDBObject()->selectOne("configurations", "parameter = '" . $parameter . "'");

        if ($param) {
            return $param["value"];
        }
        return false;
    }

    /**
     * Get User configuration value.
     * @param string $parameter
     * @return Ambigous <>|boolean
     */
    public static function getUserConfValue($parameter = "") {
        global $session_prefix;

        $SQL = "SELECT
                    if(usv.value IS NOT NULL,usv.value,us.value) as value
                    FROM user_settings us
                    LEFT JOIN user_setting_value usv ON usv.user_setting_id=us.user_setting_id and usv.user_id=:user_id
                    where parameter = :parameter";
        $param = configurations::getDBObject()->queryOne($SQL, array(":user_id" => $_SESSION['default']["user"]["user_id"], ":parameter" => $parameter));

        if ($param) {
            return $param["value"];
        }
        return false;
    }

    /**
     * Html to Special character
     * @param unknown $data
     * @param unknown $except
     * @return unknown|string
     */
    public static function htmlsplchs($data = array(), $except = array()) {
        if (!is_array($data)) {
            return $data;
        }
        foreach ($data as $k => $v) {
            if (!is_array($v) and !is_object($v)) {
                if (!in_array($k, $except)) {
                    $data[$k] = htmlspecialchars($v);
                }
            }
        }
        return $data;
    }

    /**
     * @param string $val
     * @return string
     */
    public static function xmlvalhtmlsplchs($val = "") {
        return htmlspecialchars($val, ENT_NOQUOTES);
    }

    /**
     * @param unknown $email
     * @return number
     */
    public static function isValidEmail($email) {
        return preg_match(REGEXP_EMAIL, $email);
    }

    /**
     * @param unknown $directory_name
     * @return number
     */
    public static function isValidDirectoryName($directory_name) {
        return preg_match(REGEXP_DIR_NAME, $directory_name);
    }

    /**
     * @param unknown $phone
     * @return number
     */
    public static function isValidPhone($phone) {
        return preg_match(REGEXP_PHONE, $phone);
    }

    /**
     * @param unknown $phone
     * @return number
     */
    public static function isValidMobile($phone) {
        return preg_match(REGEXP_MOBILE, $phone);
    }

    /**
     * @param string $value
     * @return number
     */
    public static function checkLatitudeLongitude($value = "") {
        return preg_match(REGEXP_LATLONG, $value);
    }

    /**
     * @param string $value
     * @return number
     */
    public static function checkFloat($value = "") {
        return preg_match(REGEXP_FLOAT, $value);
    }

    /**
     * @param string $value
     * @return number
     */
    public static function checkInt($value = "") {
        return preg_match(REGEXP_INT, $value);
    }

    /**
     * @param string $value
     * @return number
     */
    public static function checkIntNull($value = "") {
        return preg_match(REGEXP_INT_NULL, $value);
    }

    /**
     * @param string $value
     * @return number
     */
    public static function checkIntPositive($value = "") {
        return preg_match(REGEXP_INT_POSITIVE, $value);
    }

    /**
     * @param unknown $url
     * @return number
     */
    public static function checkURL($url) {
        return preg_match(REGEXP_URL, $url);
    }

    /**
     * @param string $value
     * @return number
     */
    public static function valueSet($value = "") {
        return strlen(trim($value));
    }

    /**
     * @param string $param
     * @return boolean
     */
    public static function ParamSet($param = "") {
        return isset($_POST[$param]) and strlen(trim($_POST[$param]));
    }

    /**
     * @param string $value
     * @return mixed
     */
    public static function replace_all_special_chars($value = "") {
        return preg_replace(REGEXP_SPECIAL_CHARS, "", $value);
    }

    /**
     * @param string $ccnum
     * @param string $type
     * @return boolean
     */
    public static function chkvalidCCcard($ccnum = "", $type = "") {

        $creditcard = array("visa" => "/^4\d{3}-?\d{4}-?\d{4}-?\d{4}$/",
            "mastercard" => "/^5[1-5]\d{2}-?\d{4}-?\d{4}-?\d{4}$/",
            "american express" => "/^3[4,7]\d{13}$/",
            "discover" => "/^6011-?\d{4}-?\d{4}-?\d{4}$/",
            "jcb" => "/^[3088|3096|3112|3158|3337|3528]\d{12}$/",
            "diners" => "/^3[0,6,8]\d{12}$/",
            "bankcard" => "/^5610-?\d{4}-?\d{4}-?\d{4}$/",
            "enroute" => "/^[2014|2149]\d{11}$/",
            "switch" => "/^[4903|4911|4936|5641|6333|6759|6334|6767]\d{12}$/");

        if (preg_match($creditcard[strtolower($type)], $ccnum)) {
            return true;
        }
        return false;
    }

    /**
     * Url Decode 
     */
    public static function urldecode_() {
        foreach ($_GET as $k => $v) {
            if (!is_array($v)) {
                $_GET[$k] = @urldecode($v);
            }
            if ($_REQUEST[$k]) {
                if (!is_array($v)) {
                    $_REQUEST[$k] = @urldecode($v);
                }
            }
        }
    }

    /**
     * Month Dropdown box
     * @param string $name
     * @param string $selected
     * @return string
     */
    public static function monthDropdown($name = "month", $selected = null) {
        $dd = '<select name="' . $name . '" id="' . $name . '">';

        // current month
        //$selected = is_null($selected) ? date('n', time()) : $selected;
        //$dd .= '<option value="">Month</option>';
        for ($i = 1; $i <= 12; $i++) {
            $dd .= '<option value="' . $i . '"';
            if ($i == $selected) {
                $dd .= ' selected';
            }
            /*             * * get the month ** */
            $mon = date("F", mktime(0, 0, 0, $i + 1, 0, 0, 0));
            $dd .= '>' . $mon . '</option>';
        }
        $dd .= '</select>';
        return $dd;
    }

    /* Maulik 22nd Feb,2012- htmltopdfConversion ($htmlpath,$module)
      Here $htmlpath- is the path where html is located
      $module - is like order module or report module etc.
     */

    /**
     * Html to pdf conversion
     * @param string $htmlpath
     * @param string $module
     * @param string $orderid
     * @return boolean
     */
    public static function htmltopdfConversion($htmlpath = "", $module = "", $orderid = "") {
        switch (strtolower($module)) {
            case 'order':
                $dirStructure = dirname(__FILE__) . DIRECTORY_SEPARATOR . "ordrpdf";
                if ($orderid != "") {
                    $ordrPdfPath = $dirStructure . DIRECTORY_SEPARATOR . $orderid . ".pdf";

                    $orderPdf = exec(PDFGenerationPath . ' "' . $htmlpath . '/oid/' . $orderid . '" "' . $ordrPdfPath . '"');
                    $retmode = chmod($ordrPdfPath, 0775);

                    if (!file_exists($ordrPdfPath))
                        return false;
                    else
                        return true;
                }
        }
    }

   /**
    * File download
    * @param unknown $file_name
    */
   public static function downloadFile($file_name) {
        $fullPath = APPLICATION_URL . "/upload/tmpfiles/" . $file_name;


        if (file_exists($fullPath)) {
            if ($fd = fopen($fullPath, "r")) {
                $fsize = filesize($fullPath);
                $path_parts = pathinfo($fullPath);
                $ext = strtolower($path_parts["extension"]);
                header("Content-type: application/octet-stream");
                header("Content-Disposition: filename=\"" . $path_parts["basename"] . "\"");
                //var_dump($path_parts["basename"]);
                // header("Content-length: $fsize");
                //header("Cache-control: private"); //use this to open files directly
                while (!feof($fd)) {
                    $buffer = fread($fd, 2048);
                    echo $buffer;
                }
            }
            fclose($fd);
        }
    }

    /**
     * Get Contents from the content table.
     * @param unknown $name
     * @return Ambigous <multitype:multitype: , multitype:, multitype:unknown >|boolean
     */
    public static function getContent($name) {

        $result = configurations::getDBObject()->selectField(array("`title`", "`fulltext`"), "contents", "`name` = '" . $name . "'");

        if ($result) {
            return $result;
        }
        return false;
    }

    /**
     * Get States
     * @return Ambigous <multitype:multitype: , multitype:, multitype:unknown >|boolean
     */
    public static function getStates() {

        $result = configurations::getDBObject()->selectField(array("`id`", "`name`", "`abbrev`"), "states");

        if ($result) {
            return $result;
        }
        return false;
    }

    /**
     * Parse CSV file to array
     * @param unknown $csvfile
     * @return multitype:multitype: 
     */
    public static function parseCsvFile($csvfile) {
        $arrResult = array();
        if (file_exists($csvfile)) {
            $handle = fopen($csvfile, "r");
            if ($handle) {
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $arrResult[] = $data;
                }
                fclose($handle);
            }
            return $arrResult;
        }
    }

    /**
     * Encrypt / Decrypt string
     * @param unknown $action
     * @param unknown $string
     * @return Ambigous <boolean, string>
     */
    public static function encrypt_decrypt($action, $string) {
        $output = false;

        $key = PROJECT_TITLE;

        // initialization vector
        $iv = md5(md5($key));

        if ($action == 'encrypt') {
            $output = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, $iv);
            $output = base64_encode($output);
        } else if ($action == 'decrypt') {
            $output = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($string), MCRYPT_MODE_CBC, $iv);
            $output = rtrim($output, "");
        }
        return $output;
    }

    /**
     * Remove directory
     * @param unknown $dir
     */
    public static function removeDirectory($dir) {

        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . "/" . $object) == "dir") {
                        self::removeDirectory($dir . "/" . $object);
                    } else {
                        @unlink($dir . "/" . $object);
                    }
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }

    /**
     * Get random password string
     * @param number $pw_length
     * @param string $use_caps
     * @param string $use_numeric
     * @param string $use_specials
     * @param string $use_small
     * @return string
     */
    public static function genRandomPass($pw_length = 8, $use_caps = true, $use_numeric = true, $use_specials = true, $use_small = true) {
        $chars = array();
        $pw = array();
        $caps = array();
        $numbers = array();
        $all = array();
        $num_specials = $pw_length;
        $reg_length = $pw_length;
        $pws = array();

        if ($use_numeric) {
            for ($nu = 48; $nu <= 57; $nu++) {
                $numbers[] = $nu; // create 0-9
            }
        }
        if ($use_caps) {
            for ($ca = 65; $ca <= 90; $ca++) {
                $caps[] = $ca; // create A-Z
            }
        }
        if ($use_small) {
            for ($ch = 97; $ch <= 122; $ch++) {
                $chars[] = $ch; // create a-z
            }
        }
        $all = array_merge($numbers, $caps, $chars);

        if ($use_specials) {
            if ($use_small == true || $use_caps == true || $use_numeric == true) {
                $reg_length = ceil($pw_length * 0.75);
                $num_specials = $pw_length - $reg_length;
                if ($num_specials > 5)
                    $num_specials = 5;
            }
            for ($si = 33; $si <= 47; $si++) {
                $signs[] = $si;
            }
            $rs_keys = array_rand($signs, $num_specials);
            foreach ($rs_keys as $rs) {
                $pws[] = chr($signs[$rs]);
            }
            if ($use_numeric) {
                array_pop($pws);
                $random_no = array_rand($numbers, 1);
                array_push($pws, $random_no);
            }
        }

        if (sizeof($all) > 0) {
            $rand_keys = array_rand($all, $reg_length);
            foreach ($rand_keys as $rand) {
                $pw[] = chr($all[$rand]);
            }
        }
        $compl = array_merge($pw, $pws);
        shuffle($compl);
        return implode('', $compl);
    }

    /**
     * Get Default Date Format
     * @return string
     */
    public static function getDefaultDateFormat() {
        $strFormat = self::getUserConfValue("date_format");
        switch ($strFormat) {
            case 'MM/DD/YY':
                $strPHPFormat = "m/d/y";
                break;
            case 'DD/MM/YY':
                $strPHPFormat = "d/m/y";
                break;
            default:
                $strPHPFormat = "Y/m/d";
                break;
        }
        return $strPHPFormat;
    }

    /**
     * Get page data using curl
     * @param unknown $strURL
     * @return mixed
     */
    public static function getPageDataUsingCurl($strURL) {
        // create curl resource
        $ch = curl_init();
        // set url
        curl_setopt($ch, CURLOPT_URL, $strURL);
        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // $output contains the output string
        $output = curl_exec($ch);
        // close curl resource to free up system resources
        curl_close($ch);
        return $output;
    }
    
    /**
     * Encrypt the url
     * @param unknown $pure_string
     * @param string $salt
     * @return mixed
     */
    public static function encryptURL($pure_string, $salt = 'salt') {
        $salt = PROJECT_TITLE;
        $dirty = array("+", "/", "=");
        $clean = array("_PL_", "_SH_", "_EQ_");
        $encrypted_string = mcrypt_encrypt(MCRYPT_BLOWFISH, $salt, utf8_encode($pure_string), MCRYPT_MODE_ECB, $salt);
        $encrypted_string = base64_encode($encrypted_string);
        return str_replace($dirty, $clean, $encrypted_string);
    }

    /**
     * Decrypt Url
     * @param unknown $encrypted_string
     * @param string $salt
     * @return string
     */
    public static function decryptURL($encrypted_string, $salt = 'salt') {
        $salt = PROJECT_TITLE;
        $dirty = array("+", "/", "=");
        $clean = array("_PL_", "_SH_", "_EQ_");
        $string = base64_decode(str_replace($clean, $dirty, $encrypted_string));
        $decrypted_string = mcrypt_decrypt(MCRYPT_BLOWFISH, $salt, $string, MCRYPT_MODE_ECB, $salt);
        return $decrypted_string;
    }

}