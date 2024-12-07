<?php
include 'log.php';
include 'db.php';
session_start();

if (isset($_SESSION['user_id'])) {
    saveAuditLog("User logged out");
    session_destroy();
}

header("Location: index.php");
?>
