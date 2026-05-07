<?php
declare(strict_types=1);

require_once __DIR__ . '/smart-provider.php';

function vm_pick(array $options, int $seed, int $offset = 0): string
{
    if (!$options) { return ''; }
    return (string)$options[abs($seed + $offset) % count($options)];
}

function vm_smart_seed(array $payload): int
{
    $base = implode('|', array_map(static fn($v): string => is_scalar($v) ? (string)$v : json_encode($v), $payload));
    return (int)(hexdec(substr(hash('sha256', $base . microtime(true)), 0, 7)) % 1000000);
}

function vm_sentence_html(string $text): string
{
    return '<p>' . vm_h($text) . '</p>';
}

function vm_list_html(array $items): string
{
    $html = '<ul>';
    foreach ($items as $item) {
        $html .= '<li>' . vm_h((string)$item) . '</li>';
    }
    return $html . '</ul>';
}

function vm_letter_template_label(string $templateId): string
{
    return match ($templateId) {
        'sponsorship-letter', 'student-sponsorship-letter' => 'Sponsor statement draft',
        'invitation-letter' => 'Invitation letter draft',
        'employer-support-letter' => 'Employer leave/support draft',
        'previous-refusal-explanation' => 'Previous refusal explanation draft',
        'business-owner-introduction' => 'Business owner introduction draft',
        default => 'Visa cover letter draft',
    };
}

function vm_generate_letter_draft(array $payload): array
{
    $remoteDraft = vm_remote_letter_draft($payload);
    if ($remoteDraft !== null) {
        return $remoteDraft;
    }

    $seed = (int)($payload['variant_seed'] ?? vm_smart_seed($payload));
    $templateId = (string)($payload['template'] ?? 'visa-cover-letter');
    $name = trim((string)($payload['name'] ?? ''));
    $country = trim((string)($payload['country'] ?? ''));
    $purpose = trim((string)($payload['purpose'] ?? ''));
    $dates = trim((string)($payload['dates'] ?? '')) ?: 'the travel dates shown in my itinerary';
    $funding = trim((string)($payload['funding'] ?? '')) ?: 'the financial evidence included with my file';
    $ties = trim((string)($payload['ties'] ?? '')) ?: 'my continuing commitments in Ethiopia';
    $recipient = vm_pick(['Dear Visa Officer,', 'To the Visa Officer,', 'Dear Consular Officer,'], $seed, 1);
    $closing = vm_pick(['Sincerely,', 'Respectfully,', 'Kind regards,'], $seed, 2);
    $countryPhrase = $country !== '' ? $country : '[destination country]';
    $purposePhrase = $purpose !== '' ? $purpose : '[visa purpose]';
    $namePhrase = $name !== '' ? $name : '[applicant name]';
    $title = vm_letter_template_label($templateId);
    $introOptions = [
        "My name is {$namePhrase}. I am preparing an application for {$purposePhrase} to {$countryPhrase}.",
        "I am {$namePhrase}, and I am submitting this note to explain my planned {$purposePhrase} travel to {$countryPhrase}.",
        "This letter supports my visa application for {$countryPhrase}, where my purpose of travel is {$purposePhrase}.",
    ];
    $body = '<p>' . vm_h($recipient) . '</p>';

    switch ($templateId) {
        case 'sponsorship-letter':
        case 'student-sponsorship-letter':
            $body .= vm_sentence_html(vm_pick([
                "I, {$namePhrase}, am providing this sponsor statement to explain the financial support for travel to {$countryPhrase}.",
                "This statement confirms that I am the financial sponsor for the applicant's planned travel to {$countryPhrase}.",
                "I am writing to clarify how the applicant's expenses for {$countryPhrase} will be funded and supported.",
            ], $seed, 3));
            $body .= vm_sentence_html("The planned travel or study period is {$dates}. The purpose connected to this support is {$purposePhrase}.");
            $body .= vm_sentence_html("The funding evidence should clearly show the sponsor relationship, available funds, regular income or business source, and any major deposits that may need explanation.");
            $body .= vm_list_html([
                'Attach sponsor ID/passport copy and proof of relationship.',
                'Attach bank statements, income or business evidence, and source-of-funds notes.',
                'Make sure names, dates, currency amounts and cost responsibility match the application form.',
            ]);
            break;
        case 'invitation-letter':
            $body .= vm_sentence_html(vm_pick([
                "I, {$namePhrase}, am inviting the applicant to visit me in {$countryPhrase}.",
                "This invitation confirms the purpose, dates and hosting arrangement for the applicant's planned visit to {$countryPhrase}.",
                "I am writing as the host/inviter for the applicant's trip to {$countryPhrase}.",
            ], $seed, 4));
            $body .= vm_sentence_html("The visit is planned for {$dates}, and the purpose is {$purposePhrase}.");
            $body .= vm_sentence_html("The final letter should add the host address, relationship to the applicant, inviter legal status, phone/email, accommodation arrangement, and who will pay each cost.");
            break;
        case 'employer-support-letter':
            $body .= vm_sentence_html("This letter confirms that [employee name] works with [company name] and has approved leave for {$dates}.");
            $body .= vm_sentence_html("The travel purpose is {$purposePhrase} to {$countryPhrase}. The employee is expected to return to their position after the approved leave period.");
            $body .= vm_sentence_html("The final version should include job title, start date, salary, manager contact, company registration details and official stamp/signature if available.");
            break;
        case 'previous-refusal-explanation':
            $body .= vm_sentence_html(vm_pick([
                "I previously received a visa refusal for {$countryPhrase}, and I am submitting this explanation to show what has changed.",
                "This note responds to the previous refusal by separating the refusal concerns, new evidence and changed circumstances.",
                "I respectfully ask that my new application be considered with the updated evidence described below.",
            ], $seed, 5));
            $body .= vm_list_html([
                'Previous refusal concern: [copy the exact reason from the refusal letter].',
                'What changed: [new job, clearer funds, stronger ties, corrected form, new invitation, or better purpose evidence].',
                "Funding now shown by: {$funding}.",
                "Return ties now shown by: {$ties}.",
            ]);
            break;
        case 'business-owner-introduction':
            $body .= vm_sentence_html("My name is {$namePhrase}, and I am connected to [company name], a business operating in Ethiopia.");
            $body .= vm_sentence_html("I plan to travel to {$countryPhrase} for {$purposePhrase} during {$dates}.");
            $body .= vm_sentence_html("The final letter should attach business license, tax documents, bank evidence, meeting agenda, invitation or trade-event proof, and explain why I must return to continue operations in Ethiopia.");
            break;
        default:
            $body .= vm_sentence_html(vm_pick($introOptions, $seed, 6));
            $body .= vm_sentence_html(vm_pick([
                "My planned travel period is {$dates}, and my expenses will be covered through {$funding}.",
                "The application evidence explains my travel dates as {$dates} and my funding as {$funding}.",
                "For timing and finance, my file is organized around {$dates} and {$funding}.",
            ], $seed, 7));
            $body .= vm_sentence_html(vm_pick([
                "I intend to return to Ethiopia because of {$ties}.",
                "My return to Ethiopia is supported by {$ties}.",
                "The documents attached to my file are intended to show {$ties} and my reason to return after the trip.",
            ], $seed, 8));
            $body .= vm_sentence_html("I have organized my documents so the application form, purpose evidence, funding proof and return-ties evidence tell one consistent story.");
    }

    $body .= vm_sentence_html("For purpose evidence, I have tried to make the travel reason clear through the documents attached to the application. The details in this letter should be read together with the application form, itinerary, invitation or booking evidence, and any other documents that explain why the trip is needed.");
    $body .= vm_sentence_html("For financial evidence, the file should show how the trip will be paid for and why the available funds are reasonable for the planned stay. If a sponsor, employer, school, host or organization is involved, the final version should clearly explain the relationship and what costs they will cover.");
    $body .= vm_sentence_html("For return evidence, I intend the final file to show my ongoing responsibilities in Ethiopia, including {$ties}. These details are important because the application should make it clear that the visit is temporary and that I plan to follow the conditions of the visa.");
    $body .= vm_sentence_html(vm_pick([
        'I understand that the final decision belongs only to the official visa authority.',
        'I have tried to make the purpose, funding and return evidence clear for review.',
        'Please consider the attached documents together with this explanation.',
    ], $seed, 9));
    $body .= '<p>' . vm_h($closing) . '<br>' . vm_h($namePhrase) . '</p>';

    $rewriteTips = [
        'Replace every bracketed placeholder with real facts from your documents.',
        'Add exact dates, amounts and names only if they match your evidence.',
        'Use your own natural wording for personal reasons and family/business ties.',
        'Keep one clear story across the form, itinerary, invitation, bank proof and letter.',
    ];

    return [
        'title' => $title,
        'html' => $body,
        'seed' => $seed,
        'variation_label' => 'Variation ' . (($seed % 7) + 1),
        'rewrite_tips' => $rewriteTips,
        'risk_notes' => vm_letter_risk_notes($payload),
    ];
}

