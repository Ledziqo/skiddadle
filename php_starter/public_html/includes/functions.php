<?php
function vm_load_json($filename) {
    $path = __DIR__ . '/../data/' . basename($filename);
    if (!file_exists($path)) { return []; }
    $data = json_decode(file_get_contents($path), true);
    return is_array($data) ? $data : [];
}
function vm_h($value) { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function vm_resource_visible($resource) {
    return ($resource['resource_status'] ?? '') !== 'needs_verification';
}
function vm_country_resources($slug) {
    $items = vm_load_json('official_resources_top25.json');
    return array_values(array_filter($items, fn($r) => ($r['slug'] ?? '') === $slug && vm_resource_visible($r)));
}
function vm_resource_action_label($r) {
    $status = $r['resource_status'] ?? '';
    if ($status === 'downloadable_official_pdf') return 'Preview / Download PDF';
    if ($status === 'official_online_portal') return 'Open Official Portal';
    if ($status === 'official_requirements_page') return 'Open Official Requirements';
    return 'Open Resource';
}
?>