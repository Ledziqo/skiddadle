<?php require_once __DIR__ . '/functions.php'; ?>
<!doctype html>
<html lang="<?= vm_h(vm_lang()) ?>">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= vm_h(($GLOBALS['vm_page_title'] ?? 'VisaMenged') . ' | VisaMenged') ?></title>
  <meta name="description" content="<?= vm_h($GLOBALS['vm_page_description'] ?? 'Official visa resources and document support for Ethiopian applicants.') ?>">
  <link rel="canonical" href="<?= vm_h(vm_canonical_url()) ?>">
  <link rel="alternate" hreflang="en" href="<?= vm_h(str_contains(vm_canonical_url(), '?') ? vm_canonical_url() . '&lang=en' : vm_canonical_url() . '?lang=en') ?>">
  <link rel="alternate" hreflang="am" href="<?= vm_h(str_contains(vm_canonical_url(), '?') ? vm_canonical_url() . '&lang=am' : vm_canonical_url() . '?lang=am') ?>">
  <link rel="stylesheet" href="<?= vm_url('assets/css/style.css') ?>">
  <style>
    /* Critical fallback for homepage metrics if cached CSS is stale */
    .trust-strip{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:1px;background:#d8e8f8;border-bottom:1px solid #d8e8f8}
    .trust-strip article{background:#fff;padding:22px clamp(16px,5vw,70px);text-align:center}
    .trust-strip strong{display:block;font-size:clamp(32px,4vw,48px);line-height:1;color:#062d66}
    .trust-strip span{display:block;margin-top:4px;font-size:14px;font-weight:800;color:#445c78}
    @media(max-width:980px){.trust-strip{grid-template-columns:repeat(2,minmax(0,1fr))}}
    @media(max-width:640px){.trust-strip article{padding:18px 14px}.trust-strip strong{font-size:28px}}
  </style>
  <meta property="og:type" content="website">
  <meta property="og:site_name" content="VisaMenged">
  <meta property="og:title" content="<?= vm_h($GLOBALS['vm_page_title'] ?? 'VisaMenged') ?>">
  <meta property="og:description" content="<?= vm_h($GLOBALS['vm_page_description'] ?? 'Official visa resources and document support for Ethiopian applicants.') ?>">
  <meta property="og:url" content="<?= vm_h(vm_canonical_url()) ?>">
  <meta property="og:image" content="<?= vm_h(vm_og_image()) ?>">
  <meta property="og:locale" content="en_US">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:title" content="<?= vm_h($GLOBALS['vm_page_title'] ?? 'VisaMenged') ?>">
  <meta name="twitter:description" content="<?= vm_h($GLOBALS['vm_page_description'] ?? 'Official visa resources and document support for Ethiopian applicants.') ?>">
  <meta name="twitter:image" content="<?= vm_h(vm_og_image()) ?>">
  <script type="application/ld+json"><?= vm_seo_jsonld() ?></script>
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
    <img src="<?= vm_url('assets/img/icon-logo.png') ?>" alt="" class="brand-icon">
    <span class="brand-text"><span class="brand-visa">Visa</span><span class="brand-menged">Menged</span></span>
  </a>
  <?php require __DIR__ . '/nav.php'; ?>
  <div class="top-actions">
    <div class="language-switch" aria-label="Language switch">
      <a class="<?= vm_lang() === 'en' ? 'active' : '' ?>" href="<?= vm_h(vm_lang_url('en')) ?>">EN</a>
      <a class="<?= vm_lang() === 'am' ? 'active' : '' ?>" href="<?= vm_h(vm_lang_url('am')) ?>">AM</a>
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
