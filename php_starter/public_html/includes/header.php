<?php require_once __DIR__ . '/functions.php'; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= vm_h($pageTitle ?? 'VisaMenged') ?></title>
  <meta name="description" content="VisaMenged helps Ethiopian applicants find official visa forms, embassy resources, checklists, and document support.">
  <link rel="stylesheet" href="/assets/css/style.css">
</head>
<body>
<header class="site-header">
  <a class="brand" href="/">Visa<span>Menged</span></a>
  <nav>
    <a href="/forms.php">Forms</a>
    <a href="/checklist-generator.php">Checklist</a>
    <a href="/templates.php">Templates</a>
    <a href="/pricing.php">Pricing</a>
    <a href="/contact.php">Contact</a>
  </nav>
</header>
<main>