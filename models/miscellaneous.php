<?php

namespace model;

/**
 * @author Bhaumik Patel | bhaumik.patel@infostretch.com
 * brief miscellaneou Model contains application logic for various functions and database operations of miscellaneou Module.
 */
class miscellaneousModel extends globalModel {

    /**
     * This function is used to get the list of the hours for AM and PM
     * @return $rhours
     */
    function getHoursList() {
        $hours = range(0, 23);
        $rhours = array();
        foreach ($hours as $hour) {
            $value = $hour;
            $type = "AM";
            if ($hour >= 12) {
                $type = "PM";
            }
            if ($hour > 12) {
                $hour = $hour - 12;
            }
            $rhours[sprintf("%02d", $value)] = sprintf("%02d", $hour) . " " . $type;
        }

        return $rhours;
    }

    /**
     * This function is used to get the list of the minutes for 15 minutes interval
     * @return $rminutes
     */
    function getMinutesList() {
        $minutes = range(0, 59);
        $rminutes = array();
        for ($i = 0; $i <= 59; $i++) {
            $rminutes[sprintf("%02d", $i)] = sprintf("%02d", $i);
            $i = $i + 14;
        }
        return $rminutes;
    }

    /**
     * This function is used to log the service request into database
     * @param $output
     * @param $params
     * @return void
     */
    function logService($output, $params) {
	$e_end_time = microtime(true);
        $e_start_time = $GLOBALS["e_start_time"];
        // preg_match("/(\/services\/(.*))/i", $_SERVER["REQUEST_URI"], $matches);

        $data = array();
        $data["method"] = $_SERVER["REQUEST_URI"]; //$matches[count($matches) - 1];
        $data["posted_data"] = json_encode($params);
        $data["response"] = $output;
        
        if(strlen($data["posted_data"]) > 65000) unset($data["posted_data"]);
        if(strlen($data["response"]) > 65000) unset($data["response"]);
        if(isset($_SERVER["HTTP_USER_AGENT"])) {
            $data["user_agent"] = $_SERVER["HTTP_USER_AGENT"];
        }
        $data["time"] = ($e_end_time - $e_start_time);
        $data["request_header"] = json_encode(getallheaders()); //@serialize(getallheaders());
        $data["response_header"] = json_encode(apache_response_headers()); //@serialize(apache_response_headers());
        $data["remote_address"] = $_SERVER['REMOTE_ADDR'];
        $data["created_date"] = date("Y-m-d H:i:s");
        $data["cookies"] = json_encode($_COOKIE); //@serialize($_COOKIE);
		
		//p($data);
        $this->getDBTable("request-logs")->insert($data);
    }


    
    /**
     * getCountryList get the list of all countries.
     * @param number $isService
     * @return array
     */
    function getCountryList($isService = 1) {
        $countries = array();

        $countries = $this->getAllCountries();

        if ($isService == 1) {
            return array('countries' => $countries);
        } else {
            return $countries;
        }
    }

    /**
     * getCountryList get the list of all countries.
     * @return array
     */
    function getCurrenciesList() {
        $currencies = array();

        $currencies = $this->getAllCurrencies();

        return array('currencies' => $currencies);
    }

    /**
     * getCountryList get the list of all countries.
     * @return array
     */
    function getSecurityQuestionList() {
        $questions = array();

        $questions = $this->getDBTable("security-questions")->fetchAll(array("where" => "status = :status", "params" => array(":status" => '1')));

        return array('questions' => $questions);
    }

    /**
     * getDefaultData get all default data.
     * @return array
     */
    function getDefaultData() {
        $finalData = array();

        $finalData['base_expense_types'] = $this->getDBTable("base-expense-types")->fetchAll();

        $finalData['security_questions'] = $this->getDBTable("security-questions")->fetchAll(array("where" => "status = :status", "params" => array(":status" => '1')));

        $finalData['expense_categories'] = $this->getDefaultExpenseCategories();

        $finalData['countries'] = $this->getAllCountries();

        $finalData['currencies'] = $this->getAllCurrencies();

        return array('default_data' => $finalData);
    }

    /**
     * function to get all countries
     * @return string
     */
    function getAllCountries() {
        $arrCountryData = $this->getDBTable("countries")->fetchAll();
        foreach ($arrCountryData as $strKey => $arrCountryDetails) {
            if ($arrCountryDetails['flag'] != '') {
                $arrCountryData[$strKey]['flag'] = APPLICATION_URL . "/images/countries/" . $arrCountryDetails['flag'];
            }
        }
        return $arrCountryData;
    }

