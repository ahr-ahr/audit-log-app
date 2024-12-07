<?php
include 'log.php';
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);

    if ($stmt->execute()) {
        saveAuditLog("User registered: $username");
        echo "Registrasi berhasil. <a href='index.php'>Login</a>";
    } else {
        saveAuditLog("Failed registration attempt: $username");
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>

<h1>Registrasi</h1>
<form method="POST" action="">
    Username: <input type="text" name="username" required><br>
    Password: <input type="password" name="password" required><br>
    <button type="submit">Daftar</button>
</form>
