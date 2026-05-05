<?php
require_once __DIR__ . '/../includes/functions.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: ../contact.php'); exit; }
vm_verify_csrf();
$id = vm_save_submission('contact', array_map(fn($v) => is_string($v) ? substr(trim($v), 0, 1500) : $v, $_POST));
header('Location: ../contact.php?saved=' . rawurlencode($id));
exit;