function vm_letter_risk_notes(array $payload): array
{
    $notes = [];
    $funding = strtolower((string)($payload['funding'] ?? ''));
    $templateId = (string)($payload['template'] ?? '');
    if (str_contains($funding, 'sponsor')) {
        $notes[] = 'Sponsor wording must be backed by relationship proof, bank evidence and source-of-funds explanation.';
    }
    if ($templateId === 'previous-refusal-explanation') {
        $notes[] = 'Do not argue with the refusal. Show evidence and changed circumstances.';
    }
    if (trim((string)($payload['ties'] ?? '')) === '') {
        $notes[] = 'Return ties are weak or missing in the answers. Add job, business, school, family or obligation evidence.';
    }
    return $notes;
}

function vm_refusal_smart_plan(array $payload): array
{
    $remotePlan = vm_remote_refusal_plan($payload);
    if ($remotePlan !== null) {
        return $remotePlan['steps'];
    }

    $reason = strtolower((string)($payload['reason'] ?? ''));
    $changes = trim((string)($payload['changes'] ?? ''));
    $items = [
        'Copy each refusal reason into a table and answer it with new evidence.',
        'Separate old evidence from new evidence so the improvement is easy to see.',
        'Keep the tone factual and calm. Do not blame the officer or embassy.',
    ];
    if (str_contains($reason, 'fund') || str_contains($reason, 'bank') || str_contains($reason, 'financial')) {
        $items[] = 'Prepare bank statements, income proof, source-of-funds notes and explanations for large deposits.';
    }
    if (str_contains($reason, 'ties') || str_contains($reason, 'return') || str_contains($reason, 'home')) {
        $items[] = 'Strengthen Ethiopia ties: work, business, school, family obligations, property or ongoing responsibilities.';
    }
    if (str_contains($reason, 'purpose') || str_contains($reason, 'itinerary') || str_contains($reason, 'visit')) {
        $items[] = 'Make the purpose story consistent across itinerary, invitation, accommodation, leave letter and application form.';
    }
    if ($changes === '') {
        $items[] = 'Before reapplying, identify at least two real changes since the refusal.';
    } else {
        $items[] = 'Use the changes you listed as section headings in the new explanation letter.';
    }
    return $items;
}

