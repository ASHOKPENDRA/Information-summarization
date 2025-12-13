<?php
// Set timezone globally for all PHP pages
date_default_timezone_set('Asia/Kolkata');

$servername = "sql105.infinityfree.com";
$username = "if0_39907292";
$passwordDb = "6QecRDL6dargJVf";
$database = "if0_39907292_user";

$con = new mysqli($servername, $username, $passwordDb, $database);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
?>
