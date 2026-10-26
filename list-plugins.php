<?php
require_once('wp-load.php');
if (!function_exists('get_plugins')) {
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
}
$all_plugins = get_plugins();
header('Content-Type: text/plain');
foreach ($all_plugins as $path => $data) {
    if (is_plugin_active($path)) {
        echo "ACTIVE: $path - " . $data['Name'] . "\n";
    } else {
        echo "INACTIVE: $path - " . $data['Name'] . "\n";
    }
}
unlink(__FILE__);
