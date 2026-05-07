<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/smart-engine.php';
$slug = (string)($_GET['slug'] ?? '');
$country = vm_country_by_slug($slug);
if (!$country) { http_response_code(404); vm_page_start('Country not found'); echo '<section class="page-hero"><h1>Country guide not found.</h1><p><a href="' . vm_url('index.php') . '">Return home</a></p></section>'; vm_page_end(); exit; }
$resources = vm_country_resources($slug);
$visaTypes = array_values(array_unique(array_filter(array_map(fn($r) => (string)($r['visa_type'] ?? ''), $resources))));
$allTemplates = vm_templates();
$templates = array_slice($allTemplates, 0, 4);
$packs = array_values(array_filter(vm_packs(), fn($p) => stripos((string)($p['country'] ?? ''), (string)$country['name']) !== false || (($country['hub'] ?? '') === 'schengen' && stripos((string)($p['country'] ?? ''), 'Schengen') !== false)));
$fallbackPacks = array_values(array_filter(vm_packs(), fn($p) => in_array((string)($p['country'] ?? ''), ['All countries', 'Multi-country'], true)));
$packs = array_slice(array_merge($packs, $fallbackPacks), 0, 4);
$resourceCount = count($resources);
$pdfCount = count(array_filter($resources, fn(array $r): bool => ($r['resource_status'] ?? '') === 'downloadable_official_pdf'));
$portalCount = count(array_filter($resources, fn(array $r): bool => ($r['resource_status'] ?? '') === 'official_online_portal'));
$providerNames = array_values(array_unique(array_filter(array_map(fn($r) => (string)($r['source_org'] ?? ''), $resources))));
$processSteps = vm_country_process_steps($slug, $resources);
$contactCards = vm_contact_map_cards($slug, $resources, $country);
$feeGuide = vm_fee_guide_for_country($slug, (string)($country['hub'] ?? ''));
$feeTypes = (array)($feeGuide['types'] ?? []);
$feeSourceUrl = (string)($feeGuide['fee_source_url'] ?? '');
$countryVisaTypes = vm_country_visa_types_data($slug);
$activeTab = (string)($_GET['tab'] ?? 'tourist-visa');
$visaTypeMap = [];
foreach ($countryVisaTypes as $cvt) {
    $key = strtolower((string)($cvt['visa_type_slug'] ?? ''));
    $visaTypeMap[$key] = $cvt;
}
$ethiopiaNotes = [
    'canada' => ['IRCC forms can be dynamic PDFs; download from the official page and use Adobe Reader when needed.', 'Prepare upload-ready filenames and keep purpose, invitation and funding evidence consistent.'],
    'united-kingdom' => ['UK applications are portal-first through GOV.UK; do not look for a fake PDF form.', 'Visitor files often need strong funding explanation and clear Ethiopia return ties.'],
    'united-states' => ['DS-160 is online-only through CEAC; save the confirmation page and keep answers consistent for interview prep.', 'The file should support your interview story, not replace it.'],
    'china' => ['Use the Addis Ababa China Visa Application Service Center pages for category requirements and sample form guidance.', 'China category choice matters: L, M, Z, X, Q/S, G and other categories need different support evidence.'],
    'germany' => ['Schengen timing matters; start early and treat appointments within 45 days of travel as risky.', 'Match itinerary, insurance, accommodation, leave letter and funding dates.'],
    'france' => ['Use France-Visas for the official flow and keep VFS/TLS/appointment steps separate from document requirements.', 'Family or host visits need invitation/accommodation evidence that matches the travel dates.'],
    'italy' => ['Schengen files should be organized by official checklist order for appointment day.', 'Hotel, itinerary, insurance and funding should tell the same short-stay story.'],
    'netherlands' => ['VFS checklists can be very practical; compare them with the embassy/government requirement page.', 'Sponsored or family visit files need host proof and clear cost responsibility.'],
    'india' => ['India eVisa and paper/center processes differ by visa type; choose the correct route before preparing documents.', 'Business and medical files should include purpose evidence from the receiving organization.'],
    'turkey' => ['Use official pre-application/appointment resources and keep supporting evidence ready for the selected category.', 'Visitor files benefit from a clear itinerary, accommodation, funding and return-ties explanation.'],
];
$countryNotes = $ethiopiaNotes[$slug] ?? ['Check whether your route is portal-first, PDF-based, appointment-based, or visa-center based before preparing documents.', 'For Ethiopian applicants, funding clarity, invitation consistency and return ties are often the areas that need the most organization.'];
$fallbackVisaRows = vm_public_visa_type_rows($slug, (string)($country['hub'] ?? ''));
vm_page_start(($country['name'] ?? 'Country') . ' Visa for Ethiopians — Fees, Forms & Requirements 2026', 'Complete ' . ($country['name'] ?? 'country') . ' visa guide for Ethiopian applicants. Official fees, required documents, processing time, common refusal reasons & application steps.');
?>
<section class="page-hero">
  <span class="eyebrow"><?= vm_h($country['region'] ?? 'Country guide') ?></span>
  <h1><span class="country-flag" style="font-size:1.2em"><?= vm_country_flag($slug) ?></span> <?= vm_h($country['name']) ?> visa resources for Ethiopian applicants</h1>
  <p><?= vm_h($country['note'] ?? 'Official resources, common visa types and support options.') ?></p>
  <div class="hero-actions"><a class="button" href="#official-forms">Official forms</a><a class="button secondary" href="<?= vm_url('checklist-generator.php?country=' . vm_h($slug)) ?>">Build checklist</a><a class="button ghost" href="<?= vm_url('review-request.php') ?>">Request file review</a></div>
