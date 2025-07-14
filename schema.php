<?php
if (!isset($domain)) {
  $domain = "https://" . $_SERVER['HTTP_HOST'];
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
  "downloadUrl": "<?= $domain ?>/ios",
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
  "downloadUrl": "<?= $domain ?>/android",
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
  "downloadUrl": "<?= $domain ?>/windows",
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "4.7",
    "ratingCount": "8000"
  }
}
</script>
