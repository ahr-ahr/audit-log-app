<?php
include 'log.php';
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    saveAuditLog("Unauthorized access attempt to Dashboard");
    header("Location: index.php");
    exit();
}

saveAuditLog("Accessed Dashboard");
?>

<h1>Selamat datang di Dashboard</h1>
<a href="audit_log.php">Lihat Log</a> | <a href="logout.php">Logout</a>