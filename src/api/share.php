<?php
require_once '../config.php';
require_login();

header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'POST required']);
    exit;
}

$id = (int)($_POST['id'] ?? 0);
$unshare = isset($_POST['unshare']);

$db = get_db();
$stmt = $db->prepare('SELECT * FROM files WHERE id = ? AND user_id = ?');
$stmt->execute([$id, current_user()['id']]);
$file = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$file) {
    http_response_code(404);
    echo json_encode(['error' => 'Not found']);
    exit;
}

if ($unshare) {
    $db->prepare('UPDATE files SET share_token = NULL WHERE id = ?')->execute([$id]);
    echo json_encode(['status' => 'unshared']);
} else {
    $token = generate_share_token();
    $db->prepare('UPDATE files SET share_token = ? WHERE id = ?')->execute([$token, $id]);
    echo json_encode(['status' => 'shared', 'token' => $token, 'url' => dirname(dirname($_SERVER['SCRIPT_NAME'])) . "/public.php?token=$token"]);
}
?>
