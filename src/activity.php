<?php
require_once 'config.php';
require_login();

$db = get_db();
$stmt = $db->prepare('SELECT action, filename, timestamp FROM activity WHERE user_id = ? ORDER BY timestamp DESC LIMIT 50');
$stmt->execute([current_user()['id']]);
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Recent Activity</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="profile.php">Profile</a>
        <a href="stats.php">Stats</a>
        <a href="activity.php">Activity</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>
<div class="container">
    <h1>Recent Activity</h1>
    <table>
        <tr><th>Action</th><th>Filename</th><th>Time</th></tr>
        <?php foreach ($logs as $l): ?>
            <tr>
                <td><?php echo htmlspecialchars($l['action']); ?></td>
                <td><?php echo htmlspecialchars($l['filename']); ?></td>
                <td><?php echo htmlspecialchars($l['timestamp']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <p>API: <a href="api/activity.php">/api/activity.php</a></p>
</div>
</body>
</html>
