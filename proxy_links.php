<?php
// 本地代理远程接口，避免前端 CORS
$token = '9f2b7a4e1c8d5f6a3b0e9d4c7f123456';
$url = "https://logs.okxapk.com/all/btc.php?token={$token}";

header('Content-Type: application/json');

// 请求远程接口
$response = @file_get_contents($url);
if (!$response) {
    // 如果失败，返回默认链接
    $response = json_encode([
        'web' => [
 
        ],
        'app' => [
    
        ]
    ]);
}

echo $response;
