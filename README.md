# VisaMenged PHP Build

VisaMenged is a plain PHP 8+ shared-hosting website for Ethiopian visa applicants. It uses JSON files as the v1 content database, vanilla JavaScript for the no-login basket and filters, and no build step.

## Finished App

The completed site lives in:

```text
public_html/
```

Upload the contents of that folder to your hosting account's `public_html` directory.

## Local Structure

- `public_html/*.php` - public pages.
- `public_html/includes/` - reusable layout, helpers, cards and disclaimer.
- `public_html/data/` - JSON content database copied from the source data folder.
- `public_html/assets/css/style.css` - responsive VisaMenged UI.
- `public_html/assets/js/app.js` - forms library filtering.
- `public_html/assets/js/basket.js` - localStorage My Visa File basket.
- `public_html/storage/` - v1 JSON submissions and uploads, protected by `.htaccess`.
- `public_html/public/forms/` - official PDFs downloaded from public official URLs.
- `scripts/download_official_pdfs.sh` - helper script for downloading direct official PDFs.

## Hostinger / cPanel Deployment

1. In hosting file manager or FTP, open the hosting `public_html` directory.
2. Upload everything inside this repository's `public_html/` folder.
3. In the hosting PHP selector, choose PHP 8.0 or newer.
4. Confirm `.htaccess` files uploaded correctly. They disable directory listing, add `/country/{slug}` clean URLs, and protect `storage`.
5. Make sure `public_html/storage/submissions` and `public_html/storage/uploads` are writable by PHP.
6. If your host allows it, move `storage` outside web root and update paths in `includes/functions.php` and `review-request.php`.
7. Download official PDFs with `scripts/download_official_pdfs.sh` where practical, then upload them into `public_html/public/forms/{country}/`.

## Security Notes

- Public pages hide resources with `resource_status: needs_verification`.
- Output is escaped with `htmlspecialchars`.
- POST forms use CSRF tokens.
- Uploads allow only PDF, JPG, PNG, DOC and DOCX, with a 5MB per-file limit.
- Storage is blocked from public web access with `.htaccess`.
- VisaMenged is independent guidance and does not guarantee approval.

## No Build Step

There is no Node.js, React, Next.js, Laravel, database, login system or asset build pipeline. Edit PHP, CSS, JS and JSON directly, then upload.
