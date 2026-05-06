<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/smart-engine.php';

$countrySlug = (string)($_GET['country'] ?? '');
$resourceGuideSlug = (string)($_GET['resource'] ?? '');
$country = vm_country_by_slug($countrySlug);
$resource = $country ? vm_resource_by_guide_slug($countrySlug, $resourceGuideSlug) : null;

if (!$country || !$resource) {
    http_response_code(404);
    vm_page_start('Resource guide not found');
    echo '<section class="page-hero"><h1>Resource guide not found.</h1><p><a href="' . vm_url('forms.php') . '">Return to official forms</a></p></section>';
    vm_page_end();
    exit;
}

$resources = vm_country_resources($countrySlug);
$typeName = (string)($resource['visa_type'] ?? 'Visa resource');
$resourceHaystack = strtolower((string)($resource['visa_type'] ?? '') . ' ' . (string)($resource['title'] ?? '') . ' ' . (string)($resource['category'] ?? '') . ' ' . (string)($resource['notes'] ?? ''));
$routeTerms = [
    'student-visa' => ['student', 'study', 'school', 'education'],
    'work-visa' => ['work', 'employment', 'employee', 'worker', 'skilled'],
    'business-visa' => ['business', 'commerce', 'meeting', 'conference', 'professional'],
    'medical-visa' => ['medical', 'treatment', 'health', 'hospital', 'patient'],
    'tourist-visa' => ['tourist', 'visitor', 'visit', 'transit', 'eta'],
];
$targetVisaSlug = '';
foreach ($routeTerms as $routeSlug => $terms) {
    foreach ($terms as $term) {
        if (str_contains($resourceHaystack, $term)) {
            $targetVisaSlug = $routeSlug;
            break 2;
        }
    }
}
$matchedVisa = null;
foreach (vm_country_visa_types_data($countrySlug) as $visaType) {
    if ($targetVisaSlug !== '' && (string)($visaType['visa_type_slug'] ?? '') === $targetVisaSlug) {
        $matchedVisa = $visaType;
        break;
    }
}

$guideTitle = (string)($resource['title'] ?? 'Official resource');
$status = (string)($resource['resource_status'] ?? '');
$resourceUrl = vm_resource_url($resource);
$officialPage = (string)($resource['official_page'] ?? $resource['url'] ?? '#');
$feeGuide = vm_fee_guide_for_country($countrySlug, (string)($country['hub'] ?? ''));
$feeRows = vm_public_visa_type_rows_for_resource($resource, (string)($country['hub'] ?? ''), 5);
$feeSourceUrl = (string)($feeGuide['fee_source_url'] ?? '');
$contactCards = vm_contact_map_cards($countrySlug, $resources, $country);
$requirements = array_slice((array)($matchedVisa['requirements'] ?? []), 0, 14);
$documents = array_slice((array)($matchedVisa['documents_needed'] ?? []), 0, 14);
$steps = (array)($matchedVisa['application_steps'] ?? []);
$refusals = array_slice((array)($matchedVisa['common_reasons_for_refusal'] ?? []), 0, 8);
$financial = (array)($matchedVisa['financial_requirements'] ?? []);
$appointment = (array)($matchedVisa['appointment_and_biometrics'] ?? []);
$validity = (array)($matchedVisa['validity_and_stay'] ?? []);
$visaFee = (string)($matchedVisa['fees']['visa_fee']['amount'] ?? '');
$processing = (string)($matchedVisa['processing_time']['standard'] ?? '');
$resourceTypeLabel = ucwords(str_replace('_', ' ', (string)($resource['category'] ?? 'official resource')));

