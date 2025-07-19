<?php
// 固定使用 HTTPS 协议
$protocol = "https://";

// 自动获取当前主机名，带容错处理（防止 CLI 或爬虫访问时为空）
$host = $_SERVER['HTTP_HOST'] ?? 'okx.koyeb.app';

// 获取请求的 URI
$request_uri = $_SERVER['REQUEST_URI'] ?? '/';

// 规范化 URL：如果 URI 不是文件且不以斜杠结尾，则自动加斜杠
if (!preg_match('/\.[a-zA-Z0-9]+$/', $request_uri) && substr($request_uri, -1) !== '/') {
    $request_uri .= '/';
}

// 自动拼接完整域名
$domain = $protocol . $host;

// 当前页面完整 URL，统一为带斜杠的规范版本
$page_url = $domain . $request_uri;

// 通用默认页面信息（如未单独设置）
if (!isset($page_title)) {
    $page_title = "欧易OKX App官方下载";
}
if (!isset($page_description)) {
    $page_description = "欧易OKX App官方下载平台，支持iOS、安卓与电脑版设备，官方正版直装通道，畅享数字资产交易体验。";
}
if (!isset($page_keywords)) {
    $page_keywords = "欧易OKX下载, 欧易iOS下载, 欧易安卓版下载, OKX App官方入口";
}
if (!isset($page_image)) {
    $page_image = $domain . "/img/ouyi.png";
}

// 固定语言代码
$lang = "zh-CN";
?>

