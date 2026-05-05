<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/smart-engine.php';
$schengen = ['germany','france','italy','netherlands','sweden','austria','belgium','schengen'];
$resources = array_values(array_filter(vm_resources(), fn($r) => in_array((string)($r['slug'] ?? ''), $schengen, true)));
$schengenSteps = vm_country_process_steps('germany', $resources);
vm_page_start('Schengen Visa for Ethiopians — Germany, France, Italy & More', 'Schengen visa guide for Ethiopian applicants. Germany, France, Italy, Netherlands, Sweden, Austria, Belgium. Official forms, fees and requirements.');
?>
<section class="page-hero warning-hero">
  <span class="eyebrow">Schengen applications from Ethiopia</span>
  <h1>Schengen tourist, family and business preparation hub.</h1>
  <p>Use the shared Schengen form, country-specific embassy/VFS/TLS resources, and apply early. For Ethiopia, many Schengen appointments and files need careful timing; treat 45 days before travel as a serious warning point.</p>
</section>
<section class="guide-section schengen-brain">
  <div class="section-head inline"><div><span class="eyebrow">Schengen Brain</span><h2>How to choose the right Schengen route</h2><p>Pick the destination country first, then prepare the same core Schengen evidence in the order below.</p></div></div>
  <div class="path-steps detailed-steps">
    <?php foreach (array_slice($schengenSteps, 0, 6) as $i => $step): ?><article><span><?= vm_h(str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT)) ?></span><h3><?= vm_h($step['title']) ?></h3><p><?= vm_h($step['body']) ?></p></article><?php endforeach; ?>
  </div>
</section>
<section class="grid cards-grid">
  <?php foreach (['germany','france','italy','netherlands','sweden','austria','belgium'] as $slug): $country = vm_country_by_slug($slug); if ($country) { $summary = []; require __DIR__ . '/includes/country-card.php'; } endforeach; ?>
</section>
<section class="feature-band">
  <h2>Schengen tracks</h2>
  <div class="steps"><article><h3>Tourist</h3><p>Itinerary, hotel booking, insurance, funding and employment/business ties.</p></article><article><h3>Family visit</h3><p>Host invitation, relationship evidence, sponsor details and accommodation proof.</p></article><article><h3>Business</h3><p>Company invitation, Ethiopian employer/business documents, meeting schedule and funding.</p></article></div>
</section>
<section class="section-head"><div><h2>Official Schengen resources</h2><p>Shared form, embassy pages, VFS and TLS links from the resource manifest.</p></div></section>
<section class="grid cards-grid compact"><?php foreach ($resources as $resource): require __DIR__ . '/includes/resource-card.php'; endforeach; ?></section>
<?php require __DIR__ . '/includes/disclaimer.php'; vm_page_end(); ?>
