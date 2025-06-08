<?php
require_once 'config.php';
require_login();

if (!isset($_GET['id'])) {
    header('Location: dashboard.php');
    exit;
}

$id = (int)$_GET['id'];
$db = get_db();
$stmt = $db->prepare('SELECT * FROM files WHERE id = ? AND user_id = ?');
$stmt->execute([$id, current_user()['id']]);
$file = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$file) {
    header('Location: dashboard.php');
    exit;
}

if (isset($_GET['unshare'])) {
    $db->prepare('UPDATE files SET share_token = NULL WHERE id = ?')->execute([$id]);
} else {
    $token = generate_share_token();
    $db->prepare('UPDATE files SET share_token = ? WHERE id = ?')->execute([$token, $id]);
}

header('Location: dashboard.php');
?>
