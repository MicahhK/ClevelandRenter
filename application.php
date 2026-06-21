<?php
require_once __DIR__ . '/config.php';
$page_title       = 'Application Process — Cleveland Renter';
$page_description = 'Learn how to apply for a rental property with Cleveland Renter. A simple, transparent process from inquiry to move-in.';
$current_page     = 'Apply';
require_once __DIR__ . '/includes/header.php';
?>

<main>

  <section class="page-hero">
    <div class="container">
      <h1>Application Process</h1>
      <p>A simple, transparent process — from finding a unit to picking up your keys.</p>
    </div>
  </section>

  <section class="application-section">
    <div class="container">
      <div style="display:grid;grid-template-columns:2fr 1fr;gap:4rem;align-items:start;">

        <div>
          <div class="app-intro">
            <h2>How it works</h2>
            <p>We keep the process straightforward. Most applicants hear back within one to two business days of submitting a complete application.</p>
          </div>

          <ol class="steps-list" aria-label="Application steps">
            <li class="step">
              <div class="step-number" aria-hidden="true">1</div>
              <div class="step-body">
                <h3>Browse &amp; find a unit you love</h3>
                <p>Start on our <a href="<?= BASE_URL ?>/apartments.php">Apartments page</a> to see what's currently available. If nothing is quite right today, contact us — new units open up regularly.</p>
              </div>
            </li>
            <li class="step">
              <div class="step-number" aria-hidden="true">2</div>
              <div class="step-body">
                <h3>Schedule a showing</h3>
                <p>Call us at <a href="tel:2163937779">(216) 393-7779</a> or use the <a href="<?= BASE_URL ?>/contact.php">contact form</a> to request a viewing. We'll confirm a time within one business day. Showings are available Monday–Saturday.</p>
                <div class="step-tip">💡 Come with questions about the building, neighbors, and anything you'd want to know before signing a lease. We'll give you honest answers.</div>
              </div>
            </li>
            <li class="step">
              <div class="step-number" aria-hidden="true">3</div>
              <div class="step-body">
                <h3>Submit your application</h3>
                <p>Once you've seen the unit and want to move forward, we'll send you a rental application. Have the following ready:</p>
                <ul style="margin:.75rem 0 0 1.25rem;color:var(--muted);font-size:.93rem;display:flex;flex-direction:column;gap:.35rem;">
                  <li>Government-issued photo ID</li>
                  <li>Two most recent pay stubs (or proof of income)</li>
                  <li>Contact info for previous landlords (last 2 years)</li>
                  <li>Application fee — $35 per adult applicant</li>
                </ul>
                <div class="step-tip">💡 All adult occupants (18+) must complete a separate application form.</div>
              </div>
            </li>
            <li class="step">
              <div class="step-number" aria-hidden="true">4</div>
              <div class="step-body">
                <h3>Screening &amp; approval</h3>
                <p>We run a standard credit and background check. We review income (generally 3× monthly rent), rental history, and credit. You'll hear from us within <strong>1–2 business days</strong> of a complete application.</p>
              </div>
            </li>
            <li class="step">
              <div class="step-number" aria-hidden="true">5</div>
              <div class="step-body">
                <h3>Sign the lease</h3>
                <p>If approved, we'll send a lease agreement for your review. We use a standard Ohio residential lease. Read it carefully, ask us anything, and sign when you're ready.</p>
                <div class="step-tip">💡 Leases are typically 12 months. We can discuss other terms on a case-by-case basis.</div>
              </div>
            </li>
            <li class="step">
              <div class="step-number" aria-hidden="true">6</div>
              <div class="step-body">
                <h3>Pay first month &amp; security deposit</h3>
                <p>Before receiving keys you'll pay the first month's rent and a security deposit equal to one month's rent. We accept certified check, money order, or bank transfer.</p>
              </div>
            </li>
            <li class="step">
              <div class="step-number" aria-hidden="true">7</div>
              <div class="step-body">
                <h3>Move in — welcome home!</h3>
                <p>We'll do a walk-through with you on move-in day, document the unit's condition together, and hand you the keys.</p>
                <div class="step-tip">🎉 That's it. We're glad to have you.</div>
              </div>
            </li>
          </ol>

          <div class="app-cta" style="margin-top:3rem;">
            <a href="<?= BASE_URL ?>/contact.php" class="btn btn-primary">Contact Us to Get Started</a>
            <a href="<?= BASE_URL ?>/apartments.php" class="btn btn-outline" style="margin-left:.75rem;">Browse Available Units</a>
          </div>
        </div>

        <aside>
          <div class="info-card">
            <h3>Quick reference</h3>
            <div class="info-item"><span class="info-icon">💰</span><div><strong>Application fee</strong>$35 per adult applicant</div></div>
            <div class="info-item"><span class="info-icon">🏦</span><div><strong>Security deposit</strong>One month's rent</div></div>
            <div class="info-item"><span class="info-icon">💼</span><div><strong>Income requirement</strong>Gross income ≥ 3× monthly rent</div></div>
            <div class="info-item"><span class="info-icon">⏱</span><div><strong>Decision time</strong>1–2 business days</div></div>
            <div class="info-item"><span class="info-icon">📋</span><div><strong>Lease term</strong>Typically 12 months</div></div>
          </div>
          <div class="info-card">
            <h3>Have questions?</h3>
            <p style="font-size:.9rem;color:var(--muted);margin-bottom:1rem;">We're happy to walk through the process with you before you apply.</p>
            <div class="info-item"><span class="info-icon">📞</span><div><strong>Call us</strong><a href="tel:2163937779">(216) 393-7779</a></div></div>
            <div class="info-item"><span class="info-icon">✉</span><div><strong>Email</strong><a href="mailto:clevelandrenter@gmail.com">clevelandrenter@gmail.com</a></div></div>
            <a href="<?= BASE_URL ?>/faq.php" class="btn btn-outline" style="width:100%;justify-content:center;margin-top:.5rem;">Read the FAQ</a>
          </div>
        </aside>

      </div>
    </div>
  </section>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
