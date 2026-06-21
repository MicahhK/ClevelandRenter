/* ─── Mobile nav toggle ───────────────────────────────────────────── */
(function () {
  const toggle = document.getElementById('nav-toggle');
  const menu   = document.getElementById('mobile-menu');
  if (!toggle || !menu) return;

  toggle.addEventListener('click', () => {
    const open = toggle.getAttribute('aria-expanded') === 'true';
    toggle.setAttribute('aria-expanded', String(!open));
    menu.classList.toggle('open', !open);
    document.body.style.overflow = open ? '' : 'hidden';
  });

  // Close on outside click or Escape
  document.addEventListener('keydown', e => {
    if (e.key === 'Escape' && menu.classList.contains('open')) {
      toggle.setAttribute('aria-expanded', 'false');
      menu.classList.remove('open');
      document.body.style.overflow = '';
      toggle.focus();
    }
  });

  document.addEventListener('click', e => {
    if (!menu.contains(e.target) && e.target !== toggle && !toggle.contains(e.target)) {
      toggle.setAttribute('aria-expanded', 'false');
      menu.classList.remove('open');
      document.body.style.overflow = '';
    }
  });
})();

/* ─── FAQ accordion ──────────────────────────────────────────────── */
(function () {
  document.querySelectorAll('.accordion-btn').forEach(btn => {
    const panelId = btn.getAttribute('aria-controls');
    const panel   = document.getElementById(panelId);
    if (!panel) return;

    btn.addEventListener('click', () => {
      const open = btn.getAttribute('aria-expanded') === 'true';
      // Close all in same accordion group
      const group = btn.closest('.accordion');
      if (group) {
        group.querySelectorAll('.accordion-btn').forEach(b => {
          b.setAttribute('aria-expanded', 'false');
          const p = document.getElementById(b.getAttribute('aria-controls'));
          if (p) p.setAttribute('aria-hidden', 'true');
        });
      }
      if (!open) {
        btn.setAttribute('aria-expanded', 'true');
        panel.setAttribute('aria-hidden', 'false');
      }
    });
  });
})();

/* ─── Pre-select unit from ?unit= query param on contact page ────── */
(function () {
  const select = document.getElementById('interest');
  if (!select) return;
  const params = new URLSearchParams(window.location.search);
  const unit = params.get('unit');
  if (!unit) return;
  const option = select.querySelector('option[value="' + unit + '"]');
  if (option) {
    option.selected = true;
    // Scroll form into view smoothly
    select.closest('form').scrollIntoView({ behavior: 'smooth', block: 'start' });
  }
})();

/* ─── Apartment filter buttons ───────────────────────────────────── */
(function () {
  const filters = document.querySelectorAll('.filter-btn');
  const cards   = document.querySelectorAll('.listing-card[data-neighborhood]');
  if (!filters.length) return;

  filters.forEach(btn => {
    btn.addEventListener('click', () => {
      filters.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      const val = btn.dataset.filter;
      cards.forEach(card => {
        if (val === 'all' || card.dataset.neighborhood === val) {
          card.style.display = '';
        } else {
          card.style.display = 'none';
        }
      });
    });
  });
})();
