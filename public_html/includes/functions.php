<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

const VM_DISCLAIMER = 'VisaMenged is independent guidance. We are not an embassy, government agency, immigration lawyer, or visa decision-maker. We gather official resources and provide support templates/checklists. Always verify final requirements on the official embassy, government, VFS, TLS, or visa-center website before applying. VisaMenged does not guarantee approval.';

function vm_config(): array
{
    static $cfg = null;
    if ($cfg === null) {
        $path = __DIR__ . '/config.php';
        $cfg = is_file($path) ? (require $path) : [];
    }
    return is_array($cfg) ? $cfg : [];
}

function vm_config_path(string $dotPath, mixed $default = null): mixed
{
    $keys = explode('.', $dotPath);
    $value = vm_config();
    foreach ($keys as $key) {
        if (!is_array($value) || !array_key_exists($key, $value)) {
            return $default;
        }
        $value = $value[$key];
    }
    return $value;
}

function vm_country_flag(string $slug): string
{
    $map = [
        'united-kingdom' => '🇬🇧',
        'united-states' => '🇺🇸',
        'canada' => '🇨🇦',
        'germany' => '🇩🇪',
        'france' => '🇫🇷',
        'italy' => '🇮🇹',
        'netherlands' => '🇳🇱',
        'sweden' => '🇸🇪',
        'austria' => '🇦🇹',
        'india' => '🇮🇳',
        'china' => '🇨🇳',
        'united-arab-emirates' => '🇦🇪',
        'saudi-arabia' => '🇸🇦',
        'turkey' => '🇹🇷',
        'thailand' => '🇹🇭',
        'south-africa' => '🇿🇦',
        'kenya' => '🇰🇪',
        'qatar' => '🇶🇦',
        'egypt' => '🇪🇬',
        'japan' => '🇯🇵',
        'south-korea' => '🇰🇷',
        'malaysia' => '🇲🇾',
        'australia' => '🇦🇺',
        'russia' => '🇷🇺',
        'belgium' => '🇧🇪',
    ];
    return $map[$slug] ?? '🌍';
}

function vm_h(mixed $value): string { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }

function vm_lang(): string
{
    if (isset($_GET['lang']) && in_array($_GET['lang'], ['en', 'am'], true)) {
        $_SESSION['lang'] = $_GET['lang'];
    }
    return (string)($_SESSION['lang'] ?? 'en');
}