</section>
<section class="guide-trust-strip">
  <article><strong><?= (int)$resourceCount ?></strong><span>public resources</span></article>
  <article><strong><?= (int)$pdfCount ?></strong><span>official PDFs</span></article>
  <article><strong><?= (int)$portalCount ?></strong><span>online portals</span></article>
  <article><strong>Verify</strong><span>before applying</span></article>
</section>
<nav class="country-page-nav" aria-label="<?= vm_h($country['name']) ?> guide sections">
  <a href="#official-forms">Official forms</a>
  <a href="#process">Steps</a>
  <a href="#free-tools">Free tools</a>
  <a href="#contact">Contact</a>
</nav>
<section class="guide-section country-quick-answer">
  <div class="quick-answer-card">
    <div>
      <span class="eyebrow">Start here</span>
      <h2>What you need to know first</h2>
      <p>Click the exact official resource you need. Each card opens a focused guide with requirements, documents, steps, official links and file risks for that route.</p>
    </div>
    <div class="quick-answer-actions">
      <a class="button" href="<?= vm_url('checklist-generator.php?country=' . vm_h($slug)) ?>">Generate checklist</a>
      <?php if ($feeSourceUrl !== ''): ?><a class="button ghost" href="<?= vm_h($feeSourceUrl) ?>" target="_blank" rel="noopener">Open official fee page</a><?php endif; ?>
    </div>
  </div>
