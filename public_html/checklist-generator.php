<?php
require_once __DIR__ . '/includes/functions.php';
$countries = vm_countries();
$selectedCountry = (string)($_GET['country'] ?? '');
$result = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    vm_verify_csrf();
    $payload = [
        'country' => vm_input('country'),
        'visa_type' => vm_input('visa_type'),
        'citizenship' => vm_input('citizenship'),
        'applying_from' => vm_input('applying_from'),
        'city' => vm_input('city'),
        'employment_status' => vm_input('employment_status'),
        'funding' => vm_input('funding'),
        'purpose' => vm_input('purpose', 800),
        'invitation' => vm_input('invitation'),
        'previous_refusal' => vm_input('previous_refusal'),
        'family_minor' => vm_input('family_minor'),
        'travel_date' => vm_input('travel_date'),
        'contact' => vm_input('contact'),
    ];
    $payload['submission_id'] = vm_save_submission('checklist', $payload);
    $result = $payload;
}
vm_page_start('Free Visa Checklist Generator for Ethiopians', 'Generate a free visa checklist for Ethiopian applicants. Documents, forms, fees and requirements organized by country and visa type.');
?>
<section class="page-hero generator-hero"><span class="eyebrow">Smarter no-login generator</span><h1>Build a visa checklist that reacts to your file.</h1><p>Answer a few questions and VisaMenged will organize official resources, evidence groups, timing warnings, refusal risks and support templates around your situation.</p></section>
<section class="two-col">
  <form class="card form-card" method="post" action="<?= vm_url('checklist-generator.php') ?>">
    <?= vm_csrf_field() ?>
    <div class="form-grid">
      <label>Destination country <select name="country" required><option value="">Select</option><?php foreach ($countries as $country): ?><option value="<?= vm_h($country['slug']) ?>" <?= $selectedCountry === ($country['slug'] ?? '') ? 'selected' : '' ?>><?= vm_h($country['name']) ?></option><?php endforeach; ?></select></label>
      <label>Visa type <?= vm_visa_type_select('visa_type') ?></label>
      <label>Citizenship <input name="citizenship" value="Ethiopian"></label>
      <label>Applying from <input name="applying_from" value="Ethiopia"></label>
      <label>City <input name="city" placeholder="Addis Ababa"></label>
      <label>Employment status <select name="employment_status"><option>Employed</option><option>Self-employed</option><option>Student</option><option>Unemployed</option><option>Retired</option></select></label>
      <label>Funding <select name="funding"><option>Self-funded</option><option>Sponsored</option><option>Employer-funded</option><option>Mixed</option></select></label>
      <label>Invitation <select name="invitation"><option>No invitation</option><option>Family/friend host</option><option>Business invitation</option><option>School/admission</option></select></label>
      <label>Previous refusal <select name="previous_refusal"><option>No</option><option>Yes</option></select></label>
      <label>Family/minor <select name="family_minor"><option>No minor</option><option>Minor applicant</option><option>Travelling with family</option></select></label>
      <label>Travel date <input type="date" name="travel_date"></label>
      <label>Contact <input name="contact" placeholder="phone or email"></label>
    </div>
    <label>Purpose <textarea name="purpose" rows="4" placeholder="Short reason for travel"></textarea></label>
    <button class="button" type="submit">Generate smarter checklist</button>
  </form>
  <aside class="side-panel">
    <h2>What makes it smarter</h2>
    <ul>
      <li>Matches official resources to the selected country.</li>
      <li>Separates must-have documents from situation-based evidence.</li>
      <li>Flags timing, invitation, sponsor, minor and refusal issues.</li>
      <li>Suggests the most relevant VisaMenged templates.</li>
    </ul>
  </aside>
