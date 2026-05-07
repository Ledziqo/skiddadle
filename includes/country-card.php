<?php
$country = $country ?? [];
$summary = $summary ?? [];
$summaryRow = $summaryRow ?? $summary;
if (!$summaryRow && !empty($country['slug'])) {
    $cardResources = vm_country_resources((string)$country['slug']);
    $summaryRow = [
        'resources' => count($cardResources),
        'pdfs' => count(array_filter($cardResources, fn(array $r): bool => ($r['resource_status'] ?? '') === 'downloadable_official_pdf')),
        'portals' => count(array_filter($cardResources, fn(array $r): bool => ($r['resource_status'] ?? '') === 'official_online_portal')),
    ];
}
?>
<article class="card country-card">
  <div class="card-top">
    <span class="badge"><?= vm_h($country['region'] ?? 'Guide') ?></span>
    <span class="badge muted"><?= vm_h(str_replace('_', ' ', (string)($country['status'] ?? 'guide'))) ?></span>
  </div>
  <h3><a href="<?= vm_url('country.php?slug=' . vm_h($country['slug'] ?? '')) ?>"><?= vm_h($country['name'] ?? 'Country') ?></a></h3>
  <p><?= vm_h($country['note'] ?? 'Official resources and document support.') ?></p>
  <p class="muted"><?= (int)($summaryRow['resources'] ?? 0) ?> resources &middot; <?= (int)($summaryRow['pdfs'] ?? 0) ?> PDFs &middot; <?= (int)($summaryRow['portals'] ?? 0) ?> portals</p>
  <div class="actions">
    <a class="button secondary" href="<?= vm_url('country.php?slug=' . vm_h($country['slug'] ?? '')) ?>">Open guide</a>
  </div>
</article>
