<?php
require_once __DIR__ . '/../includes/functions.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: ../checklist-generator.php'); exit; }
vm_verify_csrf();
$id = vm_save_submission('checklist', array_map(fn($v) => is_string($v) ? substr(trim($v), 0, 800) : $v, $_POST));
header('Location: ../checklist-generator.php?saved=' . rawurlencode($id));
exit;
