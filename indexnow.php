<?php
// IndexNow 配置
$host = $_SERVER['HTTP_HOST'];
$key = "71653317ec834123bf9ed9adabb4c93c";
$keyLocation = "https://$host/71653317ec834123bf9ed9adabb4c93c.txt";
$sitemapUrl = "https://$host/sitemap.xml";

// 读取并解析 sitemap.xml
$links = getLinksFromSitemap($sitemapUrl);
$links = array_map('normalizeUrl', $links);
$links = array_values(array_unique($links));

if (empty($links)) {
    echo "未从 sitemap 中获取到链接，请确认 sitemap 是否可访问或格式正确。";
    exit;
}

$postData = [
    "host" => $host,
    "key" => $key,
    "keyLocation" => $keyLocation,
    "urlList" => $links,
];

// 输出推送数据
echo nl2br(formatPushData($postData));

// 向 IndexNow API 提交请求
$apiUrl = "https://api.indexnow.org/IndexNow";
$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json; charset=utf-8",
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if (curl_errno($ch)) {
    echo "推送失败: " . curl_error($ch);
} else {
    echo "\nHTTP 状态码: $httpCode\n";
    echo "API 响应: $response";
}
curl_close($ch);

// ========= 函数部分 =========

// 读取 sitemap.xml 并提取 <loc> 链接
function getLinksFromSitemap(string $url): array {
    $content = @file_get_contents($url);
    if (!$content) {
        echo "无法读取 sitemap: $url\n";
        return [];
    }

    $xml = @simplexml_load_string($content);
    if (!$xml || !isset($xml->url)) {
        echo "sitemap 格式错误或无效。\n";
        return [];
    }

    $links = [];
    foreach ($xml->url as $entry) {
        if (!empty($entry->loc)) {
            $links[] = (string)$entry->loc;
        }
    }
    return $links;
}

// 标准化 URL（加尾斜杠）
function normalizeUrl(string $url): string {
    $parsed = parse_url($url);
    $path = $parsed['path'] ?? '/';

    // 如果路径不含扩展名且不以斜杠结尾，则加斜杠
    if (!preg_match('/\.[a-zA-Z0-9]+$/', $path) && substr($path, -1) !== '/') {
        $path .= '/';
    }

    $normalized = $parsed['scheme'] . '://' . $parsed['host'] . $path;

    if (!empty($parsed['query'])) {
        $normalized .= '?' . $parsed['query'];
    }
    if (!empty($parsed['fragment'])) {
        $normalized .= '#' . $parsed['fragment'];
    }

    return $normalized;
}

// 格式化推送数据输出
function formatPushData($data) {
    $output = "推送数据:\n";
    $output .= "Host: " . ($data["host"] ?? "未定义") . "\n";

    if (!empty($data["key"])) {
        $key = $data["key"];
        $keyMasked = substr($key, 0, 2) . str_repeat("*", strlen($key) - 4) . substr($key, -2);
        $output .= "Key: " . $keyMasked . "\n";
    } else {
        $output .= "Key: 未定义\n";
    }

    $output .= "Key Location: 隐藏\n";
    $output .= "URL List:\n";
    if (!empty($data["urlList"])) {
        foreach ($data["urlList"] as $url) {
            $output .= "$url\n";
        }
    } else {
        $output .= "  没有链接被推送。\n";
    }

    return $output;
}

