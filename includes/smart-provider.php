<?php
declare(strict_types=1);

function vm_remote_smart_enabled(): bool
{
    return false;
}

function vm_remote_smart_call(array $messages, array $options = []): ?string
{
    return null;
}

function vm_remote_json_from_text(?string $text): ?array
{
    return null;
}

function vm_remote_letter_draft(array $payload): ?array
{
    return null;
}

function vm_remote_refusal_plan(array $payload): ?array
{
    return null;
}

function vm_remote_file_brain(array $payload): ?array
{
    return null;
}
