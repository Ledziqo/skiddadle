<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/smart-engine.php';
vm_page_start('VisaMenged Services — Document Review & Letter Writing', 'Paid visa services for Ethiopian applicants. Document review, letter writing, checklist support and file organization from 999 birr.');
?>
<section class="page-hero pricing-hero">
  <span class="eyebrow">Smart services</span>
  <h1>The only 4 paid services on VisaMenged.</h1>
  <p>Built for Ethiopian applicants who want faster, cleaner, and more submission-ready files without confusion.</p>
</section>
<section class="pricing-grid simple-pricing-grid">
  <article class="card price-card"><h2>Quick Checklist Cleanup</h2><p class="price">499 birr</p><p>Personalized checklist, missing-item warnings, and submission order built from official requirements.</p><a class="button secondary" href="<?= vm_url('review-request.php') ?>" data-paddle-checkout data-paddle-price-id="pri_01kqzbs9118mc38j9szyzvzddf" data-paddle-product="quick-checklist-cleanup">Get checklist</a></article>
  <article class="card price-card"><h2>Letter Pack</h2><p class="price">from 1,500 birr</p><p>Cover, sponsor, employer, invitation, and refusal-response drafts tailored to your visa type.</p><a class="button secondary" href="<?= vm_url('letter-generator.php') ?>" data-paddle-checkout data-paddle-price-id="pri_01kqzbve8tnkvy354va6qdav21" data-paddle-product="letter-pack">Generate letters</a></article>
  <article class="card price-card"><h2>Interview Readiness Pack</h2><p class="price">from 1,200 birr</p><p>Country-specific interview questions, recommended answer angles, and common red-flag warnings.</p><a class="button secondary" href="<?= vm_url('interview-readiness.php') ?>" data-paddle-checkout data-paddle-price-id="pri_01kqzbtezwf7ppqf87zy470a06" data-paddle-product="interview-readiness-pack">Prepare interview</a></article>
  <article class="card price-card featured"><h2>Complete File Organization</h2><p class="price">from 3,000 birr</p><p>Full-file consistency cleanup across purpose, dates, finance, invitation, and return-ties evidence.</p><a class="button" href="<?= vm_url('review-request.php') ?>" data-paddle-checkout data-paddle-price-id="pri_01kqzbx5ra4hnxe0dcpn570djt" data-paddle-product="complete-file-organization">Organize full file</a></article>
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
  <strong>Service scope:</strong> VisaMenged offers only these four paid services so the experience stays simple, fast, and consistent.
</section>
<?php require __DIR__ . '/includes/disclaimer.php'; vm_page_end(); ?>
