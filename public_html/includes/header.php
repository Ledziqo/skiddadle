<?php require_once __DIR__ . '/functions.php'; ?>
<!doctype html>
<html lang="<?= vm_h(vm_lang()) ?>">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= vm_h(($GLOBALS['vm_page_title'] ?? 'VisaMenged') . ' | VisaMenged') ?></title>
  <meta name="description" content="<?= vm_h($GLOBALS['vm_page_description'] ?? 'Official visa resources and document support for Ethiopian applicants.') ?>">
  <link rel="stylesheet" href="<?= vm_url('assets/css/style.css') ?>">
  <script>
    (function(){
      const theme=localStorage.getItem('vm_theme')||'light';
      document.documentElement.dataset.theme=theme;
    })();
  </script>
</head>
<body>
<header class="site-header">
  <a class="brand" href="<?= vm_url('index.php') ?>" aria-label="VisaMenged home">
    <img src="<?= vm_url('assets/img/visamenged-logo-flat-v3-transparent.png') ?>" alt="VisaMenged">
  </a>
  <?php require __DIR__ . '/nav.php'; ?>
  <div class="top-actions">
    <div class="language-switch" aria-label="Language switch">
      <a class="<?= vm_lang() === 'en' ? 'active' : '' ?>" href="<?= vm_h(vm_lang_url('en')) ?>">EN</a>
      <a class="<?= vm_lang() === 'am' ? 'active' : '' ?>" href="<?= vm_h(vm_lang_url('am')) ?>">አማ</a>
    </div>
    <button class="theme-switch" type="button" data-theme-toggle aria-label="Toggle light or dark mode" aria-pressed="false">
      <span>Light</span>
      <i aria-hidden="true"></i>
      <span>Dark</span>
    </button>
    <button class="basket-toggle" type="button" data-basket-open><?= vm_h(vm_t('saved_list')) ?> <span data-basket-count>0</span></button>
  </div>
</header>
<script>
window.VM_I18N = <?= json_encode([
  'savedTitle' => vm_t('saved_resources'),
  'noItems' => vm_lang() === 'am' ? 'እስካሁን የተቀመጠ ነገር የለም።' : 'No saved items yet.',
  'open' => vm_lang() === 'am' ? 'ክፈት' : 'Open',
  'remove' => vm_lang() === 'am' ? 'አስወግድ' : 'Remove',
  'exportTitle' => vm_lang() === 'am' ? 'VisaMenged - የተቀመጡ መረጃዎች' : 'VisaMenged - Saved list',
  'tagline' => vm_t('tagline'),
  'generated' => vm_lang() === 'am' ? 'ተዘጋጀ' : 'Generated',
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
</script>
<aside class="basket-panel" data-basket-panel aria-label="<?= vm_h(vm_t('saved_resources')) ?>">
  <div class="basket-head">
    <h2><?= vm_h(vm_t('saved_resources')) ?></h2>
    <button type="button" data-basket-close aria-label="<?= vm_h(vm_t('close')) ?>">x</button>
  </div>
  <div data-basket-items class="basket-items"></div>
  <div class="basket-actions">
    <button type="button" class="button secondary" data-basket-print><?= vm_h(vm_t('print')) ?></button>
    <button type="button" class="button secondary" data-basket-download><?= vm_h(vm_t('download_list')) ?></button>
    <button type="button" class="button ghost" data-basket-clear><?= vm_h(vm_t('clear')) ?></button>
  </div>
</aside>
<main>
