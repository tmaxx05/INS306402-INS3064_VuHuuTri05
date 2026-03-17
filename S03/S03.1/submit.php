<?php
function h($s){ return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Content-Type: text/html; charset=utf-8');
  echo '<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><title>Submit</title></head><body>';
  echo '<p><strong>Error:</strong> Form must be submitted via POST. <a href="contact.html">Go back</a>.</p>';
  echo '</body></html>';
  exit;
}

$fields = ['fullname' => 'Full Name', 'email' => 'Email', 'phone' => 'Phone Number', 'message' => 'Message'];

$missing = [];
$values = [];

foreach ($fields as $key => $label) {
  $val = isset($_POST[$key]) ? trim($_POST[$key]) : '';
  $values[$key] = $val;
  if ($val === '') {
    $missing[] = $label;
  }
}

header('Content-Type: text/html; charset=utf-8');
echo '<!DOCTYPE html><html lang="en"><head><meta charset="utf-8"><title>Submission Result</title></head><body>';

if (!empty($missing)) {
  echo '<h1 style="color:red">Missing Data</h1>';
  echo '<p>The following fields are missing:</p><ul>';
  foreach ($missing as $m) {
    echo '<li>' . h($m) . '</li>';
  }
  echo '</ul>';
  echo '<p><a href="contact.html">Return to contact form</a></p>';
} else {
  echo '<h1>Submission Received</h1>';
  echo '<ul>';
  foreach ($fields as $key => $label) {
    echo '<li><strong>' . h($label) . ':</strong> ' . nl2br(h($values[$key])) . '</li>';
  }
  echo '</ul>';
  echo '<p><a href="contact.html">Send another message</a></p>';
}

echo '</body></html>';

?>
