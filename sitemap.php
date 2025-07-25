<?php
header("Content-Type: application/xml; charset=utf-8");
 

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'];
$baseUrl = $protocol . $host;

$urls = [
    ['loc' => '/', 'lastmod' => '2025-07-25', 'changefreq' => 'daily', 'priority' => '1.0'],
    ['loc' => '/download/', 'lastmod' => '2025-07-25', 'changefreq' => 'daily', 'priority' => '0.9'],
    ['loc' => '/android/', 'lastmod' => '2025-07-25', 'changefreq' => 'weekly', 'priority' => '0.8'],
    ['loc' => '/ios/', 'lastmod' => '2025-07-25', 'changefreq' => 'weekly', 'priority' => '0.8'],
    ['loc' => '/windows/', 'lastmod' => '2025-07-25', 'changefreq' => 'weekly', 'priority' => '0.8'],
    ['loc' => '/blog/', 'lastmod' => '2025-07-25', 'changefreq' => 'weekly', 'priority' => '0.8'],
    ['loc' => '/blog/ios/', 'lastmod' => '2025-07-25', 'changefreq' => 'weekly', 'priority' => '0.8'],
];

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<urlset 
  xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
  xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 
  http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
<?php foreach ($urls as $url): ?>
  <url>
    <loc><?= htmlspecialchars($baseUrl . $url['loc']) ?></loc>
    <lastmod><?= $url['lastmod'] ?></lastmod>
    <changefreq><?= $url['changefreq'] ?></changefreq>
    <priority><?= $url['priority'] ?></priority>
  </url>
<?php endforeach; ?>
</urlset>
