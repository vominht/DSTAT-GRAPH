<?php
$cpu_info = shell_exec("lscpu");
$cpu_name = shell_exec("echo \"$cpu_info\" | grep 'Model name' | awk -F: '{print $2}'");
$cpu_percent = shell_exec("top -b -n1 | grep 'Cpu(s)' | awk '{print $2 + $4}'");
$core_count = shell_exec("nproc");
$threads_per_core = shell_exec("echo \"$cpu_info\" | grep 'Thread(s) per core' | awk '{print $4}'");

$thread_count = intval($core_count) * intval($threads_per_core);

$data = array(
    'cpu_name' => trim($cpu_name),
    'cpu_usage' => floatval($cpu_percent),
    'core' => intval($core_count),
    'threads' => $thread_count
);

echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
?>
