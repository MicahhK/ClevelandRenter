<?php
/**
 * One-time migration: adds zillow_url column to listings table.
 * Visit /migrate_zillow.php once, then DELETE this file.
 */
require_once __DIR__ . '/includes/db.php';

$pdo->exec("ALTER TABLE listings ADD COLUMN IF NOT EXISTS zillow_url VARCHAR(500) DEFAULT NULL AFTER image_path");
echo '<p>✅ Done — <strong>zillow_url</strong> column added. Delete this file now.</p>';