function vm_file_brain_analyze(array $payload): array
{
    $remoteBrain = vm_remote_file_brain($payload);
    if ($remoteBrain !== null) {
        return $remoteBrain;
    }

    $country = strtolower((string)($payload['country'] ?? ''));
    $visaType = strtolower((string)($payload['visa_type'] ?? ''));
    $funding = strtolower((string)($payload['funding'] ?? ''));
    $invitation = strtolower((string)($payload['invitation'] ?? ''));
    $employment = strtolower((string)($payload['employment_status'] ?? ''));
    $previousRefusal = strtolower((string)($payload['previous_refusal'] ?? ''));
    $notes = strtolower((string)($payload['notes'] ?? ''));
    $uploads = (array)($payload['uploads'] ?? []);
    $uploadGroups = (array)($payload['upload_groups'] ?? []);
    $uploadedDetails = (array)($payload['upload_details'] ?? []);
    $score = 82;
    $mustFix = [];
    $likelyMissing = [];
    $consistencyChecks = [
        'Application form names, passport number and date of birth match the passport exactly.',
        'Travel dates match itinerary, invitation, leave letter, accommodation and insurance.',
        'Purpose in the form matches purpose letter, invitation and supporting evidence.',
        'Bank statement owner, sponsor name and relationship proof are easy to connect.',
    ];
    $strengths = [];

    if (count($uploads) >= 4) {
        $strengths[] = 'Multiple documents were uploaded, so the file can be checked as a package.';
    } elseif (count($uploads) > 0) {
        $score -= 8;
        $mustFix[] = 'Upload more than one document if you want a real file audit. One file rarely shows the whole story.';
    } else {
        $score -= 18;
        $mustFix[] = 'No documents were uploaded. This can only be a surface pre-check until passport/form/funding/purpose evidence is provided.';
    }

    $extensions = array_map(static fn($name): string => strtolower(pathinfo((string)$name, PATHINFO_EXTENSION)), $uploads);
    if ($uploads && !in_array('pdf', $extensions, true)) {
        $score -= 5;
        $mustFix[] = 'PDF copies are easier to review and print. Convert key documents to PDF where possible.';
    }
    $expectedGroups = [
        'passport' => 'Passport/ID pages',
        'form' => 'Application form or portal confirmation',
        'money' => 'Bank/funding proof',
        'work' => 'Employment/business proof',
    ];
    foreach ($expectedGroups as $key => $label) {
        if (empty($uploadGroups[$key])) {
            $score -= 4;
            $likelyMissing[] = $label . ' was not uploaded in its document group.';
        }
    }

    if (str_contains($funding, 'sponsor')) {
        $score -= 8;
        $likelyMissing[] = 'Sponsor statement, sponsor ID, proof of relationship, sponsor bank statements and source-of-funds explanation.';
        $consistencyChecks[] = 'Sponsor cost responsibility matches the invitation/cover letter and application form.';
    }
    if (str_contains($invitation, 'family') || str_contains($invitation, 'business') || str_contains($invitation, 'school')) {
        $score -= 5;
        $likelyMissing[] = 'Invitation evidence: inviter ID/status, address, relationship or business reason, dates and who pays costs.';
        if (empty($uploadGroups['invitation'])) {
            $score -= 5;
            $mustFix[] = 'Invitation/admission/host proof was selected but not uploaded in that group.';
        }
    }
    if (str_contains($previousRefusal, 'yes') || str_contains($notes, 'refusal') || str_contains($notes, 'refused')) {
        $score -= 18;
        $mustFix[] = 'Previous refusal risk: do not submit the same file again. Add changed circumstances and evidence for each refusal reason.';
        $likelyMissing[] = 'Previous refusal letter, changed-circumstances explanation, and new evidence that fixes the refusal concerns.';
        if (empty($uploadGroups['refusal'])) {
            $mustFix[] = 'Previous refusal was selected, but the refusal letter was not uploaded in the refusal group.';
        }
    }
    if (str_contains($employment, 'unemployed')) {
        $score -= 10;
        $mustFix[] = 'Unemployed files need stronger return-ties and funding explanation because the reason to return may be questioned.';
    }
    if (str_contains($visaType, 'student') || str_contains($visaType, 'study')) {
        $likelyMissing[] = 'Admission letter, tuition/payment proof, education history, study plan and funding plan.';
    }
    if (str_contains($visaType, 'business')) {
        $likelyMissing[] = 'Business invitation, Ethiopian company travel letter, license/tax proof, meeting agenda and company bank evidence.';
    }
    if (str_contains($visaType, 'tourist') || str_contains($visaType, 'visitor')) {
        $likelyMissing[] = 'Clear itinerary, accommodation plan, employment/business proof, return ties and trip budget.';
    }
    if (str_contains($country, 'schengen') || in_array($country, ['germany','france','italy','netherlands','sweden','austria','belgium'], true)) {
        $likelyMissing[] = 'Schengen travel insurance, accommodation, round-trip reservation, VFS/TLS appointment proof and 45-day timing check.';
    }
    if (str_contains($country, 'china')) {
        $likelyMissing[] = 'China application center form confirmation, appointment/portal record, invitation where required and category-specific materials.';
    }
    if (trim((string)($payload['travel_date'] ?? '')) !== '') {
        $days = (int)floor((strtotime((string)$payload['travel_date']) - strtotime(date('Y-m-d'))) / 86400);
        if ($days < 45) {
            $score -= 12;
            $mustFix[] = 'Timing risk: travel is less than 45 days away. Missing evidence and appointments can become urgent.';
        }
    } else {
        $score -= 4;
        $mustFix[] = 'Add a travel date or date range so timing risk can be judged.';
    }

    if (!$mustFix) {
        $strengths[] = 'No major red flag was found from the answers. The next step is checking the actual document contents.';
        $mustFix[] = 'Do a final consistency check across form, passport, invitation, bank proof, itinerary and employment/business evidence.';
    }
    if (!$likelyMissing) {
        $likelyMissing[] = 'Official checklist items, passport copy, application form, photo, funding proof, purpose evidence and return-ties evidence.';
    }

    $score = max(18, min(94, $score));
    $label = $score >= 80 ? 'Good pre-check' : ($score >= 62 ? 'Needs cleanup' : 'High-risk file');
    $groupLabels = [
        'passport' => 'Passport / ID',
        'form' => 'Application form / portal confirmation',
        'money' => 'Bank / funding proof',
        'work' => 'Employment / business proof',
        'invitation' => 'Invitation / admission / host proof',
        'refusal' => 'Previous refusal letter',
        'other' => 'Other supporting documents',
    ];
    $documentReview = [];
    foreach ($groupLabels as $key => $labelText) {
        $count = (int)($uploadGroups[$key] ?? 0);
        $names = array_values(array_filter(array_map('strval', (array)($uploadedDetails[$key] ?? []))));
        if ($count > 0) {
            $documentReview[] = $labelText . ': uploaded ' . $count . ' file' . ($count === 1 ? '' : 's') . '. Check that every name, date, passport number, address and amount matches the application form. Files saved: ' . implode(', ', $names ?: ['uploaded file']) . '.';
        } else {
            $documentReview[] = $labelText . ': not uploaded. Add this group if it applies to the visa type, because missing groups make the officer work harder to understand the file.';
        }
    }
    $detailedExplanations = [
        'Purpose story: the reason for travel should be easy to understand in under one minute. The form, cover letter, itinerary, invitation and uploaded evidence should all describe the same purpose using the same dates and names.',
        'Money story: the file should show who pays, where the money comes from, whether the balance is realistic for the trip, and why any large deposits make sense.',
        'Return story: the file should show a clear reason to return to Ethiopia, such as work, business, school, family responsibility, property, ongoing treatment, or a fixed obligation after the trip.',
        'Document order: put identity first, then application confirmation, purpose evidence, funding proof, work/business/student proof, host or invitation evidence, refusal explanation if any, and other supporting documents last.',
    ];
    if (str_contains($visaType, 'business')) {
        $detailedExplanations[] = 'Business files should connect the Ethiopian employer/business, the foreign inviter, the meeting agenda and the cost responsibility. A business invitation alone is usually not enough.';
    }
    if (str_contains($visaType, 'student') || str_contains($visaType, 'study')) {
        $detailedExplanations[] = 'Student files should connect admission, tuition, living costs, sponsor/funding capacity, study history and the plan after studies. The chosen course should make sense with past education or work.';
    }
    if (str_contains($visaType, 'tourist') || str_contains($visaType, 'visitor')) {
        $detailedExplanations[] = 'Visitor files should keep the trip realistic. The length of stay, budget, accommodation and activities should fit the applicant income and leave period.';
    }
    $submissionOrder = [
        '1. Passport biodata page and any previous visas/travel history pages.',
        '2. Application form, appointment confirmation, payment receipt or portal confirmation.',
        '3. Visa-type purpose proof: itinerary, invitation, admission, hospital letter, meeting agenda or event proof.',
        '4. Funding proof: bank statements, salary/business income, sponsor support if used, and source-of-funds explanation where needed.',
        '5. Ethiopia ties: employment letter, business license/tax, school letter, family obligations, property or other return evidence.',
        '6. Explanations: cover letter, previous refusal response, large-deposit note, sponsor relationship note or timeline note.',
        '7. Supporting copies in the same order as the official checklist.',
    ];

    return [
        'score' => $score,
        'label' => $label,
        'strengths' => $strengths,
        'must_fix' => array_values(array_unique($mustFix)),
        'likely_missing' => array_values(array_unique($likelyMissing)),
        'consistency_checks' => array_values(array_unique($consistencyChecks)),
        'document_review' => $documentReview,
        'detailed_explanations' => $detailedExplanations,
        'submission_order' => $submissionOrder,
        'review_pitch' => 'The smart pre-check finds likely gaps, contradictions and timing risks so you can clean the full file before submission.',
    ];
}

