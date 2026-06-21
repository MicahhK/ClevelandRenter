<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/db.php';

$page_title       = 'Contact Us — Cleveland Renter';
$page_description = 'Get in touch with Cleveland Renter. Call, email, or use our contact form to ask about available apartments or schedule a showing.';
$current_page     = 'Contact';

// Fetch listings for the dropdown (exclude rented)
$listings = $pdo->query("SELECT slug, name, neighborhood_label, beds, baths, rent, status FROM listings WHERE status != 'rented' ORDER BY sort_order ASC, id ASC")->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<main>

  <section class="page-hero">
    <div class="container">
      <h1>Contact Us</h1>
      <p>We're a small team — you'll always reach a real person. Don't hesitate to reach out.</p>
    </div>
  </section>

  <section class="contact-section">
    <div class="container">
      <div class="contact-layout">

        <div class="contact-form">
          <h2>Send us a message</h2>
          <p class="form-desc">Have a question about a listing, want to schedule a showing, or ready to apply? Fill out the form and we'll get back to you within one business day.</p>

          <!--
            PLACEHOLDER: Replace the action URL with your Formspree endpoint.
            Sign up at https://formspree.io
          -->
          <form action="https://formspree.io/f/REPLACE_WITH_YOUR_FORM_ID" method="POST" novalidate>
            <input type="hidden" name="_subject" value="New inquiry from Cleveland Renter website">

            <div class="form-row">
              <div class="form-group">
                <label for="first-name">First name <span aria-hidden="true">*</span></label>
                <input type="text" id="first-name" name="first_name" autocomplete="given-name" required placeholder="Jane">
              </div>
              <div class="form-group">
                <label for="last-name">Last name <span aria-hidden="true">*</span></label>
                <input type="text" id="last-name" name="last_name" autocomplete="family-name" required placeholder="Smith">
              </div>
            </div>

            <div class="form-row">
              <div class="form-group">
                <label for="email">Email address <span aria-hidden="true">*</span></label>
                <input type="email" id="email" name="email" autocomplete="email" required placeholder="jane@example.com">
              </div>
              <div class="form-group">
                <label for="phone">Phone number</label>
                <input type="tel" id="phone" name="phone" autocomplete="tel" placeholder="(216) 000-0000">
              </div>
            </div>

            <div class="form-group">
              <label for="interest">I'm interested in…</label>
              <select id="interest" name="interest">
                <option value="">— Select one —</option>
                <?php if ($listings): ?>
                <optgroup label="Available Units">
                  <?php foreach ($listings as $unit): ?>
                  <option value="<?= htmlspecialchars($unit['slug']) ?>">
                    <?= htmlspecialchars($unit['name']) ?> —
                    <?= htmlspecialchars($unit['neighborhood_label']) ?>,
                    <?= htmlspecialchars($unit['beds']) ?>bd/<?= htmlspecialchars($unit['baths']) ?>ba,
                    $<?= number_format($unit['rent']) ?>/mo
                    <?= $unit['status'] === 'coming-soon' ? '(coming soon)' : '' ?>
                  </option>
                  <?php endforeach; ?>
                </optgroup>
                <?php endif; ?>
                <optgroup label="General">
                  <option value="schedule-showing">Schedule a showing (not sure which unit)</option>
                  <option value="application">Question about the application process</option>
                  <option value="current-tenant">I'm a current tenant</option>
                  <option value="other">Something else</option>
                </optgroup>
              </select>
            </div>

            <div class="form-group">
              <label for="message">Message <span aria-hidden="true">*</span></label>
              <textarea id="message" name="message" required placeholder="Tell us a bit about what you're looking for — neighborhood preference, move-in date, number of bedrooms, etc."></textarea>
            </div>

            <div style="display:none" aria-hidden="true">
              <label for="_gotcha">Leave this blank</label>
              <input type="text" id="_gotcha" name="_gotcha" tabindex="-1" autocomplete="off">
            </div>

            <button type="submit" class="btn btn-primary form-submit">Send Message</button>
          </form>
        </div>

        <aside class="contact-sidebar">
          <div class="info-card">
            <h3>Get in touch directly</h3>
            <div class="info-item">
              <span class="info-icon">📞</span>
              <div><strong>Phone / Text</strong><a href="tel:2163937779">(216) 393-7779</a><div style="font-size:.82rem;color:var(--muted);margin-top:.15rem;">Mon–Sat, 9 am – 6 pm</div></div>
            </div>
            <div class="info-item">
              <span class="info-icon">✉</span>
              <div><strong>Email</strong><a href="mailto:clevelandrenter@gmail.com">clevelandrenter@gmail.com</a><div style="font-size:.82rem;color:var(--muted);margin-top:.15rem;">Typically replied within 1 business day</div></div>
            </div>
          </div>
          <div class="info-card">
            <h3>Areas we serve</h3>
            <p style="font-size:.9rem;color:var(--muted);margin-bottom:1rem;">We manage properties across three great Northeast Ohio neighborhoods.</p>
            <div class="areas-list">
              <span class="area-tag">Cleveland</span>
              <span class="area-tag">Lakewood</span>
              <span class="area-tag">Cleveland Heights</span>
            </div>
          </div>
          <div class="info-card">
            <h3>Helpful links</h3>
            <ul style="list-style:none;display:flex;flex-direction:column;gap:.5rem;">
              <li><a href="<?= BASE_URL ?>/apartments.php" style="font-size:.9rem;">→ Browse available apartments</a></li>
              <li><a href="<?= BASE_URL ?>/application.php" style="font-size:.9rem;">→ How to apply</a></li>
              <li><a href="<?= BASE_URL ?>/faq.php" style="font-size:.9rem;">→ Frequently asked questions</a></li>
            </ul>
          </div>
        </aside>

      </div>
    </div>
  </section>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
