<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/smart-engine.php';
$saved = null; $error = null; $brain = null; $payload = null;
$countries = vm_countries();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    vm_verify_csrf();
    $payload = [
        'name'=>vm_input('name'),
        'contact'=>vm_input('contact'),
        'country'=>vm_input('country'),
        'visa_type'=>vm_input('visa_type'),
        'employment_status'=>vm_input('employment_status'),
        'funding'=>vm_input('funding'),
        'invitation'=>vm_input('invitation'),
        'previous_refusal'=>vm_input('previous_refusal'),
        'travel_date'=>vm_input('travel_date'),
        'notes'=>vm_input('notes', 1200),
        'uploads'=>[],
        'upload_groups'=>[]
    ];
    $allowed = ['pdf'=>'application/pdf','jpg'=>'image/jpeg','jpeg'=>'image/jpeg','png'=>'image/png','doc'=>'application/msword','docx'=>'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    $uploadDir = __DIR__ . '/storage/uploads';
    if (!is_dir($uploadDir)) { mkdir($uploadDir, 0755, true); }
    $uploadGroups = ['passport'=>'Passport', 'form'=>'Application form/portal confirmation', 'money'=>'Bank/funding proof', 'work'=>'Employment/business proof', 'invitation'=>'Invitation/admission/host proof', 'refusal'=>'Previous refusal letter', 'other'=>'Other documents'];
    foreach ($uploadGroups as $field => $label) {
        $fileKey = 'documents_' . $field;
        foreach ($_FILES[$fileKey]['name'] ?? [] as $i => $name) {
            if (($_FILES[$fileKey]['error'][$i] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) { continue; }
            if (($_FILES[$fileKey]['error'][$i] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK || ($_FILES[$fileKey]['size'][$i] ?? 0) > 5 * 1024 * 1024) { $error = 'Each upload must be 5MB or smaller.'; break 2; }
            $ext = strtolower(pathinfo((string)$name, PATHINFO_EXTENSION));
            $tmp = (string)($_FILES[$fileKey]['tmp_name'][$i] ?? '');
            $mime = is_file($tmp) ? (mime_content_type($tmp) ?: '') : '';
            if (!isset($allowed[$ext]) || ($mime && $mime !== $allowed[$ext] && !($ext === 'docx' && str_contains($mime, 'zip')))) { $error = 'Only PDF, JPG, PNG, DOC, and DOCX files are allowed.'; break 2; }
            $safe = gmdate('YmdHis') . '-' . $field . '-' . bin2hex(random_bytes(4)) . '.' . $ext;
            if (move_uploaded_file($tmp, $uploadDir . '/' . $safe)) {
                $payload['uploads'][] = $safe;
                $payload['upload_groups'][$field] = ($payload['upload_groups'][$field] ?? 0) + 1;
            }
        }
    }
    if (!$error) {
        $brain = vm_file_brain_analyze($payload);
        $payload['smart_precheck'] = $brain;
        $saved = vm_save_submission('review-request', $payload);
    }
}
vm_page_start('Visa Document Review for Ethiopians — File Check', 'Submit your visa file for professional review. Missing documents, weak points, refusal risks and fix recommendations for Ethiopian applicants.');
?>
<section class="page-hero pricing-hero"><span class="eyebrow">Smart file pre-check</span><h1>Let VisaMenged find the weak points before you submit.</h1><p>Answer a few risk questions and upload copies for review. The smart pre-check flags likely gaps instantly, then we can do a deeper human audit of the actual documents.</p><div class="hero-actions"><a class="button" href="#review-form">Start smart pre-check</a><a class="button secondary" href="<?= vm_url('pack.php?id=quick-file-audit') ?>">See quick audit</a></div></section>
<section class="review-value">
  <article><h3>What we check</h3><p>Official checklist match, purpose evidence, funding story, sponsor/invitation proof, return ties and refusal-risk signals.</p></article>
  <article><h3>What you receive</h3><p>A practical fix list: missing documents, unclear statements, consistency problems and the next steps to clean the file.</p></article>
  <article><h3>What we do not do</h3><p>We do not guarantee approval, make legal decisions, submit for you, or replace official embassy/government requirements.</p></article>
</section>
<?php if ($error): ?><section class="notice risk"><?= vm_h($error) ?></section><?php endif; ?>
<?php if ($saved): ?>
<section class="notice success">
  <strong>Request saved.</strong>
  <p>Your review request ID is <strong><?= vm_h($saved) ?></strong>.</p>
  <p>We will review your documents and contact you within 24-48 hours.</p>
  <div class="hero-actions" style="margin-top:14px">
    <a class="button" href="<?= vm_url('pricing.php') ?>">View other services</a>
    <a class="button secondary" href="<?= vm_url('index.php') ?>">Back to home</a>
  </div>
</section>
<?php if ($brain): ?>
<section class="result-panel smart-file-brain">
  <div class="result-head">
    <div><span class="eyebrow">Instant pre-check</span><h2>Your file brain result: <?= vm_h($brain['label']) ?></h2></div>
    <div class="score-ring live-score" style="--score:<?= (int)$brain['score'] ?>"><strong><?= (int)$brain['score'] ?></strong><span>/100</span></div>
  </div>
  <p class="muted"><?= vm_h($brain['review_pitch']) ?></p>
  <div class="brain-grid">
    <article><h3>Fix first</h3><?= vm_list_html($brain['must_fix']) ?></article>
    <article><h3>Likely missing</h3><?= vm_list_html($brain['likely_missing']) ?></article>
    <article><h3>Consistency check</h3><?= vm_list_html($brain['consistency_checks']) ?></article>
    <article><h3>What looked okay</h3><?= vm_list_html($brain['strengths'] ?: ['The request was saved and can now be reviewed with your uploaded documents.']) ?></article>
  </div>
  <div class="notice"><strong>No approval prediction:</strong> this score only checks preparation quality. Official visa decisions belong to the embassy, government, VFS/TLS or visa center.</div>
  <div class="actions"><a class="button" href="<?= vm_url('pack.php?id=quick-file-audit') ?>">Request full audit</a><button class="button secondary" type="button" data-print-target="brain">Print pre-check</button></div>
</section>
<?php endif; ?>
<?php else: ?>
<form class="card form-card" id="review-form" method="post" enctype="multipart/form-data">
  <?= vm_csrf_field() ?>
  <div class="form-grid">
    <label>Name <input name="name" required></label>
    <label>Contact <input name="contact" required></label>
    <label>Destination country <select name="country" required><option value="">Select</option><?php foreach ($countries as $country): ?><option value="<?= vm_h($country['slug']) ?>"><?= vm_h($country['name']) ?></option><?php endforeach; ?></select></label>
    <label>Visa type <?= vm_visa_type_select('visa_type') ?></label>
    <label>Employment status <select name="employment_status"><option>Employed</option><option>Self-employed</option><option>Student</option><option>Unemployed</option><option>Retired</option></select></label>
    <label>Funding <select name="funding"><option>Self-funded</option><option>Sponsored</option><option>Employer-funded</option><option>Mixed</option></select></label>
    <label>Invitation <select name="invitation"><option>No invitation</option><option>Family/friend host</option><option>Business invitation</option><option>School/admission</option></select></label>
    <label>Previous refusal <select name="previous_refusal"><option>No</option><option>Yes</option></select></label>
    <label>Travel date <input type="date" name="travel_date"></label>
  </div>
  <label>Notes <textarea name="notes" rows="5" placeholder="What do you want checked? Mention messy bank deposits, sponsor issues, invitation/date problems, previous refusal, or missing documents."></textarea></label>
  <div class="upload-brain-grid">
    <label>Passport / ID pages <input type="file" name="documents_passport[]" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"></label>
    <label>Application form / portal confirmation <input type="file" name="documents_form[]" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"></label>
    <label>Bank / funding proof <input type="file" name="documents_money[]" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"></label>
    <label>Employment / business proof <input type="file" name="documents_work[]" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"></label>
    <label>Invitation / admission / host proof <input type="file" name="documents_invitation[]" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"></label>
    <label>Previous refusal letter <input type="file" name="documents_refusal[]" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"></label>
    <label>Other documents <input type="file" name="documents_other[]" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"></label>
  </div>
  <p class="muted">The instant pre-check uses your answers and upload summary. The full review checks the actual documents manually.</p>
  <button class="button" type="submit">Run smart pre-check</button>
</form>
<?php endif; ?>
<?php require __DIR__ . '/includes/disclaimer.php'; vm_page_end(); ?>
