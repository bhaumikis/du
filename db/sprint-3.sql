INSERT INTO `static_routers` (`source`, `destination`) VALUES ('services/update-profile', 'services/users/update-profile');
-- 18 sep
ALTER TABLE `expense_categories`
	ADD COLUMN `is_default` TINYINT(4) NULL DEFAULT '0' AFTER `status`;

	
INSERT INTO `email_templates` (`title`, `name`, `format`, `htmltext`, `text`, `to_email`, `to_name`, `from_email`, `from_name`, `subject`, `variables`, `created_date`, `updated_date`) VALUES
('Admin Activation Mail', 'admin_activation_mail', 'html', '<p>\r\n	Hi {firstname} {lastname},</p>\r\n<p>\r\n	To activate your account please login with below details.</p>\r\n\r\n<p>\r\n	URL : {link}</p>\r\n<p>\r\n	Username : {username}</p>\r\n<p>\r\n	Password : {password}</p>\r\n<p>\r\n	Thanks,</p>\r\n<p>\r\n	DailyUse</p>\r\n', 'Hi {firstname} {lastname},\r\n\r\nTo activate your account please login with below details.\r\n\r\nURL : {link}\r\nUsername : {username}\r\nPassword : {password}\r\n\r\nThanks,\r\nDailyUse\r\n', '', '', 'support@dailyuse.com', 'DailyUse', 'DailyUse - Activation Mail', '{firstname}, {lastname}, {link}, {username}, {password}', '2014-09-18 00:00:00', '2014-09-18 00:00:00');


--
-- Table structure for table `user_admin_relations`
--

