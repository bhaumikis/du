<?php
// Cron Script - Update the ticket assignment.

include("croncommon.php");

include(APPLICATION_PATH . "/models/cron.php");

$cron = new model\cronModel();

$cron->updateTicketAssignmentRights();

$cron->updateTicketAssignments();