function vm_t(string $key): string
{
    $en = [
        'tagline' => 'The clear path to your visa application.',
        'home' => 'Home',
        'forms' => 'Forms',
        'schengen' => 'Schengen',
        'checklist' => 'Checklist',
        'services' => 'Services',
        'review' => 'Review',
        'saved_list' => 'Saved list',
        'saved_resources' => 'Saved resources',
        'print' => 'Print',
        'download_list' => 'Download list',
        'clear' => 'Clear',
        'close' => 'Close',
        'for_ethiopian' => 'For Ethiopian visa applicants',
        'hero_title' => 'Find official visa forms. Check your file. Fix what is weak.',
        'hero_short' => 'Start with trusted official resources, generate a file score, then request focused help only where your application needs it.',
        'get_score' => 'Get My File Score',
        'find_forms' => 'Find Official Forms',
        'three_step' => 'Your 3-step path',
        'no_guesswork' => 'No guesswork',
        'step_official' => 'Find official resources',
        'step_official_sub' => 'Forms, portals and requirements',
        'step_score' => 'Score your file',
        'step_score_sub' => 'See gaps before submission',
        'step_fix' => 'Fix weak points',
        'step_fix_sub' => 'Letters, audit, sponsor proof, refusals',
        'country_guides' => 'country guides',
        'country_guides_nav' => 'Country guides',
        'official_forms_nav' => 'Official forms',
        'free_checklist_nav' => 'Free checklist',
        'schengen_nav' => 'Schengen visas',
        'paid_help_nav' => 'Paid help',
        'home_hero_title' => 'Visa guides and document help for Ethiopian applicants.',
        'home_hero_body' => 'Pick a country guide, open official forms, follow the steps, and request paid help when your file needs fixing.',
        'browse_country_guides' => 'Browse Country Guides',
        'see_paid_help' => 'See Paid Help',
        'start_destination' => 'Start with your destination.',
        'browse_all_forms' => 'Browse all forms',
        'paid_help_simple_title' => 'Choose one clear fix.',
        'paid_help_simple_body' => 'Start free. Pay only when you want us to clean up a weak part of the file.',
        'compare_help' => 'Compare help',
        'official_resources' => 'official resources',
        'no_approval' => 'approval promises',
        'no_login' => 'login needed',
        'what_need' => 'What do you need?',
        'choose_problem' => 'Choose the problem. Get the right help.',
        'check_file' => 'Check my file',
        'check_file_sub' => 'Missing documents and risk notes.',
        'request_audit' => 'Request audit',
        'write_letters' => 'Write my letters',
        'write_letters_sub' => 'Cover, sponsor, employer or invitation drafts.',
        'request_letters' => 'Request letters',
        'explain_sponsor' => 'Explain sponsor money',
        'explain_sponsor_sub' => 'Relationship, bank proof and source of funds.',
        'organize_proof' => 'Organize proof',
        'fix_refusal' => 'Fix a refusal',
        'fix_refusal_sub' => 'Refusal reasons and changed-circumstances plan.',
        'recover_file' => 'Recover file',
        'popular_destinations' => 'Popular destinations',
        'pick_country' => 'Pick a country guide',
        'all_resources' => 'All resources',
        'resources' => 'resources',
    ];
    $dict = [
        'tagline' => ['am' => 'ወደ ቪዛ ማመልከቻዎ ግልጽ መንገድ።'],
        'home' => ['am' => 'መነሻ'],
        'forms' => ['am' => 'ፎርሞች'],
        'schengen' => ['am' => 'ሸንገን'],
        'checklist' => ['am' => 'ቼክሊስት'],
        'services' => ['am' => 'አገልግሎቶች'],
        'review' => ['am' => 'ፋይል ምርመራ'],
        'saved_list' => ['am' => 'የተቀመጡ'],
        'saved_resources' => ['am' => 'የተቀመጡ መረጃዎች'],
        'print' => ['am' => 'አትም'],
        'download_list' => ['am' => 'ዝርዝር አውርድ'],
        'clear' => ['am' => 'አጽዳ'],
        'close' => ['am' => 'ዝጋ'],
        'for_ethiopian' => ['am' => 'ለኢትዮጵያ የቪዛ አመልካቾች'],
        'hero_title' => ['am' => 'ኦፊሴላዊ ፎርሞችን ያግኙ። ፋይልዎን ይመርምሩ። ደካማውን ያስተካክሉ።'],
        'hero_short' => ['am' => 'በኦፊሴላዊ መረጃ ይጀምሩ፣ ፋይልዎን ይመርምሩ፣ ከዚያ በደካማ ክፍሎች ላይ እገዛ ይጠይቁ።'],
        'get_score' => ['am' => 'ፋይሌን ይመርምሩ'],
        'find_forms' => ['am' => 'ኦፊሴላዊ ፎርሞችን ያግኙ'],
        'three_step' => ['am' => '3 ደረጃ መንገድ'],
        'no_guesswork' => ['am' => 'ግምት አይደለም'],
        'step_official' => ['am' => 'ኦፊሴላዊ መረጃ ያግኙ'],
        'step_official_sub' => ['am' => 'ፎርሞች፣ ፖርታሎች፣ መስፈርቶች'],
        'step_score' => ['am' => 'ፋይልዎን ይመርምሩ'],
        'step_score_sub' => ['am' => 'ከመላክዎ በፊት ክፍተቶችን ይዩ'],
        'step_fix' => ['am' => 'ደካማ ነጥቦችን ያስተካክሉ'],
        'step_fix_sub' => ['am' => 'ደብዳቤ፣ ምርመራ፣ ስፖንሰር ማስረጃ፣ ውድቅ ማስተካከያ'],
        'country_guides' => ['am' => 'የአገር መመሪያዎች'],
        'country_guides_nav' => ['am' => 'የአገር መመሪያ'],
        'official_forms_nav' => ['am' => 'ኦፊሴላዊ ፎርሞች'],
        'free_checklist_nav' => ['am' => 'ነፃ ቼክሊስት'],
        'schengen_nav' => ['am' => 'ሸንገን ቪዛ'],
        'paid_help_nav' => ['am' => 'የሚከፈል እገዛ'],
        'home_hero_title' => ['am' => 'ለኢትዮጵያውያን የቪዛ መመሪያ እና የሰነድ እገዛ።'],
        'home_hero_body' => ['am' => 'አገር ይምረጡ፣ ኦፊሴላዊ ፎርሞችን ይክፈቱ፣ ደረጃዎቹን ይከተሉ፣ ፋይልዎ መስተካከል ሲፈልግ የሚከፈል እገዛ ይጠይቁ።'],
        'browse_country_guides' => ['am' => 'የአገር መመሪያዎችን ይመልከቱ'],
        'see_paid_help' => ['am' => 'የሚከፈል እገዛ'],
        'start_destination' => ['am' => 'በመድረሻ አገርዎ ይጀምሩ።'],
        'browse_all_forms' => ['am' => 'ሁሉንም ፎርሞች ይመልከቱ'],
        'paid_help_simple_title' => ['am' => 'አንድ ግልጽ መስተካከያ ይምረጡ።'],
        'paid_help_simple_body' => ['am' => 'በነፃ ይጀምሩ። ፋይልዎን እንድናስተካክል ሲፈልጉ ብቻ ይክፈሉ።'],
        'compare_help' => ['am' => 'እገዛዎችን ያወዳድሩ'],
        'official_resources' => ['am' => 'ኦፊሴላዊ መረጃዎች'],
        'no_approval' => ['am' => 'የፈቃድ ዋስትና የለም'],
        'no_login' => ['am' => 'መለያ አያስፈልግም'],
        'what_need' => ['am' => 'ምን ይፈልጋሉ?'],
        'choose_problem' => ['am' => 'ችግሩን ይምረጡ። ትክክለኛ እገዛ ያግኙ።'],
        'check_file' => ['am' => 'ፋይሌን መርምር'],
        'check_file_sub' => ['am' => 'የጎደሉ ሰነዶች እና የስጋት ማስታወሻዎች።'],
        'request_audit' => ['am' => 'ምርመራ ጠይቅ'],
        'write_letters' => ['am' => 'ደብዳቤዎቼን አዘጋጅ'],
        'write_letters_sub' => ['am' => 'የcover፣ sponsor፣ employer ወይም invitation ድራፍት።'],
        'request_letters' => ['am' => 'ደብዳቤ ጠይቅ'],
        'explain_sponsor' => ['am' => 'የስፖንሰር ገንዘብ አብራራ'],
        'explain_sponsor_sub' => ['am' => 'ግንኙነት፣ የባንክ ማስረጃ፣ የገንዘብ ምንጭ።'],
        'organize_proof' => ['am' => 'ማስረጃ አደራጅ'],
        'fix_refusal' => ['am' => 'ውድቅ የተደረገ ፋይል አስተካክል'],
        'fix_refusal_sub' => ['am' => 'የውድቅ ምክንያቶች እና አዲስ ማስረጃ ዕቅድ።'],
        'recover_file' => ['am' => 'ፋይል አስተካክል'],
        'popular_destinations' => ['am' => 'ታዋቂ አገሮች'],
        'pick_country' => ['am' => 'የአገር መመሪያ ይምረጡ'],
        'all_resources' => ['am' => 'ሁሉንም መረጃዎች'],
        'resources' => ['am' => 'መረጃዎች'],
    ];
    return vm_lang() === 'am' ? (string)($dict[$key]['am'] ?? $en[$key] ?? $key) : (string)($en[$key] ?? $key);
}

