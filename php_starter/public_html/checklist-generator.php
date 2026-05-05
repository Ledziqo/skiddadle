<?php $pageTitle='Checklist Generator — VisaMenged'; include __DIR__.'/includes/header.php'; ?>
<section class="page-head"><h1>Find My Visa Checklist</h1><p>No login required. Answer a few questions and get a starter document plan.</p></section>
<form class="form-card" method="post" action="/handlers/checklist.php">
<label>Destination country <select name="country" required><?php foreach(vm_load_json('countries_top25.json') as $c): ?><option value="<?= vm_h($c['slug']) ?>"><?= vm_h($c['name']) ?></option><?php endforeach; ?></select></label>
<label>Visa type <input name="visa_type" placeholder="Visitor, business, student, work..." required></label>
<label>Employment status <select name="employment_status"><option>Employed</option><option>Self-employed</option><option>Business owner</option><option>Student</option><option>Unemployed</option><option>Retired</option></select></label>
<label>Funding <select name="funding"><option>Self-funded</option><option>Sponsored by parent</option><option>Sponsored by spouse</option><option>Sponsored by company</option><option>Sponsored by host abroad</option><option>Mixed funding</option></select></label>
<label>Previous refusal? <select name="previous_refusal"><option>No</option><option>Yes</option></select></label>
<label>Contact <input name="contact" placeholder="Email / WhatsApp / Telegram"></label>
<button class="btn primary" type="submit">Generate / Request Checklist</button>
</form>
<?php include __DIR__.'/includes/footer.php'; ?>