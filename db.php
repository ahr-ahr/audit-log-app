<?php
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'audit_log_app';

// Membuat koneksi
$conn = new mysqli($host, $user, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
