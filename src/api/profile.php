<?php
require_once '../config.php';
require_login();
header('Content-Type: application/json');
echo json_encode(current_user());
