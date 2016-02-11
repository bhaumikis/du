<?php

namespace dbtable;

/**
 * @author Bhaumik Patel | bhaumik.patel@infostretch.com
 * emailTemplates Database Table Class
 */
 class emailTemplates extends \DBTable {

	protected $table_name = "email_templates";/**< email_templates DB Table is mapped with email_templates table. */
	protected $primary_keys = "email_template_id";/**< email_template_id represents primary key of email_templates. */

}