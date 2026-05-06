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
        'united-states' => [
            'focus' => 'U.S. interviews are usually short and direct. Practice concise answers about purpose, funding, travel history, and why you will return to Ethiopia.',
            'rows' => [
                ['What is the purpose of your trip to the United States?', 'State the exact purpose in one sentence, then connect it to your itinerary, event, family visit, study, treatment or business meeting.'],
                ['Who will pay for your travel and stay?', 'Name the payer, explain the income/source briefly, and point to matching bank or employment evidence.'],
                ['What ties do you have in Ethiopia?', 'Use concrete facts: job role, business, school, family responsibilities, property, or fixed obligations.'],
                ['Have you traveled before or been refused before?', 'Answer honestly and briefly. If refused, say what changed and which evidence is stronger now.'],
            ],
        ],
        'united-kingdom' => [
            'focus' => 'UK visitor questions usually test genuine visitor intent, affordability, accommodation, and whether your story matches the online application.',
            'rows' => [
                ['Why are you visiting the UK now?', 'Give the reason, dates, and who/what you will visit. Keep it identical to the application and invitation.'],
                ['How much will the trip cost and who pays?', 'Mention the estimated total, your income or sponsor support, and the documents that prove it.'],
                ['Where will you stay in the UK?', 'Give the hotel or host address and connect it to booking or invitation evidence.'],
                ['What will make you return to Ethiopia?', 'Use work, business, school, family, or obligations with dates after the trip.'],
            ],
        ],
        'canada' => [
            'focus' => 'Canada questions often check temporary purpose, funds, family/work ties, travel history, and whether the visit makes sense for your situation.',
            'rows' => [
                ['Why do you want to visit Canada?', 'State the temporary purpose and connect it to the invitation, itinerary, event, family visit, or tourism plan.'],
                ['Who is inviting you or where will you stay?', 'Name the host or hotel and explain the relationship or booking clearly.'],
                ['How will you pay for the visit?', 'Show the payer, available funds, employment/business income, and any sponsor proof without exaggeration.'],
                ['Why will you leave Canada on time?', 'Point to fixed Ethiopia ties and the exact return date or obligation after travel.'],
            ],
        ],
        'china' => [
            'focus' => 'China interviews or center questions focus on visa category, invitation details, itinerary, employer/school connection, and whether documents match the category.',
            'rows' => [
                ['Why did you choose this China visa category?', 'Name the category and connect it to your invitation, itinerary, admission, employment, or business purpose.'],
                ['Who is your inviting organization or host in China?', 'Give the organization/person name, location, relationship, and what document proves it.'],
                ['What is your exact travel plan in China?', 'Use cities, dates, meetings, school/treatment location, or tourism stops that match bookings.'],
                ['What do you do in Ethiopia?', 'Explain your job, business, study, or responsibilities and why you return after the trip.'],
            ],
        ],
        'india' => [
            'focus' => 'India questions commonly test purpose clarity, medical/business/student invitation proof, funding, and health/travel-document consistency.',
            'rows' => [
                ['Why are you traveling to India?', 'State tourist, business, medical, study or family purpose and connect it to the official invitation or itinerary.'],
                ['Which hospital, company, school or city will you visit?', 'Give names and dates exactly as shown in the invitation, booking, or admission/treatment letter.'],
                ['Who is paying for the trip?', 'Explain self-funding, employer support, hospital cost plan, or family support with matching documents.'],
                ['When will you return to Ethiopia?', 'Use return ticket/date and Ethiopia commitments after travel.'],
            ],
        ],
        'australia' => [
            'focus' => 'Australia questions usually test genuine temporary stay, funds, ties, invitation, and whether uploaded evidence supports the claimed purpose.',
            'rows' => [
                ['What is your reason for visiting Australia?', 'Give a temporary reason with dates and supporting invitation, itinerary, event, or family details.'],
                ['How will you support yourself during the stay?', 'Mention funds, income, sponsor support if any, and matching bank or employment evidence.'],
                ['What obligations bring you back to Ethiopia?', 'Use specific job, business, school, family, property, or deadline facts.'],
                ['Have you complied with visas before?', 'Answer truthfully and keep the answer short; connect to travel history if available.'],
            ],
        ],
        'ireland' => [
            'focus' => 'Ireland questions focus on purpose, host/accommodation, finances, immigration history, and proof that the visit is temporary.',
            'rows' => [
                ['Why are you going to Ireland?', 'State the purpose and connect it to host, event, tourism, study, business or family evidence.'],
                ['Where will you stay and who will host you?', 'Name the host/hotel and explain relationship, address, and proof.'],
                ['Can you afford the trip?', 'Give the payer and show how bank statements, income, or employer proof support the cost.'],
                ['What are your reasons to return to Ethiopia?', 'Use concrete ties and dates after the visit.'],
            ],
        ],
    ];

    if (isset($profiles[$countrySlug])) {
        return $profiles[$countrySlug];
    }

    if ($hub === 'schengen') {
        return [
            'focus' => 'Schengen interviews focus on main destination, itinerary consistency, insurance, accommodation, funds, and return ties.',
            'rows' => [
                ['Why are you applying through this Schengen country?', 'Explain main destination, longest stay, or first entry using itinerary and booking dates.'],
                ['What is your day-by-day travel plan?', 'Mention cities, dates, accommodation and transport that match the file.'],
                ['Do you have travel insurance and accommodation?', 'Answer with the policy dates/coverage and hotel or host evidence.'],
                ['How will you pay and why will you return?', 'Connect bank/employment proof with Ethiopia ties after the trip.'],
            ],
        ];
    }

    if (in_array($hub, ['uae','saudi','qatar','oman','kuwait','bahrain','jordan','lebanon','israel'], true)) {
        return [
            'focus' => 'Middle East and GCC-style interviews often focus on host/sponsor, work or visit purpose, accommodation, funding, and overstay risk.',
            'rows' => [
                ['Who is your host, sponsor, company or hotel?', 'Give the exact name, relationship, address, and document that supports it.'],
                ['What will you do during the stay?', 'Keep the answer limited to the visa purpose: visit, business, transit, medical, pilgrimage, work or family.'],
                ['Who pays for the trip and where will you stay?', 'Connect the payer and accommodation to receipts, bookings, employer documents or invitation.'],
                ['When are you returning to Ethiopia?', 'Give the return date and the job, business, family or obligation waiting for you.'],
            ],
        ];
    }

    if (str_contains($region, 'Africa')) {
        return [
            'focus' => 'Regional Africa interviews usually check travel purpose, host or hotel, business/medical/conference reason, vaccination/travel documents, and return timing.',
            'rows' => [
                ['What is the exact purpose of this regional trip?', 'State tourism, business, conference, family, medical, study or transit and connect it to proof.'],
                ['Who will you meet or where will you stay?', 'Name the host, company, hospital, school, conference or hotel and match it to documents.'],
                ['How long will you stay and how will you travel?', 'Give dates, route, ticket/itinerary details and return plan.'],
                ['What work, family or business do you return to in Ethiopia?', 'Use simple facts that show the trip is temporary.'],
            ],
        ];
    }

    if (str_contains($region, 'Asia')) {
        return [
            'focus' => 'Asia destination interviews tend to test category choice, invitation or itinerary, funds, employer/school connection, and travel-history consistency.',
            'rows' => [
                ['Why did you choose this visa type for this country?', 'Connect the category to your invitation, itinerary, study, business, treatment or tourism evidence.'],
                ['Who invited you or what places will you visit?', 'Use the host/company/school/hospital name or a clear city-by-city itinerary.'],
                ['How will the travel costs be covered?', 'Name the payer and connect funds to bank, income, employer, scholarship or medical cost proof.'],
                ['What will you do after returning to Ethiopia?', 'Use work, school, business, family, or future obligations with dates.'],
            ],
        ];
    }

    return [
        'focus' => 'This interview prep focuses on purpose, funds, accommodation, travel dates, return ties, and consistency across the full visa file.',
        'rows' => [
            ['Why are you traveling for this visa purpose?', 'Answer in one sentence, then connect the purpose to one document in your file.'],
            ['How long do you plan to stay?', 'Give the exact dates and make sure they match itinerary, leave letter, booking and insurance.'],
            ['Who is paying for the trip?', 'Name the payer and explain the income or available funds shown in evidence.'],
            ['Why will you return to Ethiopia?', 'Use concrete facts such as job, business, school, family, property, or fixed obligations.'],
        ],
    ];
}

