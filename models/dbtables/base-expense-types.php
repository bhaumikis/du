<?php

namespace dbtable;

/**
 * @author Bhaumik Patel | bhaumik.patel@infostretch.com
 * baseExpenseTypes Database Table Class
*/
class baseExpenseTypes extends \DBTable {

    /**
     * @var table name
     */
    protected $table_name = "base_expense_types";
    
    /**
     * @var Primary Key
     */
    protected $primary_keys = "base_expense_type_id";

}