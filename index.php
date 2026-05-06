<?php
require_once __DIR__ . '/includes/functions.php';
$countries = vm_countries();
$summary = vm_load_json('country_resource_summary.json');
$countriesAlpha = $countries;
usort($countriesAlpha, fn(array $a, array $b): int => strcasecmp((string)($a['name'] ?? ''), (string)($b['name'] ?? '')));
$popularCountries = $countriesAlpha;
$paid = [
  ['Check my file', 'from 999 birr', 'We find missing documents and weak points.', 'review-request.php'],
  ['Fix my letters', 'from 1,500 birr', 'Cover, sponsor, invitation, employer or refusal letters.', 'letter-generator.php'],
  ['Organize everything', 'from 3,000 birr', 'Full file order, consistency check and final fix list.', 'review-request.php'],
];
vm_page_start('Ethiopian Visa Guide 2026 — Free Country Guides, Official Forms & Document Support', 'Free visa guides for Ethiopian passport holders. Official forms, fees, requirements for UK, USA, Canada, Schengen, China & 20+ countries. Checklist generator + document review.');
?>
<section class="simple-home-hero">
  <div>
    <span class="eyebrow">VisaMenged</span>
    <h1><?= vm_h(vm_t('home_hero_title')) ?></h1>
    <p><?= vm_h(vm_t('home_hero_body')) ?></p>
  </div>
  <div class="simple-hero-actions">
    <a class="button" href="#country-guides"><?= vm_h(vm_t('browse_country_guides')) ?></a>
    <a class="button ghost" href="#paid-help"><?= vm_h(vm_t('see_paid_help')) ?></a>
  </div>
  <div class="hero-flag-cloud" aria-hidden="true">
    <i class="route-line route-one"></i>
    <i class="route-line route-two"></i>
    <i class="route-line route-three"></i>
    <b class="stamp stamp-one">VISA</b>
    <b class="stamp stamp-two">GUIDE</b>
    <b class="doc-shape"></b>
    <?php foreach (array_slice($countriesAlpha, 0, 12) as $i => $country): ?>
      <span style="--i:<?= (int)$i ?>"><?= vm_country_flag((string)($country['slug'] ?? '')) ?></span>
    <?php endforeach; ?>
  </div>
</section>

<section class="country-first" id="country-guides">
  <div class="simple-section-head">
    <div><span class="eyebrow">Country guides + official forms</span><h2><?= vm_h(vm_t('start_destination')) ?></h2><p class="muted">Sorted A-Z so destinations are easy to scan.</p></div>
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

<section class="paid-simple" id="paid-help">
  <div class="simple-section-head">
    <div><span class="eyebrow"><?= vm_h(vm_t('paid_help_nav')) ?></span><h2><?= vm_h(vm_t('paid_help_simple_title')) ?></h2><p><?= vm_h(vm_t('paid_help_simple_body')) ?></p></div>
    <a href="<?= vm_url('pricing.php') ?>"><?= vm_h(vm_t('compare_help')) ?></a>
  </div>
  <div class="paid-simple-grid">
    <?php foreach ($paid as $item): ?>
      <a href="<?= vm_url($item[3]) ?>">
        <strong><?= vm_h($item[0]) ?></strong>
        <em><?= vm_h($item[1]) ?></em>
        <span><?= vm_h($item[2]) ?></span>
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
