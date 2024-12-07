<?php
include 'log.php';
include 'db.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$action = "Accessed Index page";
saveAuditLog($action);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id, $hashed_password);
    $stmt->fetch();

    if ($user_id && password_verify($password, $hashed_password)) {
        $_SESSION['user_id'] = $user_id;

        saveAuditLog("Login successful: $username");
        header("Location: dashboard.php");
        exit();
    } else {
        saveAuditLog("Failed login attempt: $username");
        echo "Username atau password salah.";
    }

    $stmt->close();
}
?>

<h1>Login</h1>
<form method="POST" action="">
    Username: <input type="text" name="username" required><br>
    Password: <input type="password" name="password" required><br>
    <button type="submit">Login</button>
</form>
<a href="register.php">Registrasi</a>
