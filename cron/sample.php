<?php

include("croncommon.php");

include(APPLICATION_PATH . "/models/cron.php");

$cron = new cronModel();

$cron->sentSyncNotifications();