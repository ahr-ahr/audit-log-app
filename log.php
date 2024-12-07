<?php
include 'db.php';

function getUserIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

function saveAuditLog($action) {
    global $conn;

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $ip_address = getUserIP();
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $method = $_SERVER['REQUEST_METHOD'];
    $request_uri = $_SERVER['REQUEST_URI'];
    $request_data = json_encode($method === 'POST' ? $_POST : $_GET);

    if ($stmt = $conn->prepare("INSERT INTO audit_logs (ip_address, user_id, action, method, request_uri, request_data) VALUES (?, ?, ?, ?, ?, ?)")) {
        $stmt->bind_param("sissss", $ip_address, $user_id, $action, $method, $request_uri, $request_data);

        if (!$stmt->execute()) {
            error_log("Failed to execute statement: " . $stmt->error);
        }

        $stmt->close();  
    } else {
        error_log("Failed to prepare statement: " . $conn->error);
    }
}