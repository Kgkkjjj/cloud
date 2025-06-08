<?php
session_start();

const DB_PATH = __DIR__ . '/database.sqlite';

function get_db() {
    static $db = null;
    if ($db === null) {
        $db = new PDO('sqlite:' . DB_PATH);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    return $db;
}

function current_user() {
    return $_SESSION['user'] ?? null;
}

function require_login() {
    if (!current_user()) {
        header('Location: login.php');
        exit;
    }
}
const MAX_STORAGE_BYTES = 30 * 1024 * 1024 * 1024; // 30 GiB per user

function user_storage_used($userId) {
    $db = get_db();
    $stmt = $db->prepare('SELECT IFNULL(SUM(size),0) FROM files WHERE user_id = ?');
    $stmt->execute([$userId]);
    return (int)$stmt->fetchColumn();
}

?>
