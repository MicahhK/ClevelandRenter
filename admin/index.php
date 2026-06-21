<?php
require_once __DIR__ . '/../config.php';
if (session_status() === PHP_SESSION_NONE) session_start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST['username'] ?? '');
    $pass = $_POST['password'] ?? '';

    if ($user === ADMIN_USER && $pass === ADMIN_PASS) {
        session_regenerate_id(true);
        $_SESSION['admin_logged_in'] = true;
        header('Location: dashboard.php');
        exit;
    }
    $error = 'Invalid username or password.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login — Cleveland Renter</title>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: system-ui, sans-serif; background: #f0f2f5; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
    .card { background: #fff; border-radius: 10px; padding: 2.5rem; width: 100%; max-width: 380px; box-shadow: 0 4px 20px rgba(0,0,0,.10); }
    .logo { font-size: 1.2rem; font-weight: 700; color: #1e3a5f; margin-bottom: 1.75rem; text-align: center; }
    label { display: block; font-size: .85rem; font-weight: 600; color: #1e3a5f; margin-bottom: .35rem; }
    input { width: 100%; padding: .65rem .9rem; border: 1.5px solid #e2e6ec; border-radius: 7px; font-size: .95rem; margin-bottom: 1.1rem; }
    input:focus { outline: none; border-color: #2f5bd0; box-shadow: 0 0 0 3px rgba(47,91,208,.15); }
    button { width: 100%; padding: .75rem; background: #2f5bd0; color: #fff; border: none; border-radius: 7px; font-size: 1rem; font-weight: 600; cursor: pointer; }
    button:hover { background: #4a73e8; }
    .error { background: #fff0f0; color: #b91c1c; border: 1px solid #fca5a5; border-radius: 7px; padding: .65rem .9rem; margin-bottom: 1rem; font-size: .88rem; }
  </style>
</head>
<body>
  <div class="card">
    <div class="logo">Cleveland Renter Admin</div>
    <?php if ($error): ?>
      <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST">
      <label for="username">Username</label>
      <input type="text" id="username" name="username" autocomplete="username" required>
      <label for="password">Password</label>
      <input type="password" id="password" name="password" autocomplete="current-password" required>
      <button type="submit">Log In</button>
    </form>
  </div>
</body>
</html>
