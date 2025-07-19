<?php
// IndexNow 配置
$host = "okx.koyeb.app";
$key = "b556315d51654bab91de0bcf8a43b2c0";
$keyLocation = "https://okx.koyeb.app/b556315d51654bab91de0bcf8a43b2c0.txt";

// 站点首页 URL
$siteUrl = "https://$host/";

// 获取网站链接并规范尾斜杠
$links = getLinksFromWebsite($siteUrl);

// 规范所有链接，确保目录链接带尾斜杠
$links = array_map('normalizeUrl', $links);

// 只保留唯一连续键名
$links = array_values(array_unique($links));

// 检查链接数组是否为空
if (empty($links)) {
    echo "未找到可推送的链接。";
    exit;
}

$postData = [
    "host" => $host,
    "key" => $key,
    "keyLocation" => $keyLocation,
    "urlList" => $links,
];

// 美化输出函数
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

echo nl2br(formatPushData($postData));

// 发送请求
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
    echo "HTTP 状态码: $httpCode\n";
    echo "API 响应: " . $response;
}
curl_close($ch);


// 规范化 URL，确保目录链接带尾斜杠，忽略带文件扩展名的 URL
function normalizeUrl(string $url): string {
    $parsed = parse_url($url);
    $path = $parsed['path'] ?? '/';

    // 如果路径不含文件扩展名且不以斜杠结尾，则加斜杠
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

// 获取页面所有链接
function getLinksFromWebsite(string $url): array {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0");

    $html = curl_exec($ch);
    curl_close($ch);

    if (!$html) {
        echo "无法获取 HTML 内容，请检查目标网址。";
        return [];
    }

    $dom = new DOMDocument;
    @$dom->loadHTML($html);

    $xpath = new DOMXPath($dom);
    $hrefs = $xpath->query("//a[@href]");

    $links = [];
    foreach ($hrefs as $href) {
        $link = trim($href->getAttribute('href'));

        // 排除空和锚点链接
        if ($link === '' || strpos($link, '#') === 0) {
            continue;
        }

        // 转为绝对链接
        if (!preg_match("/^(http|https):\/\//", $link)) {
            $link = rtrim($url, '/') . '/' . ltrim($link, '/');
        }

        // 过滤有效 URL 且属于本站域名
        global $host;
        if (filter_var($link, FILTER_VALIDATE_URL) && strpos($link, "https://$host") === 0) {
            $links[] = $link;
        }
    }

    return array_values(array_unique($links));
}

