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
    <p>Free country guides with official forms and requirements. Free document tools when your file needs polishing. No approval promises, just clear steps.</p>
  </div>
  <div class="simple-hero-actions">
    <a class="button" href="<?= vm_url('guides.php') ?>">Browse Country Guides</a>
    <a class="button ghost" href="<?= vm_url('free-tools.php') ?>">Open Free Tools</a>
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
      <p>Use the free tools to polish documents, draft letters, clean up sponsor proof, or plan refusal recovery. Get a clear fix plan with draft wording and evidence order.</p>
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

<section class="final-cta">
  <div>
    <h2>Ready to start?</h2>
    <p>Pick your destination and see exactly what official forms and steps you need. Or open the free tools for a guided next step.</p>
    <a class="button" href="<?= vm_url('guides.php') ?>">Browse Country Guides</a>
    <a class="button ghost" href="<?= vm_url('free-tools.php') ?>">Open Free Tools</a>
  </div>
</section>

<?php require __DIR__ . '/includes/disclaimer.php'; vm_page_end(); ?>
