<?php
require_once __DIR__ . '/includes/functions.php';
$countries = vm_countries();
$countriesAlpha = $countries;
usort($countriesAlpha, fn(array $a, array $b): int => strcasecmp((string)($a['name'] ?? ''), (string)($b['name'] ?? '')));
$featuredCountries = array_slice($countriesAlpha, 0, 8);
vm_page_start('VisaMenged — Free Visa Guides & Document Help for Ethiopians', 'Free visa country guides, official forms, checklists and document support for Ethiopian passport holders. 50 destinations. No login needed.');
?>
<section class="simple-home-hero">
  <div>
    <span class="eyebrow">For Ethiopian visa applicants</span>
    <h1>Get your visa file right — without the guesswork.</h1>
    <p>Free country guides with official forms and requirements. Optional document support when your file needs polishing. No approval promises, just clear steps.</p>
  </div>
  <div class="simple-hero-actions">
    <a class="button" href="<?= vm_url('guides.php') ?>">Browse Country Guides</a>
    <a class="button ghost" href="<?= vm_url('pricing.php') ?>">Get Document Support</a>
  </div>
  <div class="hero-flag-cloud" aria-hidden="true">
    <i class="route-line route-one"></i>
    <i class="route-line route-two"></i>
    <i class="route-line route-three"></i>
    <b class="stamp stamp-one">VISA</b>
    <b class="stamp stamp-two">GUIDE</b>
    <b class="doc-shape"></b>
    <?php foreach (array_slice($countriesAlpha, 0, 12) as $i => $country): ?>
      <span style="--i:<?= (int)$i ?>"><?= vm_country_flag((string)($country['slug'] ?? '')) ?></span>
    <?php endforeach; ?>
  </div>
</section>

<section class="trust-strip">
  <article><strong>50</strong><span>Country guides</span></article>
  <article><strong>399</strong><span>Official forms & resources</span></article>
  <article><strong>0</strong><span>Login required</span></article>
  <article><strong>Instant</strong><span>Smart service results</span></article>
</section>

<section class="how-it-works">
  <div class="simple-section-head">
    <div><span class="eyebrow">How it works</span><h2>Three steps to a stronger file.</h2></div>
  </div>
  <div class="steps-grid">
    <article>
      <span>01</span>
      <h3>Find your country guide</h3>
      <p>Open official forms, fee pages, and requirement checklists for your destination. Everything verified from embassy and government sources.</p>
    </article>
    <article>
      <span>02</span>
      <h3>Check your file for free</h3>
      <p>Use the Start Assistant or Checklist Generator to spot missing documents, weak evidence, and common refusal risks before you apply.</p>
    </article>
    <article>
      <span>03</span>
      <h3>Fix what is weak</h3>
      <p>Upgrade only when you want document polishing, letters, sponsor-proof cleanup, or refusal recovery. Get a clear fix plan with draft wording and evidence order.</p>
    </article>
  </div>
</section>

<section class="free-tools">
  <div class="simple-section-head">
    <div><span class="eyebrow">Free tools</span><h2>Start without paying.</h2></div>
  </div>
  <div class="free-tools-grid">
    <a href="<?= vm_url('start.php') ?>">
      <strong>Start Assistant</strong>
      <span>Answer 8 questions and get a personalized file direction with gaps and next steps.</span>
      <em>Use free →</em>
    </a>
    <a href="<?= vm_url('checklist-generator.php') ?>">
      <strong>Checklist Generator</strong>
      <span>Pick a country and visa type. Get a tailored document checklist with risk warnings.</span>
      <em>Generate →</em>
    </a>
    <a href="<?= vm_url('previous-refusal-helper.php') ?>">
      <strong>Refusal Helper</strong>
      <span>Had a refusal before? Get evidence suggestions and a changed-circumstances plan.</span>
      <em>Recover →</em>
    </a>
  </div>
</section>

