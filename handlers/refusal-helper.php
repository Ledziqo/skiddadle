<?php
require_once __DIR__ . '/../includes/functions.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: ../previous-refusal-helper.php'); exit; }
vm_verify_csrf();
$id = vm_save_submission('refusal-helper', array_map(fn($v) => is_string($v) ? substr(trim($v), 0, 1200) : $v, $_POST));
header('Location: ../previous-refusal-helper.php?saved=' . rawurlencode($id));
exit;
