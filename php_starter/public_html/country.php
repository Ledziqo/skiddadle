<?php
require_once __DIR__.'/includes/functions.php';
$slug=$_GET['slug'] ?? '';
$countries=vm_load_json('countries_top25.json');
$country=null;
foreach($countries as $c){ if(($c['slug']??'')===$slug){$country=$c;break;}}
if(!$country){http_response_code(404); $pageTitle='Country not found'; include __DIR__.'/includes/header.php'; echo '<section class="page-head"><h1>Country not found</h1></section>'; include __DIR__.'/includes/footer.php'; exit;}
$pageTitle=$country['name'].' Visa Guide — VisaMenged';
include __DIR__.'/includes/header.php';
$resources=vm_country_resources($slug);
?>
<section class="page-head"><p class="eyebrow">Country guide</p><h1><?= vm_h($country['name']) ?> visa resources from Ethiopia</h1><p><?= vm_h($country['note']) ?></p><span class="badge">Status: <?= vm_h($country['status']) ?></span></section>
<section><h2>Official resources</h2><div class="resource-grid"><?php foreach($resources as $r): ?><article class="resource-card"><span class="badge"><?= vm_h($r['visa_type']??'') ?></span><h3><?= vm_h($r['title']??'') ?></h3><p><?= vm_h($r['notes']??'') ?></p><a class="btn small" href="<?= vm_h($r['url']??'#') ?>" target="_blank" rel="noopener"><?= vm_h(vm_resource_action_label($r)) ?></a></article><?php endforeach; ?></div></section>
<section class="info-box"><h2>Disclaimer</h2><p>VisaMenged gathers public official resources and creates support checklists/templates. Always verify the latest requirements directly with the official embassy, government, VFS, TLS, or visa-center source before submitting.</p></section>
<?php include __DIR__.'/includes/footer.php'; ?>