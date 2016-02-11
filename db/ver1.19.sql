
-- Date: 04.11.2015 <START>
SET @@session.time_zone = "+00:00";
UPDATE user_expenses t SET t.exp_date_timestamp = (UNIX_TIMESTAMP(CONCAT(t.expense_date, ' ', t.expense_time)) * 1000) WHERE 1;
UPDATE user_trips t SET  t.trip_date_from_timestamp = (UNIX_TIMESTAMP(t.trip_date_from) * 1000), t.trip_date_to_timestamp = (UNIX_TIMESTAMP(t.trip_date_to) * 1000);
-- Date: 04.11.2015 <END>