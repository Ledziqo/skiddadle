<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/smart-engine.php';
$countrySlug = (string)($_GET['country'] ?? 'china');
$typeSlug = (string)($_GET['type'] ?? '');
$country = vm_country_by_slug($countrySlug) ?? ['name' => ucwords(str_replace('-', ' ', $countrySlug)), 'slug' => $countrySlug];
$resources = vm_country_resources($countrySlug);
$chinaTypes = ['tourist-l'=>'Tourist L','business-m'=>'Business M','work-z'=>'Work Z','student-x1-x2'=>'Student X1/X2','family-q1-q2-s1-s2'=>'Family Q1/Q2 / S1/S2','transit-g'=>'Transit G','crew-c'=>'Crew C','journalist-j1-j2'=>'Journalist J1/J2','permanent-residence-d'=>'Permanent Residence D','talent-r'=>'Talent R'];
$publicTypeRow = $typeSlug !== '' ? vm_public_visa_type_row($countrySlug, (string)($country['hub'] ?? ''), $typeSlug) : [];
$typeName = $chinaTypes[$typeSlug] ?? (string)($publicTypeRow['type'] ?? ucwords(str_replace('-', ' ', $typeSlug ?: 'Visa Type')));
$matchedResources = vm_resources_for_visa_type($resources, $typeName);
$feeGuide = vm_fee_guide_for_country($countrySlug, (string)($country['hub'] ?? ''));
$feeSourceUrl = (string)($feeGuide['fee_source_url'] ?? '');
$contactCards = vm_contact_map_cards($countrySlug, $resources, $country);
$templates = vm_templates();
vm_page_start($typeName . ' Visa Resources');
?>
<section class="page-hero">
  <span class="eyebrow"><?= vm_h($country['name']) ?> visa type</span>
  <h1><?= vm_h($typeName) ?> public resource guide</h1>
  <p>Fees, official links, location/contact lookup, evidence tips and checklist path for Ethiopian applicants.</p>
  <div class="hero-actions">
    <a class="button" href="<?= vm_url('checklist-generator.php?country=' . vm_h($countrySlug)) ?>">Generate checklist</a>
    <?php if ($feeSourceUrl !== ''): ?><a class="button ghost" href="<?= vm_h($feeSourceUrl) ?>" target="_blank" rel="noopener">Official fee page</a><?php endif; ?>
  </div>
</section>
<?php if ($countrySlug === 'china'): ?>
<section class="tabs">
  <?php foreach ($chinaTypes as $slug => $label): ?><a class="<?= $slug === $typeSlug ? 'active' : '' ?>" href="<?= vm_url('visa.php?country=china&type=' . $slug) ?>"><?= vm_h($label) ?></a><?php endforeach; ?>
</section>
<?php endif; ?>
<section class="guide-section">
  <div class="quick-answer-card">
    <div>
      <span class="eyebrow">Public resource</span>
      <h2><?= vm_h($typeName) ?> at a glance</h2>
      <p><strong>Fee:</strong> <?= vm_h((string)($publicTypeRow['fee'] ?? 'Check official fee page')) ?></p>
      <p><strong>Prepare:</strong> <?= vm_h((string)($publicTypeRow['what_to_prepare'] ?? 'Use official requirements, then organize purpose, money and return-ties evidence.')) ?></p>
    </div>
    <div class="quick-answer-actions">
      <a class="button secondary" href="<?= vm_url('country.php?slug=' . vm_h($countrySlug)) ?>">Country guide</a>
      <a class="button ghost" href="<?= vm_url('forms.php') ?>">Official forms library</a>
    </div>
  </div>
</section>
<section class="two-col">
  <div>
    <h2>Official material and portal links</h2>
    <p class="muted">These are the closest official resources we have for this visa type. If the card shows a general portal, use it to select the exact category.</p>
    <div class="stack"><?php foreach ($matchedResources as $resource): require __DIR__ . '/includes/resource-card.php'; endforeach; ?></div>
  </div>
  <aside class="side-panel">
    <h2>Starter checklist</h2>
    <ul>
      <li>Passport validity and blank-page check.</li>
      <li>Official application portal or form listed on the embassy/visa-center site.</li>
      <li>Purpose evidence matched to <?= vm_h($typeName) ?>.</li>
      <li>Travel, accommodation, funding and Ethiopian residence/work/family ties evidence where relevant.</li>
    </ul>
    <h3>Contact and hours</h3>
    <?php foreach (array_slice($contactCards, 0, 2) as $card): ?>
      <p><strong><?= vm_h($card['title']) ?></strong><br><?= vm_h($card['note']) ?></p>
      <div class="actions">
        <a class="button ghost" href="<?= vm_h($card['url']) ?>" target="_blank" rel="noopener">Official contact</a>
        <?php if (($card['map_url'] ?? '') !== ''): ?><a class="button ghost" href="<?= vm_h($card['map_url']) ?>" target="_blank" rel="noopener">Map and hours</a><?php endif; ?>
      </div>
    <?php endforeach; ?>
    <a class="button" href="<?= vm_url('checklist-generator.php?country=' . vm_h($countrySlug)) ?>">Generate checklist</a>
  </aside>
</section>
<section class="section-head"><div><h2>VisaMenged support templates</h2></div></section>
<section class="grid cards-grid compact"><?php foreach (array_slice($templates, 0, 6) as $template): require __DIR__ . '/includes/template-card.php'; endforeach; ?></section>
<?php require __DIR__ . '/includes/disclaimer.php'; vm_page_end(); ?>
