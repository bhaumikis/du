<?php
// Cron Script- Currency Rate data insert into the database.
include("croncommon.php");

include(APPLICATION_PATH . "/models/cron.php");

$cron = new model\cronModel();

$cron->getTodaysCurrencyRate();