function vm_service_match_options(): array
{
    return [
        ['id' => 'missing', 'label' => 'I do not know what is missing', 'pack' => 'quick-file-audit', 'why' => 'Start with a file score and missing-evidence list.'],
        ['id' => 'sponsor', 'label' => 'My sponsor or bank proof is confusing', 'pack' => 'sponsor-proof-pack', 'why' => 'Build relationship proof, source of funds and sponsor explanation.'],
        ['id' => 'letters', 'label' => 'My letters sound weak', 'pack' => 'embassy-ready-letter-bundle', 'why' => 'Turn facts into cover, sponsor, employer or invitation drafts.'],
        ['id' => 'refusal', 'label' => 'I had a previous refusal', 'pack' => 'previous-refusal-recovery', 'why' => 'Answer refusal reasons with changed evidence.'],
        ['id' => 'student', 'label' => 'I am applying as a student', 'pack' => 'student-visa-support-pack', 'why' => 'Connect admission, tuition, living costs and sponsor proof.'],
        ['id' => 'business', 'label' => 'I am traveling for business', 'pack' => 'business-travel-pack', 'why' => 'Organize invitation, company proof, agenda and return ties.'],
        ['id' => 'schengen', 'label' => 'I need Schengen appointment/file help', 'pack' => 'schengen-appointment-file-pack', 'why' => 'Prepare insurance, itinerary, VFS/TLS route and 45-day timing.'],
        ['id' => 'china', 'label' => 'I need China center/form help', 'pack' => 'china-application-center-prep', 'why' => 'Choose the right China category and application-center materials.'],
    ];
}

