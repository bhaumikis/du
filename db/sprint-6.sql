-- Date: 31.08.2015 <START>
ALTER TABLE `users`
	ADD COLUMN `birth_date_timestamp` VARCHAR(50) NULL DEFAULT NULL AFTER `birth_date`;

UPDATE users t SET  t.birth_date_timestamp = UNIX_TIMESTAMP(birth_date) WHERE t.birth_date_timestamp IS NULL;
-- Date: 31.08.2015 <END>

-- Date: 08.09.2015 <START>
CREATE TABLE IF NOT EXISTS `request_logs` (
	`requestlog_id` BIGINT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
	`method` VARCHAR(255) NOT NULL,
	`posted_data` TEXT NOT NULL,
	`response` TEXT NOT NULL,
	`user_agent` TEXT NOT NULL,
	`request_header` TEXT NOT NULL,
	`response_header` TEXT NOT NULL,
	`remote_address` VARCHAR(255) NOT NULL,
	`cookies` TEXT NOT NULL,
	`time` FLOAT(19,10) NOT NULL,
	`created_date` DATETIME NOT NULL,
	PRIMARY KEY (`requestlog_id`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB
ROW_FORMAT=DYNAMIC
AUTO_INCREMENT=4
;
-- Date: 08.09.2015 <END>


-- Date: 23.09.2015 <START>
ALTER TABLE `user_expenses`
	ADD COLUMN `exp_date_timestamp` VARCHAR(50) NULL DEFAULT NULL AFTER `expense_time`;

UPDATE user_expenses t SET t.exp_date_timestamp = UNIX_TIMESTAMP(CONCAT(t.expense_date, ' ', t.expense_time)) WHERE t.exp_date_timestamp IS NULL;
-- Date: 23.09.2015 <END>


-- Date: 08.10.2015 <START>
ALTER TABLE `request_logs`
	CHANGE COLUMN `posted_data` `posted_data` MEDIUMTEXT NOT NULL AFTER `method`,
	CHANGE COLUMN `response` `response` MEDIUMTEXT NOT NULL AFTER `posted_data`;
-- Date: 08.10.2015 <END>

-- Date: 13.10.2015 <START>
ALTER TABLE `archive_user_expenses`
	ADD COLUMN `exp_date_timestamp` VARCHAR(50) NULL DEFAULT NULL AFTER `expense_time`;
-- Date: 13.10.2015 <END>




ALTER TABLE `user_trips`
	ADD COLUMN `trip_date_from_timestamp` VARCHAR(50) NULL DEFAULT NULL AFTER `trip_date_to`,
	ADD COLUMN `trip_date_to_timestamp` VARCHAR(50) NULL DEFAULT NULL AFTER `trip_date_from_timestamp`;
UPDATE user_trips t SET  t.trip_date_from_timestamp = UNIX_TIMESTAMP(t.trip_date_from) WHERE t.trip_date_from_timestamp IS NULL;
UPDATE user_trips t SET  t.trip_date_to_timestamp = UNIX_TIMESTAMP(t.trip_date_to) WHERE t.trip_date_to_timestamp IS NULL;

ALTER TABLE `archive_user_trips`
	ADD COLUMN `trip_date_from_timestamp` VARCHAR(50) NULL DEFAULT NULL AFTER `trip_date_to`,
	ADD COLUMN `trip_date_to_timestamp` VARCHAR(50) NULL DEFAULT NULL AFTER `trip_date_from_timestamp`;

-- Date: 20.10.2015 <END>


------------ Till now imported on the dailyuse and dailyuse_dev db Date: 15.10.2015 ---------------
-- SMTP Settings you need to update here.