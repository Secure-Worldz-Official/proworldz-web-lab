<?php
/**
 * OWASP Lab - Standalone MySQLi DB Connection
 * Used by challenges that include this file directly.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$db_host = "sql204.infinityfree.com";
$db_user = "if0_40322633";
$db_pass = "HDm584vG4kZDnt";
$db_name = "if0_40322633_students";

$con = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($con->connect_error) {
    die("<div style='font-family:sans-serif; text-align:center; padding:50px;'><h2>Lab DB Connection Error</h2><p>" . $con->connect_error . "</p></div>");
}

$con->set_charset("utf8mb4");

// Alias so legacy code using $db or $pdo variable names still works
// (these are mysqli objects, NOT PDO — all queries must use mysqli syntax)
$db = $con;
