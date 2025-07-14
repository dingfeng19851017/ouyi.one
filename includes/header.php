<?php include('config.php'); ?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="content-language" content="<?= htmlspecialchars($lang) ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <title><?= htmlspecialchars($page_title) ?></title>
  <meta name="description" content="<?= htmlspecialchars($page_description) ?>" />
  <meta name="keywords" content="<?= htmlspecialchars($page_keywords) ?>" />
  <meta name="robots" content="index, follow" />
  <meta name="author" content="欧易APP下载站" />
  <meta name="googlebot" content="index, follow" />
  <meta name="bingbot" content="index, follow" />
  <link rel="canonical" href="<?= htmlspecialchars($page_url) ?>" />
  <meta property="og:title" content="<?= htmlspecialchars($page_title) ?>" />
  <meta property="og:description" content="<?= htmlspecialchars($page_description) ?>" />
  <meta property="og:type" content="website" />
  <meta property="og:url" content="<?= htmlspecialchars($page_url) ?>" />
  <meta property="og:image" content="<?= htmlspecialchars($page_image) ?>" />
  <meta name="twitter:card" content="summary_large_image" />
  <meta name="twitter:title" content="<?= htmlspecialchars($page_title) ?>" />
  <meta name="twitter:description" content="<?= htmlspecialchars($page_description) ?>" />
  <meta name="twitter:image" content="<?= htmlspecialchars($page_image) ?>" />
  <meta name="360-site-verification" content="2b98038cf4e5838b7d684629ae2d33c7" />
  <link rel="icon" href="<?= htmlspecialchars($domain) ?>/favicon.ico" type="image/x-icon" />
  <link rel="apple-touch-icon" href="<?= htmlspecialchars($domain) ?>/img/apple-touch-icon.png" />
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="<?= htmlspecialchars($domain) ?>/css/app.css" />
  <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/schema.php'; ?>
</head>

<body class="min-h-screen"  >
    <div class="px-4 py-4 border-b border-gray-800">
        <!-- Navigation -->
        <nav class="max-w-6xl mx-auto flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0" aria-label="主导航">
            <a href="/" title="欧易下载平台" class="flex items-center space-x-2 hover:opacity-80 transition">
              <img src="/logo.png" alt="欧易 OKX App 下载中心" class="w-10 h-10" />
            </a>
            <div class="flex flex-wrap items-center justify-center gap-4 sm:gap-8">
                <a href="/" class="hover:text-[#00aaff]">首页</a>
                <a href="/blog/ios/" class="hover:text-[#00aaff]">苹果指南</a>
                <a href="/blog/" class="hover:text-[#00aaff]">安卓指南</a>
                <div class="relative inline-block text-left" id="download-menu">
                    <button id="download-link" aria-haspopup="true" aria-expanded="false" aria-controls="dropdown-menu" class="inline-flex justify-center w-full rounded-md border border-transparent bg-[#0077b3] px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-[#0099ff] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-cyan-400 transition">
                        立即下载 ▾
                    </button>
                    <div id="dropdown-menu" role="menu" aria-label="下载选项" class="absolute right-0 mt-2 w-40 origin-top-right rounded-md bg-gray-900 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none hidden z-50">
                        <a href="<?= $domain ?>/android/" title="欧易Android版App下载" class="block px-4 py-2 text-sm text-gray-100 hover:bg-cyan-600 hover:text-white transition" role="menuitem" tabindex="-1">安卓版</a>
                        <a href="<?= $domain ?>/ios/" title="欧易苹果iOS版下载" class="block px-4 py-2 text-sm text-gray-100 hover:bg-cyan-600 hover:text-white transition" role="menuitem" tabindex="-1">苹果版</a>
                        <a href="<?= $domain ?>/windows/" title="欧易电脑版下载入口" class="block px-4 py-2 text-sm text-gray-100 hover:bg-cyan-600 hover:text-white transition" role="menuitem" tabindex="-1">电脑版</a>
                    </div>
                </div>
            </div>
            <a id="installLink" href="#" class="inline-block px-8 py-3 text-white font-semibold text-lg rounded-xl border border-cyan-400 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 hover:scale-105 transition-transform shadow-lg hidden">
                下载客户端
            </a>
        </nav>
    </div>