    /**
     * function to get all currency
     * @return string
     */
    function getAllCurrencies() {
        //$arrCurrencyData = $this->getDBTable("currencies")->fetchAll(array("where" => "currency_code != :currency_code", "params" => array(":currency_code" => '')));
        $arrCurrencyData = $this->database->queryData("SELECT t.* FROM `currencies` as t ORDER BY t.currency_code ASC");

        foreach ($arrCurrencyData as $strKey => $arrCurrencyDetails) {
            if ($arrCurrencyDetails['flag'] != '') {
                $arrCurrencyData[$strKey]['flag'] = APPLICATION_URL . "/images/countries/" . $arrCurrencyDetails['flag'];
            }
            if (empty($arrCurrencyDetails['currency_symbol'])) {
                $arrCurrencyData[$strKey]['currency_symbol'] = $arrCurrencyDetails["currency_code"];
            }
        }
        return $arrCurrencyData;
    }

    /**
     * function to get all default categories
     * @return type
     */
    function getDefaultExpenseCategories() {

        $arrData = $this->getDBTable("expense-categories")->fetchAll(array("where" => "status = :status AND is_default = :is_default", "params" => array(":status" => '1', ':is_default' => '1')));

        $this->typeCastFields(array("int" => array("base_type_id", "expense_category_id", "status", "is_default", "parent_expense_category_id", "user_id", "LUID")), $arrData, 2);

        return $arrData;
    }

    /**
     * getLanguages get the list of all languages.
     * @param number $isService
     * @return array
     */
    function getLanguages($isService = 1) {
        $languages = array();

        $languages = $this->getDBTable("languages")->fetchAll(array("where" => "status = :status", "params" => array(":status" => '1')));

        if ($isService == 1) {
            return array('languages' => $languages);
        } else {
            return $languages;
        }
    }

    /**
     * get dropdown of required formate
     * @param unknown $arrData
     * @param unknown $strKey
     * @param unknown $strVal
     * @return unknown
     */
    function getDropdownFormat($arrData, $strKey, $strVal) {

        foreach ($arrData as $arrDetails) {

            $arrResults[$arrDetails[$strKey]] = $arrDetails[$strVal];
        }

        return $arrResults;
    }

    /**
     * DU - save images
     * @param type $fullpath
     * @param type $imageDataEncoded
     * @return type
     */
    function saveImages($fullpath, $imageDataEncoded) {
        $imageData = base64_decode($imageDataEncoded);
        file_put_contents($fullpath, $imageData);
        return $fullpath;
    }

    /**
     * DU - save images
     * @param type $fullpath
     * @param type $imageDataEncoded
     * @return type
     */
    function saveAllImages($params) {
        $imageId = \generalFunctions::genRandomPass(8, false, true, false, true);
        $imageData = base64_decode($params['binary_content']);
        $imageName = $imageId . "." . $params['extension'];
        $fullpath = APPLICATION_PATH . "/images/media/" . $imageName;
        file_put_contents($fullpath, $imageData);
        return array("imageName" => $fullpath);
    }

    /**
     *  function to change money
     * @param type $params
     * @return type
     */
    function changeCurrency($params) {
        $arrResult = $this->getDBTable("exchange-rates")->fetchAll();

        foreach ($arrResult as $arrData) {
            $arrRates[$arrData['currency']] = $arrData['rate'];
        }
        $fromCurrency = $this->getCurrencyCodeById($params['from_currency']);
        $toCurrency = $this->getCurrencyCodeById($params['to_currency']);
        $converted_amount = (float) ($params['amount'] / $arrRates[$fromCurrency]) * $arrRates[$toCurrency];
        $converted_amount = round($converted_amount, 2);
        return array('converted_amount' => $converted_amount);
    }

    /**
     * function to get country name
     * @param type $intCountryId
     * @return type
     */
    function getCountryNameById($intCountryId) {
        $arrData = $this->getDBTable("countries")->fetchRowByFields(array("name"), array("where" => "country_id = :country_id", "params" => array(":country_id" => $intCountryId)));
        return $arrData['name'];
    }

    /**
     * function to get country id
     * @param type $strCountryName
     * @return type
     */
    function getCountryIdByName($strCountryName) {
        $arrData = $this->getDBTable("countries")->fetchRowByFields(array("country_id"), array("where" => "name = :name", "params" => array(":name" => $strCountryName)));
        return $arrData['country_id'];
    }

