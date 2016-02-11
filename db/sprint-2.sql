date-3sep
------------------------
INSERT INTO `static_routers` (`source`, `destination`) VALUES ('services/change-base-currency', 'services/users/change-base-currency');
INSERT INTO `static_routers` (`source`, `destination`) VALUES ('services/change-security-question', 'services/users/change-security-question');


date 5th sep
----------------------------------

CREATE TABLE `languages` (
	`language_id` INT(11) NOT NULL AUTO_INCREMENT,
	`title` VARCHAR(255) NOT NULL,
	`code` VARCHAR(255) NOT NULL,
	`direction` VARCHAR(255) NOT NULL,
	`status` TINYINT(4) NOT NULL,
	PRIMARY KEY (`language_id`),
	UNIQUE INDEX `code` (`code`)
)
COLLATE='latin1_swedish_ci'
ENGINE=MyISAM
AUTO_INCREMENT=1;

INSERT INTO `languages` (`language_id`, `title`, `code`, `direction`, `status`) VALUES (1, 'US English', 'US_EN', 'ltr', 1);

UPDATE `dailyuse`.`static_routers` SET `destination`='services/user-expenses/add-expense' WHERE  `source`='services/add-expense';
ALTER TABLE `user_expenses`
	ALTER `expense_base_currency_amount` DROP DEFAULT;
ALTER TABLE `user_expenses`
	CHANGE COLUMN `user_id` `user_id` INT(11) NULL DEFAULT NULL AFTER `user_expense_id`,
	CHANGE COLUMN `user_trip_id` `user_trip_id` INT(11) NULL DEFAULT NULL AFTER `card_id`,
	CHANGE COLUMN `expense_category_id` `expense_category_id` INT(11) NULL DEFAULT NULL AFTER `base_expense_type_id`,
	CHANGE COLUMN `expense_amount` `expense_amount` FLOAT(10,2) NULL DEFAULT NULL AFTER `expense_currency_id`,
	CHANGE COLUMN `base_currency_id` `base_currency_id` INT(11) NULL DEFAULT NULL AFTER `expense_amount`,
	CHANGE COLUMN `expense_base_currency_amount` `expense_base_currency_amount` FLOAT(10,2) NOT NULL AFTER `base_currency_id`,
	CHANGE COLUMN `expense_summary` `expense_summary` VARCHAR(50) NULL DEFAULT NULL AFTER `expense_base_currency_amount`,
	CHANGE COLUMN `expense_vendor_name` `expense_vendor_id` VARCHAR(255) NULL DEFAULT NULL AFTER `expense_summary`;

ALTER TABLE `usertypes`
	ADD COLUMN `type` TINYINT NOT NULL AFTER `description`;

	
date 9th Sep
----------------------------------

CREATE TABLE IF NOT EXISTS `forgot_password` (
  `forgot_password_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `created_date` datetime NOT NULL,
  PRIMARY KEY (`forgot_password_id`)
) COLLATE='latin1_swedish_ci' ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-------------------------------------------------
ALTER TABLE `user_expense_reference`
	CHANGE COLUMN `user_expense_media_id` `user_expense_reference_id` INT(11) NOT NULL AUTO_INCREMENT FIRST;
	INSERT INTO `static_routers` (`source`, `destination`) VALUES ('services/save-user-image', 'services/users/save-user-image');
INSERT INTO `static_routers` (`source`, `destination`) VALUES ('services/save-expense-image', 'services/user-expenses/save-expense-image');
-------------------------------Excuted on server ON 10 sep-----------------------------

ALTER TABLE `vendors`
	CHANGE COLUMN `vendor_id` `expense_vendor_id` INT(11) NOT NULL AUTO_INCREMENT FIRST;

RENAME TABLE `vendors` TO `expense_vendors`;

ALTER TABLE `user_trips`
	CHANGE COLUMN `trip_id` `user_trip_id` INT(11) NOT NULL AUTO_INCREMENT FIRST;

ALTER TABLE `currencies`
	CHANGE COLUMN `currency_symbol` `currency_symbol` VARCHAR(10) NULL DEFAULT NULL AFTER `currency_name`;

ALTER TABLE `user_expenses`
	CHANGE COLUMN `base_expense_type_id` `base_expense_type_id` INT(3) NULL DEFAULT '0' AFTER `user_trip_id`;

-------------------------------Excuted on server ON 11 sep-----------------------------