<?php
$entry = $entry ?? [];
$flag = vm_country_flag((string)($entry['country_slug'] ?? ''));
$feeAmount = (string)($entry['fees']['visa_fee']['amount'] ?? 'Check official fee page');
$feeHasVerify = vm_has_verify($feeAmount);
$feeClean = vm_strip_verify($feeAmount);
$processing = (string)($entry['processing_time']['standard'] ?? '');
$processingClean = vm_strip_verify($processing);
$processingHasVerify = vm_has_verify($processing);
$docs = array_slice((array)($entry['documents_needed'] ?? []), 0, 5);
$requirements = array_slice((array)($entry['requirements'] ?? []), 0, 5);
$refusalReasons = array_slice((array)($entry['common_reasons_for_refusal'] ?? []), 0, 4);
$officialSources = array_slice((array)($entry['official_sources'] ?? []), 0, 3);
$portalUrl = (string)($entry['application_portal_or_form']['url'] ?? '');
$countrySlug = (string)($entry['country_slug'] ?? '');
$visaSlug = (string)($entry['visa_type_slug'] ?? '');
?>
<article class="visa-hub-card">
  <details class="visa-hub-details" data-visa-hub-details>
    <summary class="visa-hub-summary">
      <span class="country-flag" style="font-size:28px"><?= $flag ?></span>
      <h3><?= vm_h($entry['country'] ?? 'Country') ?></h3>
      <span class="badge muted"><?= vm_h($entry['visa_type'] ?? 'Visa') ?></span>
      <?php if ($feeHasVerify || $processingHasVerify): ?><span class="badge muted">Confirm on official site</span><?php endif; ?>
    </summary>
    <div class="visa-hub-card-body">
      <p><?= vm_h((string)($entry['summary'] ?? '')) ?></p>

      <div class="visa-hub-fee">
        <strong>Fee:</strong>
        <span><?= vm_h($feeClean) ?></span>
        <?php if ($feeHasVerify): ?><span class="verify-badge">Confirm before paying</span><?php endif; ?>
      </div>

      <?php if ($processing): ?>
      <div class="visa-hub-meta">
        <span class="badge muted">Processing: <?= vm_h($processingClean) ?></span>
        <?php if ($processingHasVerify): ?><span class="verify-badge">Confirm timing</span><?php endif; ?>
      </div>
      <?php endif; ?>

      <div class="visa-hub-requirements">
        <?php if ($requirements): ?>
        <section>
          <h4>Key requirements</h4>
          <ul><?php foreach ($requirements as $req): ?><li><?= vm_h($req) ?></li><?php endforeach; ?></ul>
        </section>
        <?php endif; ?>
        <?php if ($docs): ?>
        <section>
          <h4>Documents</h4>
          <ul><?php foreach ($docs as $doc): ?><li><?= vm_h($doc) ?></li><?php endforeach; ?></ul>
        </section>
        <?php endif; ?>
      </div>

      <?php if ($refusalReasons): ?>
      <div class="visa-hub-refusal">
        <h4>Common refusal reasons</h4>
        <ul><?php foreach ($refusalReasons as $reason): ?><li><?= vm_h($reason) ?></li><?php endforeach; ?></ul>
      </div>
      <?php endif; ?>

      <?php if ($officialSources): ?>
      <div class="visa-hub-sources">
        <strong>Official sources:</strong>
        <?php foreach ($officialSources as $src): ?>
          <a href="<?= vm_h((string)($src['url'] ?? '#')) ?>" target="_blank" rel="noopener"><?= vm_h((string)($src['title'] ?? 'Official link')) ?> →</a>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>

      <div class="visa-hub-actions">
        <?php if ($countrySlug): ?>
          <a class="button secondary" href="<?= vm_url('country.php?slug=' . vm_h($countrySlug)) ?>">Open country guide</a>
        <?php endif; ?>
        <?php if ($portalUrl): ?>
          <a class="button ghost" href="<?= vm_h($portalUrl) ?>" target="_blank" rel="noopener">Apply / Check fees</a>
        <?php endif; ?>
      </div>
    </div>
  </details>
</article>
