<?php
// redirect.php
$validTypes = ['primary', 'backup1', 'backup2'];

if (!isset($_GET['type']) || !isset($_GET['token']) || !in_array($_GET['type'], $validTypes)) {
    header("HTTP/1.0 400 Bad Request");
    die("无效请求参数");
}

// 解密URL
$decodedUrl = base64_decode($_GET['token']);

// 验证URL格式
if (!filter_var($decodedUrl, FILTER_VALIDATE_URL)) {
    header("HTTP/1.0 403 Forbidden");
    die("无效的跳转链接");
}

// 记录下载日志（可选）
$log = sprintf(
    "[%s] %s 下载 %s => %s\n",
    date('Y-m-d H:i:s'),
    $_SERVER['REMOTE_ADDR'],
    $_GET['type'],
    parse_url($decodedUrl, PHP_URL_HOST)
);
file_put_contents('download.log', $log, FILE_APPEND);

// 执行跳转
header("Location: $decodedUrl");
exit;
?>
