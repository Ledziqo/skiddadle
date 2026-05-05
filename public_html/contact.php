<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/smart-engine.php';
$saved = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') { vm_verify_csrf(); $saved = vm_save_submission('contact', ['name'=>vm_input('name'), 'contact'=>vm_input('contact'), 'topic'=>vm_input('topic'), 'message'=>vm_input('message', 1500)]); }
$topic = (string)($_GET['topic'] ?? '');
vm_page_start('Contact VisaMenged — Ethiopian Visa Support', 'Contact VisaMenged for visa guidance, document review, letter writing and checklist support for Ethiopian applicants.');
?>
<section class="page-hero"><span class="eyebrow">Contact Brain</span><h1>Tell us the visa problem. We route it to the right help.</h1><p>Choose the closest issue, then send your message. For faster help, include destination country, visa type, deadline and what feels weak.</p></section>
<section class="contact-strip">
  <article>
    <span class="eyebrow">Email</span>
    <a href="mailto:Aesliexx@gmail.com">Aesliexx@gmail.com</a>
  </article>
  <article>
    <span class="eyebrow">Telegram</span>
    <a href="https://t.me/Aesliex" target="_blank" rel="noopener">@Aesliex</a>
  </article>
</section>
<?php if ($saved): ?>
<section class="notice success">
  <strong>Message saved.</strong>
  <p>Your request ID is <strong><?= vm_h($saved) ?></strong>.</p>
  <p>We will respond to your message as soon as possible.</p>
  <div class="hero-actions" style="margin-top:14px">
    <a class="button" href="<?= vm_url('index.php') ?>">Back to home</a>
    <a class="button secondary" href="<?= vm_url('pricing.php') ?>">View services</a>
  </div>
</section>
<?php else: ?>
<section class="problem-picker contact-router">
  <div><span class="eyebrow">Choose your issue</span><h2>What should we help with?</h2></div>
  <div class="problem-grid">
    <?php foreach (vm_service_match_options() as $option): ?><a href="<?= vm_url('contact.php?topic=' . rawurlencode($option['id'])) ?>#contact-form"><?= vm_h($option['label']) ?><small><?= vm_h($option['why']) ?></small><span>Use this topic</span></a><?php endforeach; ?>
  </div>
</section>
<form class="card form-card" id="contact-form" method="post"><?= vm_csrf_field() ?><div class="form-grid"><label>Name <input name="name" required></label><label>Contact <input name="contact" required></label><label>Topic <input name="topic" value="<?= vm_h($topic) ?>" placeholder="review, sponsor, refusal, letter"></label></div><label>Message <textarea name="message" rows="6" required placeholder="Country, visa type, deadline, and the exact issue you want fixed."></textarea></label><button class="button" type="submit">Send message</button></form>
<?php endif; ?>
<?php require __DIR__ . '/includes/disclaimer.php'; vm_page_end(); ?>
