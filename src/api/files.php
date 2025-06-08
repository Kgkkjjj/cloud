<?php
require_once '../config.php';
require_login();

header('Content-Type: application/json');

$stmt = get_db()->prepare('SELECT filename, stored_name, uploaded_at FROM files WHERE user_id = ? ORDER BY uploaded_at DESC');
$stmt->execute([current_user()['id']]);
$files = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($files);
?>
