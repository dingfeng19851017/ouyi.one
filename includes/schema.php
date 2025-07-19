<?php
// 统一定义域名，带 https，带主机名
if (!isset($domain)) {
    $protocol = 'https://';
    $host = $_SERVER['HTTP_HOST'] ?? 'okx.koyeb.app';
    $domain = $protocol . $host;
}

// 规范化函数：确保路径带尾斜杠
function build_url(string $path = ''): string {
    global $domain;
    $cleanPath = trim($path, '/');
    return $domain . '/' . $cleanPath . '/';
}
?>

<!-- iOS App -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "MobileApplication",
  "name": "欧易OKX App（iOS版）",
  "operatingSystem": "iOS",
  "applicationCategory": "FinanceApplication",
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "CNY"
  },
  "downloadUrl": "<?= build_url('ios') ?>",
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "4.9",
    "ratingCount": "15200"
  }
}
</script>

<!-- Android App -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "MobileApplication",
  "name": "欧易OKX App（Android版）",
  "operatingSystem": "Android",
  "applicationCategory": "FinanceApplication",
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "CNY"
  },
  "downloadUrl": "<?= build_url('android') ?>",
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "4.8",
    "ratingCount": "13000"
  }
}
</script>

<!-- Windows App -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "欧易OKX电脑版",
  "operatingSystem": "Windows",
  "applicationCategory": "FinanceApplication",
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "CNY"
  },
  "downloadUrl": "<?= build_url('windows') ?>",
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "4.7",
    "ratingCount": "8000"
  }
}
</script>
