<?php
// step2.php - Step 2 collects Profile Info (Bio / Location) and finalizes registration
function h($s){ return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

// If request doesn't have expected data, redirect to step1
if ($_SERVER['REQUEST_METHOD'] !== 'POST'){
    header('Location: step1.php');
    exit;
}

// If bio/location present -> final submission
if (isset($_POST['bio']) || isset($_POST['location'])){
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $bio = $_POST['bio'] ?? '';
    $location = $_POST['location'] ?? '';
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Registration Complete</title>
        <style>body{font-family:Arial,Helvetica,sans-serif;max-width:700px;margin:40px auto;padding:0 20px;}dl{background:#f8f8f8;padding:12px;border-radius:6px;}dt{font-weight:bold;margin-top:8px;}dd{margin:0 0 8px 0;padding-left:10px;}a{display:inline-block;margin-top:12px;}</style>
    </head>
    <body>
        <h2>Registration — Final Submission</h2>
        <p>All data collected from both steps is shown below.</p>

        <dl>
            <dt>Username</dt>
            <dd><?php echo h($username); ?></dd>

            <dt>Password</dt>
            <dd><?php echo h($password); ?></dd>

            <dt>Bio</dt>
            <dd><?php echo nl2br(h($bio)); ?></dd>

            <dt>Location</dt>
            <dd><?php echo h($location); ?></dd>
        </dl>

        <a href="step1.php">Start Over</a>
    </body>
    </html>
    <?php
    exit;
}

// Otherwise we expect username/password from step1 and show step2 form with hidden fields
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registration - Step 2</title>
    <style>body{font-family:Arial,Helvetica,sans-serif;max-width:700px;margin:40px auto;padding:0 20px;}label{display:block;margin:10px 0;}button{margin-top:10px;padding:8px 14px;} .hint{color:#666;font-size:0.9rem;}</style>
</head>
<body>
    <h2>Step 2 — Profile Info</h2>
    <p class="hint">Provide a short bio and your location. Click <strong>Finish</strong> to submit all data.</p>

    <form method="post" action="step2.php">
        <!-- keep account info as hidden fields so we can show all data in the final step -->
        <input type="hidden" name="username" value="<?php echo h($username); ?>">
        <input type="hidden" name="password" value="<?php echo h($password); ?>">

        <label>Bio:<br>
            <textarea name="bio" rows="4" cols="50" required></textarea>
        </label>

        <label>Location:<br>
            <input type="text" name="location" required>
        </label>

        <button type="submit">Finish</button>
    </form>

    <p><a href="step1.php">Back to Step 1</a></p>
</body>
</html>