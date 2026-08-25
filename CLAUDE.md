# Cleveland Renter — Website Project

A PHP + MySQL website for **Cleveland Renter**, a small property management
business serving Cleveland, Lakewood, and Cleveland Heights, OH.

## What this is

- **PHP / MySQL** site with an admin panel for managing listings.
- Hosted on **Bluehost shared hosting** at `clevelandrenter.com`.
- The site is proxied through **Cloudflare** (managed via Bluehost cPanel).
- No build tooling. No npm, bundlers, or frameworks. Vanilla CSS and JS only.
- `config.php` is gitignored — it lives only on the server and locally, never committed.

## The business

- **Name:** Cleveland Renter
- **What they do:** Professional property management
- **Phone:** 216-393-7779
- **Email:** clevelandrenter@gmail.com
- **Audience:** Prospective tenants looking for apartments in Cleveland, Lakewood, and Cleveland Heights.

## File structure

```
/
├── index.php              — Home page (Airbnb-style compact listing cards)
├── index2.php             — Old alternate home page (kept for reference)
├── apartments.php         — All listings (available + coming soon)
├── application.php        — Application process steps
├── faq.php                — FAQ accordion
├── contact.php            — Contact form (Formspree placeholder)
├── setup.php              — One-time DB setup (DELETE after running)
├── migrate_zillow.php     — One-time migration (DELETE after running)
├── deploy.php             — Rsync deploy script (lives only on Bluehost, not in git)
├── config.php             — DB credentials (gitignored, never commit)
├── config.example.php     — Template for config.php
├── css/
│   └── styles.css
├── js/
│   └── main.js
├── assets/
│   └── images/            — Uploaded listing photos
├── includes/
│   ├── db.php             — PDO connection
│   ├── header.php         — Shared header (nav, logo, phone)
│   ├── footer.php         — Shared footer
│   └── listing-card.php   — Full listing card component
└── admin/
    ├── index.php          — Login page
    ├── dashboard.php      — Listing management table
    ├── edit.php           — Add/edit listing form
    ├── save.php           — POST handler for add/edit
    ├── delete.php         — POST handler for delete
    ├── auth.php           — Session check + CSRF helpers
    └── logout.php
```

## config.php (never commit — create on server manually)

```php
<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'arpefwmy_Listings');
define('DB_USER', 'arpefwmy_admin');
define('DB_PASS', 'Tz6kQ@g~;+87');
define('ADMIN_USER', 'admin');
define('ADMIN_PASS', 'your_password_here');
define('BASE_URL', 'https://clevelandrenter.com');
```

## Deploy workflow (every time we make changes)

1. Make changes locally → commit → push to GitHub:
   ```
   git add <files>
   git commit -m "message"
   git push origin main
   ```
2. In **Bluehost cPanel → Git Version Control → ClevelandRenter → Update from Remote**
3. Visit `clevelandrenter.com/deploy.php` — this rsyncs from the git folder to public_html
4. **Clear Cloudflare cache** in Bluehost cPanel (REQUIRED — Cloudflare caches aggressively)

## Known Bluehost / Cloudflare issues

- **Cloudflare caches everything** — HTML pages, CSS, JS. Always clear cache after deploy or changes won't show up.
- **`public_html` permissions must be `755`** — it sometimes resets to `700` which breaks all pages. Fix: File Manager → go up one level → right-click public_html → Permissions → 755.
- **Subdirectory permissions must be `755`** — `admin/`, `css/`, `includes/`, `js/` all need 755. Files need 644.
- **`.htaccess` must exist** in public_html with at minimum `DirectoryIndex index.php`. Without it, only index.php is served and all other PHP pages return 403. `.htaccess` is NOT in git — create it manually on Bluehost.
- **`.htaccess` permissions must be `644`** — wrong permissions cause Apache error AH00529.
- **`config.php` is gitignored** — after a fresh deploy, check that config.php exists in public_html. If missing, recreate it manually in File Manager.
- **deploy.php is not in git** — it lives only in public_html on Bluehost. If lost, recreate it (see below).

## Recreating deploy.php (if lost)

Create `/home2/arpefwmy/public_html/deploy.php` with:

```php
<?php
$source = '/home2/arpefwmy/clevelandrenter/';
$dest   = '/home2/arpefwmy/public_html/';
exec("rsync -av --exclude='.git' --exclude='config.php' {$source} {$dest}", $output, $code);
echo '<pre>';
echo $code === 0 ? "✅ Deploy successful!\n" : "❌ Deploy failed (code $code)\n";
echo implode("\n", $output);
echo '</pre>';
```

## Database

- **Host:** localhost
- **Database:** `arpefwmy_Listings`
- **Table:** `listings`
- **Key columns:** id, name, neighborhood, neighborhood_label, beds, baths, sqft, rent, status, blurb, amenities (JSON), image_path, zillow_url, slug, sort_order
- **Status values:** `available`, `coming-soon`, `rented`
- Home page shows only `available` listings (limit 6)
- Apartments page shows `available` + `coming-soon` (excludes `rented`)

## Admin panel

- URL: `clevelandrenter.com/admin/`
- Login with ADMIN_USER / ADMIN_PASS from config.php
- Add/edit/delete listings, upload photos, set Zillow URLs
- Zillow URLs: when set, compact cards on home page link to them

## Status

- [x] PHP/MySQL conversion complete
- [x] Admin panel with CRUD + image upload
- [x] Airbnb-style compact listing cards on home page
- [x] Listings link to Zillow URLs when set
- [x] Home shows available only; apartments tab shows available + coming soon
- [x] Git → Bluehost deploy pipeline via Git Version Control + deploy.php
- [ ] Add real photos to listings (via admin panel)
- [ ] Replace Formspree placeholder in contact.php
- [x] Add Zillow URLs to each listing (via admin panel)
- [x] Domain transfer from Netfirms (separate — do last)
