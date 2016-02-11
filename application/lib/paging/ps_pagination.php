<?php

class PS_Pagination {

    var $php_self;
    var $rows_per_page = 10; //Number of records to display per page
    var $total_rows = 0; //Total number of rows returned by the query
    var $links_per_page = 5; //Number of links to display per page
    var $append = ""; //Paremeters to append to pagination links
    var $sql = "";
    var $debug = false;
    var $conn = false;
    var $page = 1;
    var $max_pages = 0;
    var $offset = 0;
    var $connection_type = "mysql";
    var $params = array();

    /**
     * Constructor
     *
     * @param resource $connection Mysql connection link
     * @param string $sql SQL query to paginate. Example : SELECT * FROM users
     * @param integer $rows_per_page Number of records to display per page. Defaults to 10
     * @param integer $links_per_page Number of links to display per page. Defaults to 5
     * @param string $append Parameters to be appended to pagination links
     */
    function PS_Pagination($connection, $sql, $rows_per_page = 10, $links_per_page = 5, $append = "", $params = array()) {

        if (is_object($connection) and get_class($connection) == "mysqli") {
            $this->connection_type = "mysqli";
        } elseif (is_object($connection) and strtolower(get_class($connection)) == "pdo") {
            $this->connection_type = "pdo";
        }

        $this->conn = $connection;
        $this->sql = $sql;
        $this->rows_per_page = (int) $rows_per_page;
        if (intval($links_per_page) > 0) {
            $this->links_per_page = (int) $links_per_page;
        } else {
            $this->links_per_page = 5;
        }
        $this->append = $append;
        $this->params = $params;
        //$this->php_self = htmlspecialchars($_SERVER['PHP_SELF']);
        $this->php_self = preg_replace("/\?(.*)/", "", htmlspecialchars($_SERVER['REQUEST_URI']));
        if (isset($_GET['page'])) {
            $this->page = intval($_GET['page']);
        }
    }

    /**
     * Executes the SQL query and initializes internal variables
     *
     * @access public
     * @return resource
     */
    function paginate() {
        //Check for valid mysql connection
        if (($this->connection_type == "mysql") and (!$this->conn || !is_resource($this->conn))) {
            if ($this->debug)
                echo "MySQL connection missing<br />";
            return false;
        }elseif (($this->connection_type == "mysqli") and (!$this->conn)) {
            if ($this->debug)
                echo "MySQLi connection missing<br />";
            return false;
        }elseif (($this->connection_type == "pdo") and (!$this->conn)) {
            if ($this->debug)
                echo "PDO connection missing<br />";
            return false;
        }

        //Find total number of rows
        if ($this->connection_type == "mysqli") {
            $all_rs = $this->conn->query($this->sql);
            if (!$all_rs) {
                if ($this->debug)
                    echo "SQL query failed. Check your query.<br /><br />Error Returned: " . $this->conn->error;
                return false;
            }
            $this->total_rows = $all_rs->num_rows;
            $all_rs->close();
        }elseif ($this->connection_type == "pdo") {
            $all_stmt = $this->conn->prepare($this->sql);
            $all_stmt->execute($this->params);
            
            if (!$all_stmt) {
                if ($this->debug)
                    echo "SQL query failed. Check your query.<br /><br />Error Returned: " . $this->conn->error;
                return false;
            }
            $this->total_rows = $all_stmt->rowCount();
            $all_stmt->closeCursor();
        } else {
            $all_rs = @mysql_query($this->sql);
            if (!$all_rs) {
                if ($this->debug)
                    echo "SQL query failed. Check your query.<br /><br />Error Returned: " . mysql_error();
                return false;
            }
            $this->total_rows = mysql_num_rows($all_rs);
            @mysql_close($all_rs);
        }
        //Return FALSE if no rows found
        if ($this->total_rows == 0) {
            if ($this->debug)
                echo "Query returned zero rows.";
            return FALSE;
        }

        //Max number of pages
        $this->max_pages = ceil($this->total_rows / $this->rows_per_page);
        if ($this->links_per_page > $this->max_pages) {
            $this->links_per_page = $this->max_pages;
        }

        //Check the page value just in case someone is trying to input an aribitrary value
        if ($this->page > $this->max_pages || $this->page <= 0) {
            $this->page = 1;
        }

        //Calculate Offset
        $this->offset = $this->rows_per_page * ($this->page - 1);

        //Fetch the required result set
        if ($this->connection_type == "mysqli") {
            $rs = $this->conn->query($this->sql . " LIMIT {$this->offset}, {$this->rows_per_page}");
        } elseif ($this->connection_type == "pdo") {

            $stmt = $this->conn->prepare($this->sql . " LIMIT {$this->offset}, {$this->rows_per_page}");

            $stmt->execute($this->params);
            $rs = $stmt;
        } else {
            $rs = @mysql_query($this->sql . " LIMIT {$this->offset}, {$this->rows_per_page}");
        }

        if (!$rs) {
            if ($this->debug)
                echo "Pagination query failed. Check your query.<br /><br />Error Returned: " . $this->conn->error;
            return false;
        }
        return $rs;
    }

