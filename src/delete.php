<?php
require_once 'config.php';
require_login();

$id = (int)($_GET['id'] ?? 0);
$db = get_db();
$stmt = $db->prepare('SELECT stored_name FROM files WHERE id = ? AND user_id = ?');
$stmt->execute([$id, current_user()['id']]);
$file = $stmt->fetch(PDO::FETCH_ASSOC);
if ($file) {
    $path = __DIR__ . '/uploads/' . $file['stored_name'];
    if (is_file($path)) {
        unlink($path);
    }
    $db->prepare('DELETE FROM files WHERE id = ?')->execute([$id]);
    log_activity(current_user()['id'], 'delete', $file['stored_name']);
}
header('Location: dashboard.php');
exit;
?>
