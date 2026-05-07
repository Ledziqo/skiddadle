<?php
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/smart-engine.php';
$countries = vm_countries();
$result = null;
function vm_interview_country_profile(string $countrySlug): array
{
    $country = vm_country_by_slug($countrySlug) ?? [];
    $hub = (string)($country['hub'] ?? '');
    $region = (string)($country['region'] ?? '');
    $profiles = [
        'united-states' => ['focus' => 'U.S. interviews are usually short and direct. Practice concise answers about purpose, funding, travel history, and why you will return to Ethiopia.', 'rows' => [['What is the purpose of your trip to the United States?', 'State the exact purpose in one sentence, then connect it to your itinerary, event, family visit, study, treatment or business meeting.'], ['Who will pay for your travel and stay?', 'Name the payer, explain the income/source briefly, and point to matching bank or employment evidence.'], ['What ties do you have in Ethiopia?', 'Use concrete facts: job role, business, school, family responsibilities, property, or fixed obligations.'], ['Have you traveled before or been refused before?', 'Answer honestly and briefly. If refused, say what changed and which evidence is stronger now.']]],
    ];
    if (isset($profiles[$countrySlug])) { return $profiles[$countrySlug]; }
    if ($hub === 'schengen') { return ['focus' => 'Schengen interviews focus on main destination, itinerary consistency, insurance, accommodation, funds, and return ties.', 'rows' => [['Why are you applying through this Schengen country?', 'Explain main destination, longest stay, or first entry using itinerary and booking dates.'], ['What is your day-by-day travel plan?', 'Mention cities, dates, accommodation and transport that match the file.'], ['Do you have travel insurance and accommodation?', 'Answer with the policy dates/coverage and hotel or host evidence.'], ['How will you pay and why will you return?', 'Connect bank/employment proof with Ethiopia ties after the trip.']]]; }
    return ['focus' => 'This interview prep focuses on purpose, funds, accommodation, travel dates, return ties, and consistency across the full visa file.', 'rows' => [['Why are you traveling for this visa purpose?', 'Answer in one sentence, then connect the purpose to one document in your file.'], ['How long do you plan to stay?', 'Give the exact dates and make sure they match itinerary, leave letter, booking and insurance.'], ['Who is paying for the trip?', 'Name the payer and explain the income or available funds shown in evidence.'], ['Why will you return to Ethiopia?', 'Use concrete facts such as job, business, school, family, property, or fixed obligations.']]];
}
function vm_interview_questions(string $countrySlug, string $visaType): array { $profile = vm_interview_country_profile($countrySlug); return array_slice((array)$profile['rows'], 0, 10); }
function vm_interview_readiness_plan(array $payload): array
{
    $country = trim((string)($payload['country'] ?? ''));
    $visaType = trim((string)($payload['visa_type'] ?? ''));
    $profile = vm_interview_country_profile($country);
    $questionRows = vm_interview_questions($country, $visaType);
    return ['title' => 'Interview readiness plan', 'country' => $country, 'visa_type' => $visaType, 'country_focus' => (string)$profile['focus'], 'question_rows' => $questionRows, 'questions' => array_map(static fn(array $row): string => (string)$row[0], $questionRows), 'answer_structure' => ['Answer directly in the first sentence.', 'Add one real fact from your documents.', 'Keep answers short, calm and consistent.', 'Do not memorize a speech.'], 'practice_guide' => ['Round 1: answer every question out loud.', 'Round 2: repeat and point to the exact document.', 'Round 3: practice the weakest topic twice.', 'Final check: compare every spoken answer against the form.'], 'warnings' => ['Do not give a purpose that differs from the application form or invitation.', 'Do not guess dates, job details, salary, sponsor details or hotel names.', 'Do not promise approval or argue with the officer.']];
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') { vm_verify_csrf(); $payload = ['name' => vm_input('name'), 'contact' => vm_input('contact'), 'country' => vm_input('country'), 'visa_type' => vm_input('visa_type'), 'interview_date' => vm_input('interview_date'), 'weak_area' => vm_input('weak_area'), 'notes' => vm_input('notes', 1200)]; $payload['plan'] = vm_interview_readiness_plan($payload); $payload['submission_id'] = vm_save_submission('interview-readiness', $payload); $result = $payload; }
vm_page_start('Interview Readiness Pack for Ethiopian Visa Applicants', 'Prepare for visa interviews with country-specific questions, recommended answer angles, red-flag warnings and a practical readiness plan for Ethiopian applicants.');
?>
<section class="page-hero pricing-hero">
  <span class="eyebrow">Free interview prep</span>
  <h1>Practice the answers before the officer asks.</h1>
  <p>Get country-specific interview questions, recommended answer angles, and warnings for answers that can make a file look inconsistent.</p>
  <div class="hero-actions"><a class="button" href="#interview-form">Prepare interview</a><a class="button ghost" href="<?= vm_url('free-tools.php') ?>">See free tools</a></div>
</section>
<section class="review-value"><article><h3>Country questions</h3><p>Questions change by destination, visa type, purpose, funding, and return-ties risk.</p></article><article><h3>Recommended answers</h3><p>Answer angles show what facts to mention and which documents should support the answer.</p></article><article><h3>Red-flag warnings</h3><p>Topics to prepare carefully before the interview, especially dates, money, purpose and previous refusals.</p></article></section>
<?php if ($result): $plan = $result['plan']; ?>
<section class="notice success"><strong>Interview plan ready.</strong><p>Your request ID is <strong><?= vm_h($result['submission_id']) ?></strong>.</p></section>
<section class="result-panel"><div class="result-head"><div><span class="eyebrow">Practice pack</span><h2><?= vm_h($plan['title']) ?></h2></div><span class="badge"><?= vm_h($plan['visa_type'] ?: 'Visa interview') ?></span></div><div class="brain-grid"><article><h3>Country focus</h3><p><?= vm_h($plan['country_focus']) ?></p></article><article><h3>Country questions + recommended answers</h3><div class="interview-answer-list"><?php foreach ((array)$plan['question_rows'] as $row): ?><section><strong><?= vm_h($row[0] ?? '') ?></strong><p><?= vm_h($row[1] ?? '') ?></p></section><?php endforeach; ?></div></article><article><h3>Answer structure</h3><?= vm_list_html($plan['answer_structure']) ?></article><article><h3>Practice guide</h3><?= vm_list_html($plan['practice_guide']) ?></article><article><h3>Red-flag warnings</h3><?= vm_list_html($plan['warnings']) ?></article></div><div class="actions"><button class="button secondary" type="button" data-print-target="interview">Print interview plan</button><a class="button ghost" href="<?= vm_url('free-tools.php') ?>">Back to free tools</a></div></section>
<?php else: ?>
<form class="card form-card" id="interview-form" method="post">
  <?= vm_csrf_field() ?>
  <div class="form-grid">
    <label>Name <input name="name" required></label>
    <label>Contact (optional) <input name="contact"></label>
    <label>Destination country <select name="country" required><option value="">Select</option><?php foreach ($countries as $country): ?><option value="<?= vm_h($country['slug']) ?>"><?= vm_h($country['name']) ?></option><?php endforeach; ?></select></label>
    <label>Visa type <?= vm_visa_type_select('visa_type') ?></label>
    <label>Interview date <input type="date" name="interview_date"></label>
    <label>Weakest area <select name="weak_area"><option>Purpose of travel</option><option>Money/funding explanation</option><option>Return ties to Ethiopia</option><option>Previous refusal</option><option>Not sure</option></select></label>
  </div>
  <label>What are you worried they may ask? <textarea name="notes" rows="5" placeholder="Example: my bank statement, why I will return, previous refusal, invitation, travel history, or study/business reason."></textarea></label>
  <button class="button" type="submit">Build interview readiness plan</button>
</form>
<?php endif; ?>
<?php require __DIR__ . '/includes/disclaimer.php'; vm_page_end(); ?>
