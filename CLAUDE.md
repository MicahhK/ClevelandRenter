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
├── index.php              — Home page (hero + 5 building cards)
├── index2.php             — Old alternate home page (kept for reference)
├── apartments.php         — All listings (available + coming soon)
├── building.php           — One building's page (?b=<slug>), lists its units
├── application.php        — Application process steps
├── faq.php                — FAQ accordion
├── contact.php            — Contact form (Formspree placeholder)
├── setup.php              — One-time DB setup (DELETE after running)
├── migrate_zillow.php     — One-time migration (DELETE after running)
├── migrate_buildings.php  — One-time migration (DELETE after running)
├── deploy.php             — Rsync deploy script (lives only on Bluehost, not in git)
├── config.php             — DB credentials (gitignored, never commit)
├── config.example.php     — Template for config.php
├── css/
│   └── styles.css
├── js/
│   └── main.js
├── assets/
│   ├── images/            — Uploaded listing photos
│   └── docs/              — Public downloads (the rental application PDF)
├── tools/                 — Dev-only, NOT deployed (see below)
│   ├── build_application_pdf.py
│   ├── .htaccess          — Denies web access as a second line of defence
│   └── source/            — Original 6-page application packet
├── includes/
│   ├── buildings.php      — The 5 buildings (config array + helpers)
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

**Important:** it must exclude `tools/` — that directory holds the original
application packet, which still contains the Screening Services Inc. release
page and its Social Security Number fields. It must never be web-reachable.

```php
<?php
$source = '/home2/arpefwmy/clevelandrenter/';
$dest   = '/home2/arpefwmy/public_html/';
exec("rsync -av --exclude='.git' --exclude='config.php' --exclude='tools' {$source} {$dest}", $output, $code);
echo '<pre>';
echo $code === 0 ? "✅ Deploy successful!\n" : "❌ Deploy failed (code $code)\n";
echo implode("\n", $output);
echo '</pre>';
```

## Rental application PDF

The public form lives at `assets/docs/cleveland-renter-rental-application-2026.pdf`
and is linked from `application.php`. Do not hand-edit it — regenerate it:

```
python3 tools/build_application_pdf.py    # needs pypdf + reportlab
```

The inherited packet in `tools/source/` was **already a fillable AcroForm** (91
fields). The build script does not create a form; it corrects and prunes the
existing one:

- **Drops page 6**, the Screening Services Inc. tenant release. Page 1 of the
  same packet names TransUnion SmartMove, so the packet referenced two different
  screening vendors. Page 6 was also the only page asking for an SSN.
- **Removes the SSN and Driver's License fields** from the applicant and
  Additional Occupants sections. SmartMove has the applicant enter identity data
  into TransUnion directly, so the business never takes custody of it — no
  encryption, retention policy, or breach exposure to manage on shared hosting.
- **Fixes the fee** ($50 on the form vs $75 in the instructions and on the site),
  in the text layer as well as visually, so copy/paste and screen readers agree.
- **Repairs a line** that rendered as mojibake (`7KHDSDUWPHQWaddress…`) because
  the source used a non-embedded TimesNewRoman with identity encoding.

Known cosmetic limitation: the source's `State:` label on the driver's licence
row is covered visually but still present in the text layer, because those
labels live inside Form XObjects. Harmless — there is no field to type into.

### Deferred: online application form

An application form on the site, posting to MySQL with an admin review screen,
was considered and **deliberately deferred**. The blocker is custody of
sensitive data on Bluehost shared hosting: SSNs, driver's licence numbers, and
ID photos behind an admin panel guarded by a single plaintext password in
`config.php`. If revisited, the shape that avoids the problem is: collect
contact, address history, employment, income, references and disclosures in the
web form, and leave identity data to SmartMove. Document uploads (pay stubs, ID
photos) should either stay on the existing email/Dropbox path or be stored
outside the webroot behind a gated download script — never in `assets/`.

## Database

- **Host:** localhost
- **Database:** `arpefwmy_Listings`
- **Table:** `listings`
- **Key columns:** id, name, neighborhood, neighborhood_label, building, beds, baths, sqft, rent, status, blurb, amenities (JSON), image_path, zillow_url, slug, sort_order
- **Status values:** `available`, `coming-soon`, `rented`
- Home page shows the 5 building cards, not individual units
- Apartments page shows `available` + `coming-soon` (excludes `rented`)
- Building pages show that building's `available` + `coming-soon` units

## Buildings

The five managed buildings live in `includes/buildings.php` as a PHP config
array, not in the database — the set changes rarely, so adding one is a code
edit rather than an admin task. Each entry has name, address, city, blurb,
image, and sort_order, keyed by slug:

```
9414-clifton, 16700-clifton, wagar, 2052-wascana, 2162-maplewood
```

Units point back at a building through the `listings.building` column, set
from the **Building** dropdown in the admin edit form. A unit with no building
still appears on the Apartments page, just not on any building page — the
admin dashboard flags those as "— none —".

The home page links each building card to `building.php?b=<slug>`. Individual
unit cards still link out to Zillow when a `zillow_url` is set.

To add a building: add an entry to `includes/buildings.php`, then assign units
to it in the admin panel. To add a photo: upload it to `assets/images/` and set
the entry's `image` to its path (e.g. `assets/images/wagar.jpg`); without one
the card falls back to the same gradient placeholder the unit cards use.

## Admin panel

- URL: `clevelandrenter.com/admin/`
- Login with ADMIN_USER / ADMIN_PASS from config.php
- Add/edit/delete listings, upload photos, set Zillow URLs
- Assign each unit to a Building so it appears on that building's page
- Zillow URLs: when set, unit cards link out to them

## Status

- [x] PHP/MySQL conversion complete
- [x] Admin panel with CRUD + image upload
- [x] Listings link to Zillow URLs when set
- [x] Home shows 5 building cards linking to per-building pages
- [x] Apartments tab shows available + coming soon
- [ ] Add building photos to `includes/buildings.php` (currently placeholders)
- [ ] Assign remaining units to their buildings in the admin panel
- [x] Git → Bluehost deploy pipeline via Git Version Control + deploy.php
- [x] Fillable rental application PDF published and linked from application.php
- [ ] Add real photos to listings (via admin panel)
- [ ] Replace Formspree placeholder in contact.php
- [x] Add Zillow URLs to each listing (via admin panel)
- [x] Domain transfer from Netfirms (separate — do last)