    /**
     * Display the link to the first page
     *
     * @access public
     * @param string $tag Text string to be displayed as the link. Defaults to 'First'
     * @return string
     */
    function renderFirst($tag = 'First') {
        if ($this->total_rows == 0)
            return FALSE;

        if ($this->page == 1) {
            return '<li class="unavailable"><a href="javascript:void(0);">' . $tag . '</a></li>';
        } else {
            return '<li><a href="' . $this->php_self . '?page=1&' . $this->append . '">' . $tag . '</a></li>';
        }
    }

    /**
     * Display the link to the last page
     *
     * @access public
     * @param string $tag Text string to be displayed as the link. Defaults to 'Last'
     * @return string
     */
    function renderLast($tag = 'Last') {
        if ($this->total_rows == 0)
            return FALSE;

        if ($this->page == $this->max_pages) {
            return '<li class="unavailable"><a href="javascript:void(0);">' . $tag . '</a></li>';
        } else {
            return '<li><a href="' . $this->php_self . '?page=' . $this->max_pages . '&' . $this->append . '">' . $tag . '</a></li>';
        }
    }

    /**
     * Display the next link
     *
     * @access public
     * @param string $tag Text string to be displayed as the link. Defaults to '>>'
     * @return string
     */
    function renderNext($tag = 'Next') {
        if ($this->total_rows == 0)
            return FALSE;

        if ($this->page < $this->max_pages) {
            return '<li><a href="' . $this->php_self . '?page=' . ($this->page + 1) . '&' . $this->append . '">' . $tag . '</a></li>';
        } else {
            return '<li class="unavailable"><a href="javascript:void(0);">' . $tag . '</a></li>';
        }
    }

    /**
     * Display the previous link
     *
     * @access public
     * @param string $tag Text string to be displayed as the link. Defaults to '<<'
     * @return string
     */
    function renderPrev($tag = 'Previous') {
        if ($this->total_rows == 0)
            return FALSE;

        if ($this->page > 1) {
            return '<li><a href="' . $this->php_self . '?page=' . ($this->page - 1) . '&' . $this->append . '">' . $tag . '</a></li>';
        } else {
            return '<li class="unavailable"><a href="javascript:void(0);">' . $tag . '</a>';
        }
    }

    /**
     * Display the page links
     *
     * @access public
     * @return string
     */
    function renderNav($prefix = '', $suffix = '') {
        if ($this->total_rows == 0)
            return FALSE;

        $batch = ceil($this->page / $this->links_per_page);
        $end = $batch * $this->links_per_page;
        if ($end == $this->page) {
            //$end = $end + $this->links_per_page - 1;
            //$end = $end + ceil($this->links_per_page/2);
        }
        if ($end > $this->max_pages) {
            $end = $this->max_pages;
        }
        $start = $end - $this->links_per_page + 1;
        $links = '';


        for ($i = $start; $i <= $end; $i++) {
            if ($i == $this->page) {
                $links .= $prefix . '<li class="active"><a href="javascript:void(0);">' . " $i " . '</a></li>' . $suffix;
            } else {
                $links .= $prefix . '<li><a href="' . $this->php_self . '?page=' . $i . '&' . $this->append . '">' . $i . '</a></li>' . $suffix;
            }
        }

        return $links;
    }

    /**
     * Display full pagination navigation
     *
     * @access public
     * @return string
     */
    function renderFullNav() {

        if ($this->max_pages > 1) {
            return '<ul class="pagination pagination-lg pull-right">'.$this->renderFirst(_l("FIRST", "common")) . $this->renderPrev(_l("PREVIOUS", "common")) . $this->renderNav() . $this->renderNext(_l("NEXT", "common")) . $this->renderLast(_l("LAST", "common")).'</ul>';
        }

        return "";
    }

    /**
     * Set debug mode
     *
     * @access public
     * @param bool $debug Set to TRUE to enable debug messages
     * @return void
     */
    function setDebug($debug) {
        $this->debug = $debug;
    }

}