</section>
<?php if (false): ?>
<section class="guide-section" id="visa-types">
  <div class="section-head inline"><div><h2>Visa types and guides</h2><p>Choose your visa type to see specific requirements, fees, documents and refusal risks from official sources.</p></div></div>
  <?php if ($countryVisaTypes): ?>
  <div class="country-visa-tabs">
    <?php foreach ($countryVisaTypes as $cvt):
        $tabSlug = strtolower((string)($cvt['visa_type_slug'] ?? ''));
        $tabLabel = (string)($cvt['visa_type'] ?? 'Visa');
        $isActive = $activeTab === $tabSlug;
    ?>
      <a class="<?= $isActive ? 'active' : '' ?>" href="<?= vm_url('country.php?slug=' . vm_h($slug) . '&tab=' . vm_h($tabSlug)) ?>">
        <?= vm_h($tabLabel) ?>
      </a>
    <?php endforeach; ?>
  </div>
  <?php
  $activeVisa = $visaTypeMap[$activeTab] ?? ($visaTypeMap['tourist-visa'] ?? array_values($visaTypeMap)[0] ?? null);
  if ($activeVisa):
    $vtFee = (string)($activeVisa['fees']['visa_fee']['amount'] ?? '');
    $vtFeeClean = vm_strip_verify($vtFee);
    $vtFeeHasVerify = vm_has_verify($vtFee);
    $vtProcessing = (string)($activeVisa['processing_time']['standard'] ?? '');
    $vtProcessingClean = vm_strip_verify($vtProcessing);
    $vtProcessingHasVerify = vm_has_verify($vtProcessing);
    $vtReqs = (array)($activeVisa['requirements'] ?? []);
    $vtDocs = (array)($activeVisa['documents_needed'] ?? []);
    $vtRefusal = (array)($activeVisa['common_reasons_for_refusal'] ?? []);
    $vtSteps = (array)($activeVisa['application_steps'] ?? []);
    $vtSources = array_slice((array)($activeVisa['official_sources'] ?? []), 0, 4);
    $vtPortal = (string)($activeVisa['application_portal_or_form']['url'] ?? '');
    $vtAppoint = (array)($activeVisa['appointment_and_biometrics'] ?? []);
    $vtValid = (array)($activeVisa['validity_and_stay'] ?? []);
    $vtFinancial = (array)($activeVisa['financial_requirements'] ?? []);
  ?>
  <div class="country-visa-panel">
    <div class="country-visa-panel-head">
      <h3><?= vm_h($activeVisa['visa_type'] ?? 'Visa Guide') ?></h3>
      <p><?= vm_h((string)($activeVisa['summary'] ?? '')) ?></p>
    </div>
    <div class="country-visa-grid">
      <?php if ($vtFee || $vtProcessing): ?>
      <section class="country-visa-fee-section">
        <?php if ($vtFee): ?>
        <div class="visa-hub-fee">
          <strong>Visa fee:</strong>
          <span><?= vm_h($vtFeeClean) ?></span>
          <?php if ($vtFeeHasVerify): ?><span class="verify-badge">[VERIFY]</span><?php endif; ?>
        </div>
        <?php endif; ?>
        <?php if ($vtProcessing): ?>
        <div class="visa-hub-meta">
          <span class="badge muted">Processing: <?= vm_h($vtProcessingClean) ?></span>
          <?php if ($vtProcessingHasVerify): ?><span class="verify-badge">[VERIFY]</span><?php endif; ?>
        </div>
        <?php endif; ?>
        <?php if (($activeVisa['fees']['refund_policy'] ?? '')): ?>
        <p class="muted" style="margin-top:8px"><strong>Refund:</strong> <?= vm_h(vm_strip_verify((string)$activeVisa['fees']['refund_policy'])) ?></p>
        <?php endif; ?>
      </section>
      <?php endif; ?>

      <?php if ($vtReqs): ?>
      <section>
        <h4>Requirements</h4>
        <ul><?php foreach ($vtReqs as $item): ?><li><?= vm_h($item) ?></li><?php endforeach; ?></ul>
      </section>
      <?php endif; ?>

      <?php if ($vtDocs): ?>
      <section>
        <h4>Documents needed</h4>
        <ul><?php foreach ($vtDocs as $item): ?><li><?= vm_h($item) ?></li><?php endforeach; ?></ul>
      </section>
      <?php endif; ?>

      <?php if ($vtFinancial): ?>
      <section>
        <h4>Financial requirements</h4>
        <ul>
          <?php if ($vtFinancial['bank_statement_required'] ?? ''): ?><li><strong>Bank statement:</strong> <?= vm_h((string)$vtFinancial['bank_statement_required']) ?></li><?php endif; ?>
          <?php if ($vtFinancial['minimum_amount'] ?? ''): ?><li><strong>Minimum:</strong> <?= vm_h((string)$vtFinancial['minimum_amount']) ?></li><?php endif; ?>
          <?php if ($vtFinancial['bank_statement_period'] ?? ''): ?><li><strong>Period:</strong> <?= vm_h((string)$vtFinancial['bank_statement_period']) ?></li><?php endif; ?>
          <?php if ($vtFinancial['sponsor_allowed'] ?? ''): ?><li><strong>Sponsor:</strong> <?= vm_h((string)$vtFinancial['sponsor_allowed']) ?></li><?php endif; ?>
          <?php if ($vtFinancial['notes'] ?? ''): ?><li><?= vm_h((string)$vtFinancial['notes']) ?></li><?php endif; ?>
        </ul>
      </section>
      <?php endif; ?>

      <?php if ($vtAppoint): ?>
      <section>
        <h4>Appointment & biometrics</h4>
        <ul>
          <?php if ($vtAppoint['appointment_required'] ?? ''): ?><li><strong>Appointment:</strong> <?= vm_h((string)$vtAppoint['appointment_required']) ?></li><?php endif; ?>
          <?php if ($vtAppoint['biometrics_required'] ?? ''): ?><li><strong>Biometrics:</strong> <?= vm_h((string)$vtAppoint['biometrics_required']) ?></li><?php endif; ?>
          <?php if ($vtAppoint['interview_required'] ?? ''): ?><li><strong>Interview:</strong> <?= vm_h((string)$vtAppoint['interview_required']) ?></li><?php endif; ?>
          <?php if ($vtAppoint['where_to_apply_from_ethiopia'] ?? ''): ?><li><strong>Where to apply:</strong> <?= vm_h((string)$vtAppoint['where_to_apply_from_ethiopia']) ?></li><?php endif; ?>
        </ul>
      </section>
      <?php endif; ?>

      <?php if ($vtValid): ?>
      <section>
        <h4>Validity & stay</h4>
        <ul>
          <?php if ($vtValid['visa_validity'] ?? ''): ?><li><strong>Validity:</strong> <?= vm_h((string)$vtValid['visa_validity']) ?></li><?php endif; ?>
          <?php if ($vtValid['max_stay'] ?? ''): ?><li><strong>Max stay:</strong> <?= vm_h((string)$vtValid['max_stay']) ?></li><?php endif; ?>
          <?php if ($vtValid['entries'] ?? ''): ?><li><strong>Entries:</strong> <?= vm_h((string)$vtValid['entries']) ?></li><?php endif; ?>
          <?php if ($vtValid['extension_possible'] ?? ''): ?><li><strong>Extension:</strong> <?= vm_h((string)$vtValid['extension_possible']) ?></li><?php endif; ?>
        </ul>
      </section>
      <?php endif; ?>

      <?php if ($vtSteps): ?>
      <section class="country-visa-wide">
        <h4>Application steps</h4>
        <ol><?php foreach ($vtSteps as $i => $step): ?><li><?= vm_h($step) ?></li><?php endforeach; ?></ol>
      </section>
      <?php endif; ?>

      <?php if ($vtRefusal): ?>
      <section class="country-visa-wide visa-hub-refusal">
        <h4>Common refusal reasons</h4>
        <ul><?php foreach ($vtRefusal as $item): ?><li><?= vm_h($item) ?></li><?php endforeach; ?></ul>
      </section>
      <?php endif; ?>

      <?php if ($vtSources): ?>
      <section class="country-visa-wide">
        <h4>Official sources</h4>
        <div class="visa-hub-sources">
          <?php foreach ($vtSources as $src): ?>
            <a href="<?= vm_h((string)($src['url'] ?? '#')) ?>" target="_blank" rel="noopener"><?= vm_h((string)($src['title'] ?? 'Official link')) ?> →</a>
          <?php endforeach; ?>
        </div>
      </section>
      <?php endif; ?>
    </div>
    <div class="country-visa-panel-actions">
      <?php if ($vtPortal): ?>
        <a class="button" href="<?= vm_h($vtPortal) ?>" target="_blank" rel="noopener">Apply / Check fees</a>
      <?php endif; ?>
      <a class="button secondary" href="<?= vm_url('checklist-generator.php?country=' . vm_h($slug)) ?>">Generate checklist</a>
      <a class="button ghost" href="#official-forms">Official forms for <?= vm_h($country['name']) ?></a>
    </div>
  </div>
  <?php endif; ?>
  <?php else: ?>
  <div class="country-visa-panel">
    <div class="country-visa-panel-head">
      <h3>Common visa types</h3>
      <p>Detailed per-type notes are still being built for this country. Use these starter routes, then verify the exact rules on the official source.</p>
    </div>
    <div class="fee-type-grid">
      <?php foreach ($fallbackVisaRows as $row): ?>
        <article>
          <strong><?= vm_h($row['type'] ?? 'Visa type') ?></strong>
          <p><b>Fee:</b> <?= vm_h($row['fee'] ?? 'Check official fee page') ?></p>
          <p><?= vm_h($row['what_to_prepare'] ?? 'Prepare documents according to the official checklist.') ?></p>
          <div class="actions">
            <a class="button ghost" href="<?= vm_url('visa.php?country=' . vm_h($slug) . '&type=' . vm_h($row['slug'] ?? vm_slugify((string)($row['type'] ?? 'visa')))) ?>">Open route</a>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>
  <div class="notice fee-note"><strong>Fee warning:</strong> <?= vm_h((string)($feeGuide['source_note'] ?? 'Fees can change. Verify on the official source.')) ?></div>
