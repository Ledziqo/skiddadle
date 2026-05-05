<?php
declare(strict_types=1);

// Centralized site configuration for VisaMenged
// Update these values here and they apply site-wide

return [
    'brand' => [
        'name' => 'VisaMenged',
        'tagline_en' => 'The clear path to your visa application.',
        'tagline_am' => 'ወደ ቪዛ ማመልከቻዎ ግልጽ መንገድ።',
    ],
    'contact' => [
        'email' => 'Aesliexx@gmail.com',
        'telegram_handle' => 'Aesliex',
        'telegram_url' => 'https://t.me/Aesliex',
    ],
    'pricing' => [
        'currency' => 'birr',
        'quick_audit' => 'from 999 birr',
        'letters' => 'from 1,500 birr',
        'full_review' => 'from 3,000 birr',
    ],
    'features' => [
        'enable_testimonials' => true,
        'enable_appointment_tracker' => false,
        'enable_dark_mode' => false,
        'enable_admin_panel' => false,
    ],
    'limits' => [
        'max_upload_mb' => 5,
        'max_upload_files' => 10,
        'submission_retention_days' => 90,
    ],
    'meta' => [
        'default_description' => 'Official visa resources and document support for Ethiopian applicants.',
        'default_keywords' => 'Ethiopia visa, visa application, embassy forms, VFS, TLS, Schengen visa, UK visa, US visa, Canada visa, document review, visa checklist',
    ],
];