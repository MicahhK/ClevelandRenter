<?php
header('Cache-Control: no-store, no-cache, must-revalidate');
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
            <h2>How to apply</h2>
            <p>Thank you for your interest in our apartments. To ensure a safe and enjoyable living environment for all residents, we conduct a standard background screening for each applicant. Follow the three steps below to complete your application.</p>
          </div>

          <ol class="steps-list" aria-label="Application steps">
            <li class="step">
              <div class="step-number" aria-hidden="true">1</div>
              <div class="step-body">
                <h3>Submit your application &amp; documents</h3>
                <p>Each prospective tenant must submit the following:</p>
                <ul style="margin:.75rem 0 .75rem 1.25rem;color:var(--muted);font-size:.93rem;display:flex;flex-direction:column;gap:.5rem;">
                  <li>A completed rental application form <em>(one per applicant — fill out digitally or print, complete, and scan)</em></li>
                  <li>Proof of steady income — any of the following:
                    <ul style="margin:.35rem 0 0 1.25rem;display:flex;flex-direction:column;gap:.25rem;">
                      <li>Three months of recent pay stubs</li>
                      <li>A hiring or employment offer letter</li>
                      <li>Student loan or financial aid documentation (if applicable)</li>
                    </ul>
                  </li>
                  <li>Photo of your driver's license or government-issued ID <em>(front and back)</em></li>
                </ul>
                <p style="margin-bottom:.5rem;font-size:.93rem;">You may submit documents through any of the following:</p>
                <ul style="margin:0 0 0 1.25rem;color:var(--muted);font-size:.93rem;display:flex;flex-direction:column;gap:.25rem;">
                  <li><strong>Email:</strong> <a href="mailto:clevelandrenter@gmail.com">ClevelandRenter@gmail.com</a></li>
                  <li><strong>Zillow message:</strong> Attach files directly to your Zillow inquiry</li>
                  <li><strong>Dropbox:</strong> Upload securely and share the link with <a href="mailto:clevelandrenter@gmail.com">ClevelandRenter@gmail.com</a></li>
                </ul>
              </div>
            </li>
            <li class="step">
              <div class="step-number" aria-hidden="true">2</div>
              <div class="step-body">
                <h3>Pay the application fee</h3>
                <p>A <strong>non-refundable $75 application fee</strong> is required per applicant. Please send payment via <strong>Zelle</strong> or <strong>PayPal</strong> to <a href="mailto:clevelandrenter@gmail.com">ClevelandRenter@gmail.com</a>.</p>
                <div class="step-tip">💡 For PayPal, select <strong>"Friends and Family"</strong> to avoid additional transaction fees.</div>
              </div>
            </li>
            <li class="step">
              <div class="step-number" aria-hidden="true">3</div>
              <div class="step-body">
                <h3>Screening process begins</h3>
                <p>Once we receive both the application fee and all required documents from each applicant, you will receive an email invite from our screening service (TransUnion SmartMove) to complete an online form.</p>
                <p style="margin-top:.6rem;">We will also verify your employment and income, and may contact your current and/or previous landlords to evaluate your rental history. The screening process typically takes <strong>3–5 business days</strong>, depending on response times from employers, landlords, and other agencies.</p>
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
            <div class="info-item"><span class="info-icon">💰</span><div><strong>Application fee</strong>$75 per applicant (non-refundable)</div></div>
            <div class="info-item"><span class="info-icon">💳</span><div><strong>Payment</strong>Zelle or PayPal (Friends &amp; Family)</div></div>
            <div class="info-item"><span class="info-icon">🪪</span><div><strong>ID required</strong>Gov't-issued photo ID (front &amp; back)</div></div>
            <div class="info-item"><span class="info-icon">⏱</span><div><strong>Screening time</strong>3–5 business days</div></div>
            <div class="info-item"><span class="info-icon">🔍</span><div><strong>Screening service</strong>TransUnion SmartMove</div></div>
          </div>
          <div class="info-card">
            <h3>Have questions?</h3>
            <p style="font-size:.9rem;color:var(--muted);margin-bottom:1rem;">Feel free to reach out — we look forward to potentially welcoming you to our community.</p>
            <div class="info-item"><span class="info-icon">📞</span><div><strong>Call us</strong><a href="tel:2163937740">(216) 393-7740</a></div></div>
            <div class="info-item"><span class="info-icon">✉</span><div><strong>Email</strong><a href="mailto:clevelandrenter@gmail.com">clevelandrenter@gmail.com</a></div></div>
            <a href="<?= BASE_URL ?>/faq.php" class="btn btn-outline" style="width:100%;justify-content:center;margin-top:.5rem;">Read the FAQ</a>
          </div>
        </aside>

      </div>
    </div>
  </section>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
