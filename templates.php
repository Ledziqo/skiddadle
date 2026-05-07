<?php
require_once __DIR__ . '/includes/functions.php';
$templates = vm_templates();
vm_page_start('Free Visa Templates for Ethiopians - Letters & Worksheets', 'Free visa letter templates and worksheets for Ethiopian applicants. Cover letters, sponsor letters, invitation letters and employer letters.');
?>
<section class="page-hero">
  <span class="eyebrow">VisaMenged-created support templates</span>
  <h1>Letters and worksheets for stronger document files.</h1>
  <p>Use the free draft generator, then edit every letter with your real evidence before submitting.</p>
  <div class="hero-actions">
    <a class="button" href="<?= vm_url('letter-generator.php') ?>">Open letter generator</a>
    <a class="button secondary" href="<?= vm_url('free-tools.php') ?>">Open free tools</a>
  </div>
</section>
<section class="grid cards-grid"><?php foreach ($templates as $template): require __DIR__ . '/includes/template-card.php'; endforeach; ?></section>
<?php require __DIR__ . '/includes/disclaimer.php'; vm_page_end(); ?>
