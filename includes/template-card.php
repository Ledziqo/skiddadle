<?php
$template = $template ?? [];
?>
<article class="card template-card">
  <div class="card-top">
    <span class="badge"><?= vm_h($template['category'] ?? 'template') ?></span>
    <span class="badge muted"><?= vm_h($template['priceTier'] ?? 'basic') ?></span>
  </div>
  <h3><?= vm_h($template['title'] ?? 'Template') ?></h3>
  <p><?= vm_h($template['description'] ?? '') ?></p>
  <p class="muted">Used for: <?= vm_h(implode(', ', (array)($template['usedFor'] ?? []))) ?></p>
  <div class="actions">
    <a class="button" href="<?= vm_url('letter-generator.php?template=' . vm_h($template['id'] ?? '')) ?>">Start draft</a>
  </div>
</article>
