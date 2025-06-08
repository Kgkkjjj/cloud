<?php
require_once 'config.php';
require_login();

// Disk usage
$diskTotal = disk_total_space('/');
$diskFree = disk_free_space('/');
$diskUsed = $diskTotal - $diskFree;

// Load average
$load = sys_getloadavg()[0];

// Memory info
$memTotal = $memFree = 0;
if (is_readable('/proc/meminfo')) {
    $meminfo = file('/proc/meminfo');
    foreach ($meminfo as $line) {
        if (strpos($line, 'MemTotal:') === 0) {
            $memTotal = (int)filter_var($line, FILTER_SANITIZE_NUMBER_INT) * 1024;
        } elseif (strpos($line, 'MemAvailable:') === 0) {
            $memFree = (int)filter_var($line, FILTER_SANITIZE_NUMBER_INT) * 1024;
        }
    }
}
$memUsed = $memTotal ? $memTotal - $memFree : 0;

?>
<!DOCTYPE html>
<html>
<head>
    <title>Server Statistics</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header>
    <nav>
        <a href="dashboard.php">Dashboard</a>
        <a href="profile.php">Profile</a>
        <a href="stats.php">Stats</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>
<div class="container">
    <h1>Server Statistics</h1>
    <table>
        <tr><th>Metric</th><th>Value</th></tr>
        <tr><td>Disk Used</td><td><?php echo number_format($diskUsed / (1024*1024), 2); ?> MB</td></tr>
        <tr><td>Disk Free</td><td><?php echo number_format($diskFree / (1024*1024), 2); ?> MB</td></tr>
        <tr><td>Load Average</td><td><?php echo htmlspecialchars((string)$load); ?></td></tr>
        <tr><td>Memory Used</td><td><?php echo number_format($memUsed / (1024*1024), 2); ?> MB</td></tr>
        <tr><td>Memory Total</td><td><?php echo number_format($memTotal / (1024*1024), 2); ?> MB</td></tr>
    </table>
    <p>API: <a href="api/stats.php">/api/stats.php</a></p>
</div>
</body>
</html>