</section>
<?php if ($result):
    $countryResources = vm_country_resources($result['country']);
    $countryInfo = vm_country_by_slug($result['country']);
    $countryName = (string)($countryInfo['name'] ?? ucwords(str_replace('-', ' ', $result['country'])));
    $visaType = strtolower($result['visa_type']);
    $warnings = [];
    if ($result['travel_date'] !== '') {
        $days = (int)floor((strtotime($result['travel_date']) - strtotime(date('Y-m-d'))) / 86400);
        if ($days < 45) {
            $warnings[] = 'Your travel date is less than 45 days away. Appointment availability, biometrics, courier time and missing evidence can become serious constraints.';
        }
    }
    if (strtolower($result['previous_refusal']) === 'yes') {
        $warnings[] = 'Previous refusal selected: prepare an evidence-based explanation and show what changed before reapplying.';
    }
    if (str_contains(strtolower($result['funding']), 'sponsor')) {
        $warnings[] = 'Sponsored file: connect the sponsor relationship, source of funds, bank evidence and sponsor statement clearly.';
    }
    if ($result['invitation'] !== 'No invitation') {
        $warnings[] = 'Invitation selected: include inviter ID/status, address, relationship or business purpose, dates and cost responsibility.';
    }
    if ($result['family_minor'] !== 'No minor') {
        $warnings[] = 'Minor/family travel selected: check consent, birth certificate, parent ID and custody evidence requirements.';
    }
    $score = 86;
    $fixPlan = [];
    $strengths = ['Official country resources are attached to this checklist.', 'Your evidence is separated into file sections instead of one messy list.'];
    if ($result['purpose'] === '') { $score -= 12; $fixPlan[] = 'Write a clear purpose summary so your itinerary, invitation and documents tell the same story.'; }
    if ($result['travel_date'] === '') { $score -= 5; $fixPlan[] = 'Add a travel date or date range so timing and appointment risk can be checked.'; }
    if ($result['travel_date'] !== '' && isset($days) && $days < 45) { $score -= 14; $fixPlan[] = 'Treat timing as urgent: prepare missing documents before booking or submitting.'; }
    if (strtolower($result['previous_refusal']) === 'yes') { $score -= 18; $fixPlan[] = 'Do not reapply with the same evidence. Build a refusal explanation and changed-circumstances section.'; }
    if (str_contains(strtolower($result['funding']), 'sponsor')) { $score -= 8; $fixPlan[] = 'Create a sponsor proof bundle: relationship, bank evidence, source of funds and sponsor statement draft.'; }
    if ($result['invitation'] !== 'No invitation') { $score -= 5; $fixPlan[] = 'Check invitation consistency: inviter details, dates, address, purpose and cost responsibility.'; }
    if ($result['employment_status'] === 'Unemployed') { $score -= 10; $fixPlan[] = 'Strengthen return ties and funding evidence because unemployed files need clearer context.'; }
    if ($result['contact'] === '') { $score -= 3; $fixPlan[] = 'Add a contact method if you want VisaMenged to follow up on a review or service request.'; }
    $score = max(22, min(96, $score));
    $scoreLabel = $score >= 82 ? 'Strong start' : ($score >= 65 ? 'Needs cleanup' : 'High-risk gaps');
    if (!$fixPlan) {
        $fixPlan[] = 'Use the official links, print this checklist, and do a final consistency check before submission.';
    }
    $recommendedPackIds = ['quick-file-audit'];
    if (strtolower($result['previous_refusal']) === 'yes') { $recommendedPackIds[] = 'previous-refusal-recovery'; }
    if (str_contains(strtolower($result['funding']), 'sponsor')) { $recommendedPackIds[] = 'sponsor-proof-pack'; }
    if (str_contains($visaType, 'student') || str_contains($visaType, 'study')) { $recommendedPackIds[] = 'student-visa-support-pack'; }
    if (str_contains($visaType, 'business')) { $recommendedPackIds[] = 'business-travel-pack'; }
    if ($result['country'] === 'canada') { $recommendedPackIds[] = 'canada-ircc-visitor-prep'; }
    if ($result['country'] === 'united-kingdom') { $recommendedPackIds[] = 'uk-visitor-file-builder'; }
    if ($result['country'] === 'united-states') { $recommendedPackIds[] = 'usa-ds160-interview-prep'; }
    if ($result['country'] === 'china') { $recommendedPackIds[] = 'china-application-center-prep'; }
    if (($countryInfo['hub'] ?? '') === 'schengen') { $recommendedPackIds[] = 'schengen-appointment-file-pack'; }
    $recommendedPackIds[] = 'embassy-ready-letter-bundle';
    $recommendedPacks = [];
    foreach (array_unique($recommendedPackIds) as $pid) {
        $pack = vm_pack_by_id($pid);
        if ($pack) { $recommendedPacks[] = $pack; }
        if (count($recommendedPacks) >= 3) { break; }
    }
    $core = ['Valid passport and copies of identity/previous visa pages.', 'Official application form, portal confirmation or visa-center application record.', 'Photo that follows the official size/background rules.', 'Appointment, fee, biometrics or visa-center receipt where required.'];
    $purpose = ['Clear purpose letter or cover note matching your visa type.', 'Travel itinerary, booking plan or date explanation.', 'Accommodation proof or host/invitation evidence where relevant.'];
    if (str_contains($visaType, 'student') || str_contains($visaType, 'study')) { $purpose[] = 'Admission letter, tuition/payment evidence and study funding plan.'; }
    if (str_contains($visaType, 'business')) { $purpose[] = 'Business invitation, Ethiopian company letter, meeting agenda and trade/license evidence.'; }
    if (str_contains($visaType, 'work')) { $purpose[] = 'Work permit, employment contract, employer invitation and qualification evidence.'; }
    $funding = ['Bank statements and source-of-funds explanation.', 'Employment letter, salary evidence, business license or tax documents depending on status.'];
    $ties = ['Ethiopia return evidence: job, business, school, family, property, obligations or continuing commitments.', 'Previous travel history and compliance evidence if available.'];
    $matchedTemplates = array_values(array_filter(vm_templates(), function (array $template) use ($result, $visaType): bool {
        $hay = strtolower(($template['title'] ?? '') . ' ' . ($template['description'] ?? '') . ' ' . implode(' ', (array)($template['usedFor'] ?? [])));
        return str_contains($hay, $visaType) || str_contains($hay, strtolower($result['funding'])) || str_contains($hay, 'cover') || (strtolower($result['previous_refusal']) === 'yes' && str_contains($hay, 'refusal'));
    }));
    $matchedTemplates = array_slice($matchedTemplates, 0, 4);
