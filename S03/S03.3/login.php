<?php
session_start();

// Hardcoded credentials
$hardUser = 'admin';
$hardPass = '123456';

$msg = '';
if (!isset($_SESSION['attempts'])) {
    $_SESSION['attempts'] = 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Reset counter
    if (isset($_POST['reset'])) {
        $_SESSION['attempts'] = 0;
        $msg = 'Counter reset.';
    } else {
        $user = isset($_POST['username']) ? trim($_POST['username']) : '';
        $pass = isset($_POST['password']) ? $_POST['password'] : '';

        if ($user === $hardUser && $pass === $hardPass) {
            $msg = '<span style="color:green; font-weight:bold;">Login Successful</span>';
            // Reset attempts on success
            $_SESSION['attempts'] = 0;
        } else {
            $_SESSION['attempts'] += 1;
            $msg = '<span style="color:red; font-weight:bold;">Invalid Credentials</span>';
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Simple Login</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 2rem; }
    .box { max-width: 360px; padding: 1rem; border: 1px solid #ff0000; border-radius: 6px; }
    .field { margin-bottom: .75rem; }
    label { display:block; margin-bottom:.25rem; }
    input[type="text"], input[type="password"] { width:100%; padding:.5rem; }
    .msg { margin-bottom: .75rem; }
  </style>
</head>
<body>
  <div class="box">
    <h2>Login</h2>

    <?php if ($msg): ?>
      <div class="msg"><?php echo $msg; ?></div>
    <?php endif; ?>

    <?php if ($_SESSION['attempts'] > 0): ?>
      <p><strong>Failed Attempts:</strong> <?php echo (int)$_SESSION['attempts']; ?></p>
    <?php endif; ?>

    <form method="post" action="">
      <div class="field">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>
      </div>
      <div class="field">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
      </div>
      <div class="field">
        <button type="submit">Login</button>
      </div>
    </form>

    <form method="post" action="">
      <button type="submit" name="reset">Reset Counter</button>
    </form>

    <p style="margin-top:1rem; font-size:.9rem; color:#666;">(Use <code>admin</code> / <code>123456</code>)</p>
  </div>
</body>
</html>