</section>
<?php endif; ?>
<section class="guide-section" id="process">
  <div class="section-head inline"><div><h2>Step-by-step process</h2><p>What to do, in order, before you submit. Verify final details on the official resource linked below.</p></div></div>
  <div class="path-steps detailed-steps">
    <?php foreach ($processSteps as $i => $step): ?><article><span><?= vm_h(str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT)) ?></span><h3><?= vm_h($step['title']) ?></h3><p><?= vm_h($step['body']) ?></p></article><?php endforeach; ?>
  </div>
</section>
<section class="guide-section">
  <div class="country-note-panel">
    <div><span class="eyebrow">Ethiopia applicant notes</span><h2>What to watch for in this guide</h2></div>
    <ul><?php foreach ($countryNotes as $note): ?><li><?= vm_h($note) ?></li><?php endforeach; ?></ul>
  </div>
</section>
<section class="two-col country-resource-layout" id="official-forms">
  <div>
    <h2>Official forms and links for <?= vm_h($country['name']) ?></h2>
    <p class="muted">Open these after you know your visa type. Requirement pages tell you the rule; portals are where you apply; PDFs are forms/checklists only when officially provided.</p>
    <div class="country-resource-filter" data-country-resource-filter>
      <button type="button" class="active" data-country-resource-route="">All</button>
      <button type="button" data-country-resource-route="tourist">Tourist</button>
      <button type="button" data-country-resource-route="student">Student</button>
      <button type="button" data-country-resource-route="work">Work</button>
      <button type="button" data-country-resource-route="business">Business</button>
      <button type="button" data-country-resource-route="medical">Medical</button>
    </div>
    <p class="muted country-resource-filter-count" data-country-resource-count><?= (int)$resourceCount ?> resources shown.</p>
    <div class="country-resource-card-grid" data-country-resource-grid>
      <?php foreach ($resources as $resource): require __DIR__ . '/includes/resource-card.php'; endforeach; ?>
    </div>
  </div>
  <aside class="side-panel country-at-glance">
    <h2>Country at a glance</h2>
    <dl>
      <div><dt>Status</dt><dd><?= vm_h(str_replace('_', ' ', (string)$country['status'])) ?></dd></div>
      <div><dt>Official resources</dt><dd><?= (int)$resourceCount ?> total</dd></div>
      <div><dt>PDF forms</dt><dd><?= (int)$pdfCount ?></dd></div>
      <div><dt>Online portals</dt><dd><?= (int)$portalCount ?></dd></div>
      <?php if ($providerNames): ?><div><dt>Main sources</dt><dd><?= vm_h(implode(', ', array_slice($providerNames, 0, 3))) ?></dd></div><?php endif; ?>
    </dl>
  </aside>
