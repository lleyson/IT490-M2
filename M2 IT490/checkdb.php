<?php
require_once(__DIR__ . "/lib/db.php");

$db = getDB();
if ($db) {
    echo "Database connection successful";
} else {
    echo "Database connection failed";
}
?>
