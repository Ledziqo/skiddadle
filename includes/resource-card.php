<?php
$resource = $resource ?? [];
$status = (string)($resource['resource_status'] ?? '');
$resourceUrl = vm_resource_url($resource);
$officialPage = (string)($resource['official_page'] ?? $resource['url'] ?? '#');
$detailUrl = vm_resource_detail_url($resource);
$resourceSlug = (string)($resource['slug'] ?? '');
$resourceCountry = $resourceSlug !== '' ? vm_country_by_slug($resourceSlug) : null;
$resourceHub = (string)($resourceCountry['hub'] ?? '');
$feeGuide = $resourceSlug !== '' ? vm_fee_guide_for_country($resourceSlug, $resourceHub) : [];
$feeRows = vm_public_visa_type_rows_for_resource($resource, $resourceHub, 4);
$contactCards = (function_exists('vm_contact_map_cards') && $resourceSlug !== '' && $resourceCountry) ? vm_contact_map_cards($resourceSlug, vm_country_resources($resourceSlug), $resourceCountry) : [];
$primaryContact = $contactCards[0] ?? null;
$typeSlug = vm_slugify((string)($resource['visa_type'] ?? 'visa-type'));
$basketPayload = [
    'type' => 'Official resource',
    'title' => (string)($resource['title'] ?? ''),
    'meta' => trim((string)($resource['country'] ?? '') . ' - ' . (string)($resource['visa_type'] ?? ''), ' -'),
    'url' => $resourceUrl,
];
?>
<article class="card resource-card clickable-card" data-card-href="<?= vm_h($detailUrl) ?>" data-resource-card data-country="<?= vm_h($resource['country'] ?? '') ?>" data-visa="<?= vm_h($resource['visa_type'] ?? '') ?>" data-title="<?= vm_h($resource['title'] ?? '') ?>" data-source="<?= vm_h($resource['source_org'] ?? '') ?>" data-category="<?= vm_h($resource['category'] ?? '') ?>" data-status="<?= vm_h($status) ?>">
  <div class="card-top">
    <span class="badge"><?= vm_h($resource['country'] ?? 'Resource') ?></span>
    <span class="badge muted"><?= vm_h(vm_status_label($status)) ?></span>
  </div>
  <h3><a href="<?= vm_h($detailUrl) ?>"><?= vm_h($resource['title'] ?? 'Official resource') ?></a></h3>
  <p class="muted"><?= vm_h($resource['visa_type'] ?? 'Visa resource') ?> · <?= vm_h(ucwords(str_replace('_', ' ', (string)($resource['category'] ?? 'resource')))) ?></p>
  <p><?= vm_h($resource['notes'] ?? '') ?></p>
  <?php if ($feeRows): $primaryFee = $feeRows[0]; ?>
    <p class="fee-preview"><strong>Fee idea:</strong> <?= vm_h($primaryFee['type']) ?> - <?= vm_h(vm_fee_display((string)$primaryFee['fee'])) ?></p>
  <?php endif; ?>
  <dl class="mini-list">
    <div><dt>Source</dt><dd><?= vm_h($resource['source_org'] ?? 'Official source') ?></dd></div>
    <div><dt>Status</dt><dd><?= vm_h(vm_status_label($status)) ?></dd></div>
  </dl>
  <details class="resource-intel">
    <summary>Fees, location, hours and guide tips</summary>
    <div class="resource-intel-grid">
      <section>
        <h4>Visa prices</h4>
        <?php if ($feeRows): ?>
          <ul><?php foreach ($feeRows as $row): ?><li><strong><?= vm_h($row['type']) ?>:</strong> <?= vm_h(vm_fee_display((string)$row['fee'])) ?></li><?php endforeach; ?></ul>
          <p class="fee-note">Birr amounts are approximate planning conversions; pay using the official currency and fee page.</p>
        <?php else: ?>
          <p>Check the official fee page for this country and visa type before paying.</p>
        <?php endif; ?>
        <?php if (($feeGuide['fee_source_url'] ?? '') !== ''): ?><a href="<?= vm_h((string)$feeGuide['fee_source_url']) ?>" target="_blank" rel="noopener">Open official fee page</a><?php endif; ?>
      </section>
      <section>
        <h4>Location and contact</h4>
        <?php if ($primaryContact): ?>
          <p><strong><?= vm_h($primaryContact['title']) ?></strong></p>
          <p><?= vm_h($primaryContact['note']) ?></p>
          <div class="actions">
            <a class="button ghost" href="<?= vm_h($primaryContact['url']) ?>" target="_blank" rel="noopener">Official contact</a>
            <?php if (($primaryContact['map_url'] ?? '') !== ''): ?><a class="button ghost" href="<?= vm_h($primaryContact['map_url']) ?>" target="_blank" rel="noopener">Map and hours</a><?php endif; ?>
          </div>
        <?php else: ?>
          <p>Use the official source for current contact details, address and working hours.</p>
        <?php endif; ?>
      </section>
      <section>
        <h4>Guide tips</h4>
        <ul>
          <li>Confirm the visa type before opening forms.</li>
          <li>Check whether this is a portal, PDF, appointment page or requirement page.</li>
          <li>Verify fees, working hours and appointment rules on the official source.</li>
        </ul>
      </section>
    </div>
    <div class="actions">
      <?php if ($resourceSlug !== ''): ?><a class="button secondary" href="<?= vm_url('country.php?slug=' . vm_h($resourceSlug)) ?>">Open country guide</a><?php endif; ?>
    </div>
  </details>
  <?php if ($status === 'downloadable_official_pdf' && vm_has_local_pdf($resource)): ?>
    <details class="preview">
      <summary>Preview official PDF</summary>
      <iframe src="<?= vm_h($resourceUrl) ?>" title="<?= vm_h($resource['title'] ?? 'PDF preview') ?>"></iframe>
    </details>
  <?php endif; ?>
  <div class="actions">
    <a class="button secondary" href="<?= vm_h($detailUrl) ?>">Open guide</a>
    <?php if ($status === 'downloadable_official_pdf' && (vm_has_local_pdf($resource) || vm_is_pdf_url($resourceUrl))): ?>
      <a class="button secondary" href="<?= vm_h($resourceUrl) ?>" target="_blank" rel="noopener">Preview</a>
    <?php endif; ?>
    <a class="button" href="<?= vm_h($resourceUrl) ?>" target="_blank" rel="noopener"><?= vm_h(vm_primary_action_label($resource)) ?></a>
    <a class="button ghost" href="<?= vm_h($officialPage) ?>" target="_blank" rel="noopener">Open Official Source</a>
    <button class="button secondary" type="button" data-add-basket='<?= vm_h(json_encode($basketPayload, JSON_UNESCAPED_SLASHES)) ?>'>Save to list</button>
    <a class="link-risk" href="<?= vm_url('contact.php') ?>?topic=outdated-form">Report outdated form</a>
  </div>
</article>
