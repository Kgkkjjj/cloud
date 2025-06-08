<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username && $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        try {
            $stmt = get_db()->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
            $stmt->execute([$username, $hash]);
            header('Location: login.php');
            exit;
        } catch (PDOException $e) {
            $error = 'Username already taken';
        }
    } else {
        $error = 'Both fields required';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <nav>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
    </nav>
</header>
<div class="container">
<h1>Register</h1>
<?php if (!empty($error)) echo '<p style="color:red">'.$error.'</p>'; ?>
<form method="post">
    <label>Username <input type="text" name="username"></label><br>
    <label>Password <input type="password" name="password"></label><br>
    <button class="btn" type="submit">Register</button>
</form>
<p>Already have an account? <a href="login.php">Login here</a></p>
</div>
</body>
</html>