function vm_interview_questions(string $countrySlug, string $visaType): array
{
    $type = strtolower($visaType);
    $profile = vm_interview_country_profile($countrySlug);
    $rows = (array)$profile['rows'];

    if (str_contains($type, 'business')) {
        $rows[] = ['What business activity will happen?', 'Name the meeting, company, agenda, date and how it connects to your Ethiopian work or business.'];
    } elseif (str_contains($type, 'student') || str_contains($type, 'study')) {
        $rows[] = ['Why did you choose this school or program?', 'Connect the course to your education/work history and explain how costs will be paid.'];
    } elseif (str_contains($type, 'medical')) {
        $rows[] = ['What treatment are you seeking and who arranged it?', 'Name the hospital/doctor, appointment date, cost plan and local medical referral if available.'];
    } elseif (str_contains($type, 'family') || str_contains($type, 'friend')) {
        $rows[] = ['What is your relationship with the person you will visit?', 'Explain the relationship naturally and point to invitation, host ID/status and accommodation proof.'];
    }
    $rows[] = ['What documents support your answer?', 'Name the exact document group: form, invitation, bank proof, employment letter, itinerary, insurance, admission, medical letter or refusal explanation.'];
    $rows[] = ['What changed if your situation looks risky?', 'Explain the new evidence, corrected detail, stronger tie, clearer funding source or improved purpose proof without blaming anyone.'];
    $rows[] = ['What will you do if your travel plan changes?', 'Say you will follow the official visa conditions and update bookings or documents only through the proper process.'];
    $rows[] = ['Why is the length of stay reasonable?', 'Connect the stay length to leave approval, itinerary, appointment dates, business agenda, school calendar or treatment schedule.'];

    return array_slice($rows, 0, 10);
}

