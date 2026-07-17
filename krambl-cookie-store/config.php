<?php
session_start();

/* Local XAMPP database settings */
$host = "localhost";
$username = "root";
$password = "";
$database = "krambl_store";

/* Connect to MySQL first */
$conn = mysqli_connect($host, $username, $password);

if ($conn == false) {
    echo "Could not connect to MySQL. Please start MySQL in XAMPP.";
    return;
}

/* Select the Krambl database */
$database_selected = mysqli_select_db($conn, $database);

if ($database_selected == false) {
    echo "The krambl_store database was not found. Import database/krambl_store.sql in phpMyAdmin first.";
    return;
}

define("BASE_URL", "http://localhost/krambl-cookie-store");
define("LOCAL_TESTING", true);
define("GROUP_NAME", "Krambl Development Team");

include_once __DIR__ . "/includes/functions.php";
?>
