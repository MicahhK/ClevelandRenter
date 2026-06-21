<?php
require_once __DIR__ . '/config.php';
$page_title       = 'FAQ — Cleveland Renter';
$page_description = 'Frequently asked questions about renting with Cleveland Renter — applications, pets, deposits, lease terms, and more.';
$current_page     = 'FAQ';
require_once __DIR__ . '/includes/header.php';
?>

<main>

  <section class="page-hero">
    <div class="container">
      <h1>Frequently Asked Questions</h1>
      <p>Answers to the questions we hear most often from prospective and current tenants.</p>
    </div>
  </section>

  <section class="faq-section">
    <div class="container">
      <div class="faq-layout">

        <div class="faq-main">

          <div class="faq-group">
            <div class="faq-group-title">Applying</div>
            <div class="accordion">
              <div class="accordion-item">
                <button class="accordion-btn" aria-expanded="false" aria-controls="faq-1">What do I need to apply?<span class="accordion-icon" aria-hidden="true">+</span></button>
                <div class="accordion-panel" id="faq-1" role="region" aria-hidden="true"><div class="accordion-inner"><p>A valid government-issued photo ID, your two most recent pay stubs or other proof of income, and contact information for your landlords from the past two years. There's also a $35 application fee per adult (18+), which covers the credit and background check.</p></div></div>
              </div>
              <div class="accordion-item">
                <button class="accordion-btn" aria-expanded="false" aria-controls="faq-2">What credit score do I need?<span class="accordion-icon" aria-hidden="true">+</span></button>
                <div class="accordion-panel" id="faq-2" role="region" aria-hidden="true"><div class="accordion-inner"><p>We don't have a hard cutoff. We look at the full picture — income, rental history, and credit together. A lower score paired with strong income and good references may still be approved. If you have concerns, reach out before applying.</p></div></div>
              </div>
              <div class="accordion-item">
                <button class="accordion-btn" aria-expanded="false" aria-controls="faq-3">How much income do I need?<span class="accordion-icon" aria-hidden="true">+</span></button>
                <div class="accordion-panel" id="faq-3" role="region" aria-hidden="true"><div class="accordion-inner"><p>We generally look for a gross monthly income of at least 3× the monthly rent. Multiple roommates can combine incomes. Self-employed applicants can provide bank statements or tax returns in place of pay stubs.</p></div></div>
              </div>
              <div class="accordion-item">
                <button class="accordion-btn" aria-expanded="false" aria-controls="faq-4">How long does approval take?<span class="accordion-icon" aria-hidden="true">+</span></button>
                <div class="accordion-panel" id="faq-4" role="region" aria-hidden="true"><div class="accordion-inner"><p>Once we have a complete application — all documents and the fee — we typically decide within one to two business days. Incomplete applications take longer, so have everything ready before submitting.</p></div></div>
              </div>
            </div>
          </div>

          <div class="faq-group">
            <div class="faq-group-title">Pets</div>
            <div class="accordion">
              <div class="accordion-item">
                <button class="accordion-btn" aria-expanded="false" aria-controls="faq-5">Do you allow pets?<span class="accordion-icon" aria-hidden="true">+</span></button>
                <div class="accordion-panel" id="faq-5" role="region" aria-hidden="true"><div class="accordion-inner"><p>Pet policies vary by unit. Some properties allow cats or small dogs; others do not. Each listing notes whether pets are welcome. Contact us before applying if you have a specific pet and we'll tell you which units are a good match.</p></div></div>
              </div>
              <div class="accordion-item">
                <button class="accordion-btn" aria-expanded="false" aria-controls="faq-6">Is there a pet deposit?<span class="accordion-icon" aria-hidden="true">+</span></button>
                <div class="accordion-panel" id="faq-6" role="region" aria-hidden="true"><div class="accordion-inner"><p>Yes. For pet-friendly units we charge a refundable pet deposit of $250 per pet, in addition to the standard security deposit. It's returned at move-out provided there's no pet-related damage beyond normal wear.</p></div></div>
              </div>
            </div>
          </div>

          <div class="faq-group">
            <div class="faq-group-title">Deposits &amp; Fees</div>
            <div class="accordion">
              <div class="accordion-item">
                <button class="accordion-btn" aria-expanded="false" aria-controls="faq-7">How much is the security deposit?<span class="accordion-icon" aria-hidden="true">+</span></button>
                <div class="accordion-panel" id="faq-7" role="region" aria-hidden="true"><div class="accordion-inner"><p>The security deposit is equal to one month's rent and is due before move-in. It's held per Ohio law and returned within 30 days of move-out, minus any documented deductions for damage beyond normal wear and tear.</p></div></div>
              </div>
              <div class="accordion-item">
                <button class="accordion-btn" aria-expanded="false" aria-controls="faq-8">What utilities are included?<span class="accordion-icon" aria-hidden="true">+</span></button>
                <div class="accordion-panel" id="faq-8" role="region" aria-hidden="true"><div class="accordion-inner"><p>It varies by property. Some units include heat and/or water; others are tenant-pay-all. Each listing clearly states what's included.</p></div></div>
              </div>
              <div class="accordion-item">
                <button class="accordion-btn" aria-expanded="false" aria-controls="faq-9">Are there any other fees?<span class="accordion-icon" aria-hidden="true">+</span></button>
                <div class="accordion-panel" id="faq-9" role="region" aria-hidden="true"><div class="accordion-inner"><p>The only upfront costs are the application fee ($35/adult), first month's rent, and security deposit. No move-in fees or administrative fees. Late rent incurs a $50 late fee after a 5-day grace period, as stated in the lease.</p></div></div>
              </div>
            </div>
          </div>

          <div class="faq-group">
            <div class="faq-group-title">Lease &amp; Living</div>
            <div class="accordion">
              <div class="accordion-item">
                <button class="accordion-btn" aria-expanded="false" aria-controls="faq-10">What lease lengths do you offer?<span class="accordion-icon" aria-hidden="true">+</span></button>
                <div class="accordion-panel" id="faq-10" role="region" aria-hidden="true"><div class="accordion-inner"><p>Standard leases are 12 months. We may consider shorter terms (6 months) for a modest rent premium, subject to availability.</p></div></div>
              </div>
              <div class="accordion-item">
                <button class="accordion-btn" aria-expanded="false" aria-controls="faq-11">How do I submit a maintenance request?<span class="accordion-icon" aria-hidden="true">+</span></button>
                <div class="accordion-panel" id="faq-11" role="region" aria-hidden="true"><div class="accordion-inner"><p>Call or text us at <a href="tel:2163937779">(216) 393-7779</a> or email <a href="mailto:clevelandrenter@gmail.com">clevelandrenter@gmail.com</a>. We respond to non-emergencies within 24 hours and emergencies (no heat, gas leak, flooding) the same day.</p></div></div>
              </div>
              <div class="accordion-item">
                <button class="accordion-btn" aria-expanded="false" aria-controls="faq-12">Can I renew my lease?<span class="accordion-icon" aria-hidden="true">+</span></button>
                <div class="accordion-panel" id="faq-12" role="region" aria-hidden="true"><div class="accordion-inner"><p>Yes, and we encourage it. We'll reach out 60–90 days before your lease ends to discuss renewal. We value long-term tenants and try to keep rent increases reasonable and predictable.</p></div></div>
              </div>
              <div class="accordion-item">
                <button class="accordion-btn" aria-expanded="false" aria-controls="faq-13">What is your guest policy?<span class="accordion-icon" aria-hidden="true">+</span></button>
                <div class="accordion-panel" id="faq-13" role="region" aria-hidden="true"><div class="accordion-inner"><p>Guests staying fewer than 14 consecutive days are fine. If someone will be living with you long-term, they need to be added to the lease and go through the standard screening process.</p></div></div>
              </div>
            </div>
          </div>

        </div>

        <aside class="faq-sidebar">
          <div class="faq-sidebar-card">
            <h3>Still have questions?</h3>
            <p style="color:var(--muted);font-size:.9rem;margin-bottom:1.25rem;">We're happy to talk through anything.</p>
            <div class="contact-detail"><span>📞</span><div><strong style="display:block;font-size:.78rem;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);">Phone</strong><a href="tel:2163937779" style="color:var(--navy);font-weight:600;text-decoration:none;">(216) 393-7779</a></div></div>
            <div class="contact-detail"><span>✉</span><div><strong style="display:block;font-size:.78rem;text-transform:uppercase;letter-spacing:.06em;color:var(--muted);">Email</strong><a href="mailto:clevelandrenter@gmail.com" style="color:var(--navy);font-weight:600;text-decoration:none;font-size:.9rem;">clevelandrenter@gmail.com</a></div></div>
            <a href="<?= BASE_URL ?>/contact.php" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:1rem;">Send a Message</a>
          </div>
          <div class="faq-sidebar-card">
            <h3>Ready to apply?</h3>
            <p style="color:var(--muted);font-size:.9rem;margin-bottom:1.25rem;">Browse available units and start the process today.</p>
            <a href="<?= BASE_URL ?>/apartments.php" class="btn btn-outline" style="width:100%;justify-content:center;margin-bottom:.75rem;">View Apartments</a>
            <a href="<?= BASE_URL ?>/application.php" class="btn btn-outline" style="width:100%;justify-content:center;">How to Apply</a>
          </div>
        </aside>

      </div>
    </div>
  </section>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
