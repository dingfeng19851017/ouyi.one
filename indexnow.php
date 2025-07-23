 
<?php
 
// 配置
$key = '71653317ec834123bf9ed9adabb4c93c';
$host = $_SERVER['HTTP_HOST'];
$sitemapUrl = "https://$host/sitemap.xml";

// 解析 sitemap.xml，返回所有链接数组
function parseSitemap(string $url): array {
    $content = file_get_contents($url);
    if (!$content) return [];

    $xml = simplexml_load_string($content);
    if (!$xml) return [];

    $urls = [];

    // 支持 sitemap index 和普通 sitemap
    if (isset($xml->sitemap)) {
        // sitemap index，递归读取所有 sitemap 链接
        foreach ($xml->sitemap as $smap) {
            $loc = (string)$smap->loc;
            $urls = array_merge($urls, parseSitemap($loc));
        }
    } elseif (isset($xml->url)) {
        // 普通 sitemap
        foreach ($xml->url as $urlEntry) {
            $loc = (string)$urlEntry->loc;
            if ($loc) $urls[] = $loc;
        }
    }

    return array_values(array_unique($urls));
}

// 规范链接，目录带斜杠
function normalizeUrl(string $url): string {
    $parsed = parse_url($url);
    $path = $parsed['path'] ?? '/';
    if (!preg_match('/\.[a-zA-Z0-9]+$/', $path) && substr($path, -1) !== '/') {
        $path .= '/';
    }
    $normalized = $parsed['scheme'] . '://' . $parsed['host'] . $path;
    if (!empty($parsed['query'])) $normalized .= '?' . $parsed['query'];
    if (!empty($parsed['fragment'])) $normalized .= '#' . $parsed['fragment'];
    return $normalized;
}

// 处理 Ajax 提交请求
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['url'])) {
    $url = $_POST['url'];
    $apiUrl = "https://api.indexnow.org/indexnow?url=" . urlencode($url) . "&key=$key";

    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if (curl_errno($ch)) {
        echo json_encode(['success' => false, 'error' => curl_error($ch), 'http_code' => $httpCode]);
        exit;
    }
    curl_close($ch);

    echo json_encode(['success' => ($httpCode === 200 || $httpCode === 202), 'http_code' => $httpCode, 'response' => $response]);
    exit;
}

// 主页面显示，先解析 sitemap 链接
$allLinks = parseSitemap($sitemapUrl);
$allLinks = array_map('normalizeUrl', $allLinks);
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8" />
  <title>IndexNow Sitemap 批量提交工具</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    body { font-family: Arial, sans-serif; max-width: 800px; margin: auto; padding: 20px; }
    #links { max-height: 300px; overflow-y: scroll; border: 1px solid #ccc; padding: 10px; }
    button { margin-top: 15px; padding: 10px 20px; font-size: 16px; }
    .result { margin-top: 20px; white-space: pre-wrap; background: #f9f9f9; padding: 15px; border: 1px solid #ddd; max-height: 300px; overflow-y: auto; }
  </style>
</head>
<body>

<h1>IndexNow Sitemap 批量提交工具</h1>
<p>已从 <code><?= htmlspecialchars($sitemapUrl) ?></code> 解析出 <strong><?= count($allLinks) ?></strong> 个链接。</p>

<div id="links">
  <ul>
    <?php foreach ($allLinks as $link): ?>
      <li><?= htmlspecialchars($link) ?></li>
    <?php endforeach; ?>
  </ul>
</div>

<button id="submitBtn">开始提交</button>

<div class="result" id="result"></div>

<script>
  const submitBtn = document.getElementById('submitBtn');
  const resultDiv = document.getElementById('result');
  const links = <?= json_encode($allLinks) ?>;

  submitBtn.onclick = async () => {
    submitBtn.disabled = true;
    resultDiv.textContent = '开始提交...\n\n';

    for (const url of links) {
      try {
        const response = await fetch('', {
          method: 'POST',
          headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
          body: new URLSearchParams({ url })
        });
        const data = await response.json();
        if (data.success) {
          resultDiv.textContent += `✅ 提交成功：${url}，HTTP状态码：${data.http_code}\n`;
        } else {
          resultDiv.textContent += `❌ 提交失败：${url}，错误：${data.error || '未知'}，HTTP状态码：${data.http_code}\n`;
        }
      } catch (err) {
        resultDiv.textContent += `❌ 提交异常：${url}，错误：${err.message}\n`;
      }
    }
    resultDiv.textContent += '\n全部提交完成！';
    submitBtn.disabled = false;
  };
</script>

</body>
</html>