?>
<section class="result-panel smart-result">
  <div class="result-head">
    <div>
      <span class="eyebrow">Generated for <?= vm_h($countryName) ?></span>
      <h2>Your smarter starter checklist</h2>
    </div>
    <a class="button secondary" href="javascript:window.print()">Print checklist</a>
  </div>
  <p class="muted">Saved request ID: <?= vm_h($result['submission_id']) ?></p>
  <div class="readiness-panel">
    <div class="score-ring live-score" style="--score:<?= (int)$score ?>">
      <strong><?= (int)$score ?></strong><span>/100</span>
    </div>
    <div>
      <h3>Visa File Readiness: <?= vm_h($scoreLabel) ?></h3>
      <p>This score is not a visa prediction. It is a preparation score showing how organized and explainable your file looks from the answers provided.</p>
      <div class="score-bars">
        <span style="--value:<?= $result['purpose'] === '' ? 48 : 82 ?>">Purpose story</span>
        <span style="--value:<?= str_contains(strtolower($result['funding']), 'sponsor') ? 62 : 78 ?>">Funding clarity</span>
        <span style="--value:<?= $result['employment_status'] === 'Unemployed' ? 50 : 76 ?>">Return ties</span>
        <span style="--value:<?= strtolower($result['previous_refusal']) === 'yes' ? 42 : 84 ?>">Risk control</span>
      </div>
    </div>
  </div>
  <div class="fix-plan">
    <article><h3>What looks good</h3><ul><?php foreach ($strengths as $item): ?><li><?= vm_h($item) ?></li><?php endforeach; ?></ul></article>
    <article><h3>What to fix next</h3><ul><?php foreach (array_slice($fixPlan, 0, 5) as $item): ?><li><?= vm_h($item) ?></li><?php endforeach; ?></ul></article>
  </div>
  <?php if ($warnings): ?><div class="warning-stack"><?php foreach ($warnings as $warning): ?><p class="warning-pill"><?= vm_h($warning) ?></p><?php endforeach; ?></div><?php endif; ?>
  <div class="checklist-grid">
    <article><h3>1. Official application items</h3><ul><?php foreach ($core as $item): ?><li><?= vm_h($item) ?></li><?php endforeach; ?></ul></article>
    <article><h3>2. Purpose evidence</h3><ul><?php foreach ($purpose as $item): ?><li><?= vm_h($item) ?></li><?php endforeach; ?></ul></article>
    <article><h3>3. Funding and sponsor proof</h3><ul><?php foreach ($funding as $item): ?><li><?= vm_h($item) ?></li><?php endforeach; ?></ul></article>
    <article><h3>4. Return ties and risk control</h3><ul><?php foreach ($ties as $item): ?><li><?= vm_h($item) ?></li><?php endforeach; ?></ul></article>
  </div>
  <h3>Official resources to use</h3>
  <div class="grid cards-grid compact"><?php foreach (array_slice($countryResources, 0, 4) as $resource): require __DIR__ . '/includes/resource-card.php'; endforeach; ?></div>
  <?php if ($matchedTemplates): ?><h3>Suggested support templates</h3><div class="grid cards-grid compact"><?php foreach ($matchedTemplates as $template): require __DIR__ . '/includes/template-card.php'; endforeach; ?></div><?php endif; ?>
  <section class="upgrade-strip">
    <div>
      <span class="eyebrow">Paid fix path</span>
      <h3>Want VisaMenged to fix the weak points?</h3>
      <p>Turn this score into an organized file audit, polished letters, sponsor proof bundle or previous-refusal recovery plan.</p>
    </div>
    <a class="button" href="<?= vm_url('pricing.php') ?>">See paid support</a>
  </section>
  <?php if ($recommendedPacks): ?><h3>Recommended services for this score</h3><div class="grid cards-grid compact"><?php foreach ($recommendedPacks as $pack): ?><article class="card pack-card"><div class="card-top"><span class="badge"><?= vm_h($pack['country'] ?? '') ?></span><span class="badge muted"><?= vm_h($pack['price'] ?? '') ?></span></div><h3><?= vm_h($pack['title'] ?? '') ?></h3><p><?= vm_h($pack['tagline'] ?? '') ?></p><a class="button secondary" href="<?= vm_url('pack.php?id=' . vm_h($pack['id'] ?? '')) ?>">Request service</a></article><?php endforeach; ?></div><?php endif; ?>
  <div class="actions"><a class="button" href="<?= vm_url('review-request.php') ?>">Request document review</a><a class="button secondary" href="<?= vm_url('templates.php') ?>">Find support templates</a></div>
</section>
<?php endif; ?>
<?php require __DIR__ . '/includes/disclaimer.php'; vm_page_end(); ?>