function vm_country_process_steps(string $slug, array $resources = []): array
{
    $portal = null; $requirements = null; $pdf = null;
    foreach ($resources as $resource) {
        $status = (string)($resource['resource_status'] ?? '');
        if (!$portal && $status === 'official_online_portal') { $portal = $resource; }
        if (!$requirements && in_array($status, ['official_requirements_page', 'official_page'], true)) { $requirements = $resource; }
        if (!$pdf && $status === 'downloadable_official_pdf') { $pdf = $resource; }
    }
    $steps = [
        ['title' => 'Pick the exact visa type', 'body' => 'Match your real purpose to the official category first. Tourist, student, business, work, family and transit files need different proof.'],
        ['title' => 'Open the official requirement page', 'body' => $requirements ? 'Start from "' . (string)($requirements['title'] ?? 'the official requirement page') . '" and treat it as the final rule source.' : 'Use the embassy, government, VFS, TLS or visa-center requirement page as the final rule source.'],
        ['title' => 'Complete the form or online portal', 'body' => $portal ? 'Use "' . (string)($portal['title'] ?? 'the official portal') . '" for the application route. Save confirmations and reference numbers.' : ($pdf ? 'Download the official form only from the source shown here, then check whether an online portal is also required.' : 'Check whether this country uses an online portal, a PDF form, or a visa-center pre-application route.')],
        ['title' => 'Build the evidence file', 'body' => 'Prepare identity, purpose, funding, sponsor, invitation, employment/business and Ethiopia return-ties evidence in a clean order.'],
        ['title' => 'Check timing and appointment route', 'body' => 'Confirm appointment, biometrics, fee, passport submission, courier/collection and tracking steps before booking travel.'],
        ['title' => 'Do a final consistency review', 'body' => 'Names, passport details, dates, addresses, sponsor amounts and purpose must match across every document.'],
    ];
    $specific = [
        'china' => [
            ['title' => 'Choose the China category', 'body' => 'Select L, M, Z, X1/X2, Q/S, G, C, J, D or R based on the real purpose before preparing materials.'],
            ['title' => 'Use the China Visa Application Service Center route', 'body' => 'Use the Addis Ababa center pages for the form sample, material list, appointment and category guidance.'],
            ['title' => 'Print and organize center materials', 'body' => 'Keep the application form, appointment record, passport, photo, invitation or category material list together for the visa center.'],
        ],
        'canada' => [
            ['title' => 'Use the IRCC account/application route', 'body' => 'Canada visitor files are portal-led. Prepare upload-ready PDFs and keep purpose, invitation and funding evidence consistent.'],
            ['title' => 'Prepare biometrics and passport steps', 'body' => 'After submission, follow official instructions for biometrics, requests, passport submission and final tracking.'],
            ['title' => 'Use current IRCC forms only', 'body' => 'If IRCC requires IMM forms, download them from the official form page and open dynamic PDFs with Adobe Reader when the browser cannot display them.'],
        ],
        'united-kingdom' => [
            ['title' => 'Start on GOV.UK', 'body' => 'The UK visitor route is online-first. Complete the official application, pay, then follow the visa application center instructions.'],
            ['title' => 'Build visitor evidence around credibility', 'body' => 'Your trip purpose, available money, planned costs, accommodation and Ethiopia return ties should be easy to understand.'],
            ['title' => 'Attend the UK visa application center step', 'body' => 'After the GOV.UK flow, follow the official center page for biometrics, appointment address, passport submission and document-upload instructions.'],
        ],
        'united-states' => [
            ['title' => 'Complete DS-160 through CEAC', 'body' => 'Save the DS-160 confirmation and make sure interview answers match the submitted form.'],
            ['title' => 'Prepare for interview consistency', 'body' => 'Your documents should support a clear purpose, funding and return story for the interview.'],
            ['title' => 'Use embassy Ethiopia instructions', 'body' => 'Check the U.S. Embassy Ethiopia visa page for current interview, fee, appointment and document guidance before acting.'],
        ],
        'germany' => [
            ['title' => 'Start from German Embassy Addis guidance', 'body' => 'Use the Germany Addis Ababa Schengen or visit visa page for Ethiopia-specific timing and requirement instructions.'],
            ['title' => 'Respect the 45-day Ethiopia warning', 'body' => 'German Embassy guidance for Ethiopia highlights early submission timing. Treat late preparation as a serious risk.'],
            ['title' => 'Match visit evidence tightly', 'body' => 'Insurance, invitation, accommodation, itinerary, bank proof and leave/employment documents must match the same travel dates.'],
        ],
        'france' => [
            ['title' => 'Start on France-Visas Ethiopia', 'body' => 'Use France-Visas to identify the correct visa type, generate the application flow and confirm required documents.'],
            ['title' => 'Separate France-Visas from appointment handling', 'body' => 'Use the official France-Visas route for requirements/application, then follow the linked center instructions for appointment and biometrics.'],
            ['title' => 'Prepare host or tourism evidence', 'body' => 'For family visits, invitation/accommodation evidence must match dates and cost responsibility. Tourist files need itinerary, hotel and funds.'],
        ],
        'italy' => [
            ['title' => 'Use the Italy Ethiopia checklist', 'body' => 'Start with the official Embassy/VFS Ethiopia checklist for the selected short-stay category.'],
            ['title' => 'Organize documents in checklist order', 'body' => 'Italy appointment files should be easy to scan: form, passport, photo, insurance, itinerary, accommodation, funds and ties.'],
            ['title' => 'Check VFS/embassy instructions before appointment', 'body' => 'Confirm appointment, fees, biometrics, passport handling and any category-specific checklist updates.'],
        ],
        'netherlands' => [
            ['title' => 'Start from Netherlands/VFS Ethiopia resources', 'body' => 'Use official Netherlands and VFS Ethiopia instructions to confirm short-stay requirements and appointment handling.'],
            ['title' => 'Choose tourist, family or business evidence', 'body' => 'Host/sponsor visits need inviter proof and cost responsibility; business visits need invitation and Ethiopian company support evidence.'],
            ['title' => 'Check document consistency before VFS', 'body' => 'Make sure insurance, itinerary, accommodation, funds and leave letter cover the same travel period.'],
        ],
        'sweden' => [
            ['title' => 'Use Swedish official migration/embassy guidance', 'body' => 'Start from the official Sweden resource and confirm the short-stay category or residence/study route before collecting documents.'],
            ['title' => 'Prepare appointment-day evidence', 'body' => 'For Schengen short-stay, organize form, passport, insurance, itinerary, accommodation, funding and Ethiopia return ties.'],
            ['title' => 'Verify the local submission partner', 'body' => 'Check whether the current Ethiopia process uses embassy, VFS/TLS-style partner instructions or another official route.'],
        ],
        'austria' => [
            ['title' => 'Use Austria official/VFS guidance', 'body' => 'Start from the Austria resource in this guide and verify the short-stay visa checklist for applicants from Ethiopia.'],
            ['title' => 'Prepare Schengen core documents', 'body' => 'Build insurance, itinerary, accommodation, bank proof, leave/employment or business evidence and return ties.'],
            ['title' => 'Confirm appointment and passport handling', 'body' => 'Before attending, verify appointment location, fees, biometrics, passport submission and tracking instructions.'],
        ],
        'belgium' => [
            ['title' => 'Use Belgium/TLS or official visa instructions', 'body' => 'Start from the Belgium official resources in this guide and confirm the Ethiopia appointment/submission route.'],
            ['title' => 'Match short-stay purpose to proof', 'body' => 'Tourism needs itinerary and hotel; family visits need host/invitation proof; business visits need company invitation and employer/business documents.'],
            ['title' => 'Prepare a 45-day timing buffer', 'body' => 'Schengen files from Ethiopia should be prepared early because appointment availability, biometrics and corrections can delay submission.'],
        ],
        'india' => [
            ['title' => 'Choose eVisa or embassy/center route', 'body' => 'India routes differ by visa type. Tourist, business, medical and other categories may use different official instructions.'],
            ['title' => 'Use the official India visa form/checklist source', 'body' => 'Open the embassy or official application resource, then prepare category-specific purpose evidence before submission.'],
            ['title' => 'Add receiving-side evidence', 'body' => 'Business, medical and student files should include invitation, treatment/admission or organization proof from India when relevant.'],
        ],
        'united-arab-emirates' => [
            ['title' => 'Confirm the approved UAE channel', 'body' => 'UAE applications can involve airline, sponsor, travel agency, government or embassy-related channels. Verify the channel before sharing documents or details.'],
            ['title' => 'Prepare sponsor or host details if required', 'body' => 'If a host, company or hotel is involved, keep their contact, address, booking and cost responsibility consistent.'],
            ['title' => 'Watch for scam risk', 'body' => 'Do not trust unofficial approval promises. Keep receipts, application reference numbers and official tracking links.'],
        ],
        'saudi-arabia' => [
            ['title' => 'Start with the official Saudi portal route', 'body' => 'Saudi applications are often portal-first. Choose the correct purpose such as tourism, work, visit, business or religious travel before preparing documents.'],
            ['title' => 'Prepare invitation or sponsor details', 'body' => 'For visit, business or work categories, keep invitation, sponsor, employer or host details consistent with the application.'],
            ['title' => 'Verify appointment, biometrics or service-center steps', 'body' => 'Use the official portal or linked center instructions for submission, official fees, biometrics and tracking.'],
        ],
        'turkey' => [
            ['title' => 'Check e-Visa eligibility first', 'body' => 'Some applicants may use e-Visa routes while others need sticker visa pre-application. Start by confirming the correct official path.'],
            ['title' => 'Complete pre-application where required', 'body' => 'If sticker visa is required, use the official pre-application/appointment resource and save confirmations.'],
            ['title' => 'Prepare visitor evidence', 'body' => 'Organize itinerary, accommodation, bank proof, employment/business evidence and Ethiopia return ties before the appointment.'],
        ],
        'thailand' => [
            ['title' => 'Use Thai MFA or embassy form instructions', 'body' => 'Start with the official Thai application form or embassy/MFA instruction page and confirm the visa category.'],
            ['title' => 'Prepare trip and funding proof', 'body' => 'Tourist files should show itinerary, accommodation, funds, employment/business status and return plan.'],
            ['title' => 'Check submission location and payment rules', 'body' => 'Verify whether submission is through embassy, consular section or official online/center instruction before attending.'],
        ],
        'south-africa' => [
            ['title' => 'Use the official BI-84/application route', 'body' => 'Start with the official South Africa application form and current embassy/consular requirements.'],
            ['title' => 'Build short-stay purpose evidence', 'body' => 'Prepare itinerary, accommodation, invitation if any, bank proof and evidence of ties to Ethiopia.'],
            ['title' => 'Confirm submission method', 'body' => 'Check the official source for where to submit, fees, passport handling and processing/tracking steps.'],
        ],
        'kenya' => [
            ['title' => 'Start with the official eTA route', 'body' => 'Kenya is portal-first for eTA-style travel authorization. Use the official portal, not copied forms.'],
            ['title' => 'Upload clean trip evidence', 'body' => 'Prepare passport, photo, accommodation/host, return ticket or itinerary and purpose details in upload-ready format.'],
            ['title' => 'Save approval and travel copy', 'body' => 'Keep the eTA confirmation/approval with your passport and verify entry requirements before travel.'],
        ],
        'qatar' => [
            ['title' => 'Confirm the official Qatar route', 'body' => 'Qatar visa routes may depend on nationality, sponsor, airline, hotel, business host or official portal guidance. Start with the official source.'],
            ['title' => 'Prepare host, hotel or sponsor proof', 'body' => 'Keep accommodation, invitation, employer/business purpose and cost responsibility clear.'],
            ['title' => 'Save application reference and approval copy', 'body' => 'Keep portal confirmations, receipts and approval/tracking details with the travel file.'],
        ],
        'egypt' => [
            ['title' => 'Start with embassy or consular instructions', 'body' => 'Egypt requirements can depend on visa type and consular route. Verify the official embassy/consular page before preparing forms.'],
            ['title' => 'Prepare purpose and itinerary evidence', 'body' => 'Organize hotel/host, itinerary, funds, employment/business proof and passport copies.'],
            ['title' => 'Confirm whether appointment or direct submission is required', 'body' => 'Check where to submit, fee method, passport collection and whether extra documents are requested for Ethiopian applicants.'],
        ],
        'japan' => [
            ['title' => 'Use Embassy of Japan instructions', 'body' => 'Start from the Embassy of Japan Addis Ababa or official visa page and download only current official forms.'],
            ['title' => 'Build itinerary and guarantee/invitation evidence', 'body' => 'For visits, prepare schedule of stay, invitation/guarantee documents if relevant, bank proof and Ethiopia ties.'],
            ['title' => 'Confirm submission and collection rules', 'body' => 'Check the embassy instructions for appointment, fee, processing time, passport collection and required copies.'],
        ],
        'south-korea' => [
            ['title' => 'Use Korea Visa Portal or embassy route', 'body' => 'Start with the Korea Visa Portal and embassy instructions to confirm whether the category is online, form-based or embassy-submitted.'],
            ['title' => 'Prepare category evidence', 'body' => 'Tourist, business, family and student files need different invitation, admission, employer or financial evidence.'],
            ['title' => 'Check appointment/submission requirements', 'body' => 'Verify fee, photo, passport, application form, appointment and tracking instructions on the official source.'],
        ],
        'malaysia' => [
            ['title' => 'Confirm eVisa or embassy route', 'body' => 'Malaysia may use eVisa or embassy-related steps depending on visa type. Start with the official Malaysia resource.'],
            ['title' => 'Prepare trip and sponsor evidence', 'body' => 'Organize accommodation, itinerary, funds, employment/business proof, invitation or student documents where relevant.'],
            ['title' => 'Save portal confirmation', 'body' => 'If using eVisa, keep the application reference, payment receipt and issued approval with your travel documents.'],
        ],
        'australia' => [
            ['title' => 'Use ImmiAccount or official form route', 'body' => 'Australia applications are usually online through official immigration systems. Use official forms/pages only when required by the application.'],
            ['title' => 'Prepare upload-ready evidence', 'body' => 'Scan passport, purpose, invitation, employment/business, funding and Ethiopia return-ties evidence as clear PDFs.'],
            ['title' => 'Watch for requests after lodgement', 'body' => 'After submitting, monitor official messages for biometrics, medicals, additional documents or passport instructions.'],
        ],
        'russia' => [
            ['title' => 'Complete the official online visa application form', 'body' => 'Use the official Russia visa application form resource and save/print the completed application as instructed.'],
            ['title' => 'Prepare invitation or voucher if required', 'body' => 'Tourist, business, private and other routes may need invitation, voucher, host or organization evidence.'],
            ['title' => 'Confirm embassy submission steps', 'body' => 'Check appointment, fee, passport, photo and collection instructions through the official embassy/consular source.'],
        ],
    ];
    $schengenSlugs = ['germany','france','italy','netherlands','sweden','austria','belgium'];
    if (in_array($slug, $schengenSlugs, true)) {
        array_splice($steps, 1, 0, [
            ['title' => 'Confirm the responsible Schengen country', 'body' => 'Apply through the country of main destination. If equal, use the first-entry/main-stay rule from official Schengen guidance.'],
            ['title' => 'Treat 45 days as a warning point', 'body' => 'For Ethiopia, appointment availability and corrections can create pressure. Prepare early and avoid last-minute files.'],
        ]);
    }
    if (isset($specific[$slug])) {
        array_splice($steps, 1, 0, $specific[$slug]);
    }
    return array_values($steps);
}

