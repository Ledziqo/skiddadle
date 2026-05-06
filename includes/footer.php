</main>
<footer class="site-footer">
  <div class="footer-brand">
    <strong>VisaMenged</strong>
    <p><?= vm_h(VM_DISCLAIMER) ?></p>
    <p class="footer-tagline">Made for Ethiopian visa applicants 🇪🇹</p>
  </div>
  <div class="footer-columns">
    <div class="footer-col">
      <strong>Quick links</strong>
      <a href="<?= vm_url('index.php') ?>">Home</a>
      <a href="<?= vm_url('forms.php') ?>">Official resources</a>
      <a href="<?= vm_url('checklist-generator.php') ?>">Checklist generator</a>
      <a href="<?= vm_url('pricing.php') ?>">Services</a>
    </div>
    <div class="footer-col">
      <strong>Support</strong>
      <a href="<?= vm_url('about.php') ?>">About</a>
      <a href="<?= vm_url('contact.php') ?>">Contact</a>
      <a href="<?= vm_url('templates.php') ?>">Templates</a>
      <a href="<?= vm_url('review-request.php') ?>">Document review</a>
    </div>
    <div class="footer-col">
      <strong>Contact</strong>
      <a href="mailto:<?= vm_h((string)vm_config_path('contact.email', 'Aesliexx@gmail.com')) ?>"><?= vm_h((string)vm_config_path('contact.email', 'Aesliexx@gmail.com')) ?></a>
      <a href="<?= vm_h((string)vm_config_path('contact.telegram_url', 'https://t.me/Aesliex')) ?>" target="_blank" rel="noopener">@<?= vm_h((string)vm_config_path('contact.telegram_handle', 'Aesliex')) ?></a>
    </div>
  </div>
</footer>
<button type="button" class="back-to-top" data-back-to-top aria-label="Back to top">↑</button>
<a class="telegram-float" href="<?= vm_h((string)vm_config_path('contact.telegram_url', 'https://t.me/Aesliex')) ?>" target="_blank" rel="noopener">Ask on Telegram</a>
<script src="<?= vm_url('assets/js/app.js') ?>"></script>
<script src="<?= vm_url('assets/js/basket.js') ?>"></script>
<?php
$paddleToken = (string)(getenv('PADDLE_CLIENT_TOKEN') ?: ($_ENV['PADDLE_CLIENT_TOKEN'] ?? $_SERVER['PADDLE_CLIENT_TOKEN'] ?? ''));
$paddleEnv = (string)(getenv('PADDLE_ENV') ?: ($_ENV['PADDLE_ENV'] ?? $_SERVER['PADDLE_ENV'] ?? 'sandbox'));
?>
<?php if ($paddleToken !== ''): ?>
<script src="https://cdn.paddle.com/paddle/v2/paddle.js"></script>
<script>
window.VM_PADDLE = {
  token: <?= json_encode($paddleToken, JSON_UNESCAPED_SLASHES) ?>,
  env: <?= json_encode($paddleEnv, JSON_UNESCAPED_SLASHES) ?>
};
</script>
<?php endif; ?>
</body>
</html>