function vm_lang_url(string $lang): string
{
    $uri = (string)($_SERVER['REQUEST_URI'] ?? '/');
    $parts = parse_url($uri);
    $path = (string)($parts['path'] ?? '/');
    parse_str((string)($parts['query'] ?? ''), $query);
    $query['lang'] = $lang;
    return $path . '?' . http_build_query($query);
}

function vm_load_json(string $filename): array
{
    $path = __DIR__ . '/../data/' . basename($filename);
    if (!is_file($path)) { return []; }
    $data = json_decode((string)file_get_contents($path), true);
    return is_array($data) ? $data : [];
}

function vm_resources(bool $includeHidden = false): array
{
    return array_values(array_filter(vm_load_json('official_resources_top25.json'), fn(array $r): bool => $includeHidden || ($r['resource_status'] ?? '') !== 'needs_verification'));
}

function vm_countries(): array
{
    $countries = vm_load_json('countries_top25.json');
    usort($countries, fn(array $a, array $b): int => (int)($a['priority'] ?? 999) <=> (int)($b['priority'] ?? 999));
    return $countries;
}

function vm_country_by_slug(string $slug): ?array
{
    foreach (vm_countries() as $country) {
        if (($country['slug'] ?? '') === $slug) { return $country; }
    }
    return null;
}