function vm_forms_brain_suggestions(array $resources): array
{
    $portalCount = count(array_filter($resources, fn(array $r): bool => ($r['resource_status'] ?? '') === 'official_online_portal'));
    $pdfCount = count(array_filter($resources, fn(array $r): bool => ($r['resource_status'] ?? '') === 'downloadable_official_pdf'));
    $reqCount = count(array_filter($resources, fn(array $r): bool => in_array((string)($r['resource_status'] ?? ''), ['official_requirements_page','official_page'], true)));
    return [
        'If you are confused, start with country + visa type. VisaMenged will surface the official portal, requirement page and PDF if available.',
        $portalCount . ' portal resources: use these when the country requires online application or appointment steps.',
        $pdfCount . ' official PDF resources: download only from the official source and check date/version before using.',
        $reqCount . ' requirement pages: read these before trusting any checklist or sample form.',
    ];
}

function vm_assistant_recommend_pack_ids(array $payload, ?array $countryInfo = null): array
{
    $visaType = strtolower((string)($payload['visa_type'] ?? ''));
    $funding = strtolower((string)($payload['funding'] ?? ''));
    $stuck = strtolower((string)($payload['stuck'] ?? ''));
    $country = (string)($payload['country'] ?? '');
    $ids = ['quick-file-audit'];
    if (str_contains($funding, 'sponsor') || str_contains($stuck, 'sponsor') || str_contains($stuck, 'bank')) { $ids[] = 'sponsor-proof-pack'; }
    if (str_contains($stuck, 'letter')) { $ids[] = 'embassy-ready-letter-bundle'; }
    if (str_contains($stuck, 'refusal') || strtolower((string)($payload['previous_refusal'] ?? '')) === 'yes') { $ids[] = 'previous-refusal-recovery'; }
    if (str_contains($visaType, 'student') || str_contains($visaType, 'study')) { $ids[] = 'student-visa-support-pack'; }
    if (str_contains($visaType, 'business')) { $ids[] = 'business-travel-pack'; }
    if ($country === 'canada') { $ids[] = 'canada-ircc-visitor-prep'; }
    if ($country === 'united-kingdom') { $ids[] = 'uk-visitor-file-builder'; }
    if ($country === 'united-states') { $ids[] = 'usa-ds160-interview-prep'; }
    if ($country === 'china') { $ids[] = 'china-application-center-prep'; }
    if (($countryInfo['hub'] ?? '') === 'schengen') { $ids[] = 'schengen-appointment-file-pack'; }
    return array_values(array_unique($ids));
}

