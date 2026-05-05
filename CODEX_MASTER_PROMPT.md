# CODEX MASTER PROMPT — VisaMenged PHP Build

You are Codex acting as a senior PHP full-stack developer, product designer, UX engineer, and security-minded implementation agent.

Build **VisaMenged** as a PHP 8+ website for shared hosting/cPanel/Hostinger. Do **not** use Next.js, React, Node.js, Laravel, or a database for v1 unless the user explicitly asks later.

## Context

VisaMenged is an Ethiopia-focused visa resource hub. It gathers official embassy/government/VFS/TLS/visa-center forms, PDFs, portals, checklists, and country guide resources for applicants applying from Ethiopia. It also offers VisaMenged-created support templates, checklist generator flows, document review requests, and paid document packs.

Brand promise:

> VisaMenged — The clear path to your visa application.

## Attached work folder

Use this folder as the project source of truth:

- `data/official_resources_top25.json` — official resources manifest for top 25 launch countries.
- `data/official_resources_top25.csv` — spreadsheet-friendly copy of the same resource data.
- `data/countries_top25.json` — top 25 launch country list and status.
- `data/country_resource_summary.json` — resource count summary by country.
- `data/support_templates.json` — VisaMenged-created templates.
- `data/visa_packs.json` — product-pack ideas.
- `scripts/download_official_pdfs.sh` — script to download public official PDFs into `/public/forms`.
- `php_starter/public_html/` — rough PHP starter scaffold. Replace/improve it, but keep PHP/shared-hosting architecture.

## Non-negotiable tech stack

Use PHP 8+, HTML5, CSS3, vanilla JavaScript, JSON files as the v1 content database, no login system, and no build step. The final site must be uploadable to `public_html` on shared hosting.

Do not use Next.js, React, Node.js runtime, auth/login, database for v1, or complex build tools.

## Required folder structure

```text
public_html/
  index.php
  forms.php
  country.php
  visa.php
  schengen.php
  templates.php
  pricing.php
  checklist-generator.php
  letter-generator.php
  review-request.php
  previous-refusal-helper.php
  about.php
  contact.php
  .htaccess

  assets/css/style.css
  assets/js/app.js
  assets/js/basket.js
  assets/img/

  includes/header.php
  includes/footer.php
  includes/nav.php
  includes/functions.php
  includes/resource-card.php
  includes/country-card.php
  includes/template-card.php
  includes/disclaimer.php

  data/official_resources_top25.json
  data/countries_top25.json
  data/country_resource_summary.json
  data/support_templates.json
  data/visa_packs.json

  public/forms/{country}/

  handlers/checklist.php
  handlers/letter-request.php
  handlers/review-request.php
  handlers/refusal-helper.php
  handlers/contact.php

  storage/submissions/
  storage/uploads/
```

Protect `/storage` and uploads. If storage is under public_html, add `.htaccess` restrictions to prevent script execution and directory listing.

## Core pages

### Homepage `/index.php`

Headline: `Prepare your visa file with confidence.`

Subheadline: `VisaMenged helps Ethiopian applicants find official embassy forms, application portals, checklists, and document support before they apply.`

CTAs: `Find My Visa Checklist`, `Browse Official Forms`.

Sections: top 25 country grid, how it works, popular resources, product cards, disclaimer.

### Official Forms Library `/forms.php`

Use `official_resources_top25.json`.

Features:
- Search by country, visa type, title, source organization.
- Filters: downloadable PDFs, online portals, requirements pages, country, visa type.
- Hide `needs_verification` by default.
- Resource cards must show country, visa type, title, category, resource status, source organization, notes, Preview/Download/Open Official Source, Add to My Visa File, Report outdated form.

Status rules:
- `downloadable_official_pdf`: show Preview + Download + Open Source.
- `official_online_portal`: show Open Official Portal only.
- `official_requirements_page`: show Open Official Requirements.
- `needs_verification`: hide publicly.

