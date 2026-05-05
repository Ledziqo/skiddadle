<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/smart-engine.php';
$countries = vm_countries();
$result = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    vm_verify_csrf();
    $result = ['country'=>vm_input('country'), 'visa_type'=>vm_input('visa_type'), 'date'=>vm_input('date'), 'reason'=>vm_input('reason', 1200), 'changes'=>vm_input('changes', 1200), 'contact'=>vm_input('contact')];
    $result['submission_id'] = vm_save_submission('refusal-helper', $result);
}
vm_page_start('Visa Refusal Fix for Ethiopians — Evidence & Appeal Guide', 'Fix your visa refusal as an Ethiopian applicant. Changed circumstances evidence, appeal letters and reapplication strategy.');
?>
<section class="page-hero"><span class="eyebrow">Refusal recovery</span><h1>Turn refusal reasons into an evidence plan.</h1><p>Get evidence suggestions and a CTA for a paid explanation letter or strategy review.</p></section>
<form class="card form-card" method="post"><?= vm_csrf_field() ?><div class="form-grid"><label>Country <select name="country" required><option value="">Select</option><?php foreach ($countries as $country): ?><option value="<?= vm_h($country['slug']) ?>"><?= vm_h($country['name']) ?></option><?php endforeach; ?></select></label><label>Visa type <?= vm_visa_type_select('visa_type') ?></label><label>Refusal date <input type="date" name="date"></label><label>Contact <input name="contact"></label></div><label>Refusal reasons <textarea name="reason" rows="5" required></textarea></label><label>What changed since refusal? <textarea name="changes" rows="4"></textarea></label><button class="button" type="submit">Get evidence suggestions</button></form>
<?php if ($result): $plan = vm_refusal_smart_plan($result); ?><section class="result-panel"><h2>Evidence suggestions</h2><p class="muted">Saved request ID: <?= vm_h($result['submission_id']) ?></p><?= vm_list_html($plan) ?><div class="notice"><strong>Important:</strong> do not reuse a generic refusal letter. A strong reapplication must answer the exact refusal reasons with real changed evidence.</div><a class="button" href="<?= vm_url('contact.php?topic=previous-refusal-strategy') ?>">Request previous refusal strategy</a></section><?php endif; ?>
<?php require __DIR__ . '/includes/disclaimer.php'; vm_page_end(); ?>
