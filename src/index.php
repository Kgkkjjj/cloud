<?php
require_once 'config.php';

if (current_user()) {
    header('Location: dashboard.php');
    exit;
} else {
    header('Location: login.php');
    exit;
}
?>
