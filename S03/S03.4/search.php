<?php
// Simple search echo example (GET request)
// Acceptance criteria: echo input into the value attribute using htmlspecialchars()
$q = isset($_GET['q']) ? $_GET['q'] : '';
$escaped = htmlspecialchars($q, ENT_QUOTES, 'UTF-8');
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Search Query Echo (GET Request)</title>
  <style>
    body { font-family: Arial, Helvetica, sans-serif; padding: 2rem; }
    form { margin-bottom: 1rem; }
    input[type="text"] { padding: .4rem; width: 300px; }
    button { padding: .4rem .8rem; }
  </style>
</head>
<body>
  <h1>Search Query Echo (GET Request)</h1>

  <form method="get" action="">
    <label for="q">Search:</label>
    <input id="q" name="q" type="text" value="<?php echo $escaped; ?>" autocomplete="off">
    <button type="submit">Search</button>
  </form>

  <?php if ($q !== ''): ?>
    <p>Showing results for: <strong><?php echo $escaped; ?></strong></p>
    <p>URL query string: <code>?q=<?php echo urlencode($q); ?></code></p>
  <?php else: ?>
    <p>Enter a term and press <em>Search</em> — the page will reload and the term will remain in the input box. The URL will show <code>?q=your_term</code>.</p>
  <?php endif; ?>

</body>
</html>