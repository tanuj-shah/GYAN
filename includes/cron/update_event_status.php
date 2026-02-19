<?php
// includes/cron/update_event_status.php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../events.php';

$count = updateEventStatuses();
if ($count > 0) {
    echo "[" . date('Y-m-d H:i:s') . "] Updated $count events to 'past'.\n";
} else {
    // echo "[" . date('Y-m-d H:i:s') . "] No events updated.\n";
}
