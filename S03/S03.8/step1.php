<?php
// step1.php - Step 1 collects Account Info (Username / Password)
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registration - Step 1</title>
    <style>body{font-family:Arial,Helvetica,sans-serif;max-width:700px;margin:40px auto;padding:0 20px;}label{display:block;margin:10px 0;}button{margin-top:10px;padding:8px 14px;} .hint{color:#666;font-size:0.9rem;}</style>
</head>
<body>
    <h2>Step 1 — Account Info</h2>
    <p class="hint">Enter a username and password and click <strong>Next</strong> to continue.</p>

    <form method="post" action="step2.php">
        <label>Username:<br>
            <input type="text" name="username" required autofocus>
        </label>

        <label>Password:<br>
            <input type="password" name="password" required>
        </label>

        <button type="submit">Next: Profile Info</button>
    </form>

</body>
</html>