</section>
<section class="guide-section paid-help-clean" id="free-tools">
  <div class="section-head inline"><div><span class="eyebrow">Free tools</span><h2>Fix the part of the file that is weak.</h2><p>Choose the free tool that matches your file issue.</p></div><a href="<?= vm_url('free-tools.php') ?>">See all free tools</a></div>
  <div class="paid-help-grid">
    <?php foreach ($packs as $pack): ?>
      <article>
        <div>
          <span class="badge">Free</span>
          <h3><?= vm_h($pack['title'] ?? '') ?></h3>
          <p><?= vm_h($pack['tagline'] ?? '') ?></p>
        </div>
        <?php if (!empty($pack['includes']) && is_array($pack['includes'])): ?>
          <ul><?php foreach (array_slice($pack['includes'], 0, 3) as $included): ?><li><?= vm_h($included) ?></li><?php endforeach; ?></ul>
        <?php endif; ?>
        <a class="button secondary" href="<?= vm_url('pack.php?id=' . vm_h($pack['id'] ?? '')) ?>">Open this tool</a>
      </article>
    <?php endforeach; ?>
  </div>
</section>
<section class="guide-section" id="contact">
  <div class="section-head inline"><div><h2>Official contact and location</h2><p>Use official websites for current contact details. Map links are for directions and location lookup only.</p></div></div>
  <div class="contact-map-grid">
    <?php foreach ($contactCards as $card): ?>
      <article>
        <span class="badge"><?= vm_h($card['type']) ?></span>
        <h3><?= vm_h($card['title']) ?></h3>
        <p><?= vm_h($card['note']) ?></p>
        <div class="actions">
          <a class="button secondary" href="<?= vm_h($card['url']) ?>" target="_blank" rel="noopener">Open official site</a>
          <?php if ($card['map_url'] !== ''): ?><a class="button ghost" href="<?= vm_h($card['map_url']) ?>" target="_blank" rel="noopener">Open map pin</a><?php endif; ?>
        </div>
      </article>
    <?php endforeach; ?>
  </div>
