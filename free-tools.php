<?php
require_once __DIR__ . '/includes/functions.php';
vm_page_start('VisaMenged Free Tools', 'Free visa helpers for Ethiopian applicants. Checklists, letters, interview practice and refusal recovery without payment.');
?>
<section class="page-hero pricing-hero">
  <span class="eyebrow">Free tools</span>
  <h1>Everything useful is free.</h1>
  <p>Use these tools to organize your visa file, draft starter text, and prepare for interviews without checkout or paid access.</p>
</section>
<section class="pricing-grid simple-pricing-grid">
  <article class="card price-card">
    <h2>Quick Checklist Cleanup</h2>
    <p class="price">Free</p>
    <p>Personalized checklist, missing-item warnings, and submission order built from official requirements.</p>
    <a class="button secondary" href="<?= vm_url('review-request.php') ?>">Open checklist</a>
  </article>
  <article class="card price-card">
    <h2>Letter Pack</h2>
    <p class="price">Free</p>
    <p>Cover, sponsor, employer, invitation, and refusal-response starter drafts tailored to your visa type.</p>
    <a class="button secondary" href="<?= vm_url('letter-generator.php') ?>">Generate letters</a>
  </article>
  <article class="card price-card">
    <h2>Interview Readiness Pack</h2>
    <p class="price">Free</p>
    <p>Country-specific interview questions, recommended answer angles, and common red-flag warnings.</p>
    <a class="button secondary" href="<?= vm_url('interview-readiness.php') ?>">Prepare interview</a>
  </article>
  <article class="card price-card featured">
    <h2>Complete File Organization</h2>
    <p class="price">Free</p>
    <p>Full-file consistency cleanup across purpose, dates, finance, invitation, and return-ties evidence.</p>
    <a class="button" href="<?= vm_url('review-request.php') ?>">Organize full file</a>
  </article>
</section>
<section class="premium-process">
  <div><span class="eyebrow">How it works</span><h2>Simple flow.</h2></div>
  <div class="path-steps">
    <article><span>01</span><h3>Send the situation</h3><p>Country, visa type, deadline, concern, and available documents.</p></article>
    <article><span>02</span><h3>Smart analysis runs</h3><p>Purpose, money, sponsor, invitation, return ties and refusal risk are mapped instantly.</p></article>
    <article><span>03</span><h3>You receive a fix plan</h3><p>Checklist, draft wording, evidence order, and official source links.</p></article>
    <article><span>04</span><h3>You verify officially</h3><p>Final rules still come from the embassy, government, VFS, TLS or visa-center source.</p></article>
  </div>
</section>
<section class="notice">
  <strong>Service scope:</strong> VisaMenged now offers these tools free so the experience stays simple and accessible.
</section>
<?php require __DIR__ . '/includes/disclaimer.php'; vm_page_end(); ?>
