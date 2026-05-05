<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/smart-engine.php';
$entries = vm_visa_type_hub_data('work');
vm_page_start('Work Visa for Ethiopians — 25 Countries, Fees & Requirements 2026', 'Work visa requirements for Ethiopian passport holders. Employer sponsorship, work permits, contracts, qualification proof, official application portals and fees for 25 countries.');
?>
<section class="page-hero visa-hub-hero">
  <span class="eyebrow">Visa type guide</span>
  <h1>Work Visa Requirements</h1>
  <p>Employer sponsorship, work permits, contracts, qualification proof and official application channels for 25 countries. Every entry links back to the official embassy or government source.</p>
</section>
<section class="visa-hub-tabs">
  <a href="<?= vm_url('student-visa.php') ?>">Student</a>
  <a href="<?= vm_url('work-visa.php') ?>" class="active">Work</a>
  <a href="<?= vm_url('business-visa.php') ?>">Business</a>
  <a href="<?= vm_url('medical-visa.php') ?>">Medical</a>
  <a href="<?= vm_url('tourist-visa.php') ?>">Tourist</a>
</section>
<div class="notice" style="margin:0 clamp(16px,5vw,70px) 20px">
  <strong>Work routes need extra checking.</strong> Confirm whether employer permit, sponsorship approval or labour authorization must come before the visa application.
</div>
<section class="visa-hub-grid">
  <?php foreach ($entries as $entry): require __DIR__ . '/includes/visa-hub-card.php'; endforeach; ?>
</section>
<?php require __DIR__ . '/includes/disclaimer.php'; vm_page_end(); ?>
