<?php
require_once '../config.php';
require_login();
header('Content-Type: application/json');
$db = get_db();
$usedBytes = user_storage_used(current_user()['id']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['file'])) {
    http_response_code(400);
    echo json_encode(['error' => 'file required']);
    exit;
}

$file = $_FILES['file'];
if ($file['error'] === UPLOAD_ERR_OK) {
    if ($usedBytes + $file['size'] > MAX_STORAGE_BYTES) {
        http_response_code(400);
        echo json_encode(['error' => 'storage limit exceeded']);
        exit;
    }
    $stored = bin2hex(random_bytes(16)) . '_' . basename($file['name']);
    $dest = __DIR__ . '/../uploads/' . $stored;
    if (move_uploaded_file($file['tmp_name'], $dest)) {
        $stmt = $db->prepare('INSERT INTO files (user_id, filename, stored_name, size, mime_type) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([
            current_user()['id'],
            $file['name'],
            $stored,
            $file['size'],
            $file['type']
        ]);
        echo json_encode(['success' => true]);
        exit;
    }
}
http_response_code(500);
echo json_encode(['error' => 'upload failed']);
