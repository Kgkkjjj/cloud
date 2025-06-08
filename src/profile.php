<?php
require_once 'config.php';
require_login();
$db = get_db();
$user = current_user();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pass1 = $_POST['password'] ?? '';
    if ($pass1) {
        $hash = password_hash($pass1, PASSWORD_DEFAULT);
        $stmt = $db->prepare('UPDATE users SET password = ? WHERE id = ?');
        $stmt->execute([$hash, $user['id']]);
        $message = 'Password updated.';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Profile</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="profile.php">Profile</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>
<div class="container">
    <h1>User Profile</h1>
    <?php if (!empty($message)) echo '<p>'.$message.'</p>'; ?>
    <p>Username: <?php echo htmlspecialchars($user['username']); ?></p>
    <h2>Change Password</h2>
    <form method="post">
        <label>New Password <input type="password" name="password"></label>
        <button class="btn" type="submit">Change</button>
    </form>
</div>
</body>
</html>
