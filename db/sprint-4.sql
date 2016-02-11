INSERT INTO `static_routers` (`destination`,`source`) VALUES ('services/vendors/get-user-vendor','services/get-user-vendor');

CREATE TABLE `deleted_items` (
	`deleted_item_id` INT(11) NOT NULL AUTO_INCREMENT,
	`table` VARCHAR(255) NOT NULL,
	`reference_id` INT(11) NOT NULL,
	`reference_key_id` INT(11) NOT NULL,
	`extra_reference_keys` VARCHAR(1000) NOT NULL,
	`deleted_on` DATETIME NOT NULL,
	PRIMARY KEY (`deleted_item_id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB;
=============================================

CREATE TABLE IF NOT EXISTS `archive_user_expenses` (
  `user_expense_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `card_id` int(11) DEFAULT NULL,
  `user_trip_id` int(11) DEFAULT NULL,
  `base_type_id` int(3) DEFAULT '0',
  `expense_category_id` int(11) DEFAULT NULL,
  `expense_currency_id` int(11) DEFAULT NULL,
  `expense_amount` float(10,2) DEFAULT NULL,
  `base_currency_id` int(11) DEFAULT NULL,
  `expense_base_currency_amount` float(10,2) NOT NULL,
  `expense_summary` varchar(50) DEFAULT NULL,
  `expense_vendor_id` varchar(255) DEFAULT NULL,
  `expense_date` date DEFAULT NULL,
  `expense_time` time DEFAULT NULL,
  `expense_description` text,
  `expense_tags` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `payment_mode` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=card,2=cash',
  `created_date` datetime DEFAULT NULL,
  `updated_date` datetime DEFAULT NULL,
  `LUID` int(11) DEFAULT NULL COMMENT 'Globally / Locally Unique Identifier',
  PRIMARY KEY (`user_expense_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Triggers `user_expenses`
--
DROP TRIGGER IF EXISTS `before_delete_user_expense`;
DELIMITER //
CREATE TRIGGER `before_delete_user_expense` BEFORE DELETE ON `user_expenses`
 FOR EACH ROW BEGIN

INSERT INTO archive_user_expenses 
SELECT * FROM user_expenses WHERE user_expense_id = OLD.user_expense_id;

END
//
DELIMITER ;

INSERT INTO `static_routers` (`source`, `destination`) VALUES ('services/get-user-trips', 'services/user-trips/get-user-trips');


------------------------------------------------executed on dev


DELIMITER //
CREATE TRIGGER `before_delete_user_trip` BEFORE DELETE ON `user_trips` FOR EACH ROW BEGIN

INSERT INTO archive_user_trips 
SELECT * FROM user_trips WHERE user_trip_id = OLD.user_trip_id;

UPDATE user_expenses SET user_trip_id = '0',updated_date = DATE_FORMAT(now(),'%Y-%m-%d %T') WHERE user_trip_id = OLD.user_trip_id;

END
//
DELIMITER ;

INSERT INTO `static_routers` (`source`, `destination`) VALUES ('services/get-app-setting', 'services/users/get-app-setting');
INSERT INTO `static_routers` (`source`, `destination`) VALUES ('services/get-user-profile', 'services/users/get-user-profile');

-----------------------------------------------------
INSERT INTO `dailyuse`.`static_routers` (`source`, `destination`) VALUES ('services/get-user-categories', 'services/expense-categories/get-user-categories');

CREATE TABLE `archive_expense_categories` (
	`expense_category_id` INT(11) NOT NULL AUTO_INCREMENT,
	`base_type_id` INT(3) NULL DEFAULT NULL,
	`user_id` INT(11) NULL DEFAULT NULL COMMENT 'reference to user\'s category',
	`parent_expense_category_id` INT(11) NULL DEFAULT NULL,
	`title` VARCHAR(255) NULL DEFAULT NULL,
	`description` TEXT NULL,
	`status` TINYINT(4) NULL DEFAULT NULL,
	`is_default` TINYINT(4) NULL DEFAULT '0',
	`created_date` DATETIME NULL DEFAULT NULL,
	`updated_date` DATETIME NULL DEFAULT NULL,
	`LUID` INT(11) NULL DEFAULT NULL COMMENT 'Globally / Locally Unique Identifier',
	PRIMARY KEY (`expense_category_id`),
	INDEX `FK_expense_categories_users` (`user_id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;


CREATE TRIGGER `before_delete_user_categories` BEFORE DELETE ON `expense_categories` FOR EACH ROW BEGIN INSERT INTO archive_expense_categories
SELECT *
FROM expense_categories
WHERE expense_category_id = OLD.expense_category_id;

END ;


---------------------------------

INSERT INTO `dailyuse`.`static_routers` (`source`, `destination`) VALUES ('services/get-user-expenses', 'services/user-expenses/get-user-expenses');

CREATE TABLE `archive_expense_vendors` (
`expense_vendor_id` int( 11 ) NOT NULL AUTO_INCREMENT ,
`user_id` int( 11 ) NOT NULL DEFAULT '0',
`name` varchar( 255 ) DEFAULT NULL ,
`description` text,
`address_line1` varchar( 255 ) DEFAULT NULL ,
`address_line2` varchar( 255 ) DEFAULT NULL ,
`city` varchar( 255 ) DEFAULT NULL ,
`state` varchar( 255 ) DEFAULT NULL ,
`country_id` int( 11 ) DEFAULT NULL ,
`zip_code` varchar( 255 ) DEFAULT NULL ,
`email` varchar( 255 ) DEFAULT NULL ,
`phone` int( 11 ) DEFAULT NULL ,
`deleted` tinyint( 4 ) DEFAULT NULL ,
`status` tinyint( 4 ) DEFAULT NULL ,
`created_date` datetime DEFAULT NULL ,
`updated_date` datetime DEFAULT NULL ,
`LUID` int( 11 ) DEFAULT NULL ,
PRIMARY KEY ( `expense_vendor_id` )
) ENGINE = InnoDB DEFAULT CHARSET = latin1;

DELIMITER //
CREATE TRIGGER `before_delete_expense_vendors` BEFORE DELETE ON `expense_vendors`
 FOR EACH ROW BEGIN

INSERT INTO archive_expense_vendors
SELECT * FROM expense_vendors WHERE expense_vendor_id = OLD.expense_vendor_id;

UPDATE user_expenses SET expense_vendor_id = '0',updated_date = DATE_FORMAT(now(),'%Y-%m-%d %T') WHERE expense_vendor_id = OLD.expense_vendor_id;

END
//

ALTER TABLE `user_expenses`
	CHANGE COLUMN `expense_amount` `expense_amount` DOUBLE(20,2) NULL DEFAULT NULL AFTER `expense_currency_id`,
	CHANGE COLUMN `expense_base_currency_amount` `expense_base_currency_amount` DOUBLE(20,2) NOT NULL AFTER `base_currency_id`;

INSERT INTO `static_routers` (`source` ,`destination`)VALUES ('services/export-trips', 'services/user-trips/export-trips');	

INSERT INTO `static_routers` (`source` ,`destination`)VALUES ('services/update-luids', 'services/miscellaneous/update-luids');

ALTER TABLE `user_trip_reference`
	ADD COLUMN `created_date` DATETIME NULL AFTER `LUID`,
	ADD COLUMN `updated_date` DATETIME NULL AFTER `created_date`;
	
	ALTER TABLE `user_expense_reference`
	ADD COLUMN `created_date` DATETIME NULL AFTER `LUID`,
	ADD COLUMN `updated_date` DATETIME NULL AFTER `created_date`;

DELETE FROM `currencies` WHERE `currency_id` =228

CREATE TABLE `user_device_info` (
	`user_device_info_id` INT(11) NOT NULL AUTO_INCREMENT,
	`user_id` INT(11) NULL DEFAULT NULL,
	`device_id` VARCHAR(255) NULL DEFAULT NULL,
	`app_version` VARCHAR(50) NULL DEFAULT NULL,
	`installation_date` DATETIME NULL DEFAULT NULL,
	`last_login_on` DATETIME NULL DEFAULT NULL,
	PRIMARY KEY (`user_device_info_id`),
	UNIQUE INDEX `user_id_device_id_app_version_installation_date` (`user_id`, `device_id`, `app_version`, `installation_date`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
;

INSERT INTO `static_routers` (`source`, `destination`) VALUES ('services/reset-sync-mapping', 'services/users/reset-sync-mapping');
