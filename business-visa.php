<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/smart-engine.php';
$entries = vm_visa_type_hub_data('business');
vm_page_start('Business Visa for Ethiopians — 25 Countries, Fees & Requirements 2026', 'Business visa requirements for Ethiopian passport holders. Invitation letters, company documents, meeting proof, official application portals and fees for 25 countries.');
?>
<section class="page-hero visa-hub-hero">
  <span class="eyebrow">Visa type guide</span>
  <h1>Business Visa Requirements</h1>
  <p>Invitation letters, company documents, meeting proof and official application channels for 25 countries. Every entry links back to the official embassy or government source.</p>
</section>
<section class="visa-hub-tabs">
  <a href="<?= vm_url('student-visa.php') ?>">Student</a>
  <a href="<?= vm_url('work-visa.php') ?>">Work</a>
  <a href="<?= vm_url('business-visa.php') ?>" class="active">Business</a>
  <a href="<?= vm_url('medical-visa.php') ?>">Medical</a>
  <a href="<?= vm_url('tourist-visa.php') ?>">Tourist</a>
</section>
<div class="notice" style="margin:0 clamp(16px,5vw,70px) 20px">
  <strong>Fees and rules change.</strong> Confirm the exact fee, address, processing time and document rule on the official source before you apply.
</div>
<section class="visa-hub-grid">
  <?php foreach ($entries as $entry): require __DIR__ . '/includes/visa-hub-card.php'; endforeach; ?>
</section>
<?php require __DIR__ . '/includes/disclaimer.php'; vm_page_end(); ?>
