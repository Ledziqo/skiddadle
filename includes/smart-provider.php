<?php
declare(strict_types=1);

function vm_env_value(string $key, string $default = ''): string
{
    $value = getenv($key);
    if ($value === false || $value === '') {
        $serverValue = $_SERVER[$key] ?? $_ENV[$key] ?? '';
        return is_scalar($serverValue) && (string)$serverValue !== '' ? (string)$serverValue : $default;
    }
    return (string)$value;
}

function vm_remote_smart_enabled(): bool
{
    $url = vm_env_value('OLLAMA_CLOUD_URL');
    return vm_env_value('VM_SMART_PROVIDER') === 'ollama'
        && $url !== ''
        && !str_contains($url, 'example')
        && !str_contains($url, 'your-ollama-cloud-endpoint')
        && vm_env_value('OLLAMA_CLOUD_MODEL') !== '';
}

function vm_remote_smart_call(array $messages, array $options = []): ?string
{
    if (!vm_remote_smart_enabled()) {
        return null;
    }

    $baseUrl = rtrim(vm_env_value('OLLAMA_CLOUD_URL'), '/');
    $apiKey = vm_env_value('OLLAMA_CLOUD_API_KEY');
    $model = vm_env_value('OLLAMA_CLOUD_MODEL');
    $mode = vm_env_value('OLLAMA_CLOUD_MODE', 'ollama');
    $timeout = (int)vm_env_value('OLLAMA_CLOUD_TIMEOUT', '35');
    $temperature = (float)($options['temperature'] ?? 0.35);

    if ($mode === 'openai') {
        $url = $baseUrl . '/v1/chat/completions';
        $payload = [
            'model' => $model,
            'messages' => $messages,
            'temperature' => $temperature,
        ];
    } else {
        $url = $baseUrl . '/api/chat';
        $payload = [
            'model' => $model,
            'messages' => $messages,
            'stream' => false,
            'options' => [
                'temperature' => $temperature,
            ],
        ];
    }

    $headers = ['Content-Type: application/json'];
    if ($apiKey !== '') {
        $headers[] = 'Authorization: Bearer ' . $apiKey;
    }

    $body = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if ($body === false) {
        return null;
    }

    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $timeout,
        ]);
        $response = curl_exec($ch);
        $status = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if (!is_string($response) || $status < 200 || $status >= 300) {
            return null;
        }
    } else {
        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => implode("\r\n", $headers),
                'content' => $body,
                'timeout' => $timeout,
                'ignore_errors' => true,
            ],
        ]);
        $response = file_get_contents($url, false, $context);
        if (!is_string($response)) {
            return null;
        }
    }

    $data = json_decode($response, true);
    if (!is_array($data)) {
        return null;
    }

    if ($mode === 'openai') {
        return trim((string)($data['choices'][0]['message']['content'] ?? '')) ?: null;
    }

    return trim((string)($data['message']['content'] ?? $data['response'] ?? '')) ?: null;
}

function vm_remote_json_from_text(?string $text): ?array
{
    if ($text === null || trim($text) === '') {
        return null;
    }

    $text = trim($text);
    if (str_starts_with($text, '```')) {
        $text = preg_replace('/^```(?:json)?\s*/i', '', $text) ?? $text;
        $text = preg_replace('/\s*```$/', '', $text) ?? $text;
    }

    $data = json_decode($text, true);
    return is_array($data) ? $data : null;
}

