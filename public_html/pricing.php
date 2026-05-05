<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/smart-engine.php';
$packs = vm_packs();
vm_page_start('Services');
?>
<section class="page-hero pricing-hero">
  <span class="eyebrow">Paid help</span>
  <h1>Pick the one thing you want fixed.</h1>
  <p>No approval promises. No confusing package maze. Choose the closest problem, send your situation, and we tell you what to clean up.</p>
</section>
<section class="pricing-grid simple-pricing-grid">
  <article class="card price-card"><h2>Use free tools first</h2><p class="price">0 birr</p><p>Country guide, official forms, checklist and saved resource list.</p><a class="button secondary" href="<?= vm_url('checklist-generator.php') ?>">Start free</a></article>
  <article class="card price-card featured"><h2>Check my file</h2><p class="price">from 999 birr</p><p>Missing documents, weak evidence and the next fixes before you submit.</p><a class="button" href="<?= vm_url('review-request.php') ?>">Request check</a></article>
  <article class="card price-card"><h2>Fix my letters</h2><p class="price">from 1,500 birr</p><p>Cover, sponsor, invitation, employer, business, student or refusal wording.</p><a class="button secondary" href="<?= vm_url('letter-generator.php') ?>">Start letter</a></article>
  <article class="card price-card"><h2>Organize everything</h2><p class="price">from 3,000 birr</p><p>Full document order, consistency review, risk notes and final checklist.</p><a class="button secondary" href="<?= vm_url('review-request.php') ?>">Request review</a></article>
</section>
<section class="premium-process">
  <div><span class="eyebrow">How it works</span><h2>Simple flow.</h2></div>
  <div class="path-steps">
    <article><span>01</span><h3>Send the situation</h3><p>Country, visa type, deadline, concern, and available documents.</p></article>
    <article><span>02</span><h3>We map the weak points</h3><p>Purpose, money, sponsor, invitation, return ties and refusal risk.</p></article>
    <article><span>03</span><h3>You receive a fix plan</h3><p>Checklist, draft wording, evidence order, and official source links.</p></article>
    <article><span>04</span><h3>You verify officially</h3><p>Final rules still come from the embassy, government, VFS, TLS or visa-center source.</p></article>
  </div>
</section>
<details class="all-services-drawer">
<summary>Show all detailed services</summary>
<section class="grid cards-grid pack-grid">
<?php foreach ($packs as $pack): ?>
  <article class="card pack-card">
    <div class="card-top"><span class="badge"><?= vm_h($pack['country'] ?? '') ?></span><span class="badge muted"><?= vm_h($pack['price'] ?? '') ?></span></div>
    <h3><?= vm_h($pack['title'] ?? '') ?></h3>
    <p><?= vm_h($pack['tagline'] ?? '') ?></p>
    <ul><?php foreach ((array)($pack['includes'] ?? []) as $item): ?><li><?= vm_h($item) ?></li><?php endforeach; ?></ul>
    <div class="actions">
      <a class="button secondary" href="<?= vm_url('pack.php?id=' . vm_h($pack['id'] ?? '')) ?>">Request service</a>
      <button class="button ghost" type="button" data-add-basket='<?= vm_h(json_encode(['type'=>'Service','title'=>$pack['title'] ?? 'Service','meta'=>$pack['price'] ?? '','url'=>vm_url('pack.php?id=' . (string)($pack['id'] ?? ''))], JSON_UNESCAPED_SLASHES)) ?>'>Save for later</button>
    </div>
  </article>
<?php endforeach; ?>
</section>
</details>
<?php require __DIR__ . '/includes/disclaimer.php'; vm_page_end(); ?>
