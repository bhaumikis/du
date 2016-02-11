ALTER TABLE `expense_categories`
	CHANGE COLUMN `title` `title` VARCHAR(50) NULL DEFAULT NULL AFTER `parent_expense_category_id`,
	CHANGE COLUMN `description` `description` VARCHAR(200) NULL AFTER `title`;

ALTER TABLE `expense_vendors`
	CHANGE COLUMN `name` `name` VARCHAR(50) NULL DEFAULT NULL AFTER `user_id`,
	CHANGE COLUMN `description` `description` VARCHAR(200) NULL AFTER `name`;

ALTER TABLE `user_trips`
	CHANGE COLUMN `trip_title` `trip_title` VARCHAR(50) NULL DEFAULT NULL AFTER `base_expense_type_id`,
	CHANGE COLUMN `trip_description` `trip_description` VARCHAR(200) NULL COMMENT 'purpose and other descrition' AFTER `trip_title`,
	CHANGE COLUMN `trip_destination` `trip_destination` VARCHAR(50) NULL DEFAULT NULL COMMENT 'destination could be country or city' AFTER `trip_description`;

ALTER TABLE `user_expenses`
	CHANGE COLUMN `expense_vendor_id` `expense_vendor_id` INT(50) NULL DEFAULT NULL AFTER `expense_summary`,
	CHANGE COLUMN `expense_description` `expense_description` VARCHAR(200) NULL AFTER `expense_time`;

ALTER TABLE `user_trips`
	ADD COLUMN `trip_time_from` TIME NULL DEFAULT NULL AFTER `trip_date_from`,
	ADD COLUMN `trip_time_to` TIME NULL DEFAULT NULL AFTER `trip_date_to`;

ALTER TABLE `users`
	ADD COLUMN `base_currency_id` VARCHAR(255) NULL DEFAULT NULL AFTER `base_currency`;

ALTER TABLE `users`
	DROP COLUMN `base_currency`;

ALTER TABLE `user_trips`
	CHANGE COLUMN `trip_date_from` `trip_date_from` DATETIME NULL DEFAULT NULL AFTER `trip_currency`,
	CHANGE COLUMN `trip_date_to` `trip_date_to` DATETIME NULL DEFAULT NULL AFTER `trip_date_from`,
	DROP COLUMN `trip_time_from`,
	DROP COLUMN `trip_time_to`;
