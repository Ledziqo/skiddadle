<?php
function clean($v){ return trim(strip_tags((string)$v)); }
$data = [
  'created_at' => date('c'),
  'country' => clean($_POST['country'] ?? ''),
  'visa_type' => clean($_POST['visa_type'] ?? ''),
  'employment_status' => clean($_POST['employment_status'] ?? ''),
  'funding' => clean($_POST['funding'] ?? ''),
  'previous_refusal' => clean($_POST['previous_refusal'] ?? ''),
  'contact' => clean($_POST['contact'] ?? ''),
];
$dir = __DIR__ . '/../storage/submissions';
if (!is_dir($dir)) { @mkdir($dir, 0755, true); }
@file_put_contents($dir.'/checklist_'.date('Ymd_His').'_'.bin2hex(random_bytes(3)).'.json', json_encode($data, JSON_PRETTY_PRINT));
header('Location: /checklist-generator.php?submitted=1');
exit;
?>