function vm_country_resources(string $slug): array
{
    return array_values(array_filter(vm_resources(), fn(array $r): bool => ($r['slug'] ?? '') === $slug));
}

function vm_visa_type_options(): array
{
    return [
        'Tourist / visitor',
        'Family or friend visit',
        'Business trip',
        'Student / study',
        'Work / employment',
        'Transit',
        'Medical visit',
        'Conference / event',
        'Training / short course',
        'Religious / pilgrimage',
        'Dependent / spouse / family reunion',
        'Digital nomad / remote work',
        'Permanent residence / immigration',
        'Journalist / media',
        'Crew / seafarer',
        'Diplomatic / official',
        'Schengen short-stay',
        'Schengen family visit',
        'Schengen business',
        'China Tourist L',
        'China Business M',
        'China Work Z',
        'China Student X1/X2',
        'China Family Q1/Q2 or S1/S2',
        'China Transit G',
        'China Crew C',
        'China Journalist J1/J2',
        'China Talent R',
        'China Permanent Residence D',
        'Other / not sure',
    ];
}

function vm_visa_type_select(string $name = 'visa_type', string $selected = '', bool $required = true): string
{
    $html = '<select name="' . vm_h($name) . '"' . ($required ? ' required' : '') . '>';
    $html .= '<option value="">Select visa type</option>';
    foreach (vm_visa_type_options() as $option) {
        $isSelected = strcasecmp($selected, $option) === 0 ? ' selected' : '';
        $html .= '<option value="' . vm_h($option) . '"' . $isSelected . '>' . vm_h($option) . '</option>';
    }
    $html .= '</select>';
    return $html;
}

function vm_fee_guide_for_country(string $slug, string $hub = ''): array
{
    $data = vm_load_json('visa_fee_guides.json');
    $default = is_array($data['defaults'] ?? null) ? $data['defaults'] : [];
    $guide = [];
    if (isset($data[$slug]) && is_array($data[$slug])) {
        $guide = $data[$slug];
    } elseif ($hub === 'schengen' && isset($data['schengen']) && is_array($data['schengen'])) {
        $guide = $data['schengen'];
    }
    return array_replace_recursive($default, $guide);
}

function vm_public_visa_type_rows(string $slug, string $hub = ''): array
{
    $guide = vm_fee_guide_for_country($slug, $hub);
    $rows = [];
    foreach ((array)($guide['types'] ?? []) as $row) {
        $type = (string)($row['type'] ?? 'Visa type');
        $rows[] = [
            'type' => $type,
            'slug' => vm_slugify($type),
            'fee' => (string)($row['fee'] ?? 'Check official fee page'),
            'what_to_prepare' => (string)($row['what_to_prepare'] ?? 'Prepare documents according to the official checklist.'),
        ];
    }
    return $rows;
}

function vm_public_visa_type_row(string $slug, string $hub, string $typeSlug): array
{
    $rows = vm_public_visa_type_rows($slug, $hub);
    foreach ($rows as $row) {
        if (($row['slug'] ?? '') === $typeSlug) { return $row; }
    }
    return $rows[0] ?? ['type' => 'Visa type', 'slug' => 'visa-type', 'fee' => 'Check official fee page', 'what_to_prepare' => 'Prepare documents according to the official checklist.'];
}

