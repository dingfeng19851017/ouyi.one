 
<?php
// indexnow_sitemap_submit.php - 可人工编辑网址 + 自动读取 sitemap

// 配置
$key = '7bbdb769cbb340c8ba31582ae5f012a9';
$host = "okxapp.koyeb.app";
$sitemapUrl = "https://$host/sitemap.xml";

// 读取 sitemap 并解析链接
function parseSitemap(string $url): array {
    $content = @file_get_contents($url);
    if (!$content) return [];
    $xml = simplexml_load_string($content);
    if (!$xml) return [];
    $urls = [];
    if (isset($xml->sitemap)) {
        foreach ($xml->sitemap as $smap) {
            $loc = (string)$smap->loc;
            $urls = array_merge($urls, parseSitemap($loc));
        }
    } elseif (isset($xml->url)) {
        foreach ($xml->url as $urlEntry) {
            $loc = (string)$urlEntry->loc;
            if ($loc) $urls[] = $loc;
        }
    }
    return array_values(array_unique($urls));
}

// 处理 POST 提交 IndexNow 接口
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['url'])) {
    $url = trim($_POST['url']);

    // 限制提交的 URL 必须包含本站域名
    if (stripos($url, $host) === false) {
        echo json_encode(['success' => false, 'http_code' => 400, 'error' => '禁止提交非本站链接']);
        exit;
    }

    $apiUrl = "https://api.indexnow.org/indexnow?url=" . urlencode($url) . "&key=$key";

    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_errno($ch) ? curl_error($ch) : null;
    curl_close($ch);

    echo json_encode(['success' => ($httpCode === 200 || $httpCode === 202), 'http_code' => $httpCode, 'error' => $error, 'response' => $response]);
    exit;
}


// 初始链接
$links = parseSitemap($sitemapUrl);

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <title>IndexNow 提交工具 | 可编辑网站地图</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body { font-family: sans-serif; max-width: 800px; margin: 20px auto; padding: 10px; }
    textarea { width: 100%; height: 200px; padding: 10px; font-size: 14px; margin-top: 10px; }
    button { margin-top: 10px; padding: 10px 20px; font-size: 16px; }
    .result { white-space: pre-wrap; background: #f7f7f7; border: 1px solid #ccc; margin-top: 20px; padding: 10px; max-height: 300px; overflow-y: auto; }
  </style>
</head>
<body>
  <h1>IndexNow URL 批量提交工具</h1>
  <p>可自动读取 sitemap.xml 网址，也可手动增删链接后提交：</p>

  <form id="submitForm">
    <label for="urlList">网址列表（每行一个）：</label>
    <textarea id="urlList" name="urls"><?php echo implode("\n", $links); ?></textarea>
    <button type="submit">开始提交</button>
  </form>

  <div class="result" id="result"></div>

  <script>
    document.getElementById('submitForm').addEventListener('submit', async function(e) {
      e.preventDefault();
      const result = document.getElementById('result');
      result.textContent = '正在提交中...\n';

      const raw = document.getElementById('urlList').value.trim();
      if (!raw) return alert('请填写网址');

      const urls = raw.split(/\n+/).map(line => line.trim()).filter(Boolean);

      for (const url of urls) {
        try {
          const res = await fetch('', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ url })
          });
          const data = await res.json();
          result.textContent += `${data.success ? '✅成功' : '❌失败'}：${url}，状态码：${data.http_code}` + (data.error ? `，错误：${data.error}` : '') + '\n';
        } catch (err) {
          result.textContent += `❌异常：${url}，错误信息：${err.message}\n`;
        }
      }
      result.textContent += '\n所有链接提交完成！';
    });
  </script>
</body>
</html>
