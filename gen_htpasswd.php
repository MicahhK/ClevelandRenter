<?php
// Generates .htpasswd file for admin Basic Auth — DELETE after running
$user = 'admin';
$pass = 'Meganlinos1234!';
$hash = '{SHA}' . base64_encode(sha1($pass, true));
$line = $user . ':' . $hash;

file_put_contents('/home2/arpefwmy/.htpasswd_admin', $line . PHP_EOL);
echo '<pre>';
echo "✅ Created /home2/arpefwmy/.htpasswd_admin\n";
echo "DELETE this file now.\n";
echo '</pre>';
