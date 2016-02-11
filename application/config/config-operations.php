<?php

/**
 * @author Bhaumik Patel | bhaumik.patel@infostretch.com
 * configurations Class
 * This class loads application configuration like database and router.
 */
class configurations {

    public static $database = null;
    public static $USER_IMAGE_WIDTH  = 130;
    public static $USER_IMAGE_HEIGHT = 130;
    

    /**
     * Load database table class 
     */
    public static function configure() {
        self::loadDBTable();
    }

    /**
     *  Include db_table class file
     */
    static function loadDBTable() {
        include_once(APPLICATION_PATH . "/application/db/db_table.php");
    }

    /**
     * Get Database Object
     * @return MyDB
     */
    public static function getDBObject() {

        if (self::$database == null) {
            include(APPLICATION_PATH . "/application/db/" . DB_DRIVER . ".php");
            $database_credentials = array("host" => DB_HOST, "username" => DB_USERNAME, "password" => DB_PASSWORD, "database" => DB_NAME, "pconnect" => DB_PCONNECT);
            self::$database = new MyDB($database_credentials);
        }

        return self::$database;
    }

    /**
     * This function route the url from the database.
     * @param string $request_uri
     * @return string
     */
    public static function routers($request_uri = "") {
        // Router for i/*
        if (preg_match("/^i\/(.*)$/i", $request_uri, $matches)) {
            $request_uri = "local/home/i/" . $matches[1];
        } elseif ($static_router_url = self::getDBObject()->selectFieldOne(array("destination"), "static_routers", "`source` = '" . $request_uri . "'")) {
            $request_uri = $static_router_url["destination"];
        }

        return $request_uri;
    }
}