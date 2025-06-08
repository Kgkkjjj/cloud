<?php
require_once '../config.php';
require_login();
header('Content-Type: application/json');
$db = get_db();
$stmt = $db->prepare('SELECT action, filename, timestamp FROM activity WHERE user_id = ? ORDER BY timestamp DESC LIMIT 50');
$stmt->execute([current_user()['id']]);
$logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($logs);
