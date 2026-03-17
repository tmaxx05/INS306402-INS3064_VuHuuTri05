<?php
// csrf_form.php
// Double-submit cookie CSRF protection example

$cookieName = 'csrf_token';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postedToken = $_POST['csrf_token'] ?? '';
    $cookieToken = $_COOKIE[$cookieName] ?? '';

    // Compare tokens (use hash_equals to mitigate timing attacks)
    if (!is_string($postedToken) || !is_string($cookieToken) || !hash_equals($cookieToken, $postedToken)) {
        header('HTTP/1.1 403 Forbidden');
        die('403 Forbidden');
    }

    // Safe to process the form
    $name = $_POST['name'] ?? '';
    $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');

    echo "<!doctype html>\n<html lang=\"en\">\n<head><meta charset=\"utf-8\"><title>CSRF Protected</title></head>\n<body>\n  <h1>Form submitted successfully ✅</h1>\n  <p>Received name: <strong>{$name}</strong></p>\n  <p><a href=\"{$_SERVER['PHP_SELF']}\">Back</a></p>\n</body>\n</html>";
    exit;
}

// On GET (or other methods): generate token and set cookie if not present
if (empty($_COOKIE[$cookieName])) {
    $token = bin2hex(random_bytes(32));
    // Set cookie (session cookie). In production consider Secure => true and proper SameSite value.
    setcookie($cookieName, $token, [
        'expires' => 0,
        'path' => '/',
        'secure' => false,
        'httponly' => false,
        'samesite' => 'Lax',
    ]);
} else {
    $token = $_COOKIE[$cookieName];
}

// Output the form with hidden CSRF token
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>CSRF Form</title>
</head>
<body>
  <h1>CSRF Protection (Double Submit Cookie)</h1>
  <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
    <label for="name">Name:</label>
    <input id="name" name="name" type="text" required>
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($token, ENT_QUOTES, 'UTF-8'); ?>">
    <button type="submit">Submit</button>
  </form>

  <hr>
  <p>Token (server-side cookie): <code><?php echo htmlspecialchars($token); ?></code></p>
  <p>Note: On submission, the server compares <code>$_POST['csrf_token']</code> with the stored cookie value and dies with "403 Forbidden" if they don't match.</p>
</body>
</html>
