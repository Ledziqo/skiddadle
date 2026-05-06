<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/smart-engine.php';
$resources = vm_resources();
$countries = vm_countries();
$countriesAlpha = $countries;
usort($countriesAlpha, fn(array $a, array $b): int => strcasecmp((string)($a['name'] ?? ''), (string)($b['name'] ?? '')));
$standardVisaTypes = ['Tourist visa', 'Student visa', 'Work visa', 'Business visa', 'Medical visa'];
$resourceVisaTypes = array_values(array_filter(array_unique(array_map(fn($r) => (string)($r['visa_type'] ?? ''), $resources))));
sort($resourceVisaTypes);
$visaTypes = array_values(array_unique(array_merge($standardVisaTypes, $resourceVisaTypes)));
$brainTips = vm_forms_brain_suggestions($resources);

// Group resources by country name for organised display
$resourcesByCountry = [];
foreach ($resources as $resource) {
    $countryName = (string)($resource['country'] ?? 'Other');
    if (!isset($resourcesByCountry[$countryName])) {
        $resourcesByCountry[$countryName] = [];
    }
    $resourcesByCountry[$countryName][] = $resource;
}
uksort($resourcesByCountry, fn(string $a, string $b): int => strcasecmp($a, $b));

// Build a quick lookup: country name => slug => flag
$countrySlugMap = [];
foreach ($countriesAlpha as $c) {
    $countrySlugMap[(string)($c['name'] ?? '')] = (string)($c['slug'] ?? '');
}

vm_page_start('Official Visa Forms for Ethiopians — PDFs, Portals & Checklists', 'Find official visa forms, online portals and requirement pages for Ethiopian applicants. Organized by country and visa type. Student, business, tourist & medical visa forms.');
?>
<section class="page-hero">
  <span class="eyebrow">Forms Brain</span>
  <h1>Find the right official route, not just a random form.</h1>
  <p>Search by country and visa type. VisaMenged helps separate portals, PDFs and requirement pages so applicants know what to open first.</p>
</section>
<section class="brain-strip">
  <?php foreach ($brainTips as $tip): ?><article><?= vm_h($tip) ?></article><?php endforeach; ?>
</section>
<section class="filters" data-resource-filters>
  <label>Search <input type="search" data-filter-search placeholder="Canada visitor, DS-160, VFS, checklist"></label>
  <label>Country <select data-filter-country><option value="">All countries (A-Z)</option><?php foreach ($countriesAlpha as $country): ?><option><?= vm_h($country['name'] ?? '') ?></option><?php endforeach; ?></select></label>
  <label>Visa type <select data-filter-visa><option value="">All visa types</option><?php foreach ($visaTypes as $type): ?><option><?= vm_h($type) ?></option><?php endforeach; ?></select></label>
  <label>Resource type <select data-filter-type><option value="">All resources</option><option value="downloadable_official_pdf">Downloadable PDFs</option><option value="official_online_portal">Online portals</option><option value="official_page">Requirements pages</option><option value="official_requirements_page">Official requirements pages</option></select></label>
</section>
<p class="muted results-count"><span data-results-count><?= count($resources) ?></span> public resources shown.</p>
<section class="az-list-head" aria-label="Alphabetical country list notice">
  <span>A-Z country list</span>
  <strong>Countries are sorted alphabetically.</strong>
  <p>Scroll or use the country filter above to jump straight to a destination.</p>
</section>
<section class="country-resource-list" data-country-resource-list>
  <?php foreach ($resourcesByCountry as $countryName => $countryResources):
      $slug = $countrySlugMap[$countryName] ?? '';
      $flag = $slug !== '' ? vm_country_flag($slug) : '🌍';
  ?>
  <div class="country-resource-group" data-country-group data-country-name="<?= vm_h(strtolower($countryName)) ?>">
    <details class="country-group-details" data-country-details>
      <summary class="country-group-summary">
        <span class="country-group-flag"><?= $flag ?></span>
        <h2><?= vm_h($countryName) ?></h2>
        <span class="badge country-group-count"><?= count($countryResources) ?> resource<?= count($countryResources) === 1 ? '' : 's' ?></span>
        <span class="visa-type-chips-inline">
          <?php foreach ($standardVisaTypes as $gvt): ?><span class="visa-type-chip"><?= vm_h($gvt) ?></span><?php endforeach; ?>
        </span>
      </summary>
      <div class="country-group-body">
        <?php if ($slug !== ''): ?>
          <div class="country-group-actions">
            <a class="button secondary" href="<?= vm_url('country.php?slug=' . vm_h($slug)) ?>">Open full <?= vm_h($countryName) ?> guide</a>
          </div>
        <?php endif; ?>
        <div class="grid resource-list country-resource-card-grid">
          <?php foreach ($countryResources as $resource): require __DIR__ . '/includes/resource-card.php'; endforeach; ?>
        </div>
      </div>
    </details>
  </div>
  <?php endforeach; ?>
</section>
<?php require __DIR__ . '/includes/disclaimer.php'; vm_page_end(); ?>
