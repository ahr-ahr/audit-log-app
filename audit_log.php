<?php
include 'db.php';
include 'log.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$action = "Accessed Audit Log page";
saveAuditLog($action);

$result = $conn->query("SELECT * FROM audit_logs ORDER BY timestamp DESC");
?>

<h1>Audit Log</h1>
<table border="1">
    <tr>
        <th>ID</th>
        <th>IP Address</th>
        <th>User ID</th>
        <th>Action</th>
        <th>Method</th>
        <th>Request URI</th>
        <th>Request Data</th>
        <th>Timestamp</th>
    </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['ip_address'] ?></td>
            <td><?= $row['user_id'] ?? 'Guest' ?></td>
            <td><?= $row['action'] ?></td>
            <td><?= $row['method'] ?></td>
            <td><?= $row['request_uri'] ?></td>
            <td><?= $row['request_data'] ?></td>
            <td><?= $row['timestamp'] ?></td>
        </tr>
    <?php endwhile; ?>
</table>
<a href="dashboard.php">Kembali</a>