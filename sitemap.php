<?php
declare(strict_types=1);
require_once __DIR__ . '/includes/functions.php';
header('Content-Type: application/xml; charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
$base = 'https://visamenged.com';
$countries = vm_countries();
$lastMod = gmdate('Y-m-d');
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url><loc><?= $base ?>/</loc><lastmod><?= $lastMod ?></lastmod><changefreq>weekly</changefreq><priority>1.0</priority></url>
  <url><loc><?= $base ?>/forms.php</loc><lastmod><?= $lastMod ?></lastmod><changefreq>weekly</changefreq><priority>0.9</priority></url>
  <url><loc><?= $base ?>/checklist-generator.php</loc><lastmod><?= $lastMod ?></lastmod><changefreq>weekly</changefreq><priority>0.9</priority></url>
  <url><loc><?= $base ?>/schengen.php</loc><lastmod><?= $lastMod ?></lastmod><changefreq>weekly</changefreq><priority>0.8</priority></url>
  <url><loc><?= $base ?>/free-tools.php</loc><lastmod><?= $lastMod ?></lastmod><changefreq>monthly</changefreq><priority>0.8</priority></url>
  <url><loc><?= $base ?>/student-visa.php</loc><lastmod><?= $lastMod ?></lastmod><changefreq>weekly</changefreq><priority>0.9</priority></url>
  <url><loc><?= $base ?>/business-visa.php</loc><lastmod><?= $lastMod ?></lastmod><changefreq>weekly</changefreq><priority>0.9</priority></url>
  <url><loc><?= $base ?>/medical-visa.php</loc><lastmod><?= $lastMod ?></lastmod><changefreq>weekly</changefreq><priority>0.9</priority></url>
  <url><loc><?= $base ?>/tourist-visa.php</loc><lastmod><?= $lastMod ?></lastmod><changefreq>weekly</changefreq><priority>0.9</priority></url>
  <url><loc><?= $base ?>/work-visa.php</loc><lastmod><?= $lastMod ?></lastmod><changefreq>weekly</changefreq><priority>0.8</priority></url>
  <url><loc><?= $base ?>/review-request.php</loc><lastmod><?= $lastMod ?></lastmod><changefreq>monthly</changefreq><priority>0.7</priority></url>
  <url><loc><?= $base ?>/letter-generator.php</loc><lastmod><?= $lastMod ?></lastmod><changefreq>monthly</changefreq><priority>0.7</priority></url>
  <url><loc><?= $base ?>/previous-refusal-helper.php</loc><lastmod><?= $lastMod ?></lastmod><changefreq>monthly</changefreq><priority>0.7</priority></url>
  <url><loc><?= $base ?>/templates.php</loc><lastmod><?= $lastMod ?></lastmod><changefreq>monthly</changefreq><priority>0.6</priority></url>
  <url><loc><?= $base ?>/about.php</loc><lastmod><?= $lastMod ?></lastmod><changefreq>monthly</changefreq><priority>0.6</priority></url>
  <url><loc><?= $base ?>/contact.php</loc><lastmod><?= $lastMod ?></lastmod><changefreq>monthly</changefreq><priority>0.6</priority></url>
<?php foreach ($countries as $country):
  $slug = (string)($country['slug'] ?? '');
  if (!$slug) continue;
  $countryName = (string)($country['name'] ?? '');
  $visaTypes = ['tourist-visa', 'business-visa', 'student-visa', 'medical-visa'];
?>
  <url><loc><?= $base ?>/country.php?slug=<?= urlencode($slug) ?></loc><lastmod><?= $lastMod ?></lastmod><changefreq>weekly</changefreq><priority>0.85</priority></url>
<?php foreach ($visaTypes as $vt): ?>
  <url><loc><?= $base ?>/country.php?slug=<?= urlencode($slug) ?>&tab=<?= urlencode($vt) ?></loc><lastmod><?= $lastMod ?></lastmod><changefreq>weekly</changefreq><priority>0.75</priority></url>
<?php endforeach; ?>
<?php endforeach; ?>
</urlset>
