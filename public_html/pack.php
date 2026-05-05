<?php
require_once __DIR__ . '/includes/functions.php';
$countries = vm_countries();
$packId = (string)($_GET['id'] ?? '');
$pack = vm_pack_by_id($packId);
if (!$pack) {
    http_response_code(404);
    vm_page_start('Service not found');
    echo '<section class="page-hero"><h1>Service not found.</h1><p><a href="' . vm_url('pricing.php') . '">View services</a></p></section>';
    vm_page_end();
    exit;
}
$saved = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    vm_verify_csrf();
    $saved = vm_save_submission('pack-request', [
        'pack_id' => $packId,
        'service_title' => (string)($pack['title'] ?? ''),
        'name' => vm_input('name'),
        'contact' => vm_input('contact'),
        'country' => vm_input('country'),
        'visa_type' => vm_input('visa_type'),
        'timeline' => vm_input('timeline'),
        'notes' => vm_input('notes', 1200),
    ]);
}
$deliverables = [
    'A customized checklist or document map based on this pack.',
    'Plain-language notes on weak points, missing evidence, and consistency risks.',
    'Official source links to verify final requirements before applying.',
    'A short next-step plan so you know what to fix first.',
];
$sendUs = [
    'Destination country and visa type.',
    'Your travel purpose, planned dates, and applying-from city.',
    'Existing document list or uploads if you request review.',
    'Sponsor, invitation, employment/business, student, refusal, or medical details where relevant.',
];
$forYouIf = [
    'You want a clearer file before submitting or attending an appointment.',
    'You are worried about funding, sponsor proof, invitation, dates, ties, or refusal risk.',
    'You want practical wording and document order, not vague advice.',
];
$notForYouIf = [
    'You want guaranteed approval.',
    'You want VisaMenged to act as an embassy, lawyer, sponsor, doctor, or decision-maker.',
    'You have not checked the official requirements for your destination yet.',
];
$turnaround = str_contains(strtolower((string)($pack['title'] ?? '')), 'quick') ? 'Usually 24-48 hours after we receive the required details.' : 'Usually 2-4 business days depending on file complexity and document completeness.';
vm_page_start((string)$pack['title']);
?>
<section class="page-hero pricing-hero">
  <span class="eyebrow"><?= vm_h($pack['country'] ?? 'Paid support') ?></span>
  <h1><?= vm_h($pack['title'] ?? 'VisaMenged service') ?></h1>
  <p><?= vm_h($pack['tagline'] ?? 'Paid support for organizing your visa file.') ?></p>
  <p class="price"><?= vm_h($pack['price'] ?? '') ?></p>
  <div class="hero-actions"><a class="button" href="#request-service">Request service</a><a class="button secondary" href="<?= vm_url('checklist-generator.php') ?>">Get free score first</a></div>
</section>
<?php if ($saved): ?>
<section class="notice success">
  <strong>Service request saved.</strong>
  <p>Your request ID is <strong><?= vm_h($saved) ?></strong>. This is not a payment confirmation; checkout/payment will be connected later.</p>
  <p>We will contact you within the stated turnaround time to discuss next steps.</p>
  <div class="hero-actions" style="margin-top:14px">
    <a class="button" href="<?= vm_url('pricing.php') ?>">View all services</a>
    <a class="button secondary" href="<?= vm_url('index.php') ?>">Back to home</a>
  </div>
</section>
<?php endif; ?>
<section class="two-col pack-detail">
  <div class="stack">
    <article class="card pack-fit">
      <div>
        <h2>This is for you if</h2>
        <ul><?php foreach ($forYouIf as $item): ?><li><?= vm_h($item) ?></li><?php endforeach; ?></ul>
      </div>
      <div>
        <h2>This is not for you if</h2>
        <ul><?php foreach ($notForYouIf as $item): ?><li><?= vm_h($item) ?></li><?php endforeach; ?></ul>
      </div>
    </article>
    <article class="card">
      <h2>What this service includes</h2>
      <ul><?php foreach ((array)($pack['includes'] ?? []) as $item): ?><li><?= vm_h($item) ?></li><?php endforeach; ?></ul>
    </article>
    <article class="card">
      <h2>What you receive</h2>
      <ul><?php foreach ($deliverables as $item): ?><li><?= vm_h($item) ?></li><?php endforeach; ?></ul>
    </article>
    <article class="card">
      <h2>What you send us</h2>
      <ul><?php foreach ($sendUs as $item): ?><li><?= vm_h($item) ?></li><?php endforeach; ?></ul>
    </article>
    <article class="card sample-output">
      <h2>Sample output preview</h2>
      <div class="sample-sheet">
        <strong>VisaMenged File Fix Plan</strong>
        <p><b>Priority 1:</b> clarify purpose and dates across application, invitation, itinerary and leave letter.</p>
        <p><b>Priority 2:</b> explain funding source and sponsor responsibility with evidence.</p>
        <p><b>Priority 3:</b> add Ethiopia return-ties evidence before submission.</p>
      </div>
    </article>
  </div>
  <aside class="side-panel" id="request-service">
    <h2>Request service</h2>
    <p><strong>Turnaround:</strong> <?= vm_h($turnaround) ?></p>
    <p><strong>Revision expectation:</strong> one clarification pass is included for wording or checklist corrections. New facts/documents may require extra review.</p>
    <form method="post" class="mini-form">
      <?= vm_csrf_field() ?>
      <label>Name <input name="name" required></label>
      <label>Phone or email <input name="contact" required></label>
      <label>Destination country <select name="country"><option value="">Select</option><?php foreach ($countries as $country): ?><option value="<?= vm_h($country['slug']) ?>" <?= strcasecmp((string)($pack['country'] ?? ''), (string)($country['name'] ?? '')) === 0 ? 'selected' : '' ?>><?= vm_h($country['name']) ?></option><?php endforeach; ?></select></label>
      <label>Visa type <?= vm_visa_type_select('visa_type', '', false) ?></label>
      <label>Timeline <input name="timeline" placeholder="travel date / deadline"></label>
      <label>Notes <textarea name="notes" rows="4" placeholder="Tell us what worries you most about the file."></textarea></label>
      <button class="button" type="submit">Request service</button>
    </form>
    <p class="muted">Payment is not connected yet. This request records interest and required details for follow-up.</p>
  </aside>
</section>
<?php require __DIR__ . '/includes/disclaimer.php'; vm_page_end(); ?>
