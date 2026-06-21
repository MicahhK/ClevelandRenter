# Cleveland Renter — Website Project

A static marketing website for **Cleveland Renter**, a small property management
business. This file is the project brief: read it before making changes, and keep
it updated as the project evolves.

## What this is

- A **plain HTML / CSS / JavaScript** site. No framework, no build step.
- Hosted on **GitHub Pages** (free, static only).
- It replaces an older WordPress site at the same domain. The new site is built
  here first; the live domain is pointed at it only at the very end.

## Hard constraints (do not violate)

- **No build tooling.** No npm, bundlers, transpilers, or frameworks (no React,
  Vue, Astro, Tailwind build step, etc.). Files run as-is in a browser.
- **Static only.** No server-side code. GitHub Pages cannot run PHP, Node, or
  Python servers, and cannot use a database.
- **Vanilla CSS and JS.** A CDN link is acceptable for fonts or an icon set, but
  prefer keeping dependencies near zero.
- The contact form must use a **third-party form service** (e.g. Formspree),
  since there is no backend. Leave the endpoint as a clearly-marked placeholder.

## The business

- **Name:** Cleveland Renter
- **What they do:** Professional property management serving Cleveland, Lakewood,
  and Cleveland Heights, OH.
- **Phone:** 216-393-7779
- **Email:** clevelandrenter@gmail.com
- **Audience:** prospective tenants looking for apartments in these neighborhoods.
- **The site's main job:** get visitors to browse available apartments and start
  an application / make contact.

## Pages

Mirror the existing site's structure. Each page shares the same header (logo +
nav + phone number) and footer.

1. **Home** (`index.html`) — Hero introducing Cleveland Renter and the areas
   served, a short "why rent with us" section, a few featured apartments, and a
   clear call to action (browse apartments / contact).
2. **Available Apartments** (`apartments.html`) — A grid or list of apartment
   listings. Each listing: photo, neighborhood, bed/bath, rent, short blurb, and
   a "Contact about this unit" / "Apply" link. Use placeholder listings for now,
   structured so they're easy to copy-paste and edit by hand.
3. **Application Process** (`application.html`) — Step-by-step explanation of how
   to apply (the steps ARE a real sequence, so numbered steps are appropriate
   here). End with a CTA to contact or apply.
4. **FAQ** (`faq.html`) — Common renter questions (application requirements,
   pets, deposits, lease terms, maintenance). Use an accordion or simple Q&A
   layout. Write plausible placeholder Q&A the owner can edit.
5. **Contact Us** (`contact.html`) — Contact form (name, email, phone, message),
   plus phone, email, and the areas served. Form posts to a placeholder
   Formspree endpoint.

## Design direction

The existing site uses a blue identity (royal-blue logo, navy footer). Keep the
blue family for brand continuity but make it a deliberate, refined system rather
than a default. Treat the tokens below as the starting point — improve on them if
you can justify it, but stay in this trustworthy/local register. Avoid a
generic SaaS-startup look; this is a local, hands-on property manager.

**Color (starting palette):**
- `--navy: #1e3a5f` — primary / footer / headings
- `--blue: #2f5bd0` — accent, links, primary buttons
- `--ink: #1a1d23` — body text
- `--paper: #f7f8fa` — page background
- `--line: #e2e6ec` — borders / dividers

**Type:** Playfair Display (headings) + Source Sans 3 (body) via Google Fonts CDN.

**Layout:** clean, generous spacing, content max-width 1160px. Listings are the
most important content — the apartments page is designed to be scannable and real.

**Quality floor (implemented):**
- Fully responsive, mobile-first. Header nav collapses at 680px.
- Visible keyboard focus states; sufficient color contrast.
- Respect `prefers-reduced-motion`.
- Semantic HTML (real `<nav>`, `<main>`, `<footer>`, headings in order).

## File structure

```
/
├── index.html
├── apartments.html
├── application.html
├── faq.html
├── contact.html
├── css/
│   └── styles.css
├── js/
│   └── main.js          (nav toggle, FAQ accordion, filter buttons)
├── assets/
│   └── images/          (logo, apartment photos — placeholders for now)
├── CLAUDE.md            (this file)
└── README.md
```

Header and footer markup is duplicated identically across all pages for
simplicity (no JS include pattern). Mirror edits across all five files.

## Deployment

- Repo: `git@github.com:MicahhK/ClevelandRenter.git`
- Hosted on **GitHub Pages** from the `main` branch (root).
- Use **relative links** between pages.
- The custom domain (`clevelandrenter.com`) is connected later via a `CNAME`
  file + DNS — NOT yet. Don't hardcode absolute URLs in internal links.

## Status / notes

- [x] Scaffold all five pages with shared header/footer
- [x] Build out Home
- [x] Build Available Apartments with editable placeholder listings
- [x] Application Process (numbered steps)
- [x] FAQ (accordion)
- [x] Contact form (placeholder Formspree endpoint)
- [x] Responsive + accessibility pass
- [ ] Add real photos and copy
- [ ] Replace Formspree placeholder endpoint (contact.html line ~60)
- [ ] Set up GitHub Pages
- [ ] (Last) point domain via CNAME + DNS

> The old WordPress site stays live at the domain until the very last step, so
> nothing here affects the current live site.
