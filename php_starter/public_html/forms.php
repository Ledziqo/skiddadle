<?php $pageTitle='Official Visa Forms Library — VisaMenged'; include __DIR__.'/includes/header.php'; $resources = array_values(array_filter(vm_load_json('official_resources_top25.json'), 'vm_resource_visible')); ?>
<section class="page-head"><h1>Official Visa Forms & Embassy Resources</h1><p>Preview, download, and organize official visa forms, checklists, and portal links for applicants applying from Ethiopia.</p></section>
<div class="toolbar"><input id="resourceSearch" placeholder="Search country, visa type, or form name..."><select id="resourceType"><option value="">All types</option><option value="downloadable_official_pdf">Downloadable PDFs</option><option value="official_online_portal">Online portals</option><option value="official_requirements_page">Requirements pages</option></select></div>
<section class="resource-grid" id="resourcesGrid">
<?php foreach ($resources as $r): ?>
  <article class="resource-card" data-search="<?= vm_h(strtolower(($r['country']??'').' '.($r['visa_type']??'').' '.($r['title']??'').' '.($r['resource_status']??''))) ?>" data-type="<?= vm_h($r['resource_status'] ?? '') ?>">
    <span class="badge"><?= vm_h($r['country'] ?? '') ?></span>
    <h3><?= vm_h($r['title'] ?? '') ?></h3>
    <p><?= vm_h($r['notes'] ?? '') ?></p>
    <small><?= vm_h($r['source_org'] ?? '') ?> • <?= vm_h($r['resource_status'] ?? '') ?></small>
    <div class="actions">
      <a class="btn small" href="<?= vm_h($r['url'] ?? '#') ?>" target="_blank" rel="noopener"><?= vm_h(vm_resource_action_label($r)) ?></a>
      <button class="btn small" onclick='VM_Basket.add(<?= json_encode(['title'=>$r['title']??'', 'country'=>$r['country']??'', 'url'=>$r['url']??'']) ?>)'>Add to My File</button>
    </div>
  </article>
<?php endforeach; ?>
</section>
<?php include __DIR__.'/includes/footer.php'; ?>