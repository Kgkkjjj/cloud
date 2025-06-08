<?php
require_once '../config.php';
require_login();
header('Content-Type: application/json');

$diskTotal = disk_total_space('/');
$diskFree  = disk_free_space('/');
$diskUsed  = $diskTotal - $diskFree;
$load = sys_getloadavg()[0];
$memTotal = $memFree = 0;
if (is_readable('/proc/meminfo')) {
    $meminfo = file('/proc/meminfo');
    foreach ($meminfo as $line) {
        if (strpos($line, 'MemTotal:') === 0) {
            $memTotal = (int)filter_var($line, FILTER_SANITIZE_NUMBER_INT) * 1024;
        } elseif (strpos($line, 'MemAvailable:') === 0) {
            $memFree = (int)filter_var($line, FILTER_SANITIZE_NUMBER_INT) * 1024;
        }
    }
}
$memUsed = $memTotal ? $memTotal - $memFree : 0;

echo json_encode([
    'disk_used' => $diskUsed,
    'disk_free' => $diskFree,
    'load_avg'  => $load,
    'mem_used'  => $memUsed,
    'mem_total' => $memTotal,
]);