    /**
     * function to get currency id
     * @param type $strCountryCode
     * @return type
     */
    function getCurrencyIdByCode($strCountryCode) {
        $arrData = $this->getDBTable("currencies")->fetchRowByFields(array("currency_id"), array("where" => "currency_code = :currency_code", "params" => array(":currency_code" => $strCountryCode)));
        return $arrData['currency_id'];
    }

    /**
     * function to get currency id
     * @param type $strCountryCode
     * @return type
     */
    function getCurrencyCodeById($strCurrencyId) {
        $arrData = $this->getDBTable("currencies")->fetchRowByFields(array("currency_code"), array("where" => "currency_id = :currency_id", "params" => array(":currency_id" => $strCurrencyId)));
        return $arrData['currency_code'];
    }

    /**
     * function to get currency symbol
     * @param type $strCurrencyCode
     * @return type
     */
    function getCurrencySymbolByCode($strCurrencyCode) {
        $arrData = $this->getDBTable("currencies")->fetchRowByFields(array("currency_symbol"), array("where" => "currency_code = :currency_code", "params" => array(":currency_code" => $strCurrencyCode)));
        return $arrData['currency_symbol'];
    }

    /**
     * function to get currency symbol
     * @param type $strCurrencyCode
     * @return type
     */
    function getCurrencySymbolById($strCurrencyId) {
        $arrData = $this->getDBTable("currencies")->fetchRowByFields(array("currency_symbol"), array("where" => "currency_id = :currency_id", "params" => array(":currency_id" => $strCurrencyId)));
        return $arrData['currency_symbol'];
    }

    /**
     * DU - This function is used to get country list.
     * @return array
     */
    function getCountries() {
        $data = $this->getDBTable("countries")->fetchAll();
        $dataArr = array();

        if (!empty($data)) {
            $dataArr = $data;
        }
        return $dataArr;
    }

    /**
     * function to get all images
     * @param type $params
     * @return type
     */
    function getAllImages($params) {

        $finalData = array();

        $finalData['expense_images'] = $this->getModel("user-expenses")->getAllUserExpensesImages($params['user_id'], $params['timestamp']);

        $finalData['trip_images'] = $this->getModel("user-trips")->getAllUserTripImages($params['user_id'], $params['timestamp']);

        $finalData['user_image'] = $this->getModel("users")->getProfileImage($params['user_id']);

        return array(true, $finalData);
    }

    /**
     * function to get all delete images
     * @param type $user_id
     * @param type $timestamp
     * @return type
     */
    function getDeletedImages($user_id, $timestamp) {
        $finalData = array();

        $finalData['expense_images'] = $this->getModel("user-expenses")->getDeletedUserExpensesImages($user_id, $timestamp);

        $finalData['trip_images'] = $this->getModel("user-trips")->getDeletedUserTripImages($user_id, $timestamp);

        //$finalData['user_image'] = $this->getModel("users")->getProfileImage($params['user_id']);

        return $finalData;
    }

    /**
     * function to update servcer ids
     * @param type $params
     * @return type
     */
    function updateServerIDs($params) {
        $arrLUID = array();
        switch ($params['table_name']) {
            case 'user_expenses':
                foreach ($params['request_data'] as $arrData) {
                    $this->getDBTable("user-expenses")->update(array("LUID" => $arrData['LUID']), array("where" => "user_expense_id = :user_expense_id", "params" => array(":user_expense_id" => $arrData['server_id'])));
                    $arrLUID[]['LUID'] = $arrData['LUID'];
                }
                break;
            case 'user_trips':
                foreach ($params['request_data'] as $arrData) {
                    $this->getDBTable("user-trips")->update(array("LUID" => $arrData['LUID']), array("where" => "user_trip_id = :user_trip_id", "params" => array(":user_trip_id" => $arrData['server_id'])));
                    $arrLUID[]['LUID'] = $arrData['LUID'];
                }
                break;
            case 'expense_vendors':
                foreach ($params['request_data'] as $arrData) {
                    $this->getDBTable("expense-vendors")->update(array("LUID" => $arrData['LUID']), array("where" => "expense_vendor_id = :expense_vendor_id", "params" => array(":expense_vendor_id" => $arrData['server_id'])));
                    $arrLUID[]['LUID'] = $arrData['LUID'];
                }
                break;
            case 'expense_categories':
                foreach ($params['request_data'] as $arrData) {
                    $this->getDBTable("expense-categories")->update(array("LUID" => $arrData['LUID']), array("where" => "expense_category_id = :expense_category_id", "params" => array(":expense_category_id" => $arrData['server_id'])));
                    $arrLUID[]['LUID'] = $arrData['LUID'];
                }
                break;
            case 'user_expense_reference':
                foreach ($params['request_data'] as $arrData) {
                    $this->getDBTable("user-expenses-reference")->update(array("LUID" => $arrData['LUID']), array("where" => "user_expense_reference_id = :user_expense_reference_id", "params" => array(":user_expense_reference_id" => $arrData['server_id'])));
                    $arrLUID[]['LUID'] = $arrData['LUID'];
                }
                break;
            case 'user_trip_reference':
                foreach ($params['request_data'] as $arrData) {
                    $this->getDBTable("user-trip-reference")->update(array("LUID" => $arrData['LUID']), array("where" => "user_trip_reference_id = :user_trip_reference_id", "params" => array(":user_trip_reference_id" => $arrData['server_id'])));
                    $arrLUID[]['LUID'] = $arrData['LUID'];
                }
                break;
        }
        return array(true, $arrLUID);
    }