function vm_interview_readiness_plan(array $payload): array
{
    $country = trim((string)($payload['country'] ?? ''));
    $visaType = trim((string)($payload['visa_type'] ?? ''));
    $weak = strtolower((string)($payload['weak_area'] ?? ''));
    $notes = trim((string)($payload['notes'] ?? ''));
    $profile = vm_interview_country_profile($country);
    $questionRows = vm_interview_questions($country, $visaType);
    $answerStructure = [
        'Answer directly in the first sentence.',
        'Add one real fact from your documents: job, school, invitation, hotel, bank proof, itinerary or family tie.',
        'Keep answers short, calm and consistent with the application form.',
        'Do not memorize a speech. Practice clear answers that match your evidence.',
    ];
    $practiceGuide = [
        'Round 1: answer every question out loud in 20-40 seconds without reading notes.',
        'Round 2: repeat the answer and point to the exact document that proves it.',
        'Round 3: practice the weakest topic twice: money, purpose, return ties, invitation, previous refusal or travel history.',
        'Final check: compare every spoken answer against the form. If one answer changes the story, fix the document or the wording before submission.',
    ];
    $warnings = [
        'Do not give a purpose that differs from the application form or invitation.',
        'Do not guess dates, job details, salary, sponsor details or hotel names.',
        'Do not promise approval or argue with the officer. Keep answers factual.',
    ];

    if (str_contains($weak, 'money') || str_contains($notes, 'bank') || str_contains($notes, 'fund')) {
        $warnings[] = 'Prepare a simple explanation of who pays, why they can pay, and which documents prove it.';
    }
    if (str_contains($weak, 'return') || str_contains($notes, 'ties')) {
        $warnings[] = 'Practice a strong return answer using work, business, school, family, property or obligations in Ethiopia.';
    }
    if (str_contains($weak, 'refusal') || str_contains($notes, 'refusal')) {
        $warnings[] = 'If asked about a previous refusal, answer calmly and explain what changed with new evidence.';
    }

    return [
        'title' => 'Interview readiness plan',
        'country' => $country,
        'visa_type' => $visaType,
        'country_focus' => (string)$profile['focus'],
        'question_rows' => $questionRows,
        'questions' => array_map(static fn(array $row): string => (string)$row[0], $questionRows),
        'answer_structure' => $answerStructure,
        'practice_guide' => $practiceGuide,
        'warnings' => array_values(array_unique($warnings)),
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    vm_verify_csrf();
    $payload = [
        'name' => vm_input('name'),
        'contact' => vm_input('contact'),
        'country' => vm_input('country'),
        'visa_type' => vm_input('visa_type'),
        'interview_date' => vm_input('interview_date'),
        'weak_area' => vm_input('weak_area'),
        'notes' => vm_input('notes', 1200),
    ];
    $payload['plan'] = vm_interview_readiness_plan($payload);
    $payload['submission_id'] = vm_save_submission('interview-readiness', $payload);
    $result = $payload;
}

vm_page_start('Interview Readiness Pack for Ethiopian Visa Applicants', 'Prepare for visa interviews with country-specific questions, recommended answer angles, red-flag warnings and a practical readiness plan for Ethiopian applicants.');
?>
<section class="page-hero pricing-hero">
  <span class="eyebrow">Interview Readiness Pack</span>
  <h1>Practice the answers before the officer asks.</h1>
  <p>Get country-specific interview questions, recommended answer angles, and warnings for answers that can make a file look inconsistent.</p>
  <div class="hero-actions">
    <a class="button" href="#interview-form">Prepare interview</a>
    <a class="button ghost" href="<?= vm_url('pricing.php') ?>">See all services</a>
  </div>
</section>

<section class="review-value">
  <article><h3>Country questions</h3><p>Questions change by destination, visa type, purpose, funding, and return-ties risk.</p></article>
  <article><h3>Recommended answers</h3><p>Answer angles show what facts to mention and which documents should support the answer.</p></article>
  <article><h3>Red-flag warnings</h3><p>Topics to prepare carefully before the interview, especially dates, money, purpose and previous refusals.</p></article>
</section>

<?php if ($result): $plan = $result['plan']; ?>
<section class="notice success">
  <strong>Interview plan ready.</strong>
  <p>Your request ID is <strong><?= vm_h($result['submission_id']) ?></strong>.</p>
</section>
<section class="result-panel">
  <div class="result-head">
    <div><span class="eyebrow">Practice pack</span><h2><?= vm_h($plan['title']) ?></h2></div>
    <span class="badge"><?= vm_h($plan['visa_type'] ?: 'Visa interview') ?></span>
  </div>
  <div class="brain-grid">
    <article><h3>Country focus</h3><p><?= vm_h($plan['country_focus']) ?></p></article>
    <article><h3>Country questions + recommended answers</h3>
      <div class="interview-answer-list">
        <?php foreach ((array)$plan['question_rows'] as $row): ?>
          <section>
            <strong><?= vm_h($row[0] ?? '') ?></strong>
            <p><?= vm_h($row[1] ?? '') ?></p>
          </section>
        <?php endforeach; ?>
      </div>
    </article>
    <article><h3>Answer structure</h3><?= vm_list_html($plan['answer_structure']) ?></article>
    <article><h3>Practice guide</h3><?= vm_list_html($plan['practice_guide']) ?></article>
    <article><h3>Red-flag warnings</h3><?= vm_list_html($plan['warnings']) ?></article>
  </div>
  <div class="actions"><button class="button secondary" type="button" data-print-target="interview">Print interview plan</button><a class="button ghost" href="<?= vm_url('pricing.php') ?>">Back to services</a></div>
</section>
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
