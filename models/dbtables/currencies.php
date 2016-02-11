<?php

namespace dbtable;

/**
 * @author Bhaumik Patel | bhaumik.patel@infostretch.com
 * currencies Database Table Class
 */
class currencies extends \DBTable {

    protected $table_name = "currencies";/**< usertypes DB Table is mapped with usertypes table. */
    protected $primary_keys = "currency_id";/**< usertype_id represents primary key of usertypes table. */

}