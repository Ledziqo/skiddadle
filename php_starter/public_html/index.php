<?php $pageTitle='VisaMenged — Visa forms and embassy resources for Ethiopia'; include __DIR__.'/includes/header.php'; ?>
<section class="hero">
  <div>
    <p class="eyebrow">Ethiopia-focused visa resource hub</p>
    <h1>Prepare your visa file with confidence.</h1>
    <p>Find official embassy forms, application portals, checklists, and VisaMenged document support for applicants applying from Ethiopia.</p>
    <div class="actions"><a class="btn primary" href="/checklist-generator.php">Find My Visa Checklist</a><a class="btn" href="/forms.php">Browse Official Forms</a></div>
  </div>
  <div class="hero-card">
    <strong>My Visa File</strong>
    <ul><li>Official forms</li><li>Checklist</li><li>Support letters</li><li>Review request</li></ul>
    <span class="badge">No login required</span>
  </div>
</section>
<section class="grid-section">
  <h2>Top 25 launch countries</h2>
  <div class="country-grid">
  <?php foreach (vm_load_json('countries_top25.json') as $c): ?>
    <a class="country-card" href="/country.php?slug=<?= vm_h($c['slug']) ?>"><strong><?= vm_h($c['name']) ?></strong><small><?= vm_h($c['region']) ?></small></a>
  <?php endforeach; ?>
  </div>
</section>
<?php include __DIR__.'/includes/footer.php'; ?>