function vm_resources_for_visa_type(array $resources, string $typeName): array
{
    $needle = strtolower($typeName);
    $groups = [
        'tourist' => ['tourist', 'visitor', 'visit', 'short-stay', 'trv', 'eta'],
        'visitor' => ['tourist', 'visitor', 'visit', 'short-stay', 'trv', 'eta'],
        'business' => ['business', 'meeting', 'conference', 'm'],
        'student' => ['student', 'study', 'x1', 'x2', 'school', 'admission'],
        'study' => ['student', 'study', 'x1', 'x2', 'school', 'admission'],
        'medical' => ['medical', 'treatment', 'hospital', 'patient'],
        'work' => ['work', 'employment', 'worker', 'z'],
        'family' => ['family', 'friend', 'spouse', 'dependent', 'q1', 'q2', 's1', 's2'],
        'transit' => ['transit', 'g'],
    ];
    $keywords = [];
    foreach ($groups as $key => $values) {
        if (str_contains($needle, $key)) { $keywords = array_merge($keywords, $values); }
    }
    $plainWords = array_filter(preg_split('/[^a-z0-9]+/', $needle) ?: [], fn($word) => strlen($word) > 2);
    $keywords = array_values(array_unique(array_merge($keywords, $plainWords)));
    $matched = array_values(array_filter($resources, function (array $resource) use ($keywords): bool {
        $hay = strtolower((string)($resource['visa_type'] ?? '') . ' ' . (string)($resource['title'] ?? '') . ' ' . (string)($resource['category'] ?? '') . ' ' . (string)($resource['notes'] ?? ''));
        foreach ($keywords as $keyword) {
            if (str_contains($hay, $keyword)) { return true; }
        }
        return false;
    }));
    return $matched ?: $resources;
}

function vm_resource_guide_slug(array $resource): string
{
    return vm_slugify((string)($resource['title'] ?? 'official-resource'));
}

function vm_resource_detail_url(array $resource): string
{
    return vm_url('resource.php?country=' . vm_h((string)($resource['slug'] ?? '')) . '&resource=' . vm_h(vm_resource_guide_slug($resource)));
}

function vm_resource_by_guide_slug(string $countrySlug, string $resourceGuideSlug): ?array
{
    foreach (vm_country_resources($countrySlug) as $resource) {
        if (vm_resource_guide_slug($resource) === $resourceGuideSlug) {
            return $resource;
        }
    }
    return null;
}

function vm_visa_type_hub_data(string $keyword): array
{
    $data = vm_load_json('visa_menged_research_seed_may_2026.json');
    $results = [];
    foreach ($data as $countryData) {
        $country = (string)($countryData['country'] ?? '');
        $slug = (string)($countryData['slug'] ?? '');
        foreach ((array)($countryData['visa_types'] ?? []) as $vt) {
            $vtName = (string)($vt['visa_type'] ?? '');
            $vtSlug = (string)($vt['visa_type_slug'] ?? '');
            $haystack = strtolower($vtName . ' ' . $vtSlug);
            if (str_contains($haystack, strtolower($keyword))) {
                $results[] = array_merge([
                    'country' => $country,
                    'country_slug' => $slug,
                    'country_notes' => (array)($countryData['country_level_notes'] ?? []),
                    'country_official_sources' => (array)($countryData['official_sources'] ?? []),
                    'application_channel' => (string)($countryData['application_channel_from_ethiopia'] ?? ''),
                ], $vt);
                break;
            }
        }
    }
    return $results;
}

function vm_country_visa_types_data(string $slug): array
{
    $data = vm_load_json('visa_menged_research_seed_may_2026.json');
    foreach ($data as $countryData) {
        if (($countryData['slug'] ?? '') === $slug) {
            $out = [];
            foreach ((array)($countryData['visa_types'] ?? []) as $vt) {
                $out[] = array_merge([
                    'country' => (string)($countryData['country'] ?? ''),
                    'country_slug' => $slug,
                    'country_notes' => (array)($countryData['country_level_notes'] ?? []),
                    'country_official_sources' => (array)($countryData['official_sources'] ?? []),
                    'application_channel' => (string)($countryData['application_channel_from_ethiopia'] ?? ''),
                ], $vt);
            }
            return $out;
        }
    }
    return [];
}