PDF preview:
If local PDF exists under `/public/forms/...`, use iframe preview. If not local but URL is PDF, show official URL download/open.

### Country pages `/country.php?slug=canada`

Render from `countries_top25.json`, filter resources from `official_resources_top25.json`.

Each page needs country name, region, guide status, summary note, official resources, common visa types, support templates, document pack CTA, file review CTA, last verified concept, disclaimer.

Clean URL: `/country/canada`.

### Visa type pages `/visa.php?country=china&type=student-x1-x2`

Build data-driven support for visa-type pages, especially China.

China category pages:
- Tourist L
- Business M
- Work Z
- Student X1/X2
- Family Q1/Q2 / S1/S2
- Transit G
- Crew C
- Journalist J1/J2
- Permanent Residence D
- Talent R

Do not invent fake PDFs. Show official material list/portal buttons and VisaMenged support templates.

### Schengen hub `/schengen.php`

Include shared Schengen form, 45-day warning for Ethiopia where relevant, links to Germany/France/Italy/Netherlands/Sweden/Austria/Belgium, VFS/TLS/embassy resources, tourist/family/business sections.

### Checklist Generator `/checklist-generator.php`

No login. Fields: destination country, visa type, citizenship, applying from Ethiopia, city, employment status, funding, purpose, invitation, previous refusal, family/minor, travel date, contact.

Output starter checklist, official resources to use, warnings, support templates, paid pack CTA, review CTA. Save request to JSON for v1.

### Templates `/templates.php`

Use `support_templates.json`. Show cards and generator CTAs for all templates.

### Letter Generator `/letter-generator.php`

No login. Generate rough draft preview and CTA for polished embassy-ready version.

### Document Review `/review-request.php`

Fields and uploads. Allow only PDF, JPG, PNG, DOC, DOCX. Sanitize, limit size, never execute uploads.

### Previous Refusal Helper `/previous-refusal-helper.php`

Collect refusal details and output evidence suggestions + paid explanation letter CTA.

### Pricing `/pricing.php`

Show free guides, checklist generator, packs from 499 birr, custom letters from 1,500 birr, full review from 3,000 birr, previous refusal strategy.

## My Visa File Basket

No login. Use localStorage. Users can add official resources, forms, country guide pages, templates, and packs. Provide remove/clear/print/download list.

## Design

Modern government-office + premium SaaS.

Palette:
- Background `#F8F5EF`
- Card `#FFFFFF`
- Text `#172033`
- Navy `#103B5B`
- Emerald `#0E8F72`
- Gold `#C9A227`
- Border `#E5E0D6`
- Warning `#FFF7E6`
- Risk `#B42318`
- Success `#027A48`

Use clean cards, rounded corners, source badges, last verified badges, strong search/filter UI, mobile-first responsive layout. Avoid fake approval claims, cheap travel-agency visuals, stock photo clutter, and too much Ethiopian flag color.

## Legal/disclaimer

Show site-wide:

`VisaMenged is independent guidance. We are not an embassy, government agency, immigration lawyer, or visa decision-maker. We gather official resources and provide support templates/checklists. Always verify final requirements on the official embassy, government, VFS, TLS, or visa-center website before applying. VisaMenged does not guarantee approval.`

Never say “guaranteed approval” or “we approve visas.”

## Security

Escape all output with `htmlspecialchars`, sanitize inputs, validate uploads, limit size, prevent PHP execution in uploads, add CSRF tokens if practical, do not expose raw submissions, disable directory listing, hide `needs_verification`.

## Implementation steps

1. Inspect the attached folder and JSON data.
2. Build/clean the PHP shared-hosting project.
3. Copy JSON into `public_html/data/`.
4. Make reusable includes/components.
5. Build homepage, forms library, country pages, checklist generator, templates, review forms.
6. Add `.htaccess` clean URLs.
7. Add localStorage My Visa File Basket.
8. Make UI polished and mobile responsive.
9. Add README with Hostinger/cPanel deployment steps.
10. Do not stop at a skeleton; make it usable.