function vm_assistant_brain(array $payload): array
{
    $countryInfo = vm_country_by_slug((string)($payload['country'] ?? ''));
    $resources = $countryInfo ? vm_country_resources((string)$payload['country']) : [];
    $fileBrain = vm_file_brain_analyze([
        'country' => $payload['country'] ?? '',
        'visa_type' => $payload['visa_type'] ?? '',
        'employment_status' => $payload['employment_status'] ?? '',
        'funding' => $payload['funding'] ?? '',
        'invitation' => $payload['invitation'] ?? '',
        'previous_refusal' => $payload['previous_refusal'] ?? '',
        'travel_date' => $payload['travel_date'] ?? '',
        'notes' => $payload['stuck'] ?? '',
        'uploads' => [],
        'upload_groups' => [],
    ]);
    $warnings = $fileBrain['must_fix'];
    $steps = $countryInfo ? array_slice(vm_country_process_steps((string)$payload['country'], $resources), 0, 6) : [];
    $packIds = vm_assistant_recommend_pack_ids($payload, $countryInfo);
    $packs = [];
    foreach ($packIds as $id) {
        $pack = vm_pack_by_id($id);
        if ($pack) { $packs[] = $pack; }
        if (count($packs) >= 3) { break; }
    }
    $official = array_slice($resources, 0, 4);
    return [
        'country' => $countryInfo,
        'resources' => $official,
        'steps' => $steps,
        'score' => $fileBrain['score'],
        'label' => $fileBrain['label'],
        'warnings' => array_slice($warnings, 0, 5),
        'missing' => array_slice($fileBrain['likely_missing'], 0, 5),
        'packs' => $packs,
    ];
}

