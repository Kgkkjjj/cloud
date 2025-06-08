<?php
require_once 'config.php';
if (!isset($_GET['token'])) {
    http_response_code(404);
    exit('Not found');
}
$token = $_GET['token'];
$file = file_by_token($token);
if (!$file) {
    http_response_code(404);
    exit('Not found');
}
$path = __DIR__ . '/uploads/' . $file['stored_name'];
if (!file_exists($path)) {
    http_response_code(404);
    exit('File missing');
}
header('Content-Type: ' . $file['mime_type']);
header('Content-Length: ' . $file['size']);
header('Content-Disposition: attachment; filename="' . basename($file['filename']) . '"');
readfile($path);
?>