CREATE TABLE IF NOT EXISTS `user_admin_relations` (
  `user_id` int(11) DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL COMMENT 'this must be an admin id'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_area_mappings`
--

CREATE TABLE IF NOT EXISTS `user_area_mappings` (
  `user_area_mapping_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL COMMENT 'this will be admin ID',
  `country_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`user_area_mapping_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;	

INSERT INTO `configurations` (`title`, `parameter`, `type`, `value`, `comment`) VALUES ('Captcha Public Key', 'captcha_public_key', 'text', '6LcRBPoSAAAAAD9_xYXRio0RBCTRaXqragXkxfpn', 'Captcha public key');

INSERT INTO `configurations` (`title`, `parameter`, `type`, `value`, `comment`) VALUES ('Captcha Private Key', 'captcha_private_key', 'text', '6LcRBPoSAAAAAMBf0FMunJXCPOt6_0f_rNQyJUSY', 'captcha private key');

ALTER TABLE `users` CHANGE `country` `country_id` INT( 11 ) NULL DEFAULT NULL; 

ALTER TABLE `user_expenses`
	CHANGE COLUMN `expense_date` `expense_date` DATETIME NULL DEFAULT NULL AFTER `expense_vendor_id`;
**********************************************
INSERT INTO `dailyuse`.`static_routers` (`source`, `destination`) VALUES ('services/exchange-currency-rate', 'services/miscellaneous/exchangeCurrencyRate');

CREATE TABLE `exchange_rates` (
	`currency` CHAR(3) NOT NULL DEFAULT '',
	`rate` FLOAT(10,5) NOT NULL DEFAULT '0.00000',
	PRIMARY KEY (`currency`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB;


CREATE TABLE `tickets` (
	`ticket_id` INT NOT NULL AUTO_INCREMENT,
	`assigned_to` INT NOT NULL DEFAULT '0',
	`subject` VARCHAR(500) NOT NULL,
	`comment` TEXT NOT NULL DEFAULT '',
	`status` TINYINT NOT NULL COMMENT '0=pending, 1=in progress, 2 = hold, 3=resolved, 4=closed',
	`is_read` TINYINT NOT NULL,
	`created_by` INT NOT NULL,
	`created_date` DATETIME NOT NULL,
	PRIMARY KEY (`ticket_id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB;

INSERT INTO `static_routers` (`source`, `destination`) VALUES ('services/add-ticket', 'services/tickets/add-ticket');


INSERT INTO `static_routers` (`source`, `destination`) VALUES ('services/delete-expenses', 'services/user-expenses/delete-expenses');
INSERT INTO `static_routers` (`source`, `destination`) VALUES ('services/update-expenses', 'services/user-expenses/update-expenses');
INSERT INTO `static_routers` (`source`, `destination`) VALUES ('services/add-categories', 'services/categories/add-categories');
INSERT INTO `static_routers` (`source`, `destination`) VALUES ('services/add-expenses', 'services/user-expenses/add-expenses');
**********************************Executed*******************************
ALTER TABLE `expense_categories`
	CHANGE COLUMN `base_expense_type_id` `base_type_id` INT(3) NULL DEFAULT NULL AFTER `expense_category_id`;

INSERT INTO `dailyuse`.`static_routers` (`source`, `destination`) VALUES ('services/delete-expense-images', 'services/user-expenses/delete-expense-images');


--
-- Table structure for table `send_email_logs`
--

CREATE TABLE IF NOT EXISTS `send_email_logs` (
  `send_email_log_id` int(11) NOT NULL AUTO_INCREMENT,
  `email_template_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` text NOT NULL,
  `status` int(11) NOT NULL,
  `created_date` datetime NOT NULL,
  PRIMARY KEY (`send_email_log_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


ALTER TABLE `user_expenses`
	ADD COLUMN `payment_mode` TINYINT(4) NOT NULL DEFAULT '1' COMMENT '1=card,2=cash' AFTER `status`;

ALTER TABLE `tickets`
	ADD COLUMN `is_manual` TINYINT(4) NOT NULL COMMENT '0 = from app, 1 =created manually by admin' AFTER `is_read`;

ALTER TABLE `user_expenses` CHANGE `base_expense_type_id` `base_type_id` INT( 3 ) NULL DEFAULT '0';
ALTER TABLE `currencies`
	ADD COLUMN `iso_alpha` VARCHAR(50) NULL AFTER `flag`;

ALTER TABLE `configurations`	ENGINE=InnoDB;
ALTER TABLE `countries`
	ENGINE=InnoDB;
	ALTER TABLE `currencies`
	ENGINE=InnoDB;
ALTER TABLE `email_templates`
	ENGINE=InnoDB;
ALTER TABLE `languages`
	ENGINE=InnoDB;
ALTER TABLE `privileges`
	ENGINE=InnoDB;
	ALTER TABLE `static_routers`
	ENGINE=InnoDB;
ALTER TABLE `usertypes`
	ENGINE=InnoDB;


CREATE TABLE `user_trip_reference` (
	`user_trip_reference_id` INT(11) NOT NULL AUTO_INCREMENT,
	`user_trip_id` INT(11) NULL DEFAULT NULL,
	`trip_filename` VARCHAR(255) NULL DEFAULT NULL,
	`trip_file_path` VARCHAR(255) NULL DEFAULT NULL COMMENT 'physical path for reference',
	`trip_file_url` VARCHAR(255) NULL DEFAULT NULL,
	`trip_filetype` VARCHAR(10) NULL DEFAULT NULL,
	`LUID` INT(11) NULL DEFAULT NULL COMMENT 'Globally / Locally Unique Identifier',
	PRIMARY KEY (`user_trip_reference_id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB;

ALTER TABLE `email_templates` ADD `is_query_template` TINYINT NOT NULL AFTER `type`;

ALTER TABLE `tickets` ADD `query_template_id` INT NOT NULL AFTER `is_manual` ,
ADD `final_solution` TEXT NULL AFTER `query_template_id`;


ALTER TABLE `user_trips`
	ADD COLUMN `trip_currency` VARCHAR(50) NULL DEFAULT NULL AFTER `trip_destination`;
ALTER TABLE `user_trips`
	CHANGE COLUMN `trip_status` `trip_status` TINYINT(4) NULL DEFAULT NULL COMMENT '1-upcoming,2-outgoing,3-previous' AFTER `trip_budget`;
	
	
ALTER TABLE `tickets` ADD `is_reassigned` TINYINT NOT NULL AFTER `created_by`;

CREATE TABLE IF NOT EXISTS `ticket_assignments` (
  `ticket_assignment_id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_id` int(11) NOT NULL,
  `reassigned_to` int(11) NOT NULL,
  `date_from` date NOT NULL,
  `date_to` date NOT NULL,
  `assigned_date` datetime NOT NULL,
  PRIMARY KEY (`ticket_assignment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

INSERT INTO `dailyuse`.`static_routers` (`source`, `destination`) VALUES ('services/save-trip-image', 'services/user-trips/save-trip-image');
INSERT INTO `dailyuse`.`static_routers` (`source`, `destination`) VALUES ('services/save-trip-images', 'services/user-trips/save-trip-images');
ALTER TABLE `send_email_logs` ADD `user_id` INT NOT NULL AFTER `email_template_id` ;

CREATE TABLE `ticket_action_logs` (
	`ticket_action_log_id` INT(11) NOT NULL AUTO_INCREMENT,
	`ticket_id` INT(11) NOT NULL DEFAULT '0',
	`status` INT(11) NOT NULL DEFAULT '0',
	`description` TEXT NULL,
	`logged_by` INT(11) NOT NULL DEFAULT '0',
	`log_date` DATETIME NULL DEFAULT NULL,
	PRIMARY KEY (`ticket_action_log_id`)
)
ENGINE=InnoDB;

ALTER TABLE `users` ADD `assign_tickets` TINYINT NOT NULL AFTER `status`;

INSERT INTO `dailyuse`.`static_routers` (`source`, `destination`) VALUES ('services/delete-trip-images', 'services/user-trips/delete-trip-images');

ALTER TABLE `ticket_assignment_details` ADD `is_deleted` INT NOT NULL AFTER `assigned_by`;

ALTER TABLE `ticket_assignment_details` CHANGE `date_from` `date_from` DATE NOT NULL ,
CHANGE `date_to` `date_to` DATE NOT NULL ,
CHANGE `created_date` `created_date` DATETIME NOT NULL;

ALTER TABLE `ticket_assignment_details` ADD `updated_date` DATETIME NOT NULL;

ALTER TABLE `ticket_assignments` ADD `assigned_by` INT NOT NULL AFTER `date_to` ,
ADD `is_deleted` INT NOT NULL AFTER `assigned_by`;

ALTER TABLE `ticket_assignments` ADD `ticket_assignment_detail_id` INT NOT NULL AFTER `assigned_by`;

INSERT INTO `static_routers` (`source`, `destination`) VALUES ('services/delete-user-image', 'services/users/delete-user-image');