    /**
     * function to check currenct rate are available or not
     * @param type $currencyCode
     * @return type available or not
     */
    function checkExchangeRateAvailability($currencyCode) {
        $arrData = $this->getDBTable("exchange-rates")->fetchRowByFields(array("currency"), array("where" => "currency = :currency", "params" => array(":currency" => strtoupper($currencyCode))));
        return $arrData;
    }

    /**
     * function to check currenct rate are available or not
     * @param type $currencyCode
     * @return type available or not
     */
    function checkExchangeRateAvailabilityById($currencyId) {
        $currencyCode = $this->getCurrencyCodeById($currencyId);
        $arrData = $this->getDBTable("exchange-rates")->fetchRowByFields(array("currency"), array("where" => "currency = :currency", "params" => array(":currency" => strtoupper($currencyCode))));
        return $arrData;
    }

    /**
     * Resize an image and keep the proportions
     * @param string $src Sourc Path
     * @param string $dst Destination path
     * @param integer $dstx Width
     * @param integer $dsty Height
     * @return image
     */
    function saveUserImage($src, $dst, $dstx, $dsty) {
        $allowedExtensions = 'jpg jpeg gif png';

        $name = explode(".", $src);
        $currentExtensions = $name[count($name) - 1];
        $extensions = explode(" ", $allowedExtensions);

        for ($i = 0; count($extensions) > $i; $i = $i + 1) {
            if ($extensions[$i] == $currentExtensions) {
                $extensionOK = 1;
                $fileExtension = $extensions[$i];
                break;
            }
        }

        if ($extensionOK) {

            $size = getImageSize($src);
            $width = $size[0];
            $height = $size[1];

            if ($width >= $dstx AND $height >= $dsty) {

                $proportion_X = $width / $dstx;
                $proportion_Y = $height / $dsty;

                if ($proportion_X > $proportion_Y) {
                    $proportion = $proportion_Y;
                } else {
                    $proportion = $proportion_X;
                }
                $target['width'] = $dstx * $proportion;
                $target['height'] = $dsty * $proportion;

                $original['diagonal_center'] = round(sqrt(($width * $width) + ($height * $height)) / 2);
                $target['diagonal_center'] = round(sqrt(($target['width'] * $target['width']) +
                                ($target['height'] * $target['height'])) / 2);

                $crop = round($original['diagonal_center'] - $target['diagonal_center']);

                if ($proportion_X < $proportion_Y) {
                    $target['x'] = 0;
                    $target['y'] = round((($height / 2) * $crop) / $target['diagonal_center']);
                } else {
                    $target['x'] = round((($width / 2) * $crop) / $target['diagonal_center']);
                    $target['y'] = 0;
                }

                if ($fileExtension == "jpg" OR $fileExtension == 'jpeg') {
                    $from = ImageCreateFromJpeg($src);
                } elseif ($fileExtension == "gif") {
                    $from = ImageCreateFromGIF($src);
                } elseif ($fileExtension == 'png') {
                    $from = imageCreateFromPNG($src);
                }

                $new = ImageCreateTrueColor($dstx, $dsty);

                imagecopyresampled($new, $from, 0, 0, $target['x'], $target['y'], $dstx, $dsty, $target['width'], $target['height']);

                if ($fileExtension == "jpg" OR $fileExtension == 'jpeg') {
                    imagejpeg($new, $dst, 70);
                } elseif ($fileExtension == "gif") {
                    imagegif($new, $dst);
                } elseif ($fileExtension == 'png') {
                    imagepng($new, $dst);
                }
                return true;
            }
        }
        return false;
    }

}
