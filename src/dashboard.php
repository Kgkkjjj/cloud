<?php
require_once 'config.php';
require_login();

$db = get_db();
$usedBytes = user_storage_used(current_user()['id']);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    if ($file['error'] === UPLOAD_ERR_OK) {
        if ($usedBytes + $file['size'] > MAX_STORAGE_BYTES) {
            $error = 'Storage limit exceeded';
        } else {
            $stored = bin2hex(random_bytes(16)) . '_' . basename($file['name']);
            $dest = __DIR__ . '/uploads/' . $stored;
            if (move_uploaded_file($file['tmp_name'], $dest)) {
                $stmt = $db->prepare('INSERT INTO files (user_id, filename, stored_name, size, mime_type) VALUES (?, ?, ?, ?, ?)');
                $stmt->execute([
                    current_user()['id'],
                    $file['name'],
                    $stored,
                    $file['size'],
                    $file['type']
                ]);
                log_activity(current_user()['id'], 'upload', $file['name']);
                $usedBytes += $file['size'];
            }
        }
    }
}

$stmt = $db->prepare('SELECT * FROM files WHERE user_id = ? ORDER BY uploaded_at DESC');
$stmt->execute([current_user()['id']]);
$files = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
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
<h1>Welcome, <?php echo htmlspecialchars(current_user()['username']); ?></h1>
<?php if (!empty($error)) echo '<p style="color:red">'.htmlspecialchars($error).'</p>'; ?>
<?php
$count = count($files);
$totalSize = array_sum(array_column($files, 'size'));
?>
<p>You have <?php echo $count; ?> file(s) using <?php echo $totalSize; ?> bytes (<?php echo $usedBytes; ?> of <?php echo MAX_STORAGE_BYTES; ?> allowed).</p>
<h2>Upload File</h2>
<form method="post" enctype="multipart/form-data">
    <input type="file" name="file">
    <button class="btn" type="submit">Upload</button>
</form>
<h2>Your Files</h2>
<table>
<tr><th>Name</th><th>Size</th><th>Type</th><th>Uploaded</th><th>Share</th><th></th></tr>
<?php foreach ($files as $f): ?>
    <tr>
        <td><a href="uploads/<?php echo urlencode($f['stored_name']); ?>" download><?php echo htmlspecialchars($f['filename']); ?></a></td>
        <td><?php echo $f['size']; ?></td>
        <td><?php echo htmlspecialchars($f['mime_type']); ?></td>
        <td><?php echo $f['uploaded_at']; ?></td>
        <td>
            <?php if ($f['share_token']): ?>
                <a class="btn" href="public.php?token=<?php echo urlencode($f['share_token']); ?>" target="_blank">Link</a>
                <a class="btn" href="share.php?id=<?php echo $f['id']; ?>&unshare=1">Unshare</a>
            <?php else: ?>
                <a class="btn" href="share.php?id=<?php echo $f['id']; ?>">Share</a>
            <?php endif; ?>
        </td>
        <td><a class="btn" href="delete.php?id=<?php echo $f['id']; ?>" onclick="return confirm('Delete this file?');">Delete</a></td>
    </tr>
<?php endforeach; ?>
</table>
<p>APIs: <a href="api/files.php">List Files</a>, <a href="api/profile.php">Profile</a>, <a href="api/share.php">Share</a></p>
</div>
</body>
</html>
