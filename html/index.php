<?php
//读取文件内容
$filename = "info.txt";
$content = file_get_contents($filename);

// 分割内容为多行，并存储在数组中
$lines = explode("\n", $content);

// 初始化数组存储机器状态
$hostsStatus = [];
$current_ip = '';
// 遍历每一行
foreach ($lines as $line) {
    // 使用正则表达式匹配and判断状态信息
    if (preg_match('/(changed|fatal): \[(\d+\.\d+\.\d+\.\d+)\]/', $line, $matches)) {
        $current_ip = $matches[2];
        $status = $matches[1] === 'changed' ? '运行状态' : '离线状态';
        $hostsStatus[$current_ip]['status'] = $status;
    }

    // 使用正则表达式匹配IP地址
    if (preg_match('/ok: \[(\d+\.\d+\.\d+\.\d+)\] => \{/', $line, $matches)) {
        $current_ip = $matches[1];
    }

    // 使用正则表达式匹配主机名
    if (preg_match('/Hostname: (.+)/', $line, $matches)) {
        if ($current_ip) {
            $hostsStatus[$current_ip]['hostname'] = trim($matches[1], '"');
        }
    }

    // 使用正则表达式匹配CPU使用率
    if (preg_match('/CPU_Usage: ([0-9.]+%)/', $line, $matches)) {
        if ($current_ip) {
            $hostsStatus[$current_ip]['cpu_usage'] = $matches[1];
        }
    }

    // 使用正则表达式匹配内存使用率
    if (preg_match('/Memory_Usage: +([0-9]+\/[0-9]+MB \([0-9.]+%\))/', $line, $matches)) {
        if ($current_ip) {
            $hostsStatus[$current_ip]['memory_usage'] = $matches[1];
        }
    }

    // 使用正则表达式匹配硬盘使用率
    if (preg_match('/Disk_Usage: ([0-9.]+%)/', $line, $matches)) {
        if ($current_ip) {
            $hostsStatus[$current_ip]['disk_usage'] = $matches[1];
        }
    }

    // 使用正则表达式匹配网络上传速率
    if (preg_match('/Sender Bandwidth: ([0-9.]+ Gbits\/sec)/', $line, $matches)) {
        if ($current_ip) {
            $hostsStatus[$current_ip]['sender_bandwidth'] = $matches[1];
        }
    }

    // 使用正则表达式匹配网络下载速率
    if (preg_match('/Receiver Bandwidth: ([0-9.]+ Gbits\/sec)/', $line, $matches)) {
        if ($current_ip) {
            $hostsStatus[$current_ip]['receiver_bandwidth'] = $matches[1];
        }
    }
}

echo <<<HTML
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="5">
    <style>
        body {
            /* 设置背景图片 */
            background-image: url('/background.png'); /* 适用于网络路径 */
            background-size: cover; /* 使背景图片覆盖整个页面 */
            background-repeat: no-repeat; /* 防止背景图片重复 */
            margin: 0;
            font-family: Arial, sans-serif;
        }
        .running { color: green; font-weight: bold; }
        .offline { color: red; font-weight: bold; }
        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
            background: rgba(255, 255, 255, 0.8); /* 半透明背景，使表格更易阅读 */
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h2>所有机器的运行/离线状态</h2>
    <table>
        <thead>
            <tr>
                <th>机器IP</th>
                <th>状态</th>
            </tr>
        </thead>
        <tbody>
HTML;

// 显示所有机器的运行/离线状态
foreach ($hostsStatus as $ip => $info) {
    $status = $info['status'];
    $class = ($status == '运行状态') ? 'running' : 'offline';
    echo "<tr><td>{$ip}</td><td class='{$class}'>{$status}</td></tr>\n";
}

echo <<<HTML
        </tbody>
    </table>
    <h2>运行中机器的详细信息</h2>
    <table>
        <thead>
            <tr>
                <th>运行中机器IP</th>
                <th>机器名</th>
                <th>CPU使用率</th>
                <th>硬盘使用率</th>
                <th>内存使用率</th>
                <th>网络上传速率</th>
                <th>网络下载速率</th>
            </tr>
        </thead>
        <tbody>
HTML;

// 输出每台运行中的机器的详细信息（如果为空，则输出N/A）
foreach ($hostsStatus as $ip => $info) {
    if ($info['status'] === '运行状态') {
        $class = 'running';
        $hostname = isset($info['hostname']) ? $info['hostname'] : 'N/A';
        $cpu_usage = isset($info['cpu_usage']) ? $info['cpu_usage'] : 'N/A';
        $disk_usage = isset($info['disk_usage']) ? $info['disk_usage'] : 'N/A';
        $memory_usage = isset($info['memory_usage']) ? $info['memory_usage'] : 'N/A';
        $sender_bandwidth = isset($info['sender_bandwidth']) ? $info['sender_bandwidth'] : 'N/A';
        $receiver_bandwidth = isset($info['receiver_bandwidth']) ? $info['receiver_bandwidth'] : 'N/A';

        echo "<tr class='{$class}'>";
        echo "<td>{$ip}</td>";
        echo "<td>{$hostname}</td>";
        echo "<td>{$cpu_usage}</td>";
        echo "<td>{$disk_usage}</td>";
        echo "<td>{$memory_usage}</td>";
        echo "<td>{$sender_bandwidth}</td>";
        echo "<td>{$receiver_bandwidth}</td>";
        echo "</tr>\n";
    }
}

echo <<<HTML
        </tbody>
    </table>
</body>
</html>
HTML;
?>