function vm_contact_map_cards(string $slug, array $resources, array $country): array
{
    $cards = [];
    foreach ($resources as $resource) {
        $sourceOrg = trim((string)($resource['source_org'] ?? ''));
        $sourceType = trim((string)($resource['source_type'] ?? ''));
        $url = (string)($resource['official_page'] ?? $resource['url'] ?? '');
        if ($sourceOrg === '' || $url === '') { continue; }
        $key = strtolower($sourceOrg . '|' . $sourceType);
        if (isset($cards[$key])) { continue; }
        $label = match ($sourceType) {
            'embassy' => 'Embassy / consular source',
            'vfs' => 'Visa application center',
            'government' => 'Government portal',
            default => 'Official source',
        };
        $needsMap = in_array($sourceType, ['embassy', 'vfs'], true) || str_contains(strtolower($sourceOrg), 'tls') || str_contains(strtolower($sourceOrg), 'visa application');
        $mapQuery = rawurlencode($sourceOrg . ' Addis Ababa Ethiopia');
        $cards[$key] = [
            'title' => $sourceOrg,
            'type' => $label,
            'url' => $url,
            'map_url' => $needsMap ? 'https://www.google.com/maps/search/?api=1&query=' . $mapQuery : '',
            'note' => $needsMap ? 'Use Google Maps for location, hours and directions, then verify details on the official site before visiting.' : 'Use the official website for current forms, portals, instructions and contact details.',
        ];
        if (count($cards) >= 5) { break; }
    }
    if (!$cards) {
        $countryName = (string)($country['name'] ?? ucwords(str_replace('-', ' ', $slug)));
        $cards['fallback'] = [
            'title' => $countryName . ' official visa contact',
            'type' => 'Official contact lookup',
            'url' => vm_url('forms.php'),
            'map_url' => 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($countryName . ' embassy Addis Ababa Ethiopia'),
            'note' => 'Use the official resource page and Google Maps search to verify current address, phone and visiting hours.',
        ];
    }
    return array_values($cards);
}