function vm_remote_letter_draft(array $payload): ?array
{
    $messages = [
        [
            'role' => 'system',
            'content' => 'You create practical, natural visa document drafts for Ethiopian applicants. Write like a careful real applicant or supporting person, not a copied template. Do not claim approval. Do not mention AI. Return strict JSON only with keys: title, html, rewrite_tips, risk_notes.',
        ],
        [
            'role' => 'user',
            'content' => json_encode([
                'task' => 'Create a visa letter draft and safety notes.',
                'template' => $payload['template'] ?? '',
                'name' => $payload['name'] ?? '',
                'country' => $payload['country'] ?? '',
                'purpose' => $payload['purpose'] ?? '',
                'dates' => $payload['dates'] ?? '',
                'funding' => $payload['funding'] ?? '',
                'ties' => $payload['ties'] ?? '',
                'requirements' => [
                    'Write 6 to 9 developed paragraphs where appropriate.',
                    'Use warm, factual, human wording without sounding emotional or desperate.',
                    'Mention purpose, dates, funding, ties, document consistency and final decision respect.',
                    'Use placeholders only when a fact is missing.',
                    'Keep output suitable as a starter draft, not a final submitted document.',
                    'Do not mention AI, model, automation, or generated.',
                ],
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        ],
    ];

    $data = vm_remote_json_from_text(vm_remote_smart_call($messages, ['temperature' => 0.25]));
    if (!$data) {
        return null;
    }

    $html = (string)($data['html'] ?? '');
    if ($html === '') {
        return null;
    }

    return [
        'title' => (string)($data['title'] ?? vm_letter_template_label((string)($payload['template'] ?? ''))),
        'html' => $html,
        'seed' => (int)($payload['variant_seed'] ?? 0),
        'variation_label' => 'Smart draft',
        'rewrite_tips' => array_values(array_filter((array)($data['rewrite_tips'] ?? []), 'is_scalar')),
        'risk_notes' => array_values(array_filter((array)($data['risk_notes'] ?? []), 'is_scalar')),
    ];
}

function vm_remote_refusal_plan(array $payload): ?array
{
    $messages = [
        [
            'role' => 'system',
            'content' => 'You build refusal-recovery action plans for Ethiopian visa applicants. Do not mention AI. Return strict JSON only with key: steps (array of short strings).',
        ],
        [
            'role' => 'user',
            'content' => json_encode([
                'task' => 'Create a refusal recovery plan.',
                'country' => $payload['country'] ?? '',
                'visa_type' => $payload['visa_type'] ?? '',
                'reason' => $payload['reason'] ?? '',
                'changes' => $payload['changes'] ?? '',
                'requirements' => [
                    'Give practical, evidence-focused steps.',
                    'Keep each step concise and actionable.',
                    'Do not guarantee approval.',
                ],
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        ],
    ];

    $data = vm_remote_json_from_text(vm_remote_smart_call($messages, ['temperature' => 0.2]));
    if (!$data || !isset($data['steps']) || !is_array($data['steps'])) {
        return null;
    }
    $steps = array_values(array_filter(array_map('strval', $data['steps']), static fn(string $v): bool => trim($v) !== ''));
    return $steps ? ['steps' => $steps] : null;
}

function vm_remote_file_brain(array $payload): ?array
{
    $messages = [
        [
            'role' => 'system',
            'content' => 'You analyze visa file readiness for Ethiopian applicants. Do not mention AI. Return strict JSON with keys: score (int 18-94), label (string), strengths (array), must_fix (array), likely_missing (array), consistency_checks (array), review_pitch (string).',
        ],
        [
            'role' => 'user',
            'content' => json_encode([
                'task' => 'Analyze file readiness and produce practical fix guidance.',
                'payload' => $payload,
                'requirements' => [
                    'No approval guarantees.',
                    'Use concise bullet-ready strings.',
                    'Focus on evidence, consistency, timing, and risk reduction.',
                ],
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        ],
    ];

    $data = vm_remote_json_from_text(vm_remote_smart_call($messages, ['temperature' => 0.15]));
    if (!$data || !is_array($data)) {
        return null;
    }
    if (!isset($data['score'], $data['label'], $data['must_fix'], $data['likely_missing'], $data['consistency_checks'])) {
        return null;
    }
    return [
        'score' => max(18, min(94, (int)$data['score'])),
        'label' => (string)$data['label'],
        'strengths' => array_values(array_filter(array_map('strval', (array)($data['strengths'] ?? [])))),
        'must_fix' => array_values(array_filter(array_map('strval', (array)$data['must_fix']))),
        'likely_missing' => array_values(array_filter(array_map('strval', (array)$data['likely_missing']))),
        'consistency_checks' => array_values(array_filter(array_map('strval', (array)$data['consistency_checks']))),
        'review_pitch' => (string)($data['review_pitch'] ?? 'The smart pre-check flags likely gaps and consistency risks so you can clean the file before submission.'),
    ];
}