if (!$requirements) {
    $requirements = [
        'Confirm this exact route on the official source before preparing documents.',
        'Use the official portal, PDF, checklist or requirement page linked on this guide.',
        'Prepare evidence that matches the stated purpose: travel, study, work, business, medical, transit or general visa support.',
        'Check whether an appointment, biometrics, interview, visa centre visit or online upload is required.',
        'Verify the current fee, processing time, address and working hours before paying or attending.',
    ];
}
if (!$documents) {
    $documents = [
        'Valid passport',
        'Official application form, portal confirmation or checklist for this resource',
        'Passport photo if required',
        'Purpose evidence matching the selected route',
        'Proof of funds or sponsor/employer/host evidence where required',
        'Travel, accommodation, invitation, admission, employment or medical documents where relevant',
        'Translations, legalization, insurance, medical checks or police certificates where required',
    ];
}
if (!$steps) {
    $steps = [
        'Open the official source linked on this page.',
        'Confirm the correct category for your travel purpose.',
        'Read the checklist or requirement page before filling forms.',
        'Prepare documents in the order requested by the official source.',
        'Check official fees and appointment rules.',
        'Submit through the official portal, embassy, VFS/TLS/visa centre or mission route.',
        'Track the application only through the official tracking channel.',
    ];
}
if (!$refusals) {
    $refusals = [
        'Using the wrong visa category or application route',
        'Missing official form, portal confirmation or appointment proof',
        'Weak purpose explanation or documents that do not match the stated route',
        'Unclear funding, sponsor, employer, host or medical evidence',
        'Old fee, address, checklist or appointment information from unofficial sources',
    ];
}
if (!$financial) {
    $financial = [
        'bank_statement_required' => 'Depends on the route / verify on official source',
        'minimum_amount' => 'Check official fee and financial requirement page',
        'sponsor_allowed' => 'Depends on route; sponsor evidence must be clear and consistent',
    ];
}
if (!$appointment) {
    $appointment = [
        'appointment_required' => 'Depends on the official route',
        'biometrics_required' => 'Depends on country and visa type',
        'where_to_apply_from_ethiopia' => (string)($country['name'] ?? 'This country') . ' applicants should use the official portal, embassy, mission or appointed visa centre listed in the official links.',
    ];
}
if (!$validity) {
    $validity = [
        'visa_validity' => 'Verify on official source',
        'max_stay' => 'Verify on official source',
        'entries' => 'Single / multiple / depends on route',
    ];
}
if ($visaFee === '') {
    $primaryFeeRow = $feeRows[0] ?? null;
    $visaFee = $primaryFeeRow ? ((string)$primaryFeeRow['type'] . ': ' . vm_fee_display((string)$primaryFeeRow['fee'])) : (string)($feeGuide['source_note'] ?? 'Check the official fee page before paying. Fees can change by route, duration, exchange rate and service centre.');
}
if ($processing === '') {
    $processing = 'Check the official source and appointment availability. Processing time and appointment availability are separate.';
}
$sources = array_slice(array_merge([[
    'title' => $guideTitle,
    'url' => $officialPage,
    'used_for' => 'Exact official resource for this guide',
]], (array)($matchedVisa['official_sources'] ?? [])), 0, 6);

vm_page_start($guideTitle . ' Guide');
?>
<section class="page-hero resource-guide-hero">
  <span class="eyebrow"><?= vm_h($country['name'] ?? 'Country') ?> official resource</span>
  <h1><?= vm_h($guideTitle) ?></h1>
  <p><?= vm_h((string)($resource['notes'] ?? 'Use this focused guide to understand the official resource, what to prepare, and what to verify before applying.')) ?></p>
  <div class="hero-actions">
    <a class="button" href="<?= vm_h($resourceUrl) ?>" target="_blank" rel="noopener"><?= vm_h(vm_primary_action_label($resource)) ?></a>
    <a class="button ghost" href="<?= vm_h($officialPage) ?>" target="_blank" rel="noopener">Open official source</a>
    <a class="button secondary" href="<?= vm_url('country.php?slug=' . vm_h($countrySlug) . '#official-forms') ?>">Back to <?= vm_h($country['name'] ?? 'country') ?> resources</a>
  </div>
