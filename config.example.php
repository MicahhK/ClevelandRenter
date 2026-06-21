<?php
// Copy this file to config.php and fill in your Bluehost values.
// config.php is gitignored — never commit real credentials.

// ── MySQL credentials (Bluehost cPanel → MySQL Databases) ───────────
define('DB_HOST', 'localhost');
define('DB_NAME', 'your_database_name');
define('DB_USER', 'your_database_user');
define('DB_PASS', 'your_database_password');

// ── Admin login ──────────────────────────────────────────────────────
define('ADMIN_USER', 'admin');
define('ADMIN_PASS', 'change_this_to_a_strong_password');

// ── Site root URL (no trailing slash) ───────────────────────────────
define('BASE_URL', 'https://clevelandrenter.com');