function vm_has_verify(string $text): bool
{
    return str_contains(strtolower((string)$text), '[verify]');
}

function vm_strip_verify(string $text): string
{
    return trim(preg_replace('/\s*\[VERIFY[^\]]*\]\s*/i', ' ', (string)$text) ?? '');
}

function vm_templates(): array { return vm_load_json('support_templates.json'); }
function vm_packs(): array { return vm_load_json('visa_packs.json'); }
function vm_pack_by_id(string $id): ?array
{
    foreach (vm_packs() as $pack) {
        if (($pack['id'] ?? '') === $id) { return $pack; }
    }
    return null;
}

function vm_url(string $path = ''): string
{
    $base = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/');
    $base = $base === '/' ? '' : $base;
    return $base . '/' . ltrim($path, '/');
}

function vm_status_label(string $status): string
{
    return match ($status) {
        'downloadable_official_pdf' => 'Official PDF',
        'official_online_portal' => 'Official portal',
        'official_requirements_page', 'official_page' => 'Official requirements',
        default => ucwords(str_replace('_', ' ', $status ?: 'Official resource')),
    };
}

function vm_primary_action_label(array $resource): string
{
    return match ($resource['resource_status'] ?? '') {
        'downloadable_official_pdf' => 'Download PDF',
        'official_online_portal' => 'Open Official Portal',
        'official_requirements_page', 'official_page' => 'Open Official Requirements',
        default => 'Open Official Source',
    };
}

function vm_is_pdf_url(string $url): bool { return (bool)preg_match('/\.pdf(\?|$)/i', $url); }
function vm_local_public_path(string $localPath): string { return __DIR__ . '/../public/' . ltrim($localPath, '/'); }
function vm_has_local_pdf(array $resource): bool
{
    $path = (string)($resource['local_path'] ?? '');
    return $path !== '' && is_file(vm_local_public_path($path));
}

function vm_resource_url(array $resource): string
{
    if (vm_has_local_pdf($resource)) { return vm_url('public/' . ltrim((string)$resource['local_path'], '/')); }
    return (string)($resource['url'] ?? $resource['official_page'] ?? '#');
}

function vm_slugify(string $value): string
{
    $value = strtolower(trim($value));
    $value = preg_replace('/[^a-z0-9]+/', '-', $value) ?? '';
    return trim($value, '-');
}

function vm_csrf_token(): string
{
    if (empty($_SESSION['csrf'])) { $_SESSION['csrf'] = bin2hex(random_bytes(32)); }
    return (string)$_SESSION['csrf'];
}

function vm_csrf_field(): string { return '<input type="hidden" name="csrf" value="' . vm_h(vm_csrf_token()) . '">'; }

function vm_verify_csrf(): void
{
    $posted = (string)($_POST['csrf'] ?? '');
    if ($posted === '' || !hash_equals((string)($_SESSION['csrf'] ?? ''), $posted)) {
        http_response_code(403);
        exit('Invalid request token.');
    }
}

function vm_input(string $key, int $max = 300): string
{
    $value = trim((string)($_POST[$key] ?? ''));
    $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', '', $value) ?? '';
    return substr($value, 0, $max);
}

function vm_save_submission(string $type, array $payload): string
{
    $dir = __DIR__ . '/../storage/submissions';
    if (!is_dir($dir)) { mkdir($dir, 0755, true); }
    $id = gmdate('Ymd-His') . '-' . bin2hex(random_bytes(4));
    $record = ['id' => $id, 'type' => $type, 'created_at' => gmdate('c'), 'ip_hash' => hash('sha256', (string)($_SERVER['REMOTE_ADDR'] ?? 'unknown')), 'data' => $payload];
    file_put_contents($dir . '/' . $type . '-' . $id . '.json', json_encode($record, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), LOCK_EX);
    return $id;
}

function vm_page_start(string $title, string $description = ''): void
{
    $GLOBALS['vm_page_title'] = $title;
    $GLOBALS['vm_page_description'] = $description;
    require __DIR__ . '/header.php';
}

function vm_page_end(): void { require __DIR__ . '/footer.php'; }
?>
