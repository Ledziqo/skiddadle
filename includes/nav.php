<button class="menu-toggle" type="button" data-menu-toggle aria-label="Menu" aria-expanded="false">
  <span></span><span></span><span></span>
</button>
<?php
$vm_current_page = basename(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: 'index.php');
$vm_nav_items = [
  ['href' => 'index.php', 'label' => vm_t('home'), 'active' => $vm_current_page === 'index.php'],
  ['href' => 'guides.php', 'label' => vm_t('country_guides_nav'), 'active' => in_array($vm_current_page, ['guides.php', 'country.php', 'visa.php', 'resource.php'], true)],
  ['href' => 'forms.php', 'label' => vm_t('official_forms_nav'), 'active' => $vm_current_page === 'forms.php'],
  ['href' => 'checklist-generator.php', 'label' => vm_t('free_checklist_nav'), 'active' => $vm_current_page === 'checklist-generator.php'],
  ['href' => 'schengen.php', 'label' => vm_t('schengen_nav'), 'active' => $vm_current_page === 'schengen.php'],
  ['href' => 'free-tools.php', 'label' => 'Free tools', 'active' => in_array($vm_current_page, ['free-tools.php', 'review-request.php', 'letter-generator.php', 'pack.php', 'previous-refusal-helper.php', 'templates.php', 'contact.php'], true), 'class' => 'paid-help-link'],
];
?>
<nav class="site-nav" data-site-nav>
  <?php foreach ($vm_nav_items as $vm_item): ?>
    <a class="<?= trim(($vm_item['active'] ? 'active ' : '') . (string)($vm_item['class'] ?? '')) ?>" href="<?= vm_url($vm_item['href']) ?>"<?= $vm_item['active'] ? ' aria-current="page"' : '' ?>><?= vm_h($vm_item['label']) ?></a>
  <?php endforeach; ?>
</nav>