<section class="preview-countries">
  <div class="simple-section-head row">
    <div><span class="eyebrow">Popular destinations</span><h2>Where Ethiopians travel most.</h2></div>
    <a href="<?= vm_url('guides.php') ?>">See all countries →</a>
  </div>
  <div class="preview-countries-grid">
    <?php foreach ($featuredCountries as $country): ?>
      <a href="<?= vm_url('country.php?slug=' . vm_h($country['slug'] ?? '')) ?>">
        <span class="country-flag"><?= vm_country_flag((string)($country['slug'] ?? '')) ?></span>
        <strong><?= vm_h($country['name'] ?? '') ?></strong>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<section class="paid-services" id="document-support">
  <div class="paid-services-head">
    <div><span class="eyebrow">Smart services</span><h2>The only 4 upgrades you need before submission.</h2><p>Fast automated delivery, clear pricing, and output designed to reduce avoidable refusal risk.</p></div>
  </div>
  <div class="paid-services-grid">
    <a href="<?= vm_url('review-request.php') ?>" class="service-card" data-paddle-checkout data-paddle-price-id="pri_01kqzbs9118mc38j9szyzvzddf" data-paddle-product="quick-checklist-cleanup">
      <div class="service-card-top">
        <span class="service-badge">Easy starter</span>
        <strong class="service-price">499 birr</strong>
      </div>
      <h3>Quick Checklist Cleanup</h3>
      <p>Instantly convert official requirements into a clean, personalized action checklist you can follow line by line.</p>
      <ul>
        <li>Personal checklist</li>
        <li>Missing-item warnings</li>
        <li>Submission order</li>
      </ul>
      <span class="service-cta">Get my checklist now -></span>
    </a>
    <a href="<?= vm_url('letter-generator.php') ?>" class="service-card" data-paddle-checkout data-paddle-price-id="pri_01kqzbve8tnkvy354va6qdav21" data-paddle-product="letter-pack">
      <div class="service-card-top">
        <span class="service-badge secondary">Same-day drafts</span>
        <strong class="service-price">from 1,500 birr</strong>
      </div>
      <h3>Letter Pack</h3>
      <p>Generate strong first-draft letters for cover, sponsor, employer, invitation, and refusal response in one workflow.</p>
      <ul>
        <li>Tailored to your visa type</li>
        <li>Funding & relationship proof</li>
        <li>One revision included</li>
      </ul>
      <span class="service-cta">Generate my letter pack -></span>
    </a>
    <a href="<?= vm_url('interview-readiness.php') ?>" class="service-card" data-paddle-checkout data-paddle-price-id="pri_01kqzbtezwf7ppqf87zy470a06" data-paddle-product="interview-readiness-pack">
      <div class="service-card-top">
        <span class="service-badge">High demand</span>
        <strong class="service-price">from 1,200 birr</strong>
      </div>
      <h3>Interview Readiness Pack</h3>
      <p>Get country-specific interview questions, recommended answer angles, and confidence-building practice prompts.</p>
      <ul>
        <li>Country and visa-type question bank</li>
        <li>Strong-answer structure guide</li>
        <li>Red-flag answer warnings</li>
      </ul>
      <span class="service-cta">Prepare me for interview -></span>
    </a>
    <a href="<?= vm_url('review-request.php') ?>" class="service-card featured" data-paddle-checkout data-paddle-price-id="pri_01kqzbx5ra4hnxe0dcpn570djt" data-paddle-product="complete-file-organization">
      <div class="service-card-top">
        <span class="service-badge">Best value</span>
        <strong class="service-price">from 3,000 birr</strong>
      </div>
      <h3>Complete File Organization</h3>
      <p>Get a full-file cleanup system that aligns purpose, timing, money, invitation, and return ties into one consistent story.</p>
      <ul>
      <li>Full document audit + rewrite</li>
        <li>Consistency check across all papers</li>
        <li>Final checklist + submission order</li>
      </ul>
      <span class="service-cta">Organize my full file -></span>
  </a>
  </div>
  <div class="paid-services-foot">
    <p>These are the only paid services on VisaMenged. <a href="<?= vm_url('pricing.php') ?>">View full details -></a></p>
  </div>
</section>

<section class="final-cta">
  <div>
    <h2>Ready to start?</h2>
    <p>Pick your destination and see exactly what official forms and steps you need.</p>
    <a class="button" href="<?= vm_url('guides.php') ?>">Browse Country Guides</a>
  </div>
</section>

<?php require __DIR__ . '/includes/disclaimer.php'; vm_page_end(); ?>
