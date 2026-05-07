<?php
require_once __DIR__ . '/functions.php';
if (!headers_sent()) {
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Pragma: no-cache');
    header('Expires: 0');
}
$vm_css_path = __DIR__ . '/../assets/css/style.css';
clearstatcache(true, $vm_css_path);
$vm_css_version = is_file($vm_css_path) ? (string)(filemtime($vm_css_path) . '-' . filesize($vm_css_path)) : '1';
?>
<!doctype html>
<html lang="<?= vm_h(vm_lang()) ?>">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= vm_h(($GLOBALS['vm_page_title'] ?? 'VisaMenged') . ' | VisaMenged') ?></title>
  <meta name="description" content="<?= vm_h($GLOBALS['vm_page_description'] ?? 'Official visa resources and document support for Ethiopian applicants.') ?>">
  <link rel="canonical" href="<?= vm_h(vm_canonical_url()) ?>">
  <link rel="alternate" hreflang="en" href="<?= vm_h(str_contains(vm_canonical_url(), '?') ? vm_canonical_url() . '&lang=en' : vm_canonical_url() . '?lang=en') ?>">
  <link rel="stylesheet" href="<?= vm_url('assets/css/style.css?v=' . $vm_css_version) ?>">
  <style>
    /* Critical homepage fallback if cached/deployed CSS is stale */
    .trust-strip{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:1px;background:#d8e8f8;border-bottom:1px solid #d8e8f8}
    .trust-strip article{background:#fff;padding:22px clamp(16px,5vw,70px);text-align:center}
    .trust-strip strong{display:block;font-size:clamp(32px,4vw,48px);line-height:1;color:#062d66}
    .trust-strip span{display:block;margin-top:4px;font-size:14px;font-weight:800;color:#445c78}
    .how-it-works,.free-tools,.preview-countries,.final-cta{padding:clamp(46px,7vw,92px) clamp(18px,5vw,72px)}
    .how-it-works,.preview-countries,.final-cta{background:#fffdf8}
    .free-tools{background:linear-gradient(135deg,#f6faff 0%,#ffffff 60%,#eef8ff 100%)}
    .simple-section-head{max-width:820px;margin:0 0 30px}
    .simple-section-head.row{display:flex;align-items:end;justify-content:space-between;gap:24px;max-width:none}
    .simple-section-head span{display:block;margin-bottom:10px;color:#1177e8;font-size:13px;font-weight:900;letter-spacing:.08em;text-transform:uppercase}
    .simple-section-head h2,.final-cta h2{margin:0;color:#07345f;font-size:clamp(34px,5vw,58px);line-height:.98;letter-spacing:-.04em}
    .simple-section-head p,.final-cta p{max-width:760px;color:#435771;font-size:18px;line-height:1.7}
    .steps-grid,.free-tools-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:22px}
    .steps-grid article,.free-tools-grid a{border:1px solid #d7e6f4;border-radius:26px;background:rgba(255,255,255,.86);box-shadow:0 22px 54px rgba(6,45,102,.09)}
    .steps-grid article{padding:28px}
    .steps-grid span{display:inline-grid;place-items:center;width:48px;height:48px;margin-bottom:22px;border-radius:16px;background:#eaf5ff;color:#1177e8;font-size:18px;font-weight:900}
    .steps-grid h3,.free-tools-grid strong,.service-card h3{margin:0 0 12px;color:#07345f;font-size:22px}
    .steps-grid p,.free-tools-grid span,.service-card p{margin:0;color:#435771;font-size:16px;line-height:1.65}
    .free-tools-grid a{display:block;padding:26px;text-decoration:none;transition:transform .2s ease,box-shadow .2s ease}
    .free-tools-grid a:hover{transform:translateY(-4px);box-shadow:0 28px 62px rgba(6,45,102,.14)}
    .free-tools-grid strong,.free-tools-grid span,.free-tools-grid em{display:block}
    .free-tools-grid em{margin-top:18px;color:#1177e8;font-style:normal;font-weight:900}
    .preview-countries-grid{display:flex;flex-wrap:wrap;gap:12px}
    .preview-countries-grid a{display:inline-flex;align-items:center;gap:8px;padding:10px 14px;border:1px solid #d7e6f4;border-radius:999px;background:#fff;color:#0a3158;font-weight:900;text-decoration:none;box-shadow:0 12px 28px rgba(6,45,102,.07)}
    .country-flag{font-size:18px}
    .final-cta{border-top:1px solid #d8e8f8;text-align:center}
    .final-cta h2,.final-cta p{margin-left:auto;margin-right:auto}
    .final-cta .button{margin-top:12px}
    @media(max-width:1100px){.steps-grid,.free-tools-grid{grid-template-columns:1fr}}
    @media(max-width:980px){.trust-strip{grid-template-columns:repeat(2,minmax(0,1fr))}}
    @media(max-width:760px){.simple-section-head.row{display:block}.how-it-works,.free-tools,.preview-countries,.final-cta{padding:38px 18px}}
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
    <button class="theme-switch" type="button" data-theme-toggle aria-label="Toggle light or dark mode" aria-pressed="false">
      <span>Light</span>
      <i aria-hidden="true"></i>
      <span>Dark</span>
    </button>
  </div>
</header>
<main>