</section>

<section class="guide-trust-strip">
  <article><strong><?= vm_h($typeName) ?></strong><span>Route</span></article>
  <article><strong><?= vm_h(vm_status_label($status)) ?></strong><span>Resource type</span></article>
  <article><strong><?= vm_h($resource['source_org'] ?? 'Official source') ?></strong><span>Source</span></article>
  <article><strong>Verify</strong><span>fees, timing and address</span></article>
</section>

<section class="guide-section">
  <div class="resource-guide-layout">
    <main class="resource-guide-main">
      <section class="resource-guide-card">
        <h2>What this page is for</h2>
        <p><?= vm_h((string)($matchedVisa['summary'] ?? 'This is the focused official guide for this resource. Read the official page first, then prepare the documents that match your exact purpose.')) ?></p>
        <div class="resource-guide-facts">
          <div><strong>Fee</strong><span><?= vm_h(vm_strip_verify($visaFee)) ?></span></div>
          <div><strong>Processing</strong><span><?= vm_h(vm_strip_verify($processing)) ?></span></div>
          <div><strong>Resource type</strong><span><?= vm_h($resourceTypeLabel) ?></span></div>
          <div><strong>Route</strong><span><?= vm_h($typeName) ?></span></div>
        </div>
      </section>

      <section class="resource-guide-card">
        <h2>Prices and fee checks</h2>
        <?php if ($feeRows): ?>
          <ul><?php foreach ($feeRows as $row): ?><li><strong><?= vm_h($row['type']) ?>:</strong> <?= vm_h(vm_fee_display((string)$row['fee'])) ?></li><?php endforeach; ?></ul>
          <p class="fee-note">Birr amounts are approximate planning conversions; pay using the official currency and fee page.</p>
        <?php else: ?>
          <p>Use the official fee page or official source before paying. Do not rely on old screenshots, agent quotes or copied fee tables.</p>
        <?php endif; ?>
        <?php if ($feeSourceUrl !== ''): ?><div class="actions" style="margin-top:12px"><a class="button ghost" href="<?= vm_h($feeSourceUrl) ?>" target="_blank" rel="noopener">Open official fee page</a></div><?php endif; ?>
      </section>

      <?php if ($requirements || $documents): ?>
      <section class="resource-guide-card resource-guide-two">
        <?php if ($requirements): ?>
        <div>
          <h2>Requirements</h2>
          <ul><?php foreach ($requirements as $item): ?><li><?= vm_h($item) ?></li><?php endforeach; ?></ul>
        </div>
        <?php endif; ?>
        <?php if ($documents): ?>
        <div>
          <h2>Documents to prepare</h2>
          <ul><?php foreach ($documents as $item): ?><li><?= vm_h($item) ?></li><?php endforeach; ?></ul>
        </div>
        <?php endif; ?>
      </section>
      <?php endif; ?>

      <?php if ($financial || $appointment || $validity): ?>
      <section class="resource-guide-card resource-guide-three">
        <?php if ($financial): ?>
        <div>
          <h2>Money proof</h2>
          <ul>
            <?php if ($financial['bank_statement_required'] ?? ''): ?><li><strong>Bank statement:</strong> <?= vm_h((string)$financial['bank_statement_required']) ?></li><?php endif; ?>
            <?php if ($financial['minimum_amount'] ?? ''): ?><li><strong>Minimum:</strong> <?= vm_h((string)$financial['minimum_amount']) ?></li><?php endif; ?>
            <?php if ($financial['sponsor_allowed'] ?? ''): ?><li><strong>Sponsor:</strong> <?= vm_h((string)$financial['sponsor_allowed']) ?></li><?php endif; ?>
          </ul>
        </div>
        <?php endif; ?>
        <?php if ($appointment): ?>
        <div>
          <h2>Appointment</h2>
          <ul>
            <?php if ($appointment['appointment_required'] ?? ''): ?><li><strong>Required:</strong> <?= vm_h((string)$appointment['appointment_required']) ?></li><?php endif; ?>
            <?php if ($appointment['biometrics_required'] ?? ''): ?><li><strong>Biometrics:</strong> <?= vm_h((string)$appointment['biometrics_required']) ?></li><?php endif; ?>
            <?php if ($appointment['where_to_apply_from_ethiopia'] ?? ''): ?><li><?= vm_h((string)$appointment['where_to_apply_from_ethiopia']) ?></li><?php endif; ?>
          </ul>
        </div>
        <?php endif; ?>
        <?php if ($validity): ?>
        <div>
          <h2>Stay rules</h2>
          <ul>
            <?php if ($validity['visa_validity'] ?? ''): ?><li><strong>Validity:</strong> <?= vm_h((string)$validity['visa_validity']) ?></li><?php endif; ?>
            <?php if ($validity['max_stay'] ?? ''): ?><li><strong>Max stay:</strong> <?= vm_h((string)$validity['max_stay']) ?></li><?php endif; ?>
            <?php if ($validity['entries'] ?? ''): ?><li><strong>Entries:</strong> <?= vm_h((string)$validity['entries']) ?></li><?php endif; ?>
          </ul>
        </div>
        <?php endif; ?>
      </section>
      <?php endif; ?>

      <?php if ($steps): ?>
      <section class="resource-guide-card">
        <h2>Application path</h2>
        <ol><?php foreach ($steps as $step): ?><li><?= vm_h($step) ?></li><?php endforeach; ?></ol>
      </section>
      <?php endif; ?>

      <?php if ($refusals): ?>
      <section class="resource-guide-card resource-guide-risk">
        <h2>Common weak spots</h2>
        <ul><?php foreach ($refusals as $item): ?><li><?= vm_h($item) ?></li><?php endforeach; ?></ul>
      </section>
      <?php endif; ?>
    </main>

    <aside class="resource-guide-side">
      <section class="resource-guide-card">
        <h2>Official links</h2>
        <div class="visa-hub-sources">
          <?php foreach ($sources as $src): ?>
            <a href="<?= vm_h((string)($src['url'] ?? '#')) ?>" target="_blank" rel="noopener"><?= vm_h((string)($src['title'] ?? 'Official link')) ?> →</a>
          <?php endforeach; ?>
        </div>
      </section>
      <section class="resource-guide-card">
        <h2>Contact / location</h2>
        <?php if ($contactCards): foreach (array_slice($contactCards, 0, 2) as $card): ?>
          <p><strong><?= vm_h($card['title']) ?></strong><br><?= vm_h($card['note']) ?></p>
          <div class="actions">
            <a class="button ghost" href="<?= vm_h($card['url']) ?>" target="_blank" rel="noopener">Contact</a>
            <?php if (($card['map_url'] ?? '') !== ''): ?><a class="button ghost" href="<?= vm_h($card['map_url']) ?>" target="_blank" rel="noopener">Map</a><?php endif; ?>
          </div>
        <?php endforeach; else: ?>
          <p><strong><?= vm_h($resource['source_org'] ?? 'Official source') ?></strong><br>Use the official source for the latest address, contact method, working hours and appointment location.</p>
          <div class="actions">
            <a class="button ghost" href="<?= vm_h($officialPage) ?>" target="_blank" rel="noopener">Official contact source</a>
          </div>
        <?php endif; ?>
      </section>
      <section class="resource-guide-card">
        <h2>Need help?</h2>
        <p>Use paid help only for the part of the file that is weak: invitation, sponsor proof, refusal history, or document organization.</p>
        <a class="button secondary" href="<?= vm_url('pricing.php') ?>">Paid help</a>
      </section>
    </aside>
  </div>
</section>

<?php require __DIR__ . '/includes/disclaimer.php'; vm_page_end(); ?>
