<?php
include __DIR__ . "/config.php";

if (is_logged_in()) {
    audit_log($conn, "Logged out");
}

session_unset();
session_destroy();
session_start();
$_SESSION["message"] = "You have been logged out.";

header("Location: index.php");
exit;
?>
