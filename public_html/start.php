<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/smart-engine.php';
$countries = vm_countries();
$result = null;
$payload = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    vm_verify_csrf();
    $payload = [
        'country' => vm_input('country'),
        'visa_type' => vm_input('visa_type'),
        'employment_status' => vm_input('employment_status'),
        'funding' => vm_input('funding'),
        'invitation' => vm_input('invitation'),
        'previous_refusal' => vm_input('previous_refusal'),
        'travel_date' => vm_input('travel_date'),
        'stuck' => vm_input('stuck', 700),
        'contact' => vm_input('contact'),
    ];
    $result = vm_assistant_brain($payload);
    $payload['assistant_result'] = ['score' => $result['score'], 'label' => $result['label']];
    $payload['submission_id'] = vm_save_submission('start-assistant', $payload);
}
vm_page_start('Start Assistant', 'Guided visa assistant for Ethiopian applicants.');
?>
<section class="page-hero assistant-hero">
  <span class="eyebrow">VisaMenged Start Assistant</span>
  <h1>Answer 8 questions. Get your visa file direction.</h1>
  <p>This is not open chat. It is a guided brain that points you to official links, likely gaps, next steps and the right support option.</p>
</section>
<section class="assistant-shell">
  <form class="assistant-chat card" method="post">
    <?= vm_csrf_field() ?>
    <div class="chat-row bot"><span>VisaMenged</span><p>Where are you applying, and what kind of visa is it?</p></div>
    <div class="assistant-fields">
      <label>Destination country <select name="country" required><option value="">Select</option><?php foreach ($countries as $country): ?><option value="<?= vm_h($country['slug']) ?>"><?= vm_h($country['name']) ?></option><?php endforeach; ?></select></label>
      <label>Visa type <?= vm_visa_type_select('visa_type') ?></label>
    </div>
    <div class="chat-row bot"><span>VisaMenged</span><p>What are the pressure points in your file?</p></div>
    <div class="assistant-fields">
      <label>Employment status <select name="employment_status"><option>Employed</option><option>Self-employed</option><option>Student</option><option>Unemployed</option><option>Retired</option></select></label>
      <label>Funding <select name="funding"><option>Self-funded</option><option>Sponsored</option><option>Employer-funded</option><option>Mixed</option></select></label>
      <label>Invitation <select name="invitation"><option>No invitation</option><option>Family/friend host</option><option>Business invitation</option><option>School/admission</option></select></label>
      <label>Previous refusal <select name="previous_refusal"><option>No</option><option>Yes</option></select></label>
      <label>Travel date <input type="date" name="travel_date"></label>
      <label>Contact optional <input name="contact" placeholder="email or Telegram"></label>
    </div>
    <div class="chat-row bot"><span>VisaMenged</span><p>What are you most stuck on?</p></div>
    <label class="full-field"><textarea name="stuck" rows="4" placeholder="Example: my bank statement is messy, I have a sponsor, invitation dates do not match, or I was refused before."></textarea></label>
    <button class="button" type="submit">Get my direction</button>
  </form>
</section>
<?php if ($result && $payload): $country = $result['country']; ?>
<section class="result-panel assistant-result">
  <div class="result-head">
    <div><span class="eyebrow">Assistant result</span><h2><?= vm_h($country['name'] ?? 'Your') ?> visa file direction</h2><p class="muted">Saved request ID: <?= vm_h($payload['submission_id']) ?></p></div>
    <div class="score-ring live-score" style="--score:<?= (int)$result['score'] ?>"><strong><?= (int)$result['score'] ?></strong><span>/100</span></div>
  </div>
  <div class="brain-grid">
    <article><h3>Start here</h3><ol><?php foreach ($result['steps'] as $step): ?><li><strong><?= vm_h($step['title']) ?>:</strong> <?= vm_h($step['body']) ?></li><?php endforeach; ?></ol></article>
    <article><h3>Likely gaps</h3><?= vm_list_html($result['missing']) ?></article>
    <article><h3>Warnings</h3><?= vm_list_html($result['warnings']) ?></article>
    <article><h3>Official links to open</h3><ul><?php foreach ($result['resources'] as $resource): ?><li><a href="<?= vm_h(vm_resource_url($resource)) ?>" target="_blank" rel="noopener"><?= vm_h($resource['title'] ?? 'Official resource') ?></a></li><?php endforeach; ?></ul></article>
  </div>
  <?php if ($result['packs']): ?><h3>Best support options</h3><div class="grid cards-grid compact assistant-pack-grid"><?php foreach ($result['packs'] as $pack): ?><article class="card pack-card"><span class="badge"><?= vm_h($pack['price'] ?? '') ?></span><h3><?= vm_h($pack['title'] ?? '') ?></h3><p><?= vm_h($pack['tagline'] ?? '') ?></p><a class="button secondary" href="<?= vm_url('pack.php?id=' . vm_h($pack['id'] ?? '')) ?>">Request service</a></article><?php endforeach; ?></div><?php endif; ?>
  <div class="actions"><a class="button" href="<?= vm_url('review-request.php') ?>">Run document pre-check</a><a class="button secondary" href="<?= vm_url('country.php?slug=' . vm_h((string)($payload['country'] ?? ''))) ?>">Open country guide</a><a class="button ghost" href="<?= vm_url('contact.php?topic=start-assistant') ?>">Ask on Telegram/contact</a></div>
  <div class="notice"><strong>Reminder:</strong> this assistant organizes preparation. Always verify final requirements on the official source before applying.</div>
</section>
<?php endif; ?>
<?php require __DIR__ . '/includes/disclaimer.php'; vm_page_end(); ?>
