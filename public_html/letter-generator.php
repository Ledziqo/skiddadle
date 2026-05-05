<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/smart-engine.php';
$templates = vm_templates();
$selected = (string)($_GET['template'] ?? '');
$draft = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    vm_verify_csrf();
    $draft = ['template' => vm_input('template'), 'name' => vm_input('name'), 'country' => vm_input('country'), 'purpose' => vm_input('purpose', 800), 'dates' => vm_input('dates'), 'funding' => vm_input('funding'), 'ties' => vm_input('ties', 500), 'variant_seed' => random_int(1000, 999999)];
    $draft['submission_id'] = vm_save_submission('letter-request', $draft);
}
vm_page_start('Letter Generator');
?>
<section class="page-hero"><span class="eyebrow">Offline smart draft engine</span><h1>Generate a letter draft that does not sound copied.</h1><p>VisaMenged rotates safe wording, adapts to your answers and gives rewrite points so your final letter stays personal to your evidence.</p></section>
<section class="two-col">
  <form class="card form-card" method="post">
    <?= vm_csrf_field() ?>
    <label>Template <select name="template"><?php foreach ($templates as $template): ?><option value="<?= vm_h($template['id']) ?>" <?= $selected === ($template['id'] ?? '') ? 'selected' : '' ?>><?= vm_h($template['title']) ?></option><?php endforeach; ?></select></label>
    <label>Applicant name <input name="name" required></label>
    <label>Destination country <input name="country" required></label>
    <label>Travel dates <input name="dates" placeholder="June 10-24, 2026"></label>
    <label>Funding <input name="funding" placeholder="self-funded, sponsored by..."></label>
    <label>Purpose <textarea name="purpose" rows="4" required></textarea></label>
    <label>Ties / return evidence <textarea name="ties" rows="3"></textarea></label>
    <button class="button" type="submit">Generate unique starter draft</button>
  </form>
  <aside class="side-panel smart-ai-panel"><h2>Why drafts vary</h2><ul><li>Different openings and closings.</li><li>Country, purpose, funding and ties change the wording.</li><li>Risk notes appear only when your answers need them.</li><li>Every final letter should be edited with your real evidence.</li></ul><a class="button secondary" href="<?= vm_url('contact.php?topic=custom-letter') ?>">Request polished letter</a></aside>
</section>
<?php if ($draft):
  $smartDraft = vm_generate_letter_draft($draft);
?>
<section class="result-panel smart-letter-result">
  <div class="result-head">
    <div><span class="eyebrow"><?= vm_h($smartDraft['variation_label']) ?></span><h2><?= vm_h($smartDraft['title']) ?></h2></div>
    <form method="post" class="inline-regenerate"><?= vm_csrf_field() ?><?php foreach (['template','name','country','dates','funding','purpose','ties'] as $field): ?><input type="hidden" name="<?= vm_h($field) ?>" value="<?= vm_h($draft[$field] ?? '') ?>"><?php endforeach; ?><button class="button ghost" type="submit">Generate different version</button></form>
  </div>
  <p class="muted">Saved request ID: <?= vm_h($draft['submission_id']) ?>. This is a starter draft, not a final embassy document.</p>
  <div class="letter-preview"><?= $smartDraft['html'] ?></div>
  <div class="smart-output-grid">
    <article><h3>Make it yours before submitting</h3><?= vm_list_html($smartDraft['rewrite_tips']) ?></article>
    <?php if ($smartDraft['risk_notes']): ?><article class="notice"><h3>Risk notes</h3><?= vm_list_html($smartDraft['risk_notes']) ?></article><?php endif; ?>
  </div>
  <div class="actions"><a class="button" href="<?= vm_url('review-request.php') ?>">Request review</a><button class="button secondary" type="button" data-print-target="letter">Print draft</button><button class="button ghost" type="button" data-download-letter>Download text</button></div>
</section>
<?php endif; ?>
<?php require __DIR__ . '/includes/disclaimer.php'; vm_page_end(); ?>
