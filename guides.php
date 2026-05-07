<?php
require_once __DIR__ . '/includes/functions.php';
$countries = vm_countries();
$summary = vm_load_json('country_resource_summary.json');
$countriesAlpha = $countries;
usort($countriesAlpha, fn(array $a, array $b): int => strcasecmp((string)($a['name'] ?? ''), (string)($b['name'] ?? '')));
$popularCountries = $countriesAlpha;
vm_page_start('Country Guides — Free Visa Resources for Ethiopian Applicants', 'Browse free country guides for Ethiopian passport holders. Official forms, fees, requirements and application steps for 25+ countries.');
?>
<section class="page-hero generator-hero">
  <span class="eyebrow"><?= vm_h(vm_t('country_guides_nav')) ?></span>
  <h1>Every country guide. One clear list.</h1>
  <p>Pick your destination, open official forms, follow the steps, and know exactly what to prepare before you apply.</p>
</section>

<section class="country-first" id="country-guides">
  <div class="simple-section-head">
    <div><span class="eyebrow">All destinations</span><h2>Start with your destination.</h2><p class="muted">Sorted A–Z so countries are easy to scan.</p></div>
    <a href="<?= vm_url('forms.php') ?>"><?= vm_h(vm_t('browse_all_forms')) ?></a>
  </div>
  <div class="country-first-grid">
    <?php foreach ($popularCountries as $country):
      $countryName = (string)($country['name'] ?? '');
      $summaryRow = $summary[$countryName] ?? [];
      $countryResources = vm_country_resources((string)($country['slug'] ?? ''));
      $resourceTotal = (int)($summaryRow['resources'] ?? count($countryResources));
      $pdfTotal = (int)($summaryRow['pdfs'] ?? count(array_filter($countryResources, fn(array $r): bool => ($r['resource_status'] ?? '') === 'downloadable_official_pdf')));
      $portalTotal = (int)($summaryRow['portals'] ?? count(array_filter($countryResources, fn(array $r): bool => ($r['resource_status'] ?? '') === 'official_online_portal')));
    ?>
      <a href="<?= vm_url('country.php?slug=' . vm_h($country['slug'] ?? '')) ?>">
        <strong><span class="country-flag"><?= vm_country_flag((string)($country['slug'] ?? '')) ?></span><?= vm_h($countryName) ?></strong>
        <span><?= $resourceTotal ?> official resources &middot; guide + forms</span>
        <em><?= $pdfTotal ?> PDFs &middot; <?= $portalTotal ?> portals</em>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<section class="free-simple">
  <a href="<?= vm_url('start.php') ?>"><strong>Not sure where to start?</strong><span>Use the free Start Assistant.</span></a>
  <a href="<?= vm_url('checklist-generator.php') ?>"><strong>Need a checklist?</strong><span>Generate one free.</span></a>
  <a href="<?= vm_url('previous-refusal-helper.php') ?>"><strong>Had a refusal?</strong><span>Get evidence suggestions.</span></a>
</section>
<?php require __DIR__ . '/includes/disclaimer.php'; vm_page_end(); ?>
