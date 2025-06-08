<?php
require_once 'config.php';
require_login();

$db = get_db();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    if ($file['error'] === UPLOAD_ERR_OK) {
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
    <style>
        table { border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 4px 8px; }
    </style>
</head>
<body>
<h1>Welcome, <?php echo htmlspecialchars(current_user()['username']); ?></h1>
<p><a href="logout.php">Logout</a></p>
<h2>Upload File</h2>
<form method="post" enctype="multipart/form-data">
    <input type="file" name="file">
    <button type="submit">Upload</button>
</form>
<h2>Your Files</h2>
<table>
<tr><th>Name</th><th>Size (bytes)</th><th>Type</th><th>Uploaded</th><th></th></tr>
<?php foreach ($files as $f): ?>
    <tr>
        <td><a href="uploads/<?php echo urlencode($f['stored_name']); ?>" download><?php echo htmlspecialchars($f['filename']); ?></a></td>
        <td><?php echo $f['size']; ?></td>
        <td><?php echo htmlspecialchars($f['mime_type']); ?></td>
        <td><?php echo $f['uploaded_at']; ?></td>
        <td><a href="delete.php?id=<?php echo $f['id']; ?>" onclick="return confirm('Delete this file?');">Delete</a></td>
    </tr>
<?php endforeach; ?>
</table>
<p>API: <a href="api/files.php">List Files (JSON)</a></p>
</body>
</html>
