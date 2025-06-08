<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = get_db()->prepare('SELECT * FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = ['id' => $user['id'], 'username' => $user['username']];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = 'Invalid credentials';
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Login</title></head>
<body>
<h1>Login</h1>
<?php if (!empty($error)) echo '<p style="color:red">'.$error.'</p>'; ?>
<form method="post">
    <label>Username:<input type="text" name="username"></label><br>
    <label>Password:<input type="password" name="password"></label><br>
    <button type="submit">Login</button>
</form>
<p>Don't have an account? <a href="register.php">Register here</a></p>
</body>
</html>