</section>
<section class="guide-section">
  <div class="section-head inline"><div><h2>Evidence map</h2><p>Use this as the file structure before uploading or attending an appointment.</p></div></div>
  <div class="evidence-grid">
    <article><h3>Identity and form</h3><ul><li>Passport and copies</li><li>Official form or portal confirmation</li><li>Photo, fee and appointment proof</li></ul></article>
    <article><h3>Purpose</h3><ul><li>Itinerary, admission, treatment, event or meeting proof</li><li>Invitation or host details where relevant</li><li>Dates consistent across every document</li></ul></article>
    <article><h3>Money and sponsor</h3><ul><li>Bank statements and source-of-funds explanation</li><li>Sponsor letter and relationship proof</li><li>Employment, business or tax evidence</li></ul></article>
    <article><h3>Return ties</h3><ul><li>Work, business, school or family obligations</li><li>Property or continuing commitments if available</li><li>Previous travel and compliance history</li></ul></article>
  </div>
</section>
<section class="guide-section split-guide">
  <div>
    <h2>Common mistakes</h2>
    <ul class="mistake-list">
      <li>Using an old form after the official source changed.</li>
      <li>Submitting bank statements without explaining large deposits or sponsor support.</li>
      <li>Dates that do not match between invitation, itinerary, leave letter and form.</li>
      <li>Weak return-ties evidence from Ethiopia.</li>
      <li>Applying too close to travel when appointments, biometrics or corrections may take time.</li>
      <li>Ignoring previous refusal reasons instead of addressing them with new evidence.</li>
    </ul>
  </div>
  <div>
    <h2>FAQ</h2>
    <details open><summary>Is VisaMenged an official source?</summary><p>No. Use VisaMenged to organize the process, then verify final requirements on the official source.</p></details>
    <details><summary>Can VisaMenged fill forms?</summary><p>VisaMenged can help prepare information and review consistency, but applicants should verify and submit through official portals or centers.</p></details>
    <details><summary>How early should I prepare?</summary><p>Start as early as possible. For appointment-based applications, treat anything under 45 days as a timing risk.</p></details>
    <details><summary>What if I had a previous refusal?</summary><p>Do not reuse the same file unchanged. Address refusal reasons with clearer evidence and changed circumstances.</p></details>
  </div>
</section>
<?php require __DIR__ . '/includes/disclaimer.php'; vm_page_end(); ?>
