<?php
// Self-processing contact form
// Combines logic and view in one file. Detect submission with POST.

$errors = [];
$values = [
    'name' => '',
    'email' => '',
    'phone' => '',
    'message' => ''
];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and trim input
    $values['name'] = isset($_POST['name']) ? trim($_POST['name']) : '';
    $values['email'] = isset($_POST['email']) ? trim($_POST['email']) : '';
    $values['phone'] = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $values['message'] = isset($_POST['message']) ? trim($_POST['message']) : '';

    // Validate name
    if ($values['name'] === '') {
        $errors['name'] = 'Please enter your name.';
    } elseif (mb_strlen($values['name']) < 2) {
        $errors['name'] = 'Name must be at least 2 characters.';
    }

    // Validate email
    if ($values['email'] === '') {
        $errors['email'] = 'Please enter your email.';
    } elseif (!filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Please enter a valid email address.';
    }

    // Validate phone (optional)
    if ($values['phone'] !== '' && !preg_match('/^\+?[\d\s\-\(\)]+$/', $values['phone'])) {
        $errors['phone'] = 'Please enter a valid phone number.';
    }

    // Validate message
    if ($values['message'] === '') {
        $errors['message'] = 'Please enter a message.';
    } elseif (mb_strlen($values['message']) < 5) {
        $errors['message'] = 'Message must be at least 5 characters.';
    }

    // If no errors, mark success (here you could send email, save to DB, etc.)
    if (empty($errors)) {
        // Example: mail(...) or other processing would occur here.
        $success = true;
    }
}

function e($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Contact (Self-Processing Form)</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; padding: 20px; max-width: 700px; margin: 0 auto; }
        form { margin-top: 1rem; }
        label { display:block; margin-top: .75rem; }
        input[type="text"], input[type="email"], textarea { width:100%; padding:.5rem; font-size:1rem; }
        .error { color:#b00020; font-size:.95rem; }
        .field { margin-bottom:.5rem; }
        .success { background:#e6ffed; border:1px solid #8fce9b; padding:15px; }
        .submit { margin-top: 1rem; }
        .button { display:inline-block; padding:.5rem .75rem; background:#1976d2; color:#fff; border-radius:4px; border:none; cursor:pointer; font-size:1rem; text-decoration:none; margin-top:1rem; font-weight:600; }
    </style>
</head>
<body>
    <h1>Contact (Self-Processing Form) to Me !!!</h1>

    <?php if ($success): ?>
        <div class="success" role="status">
            <h2>Thank You!</h2>
            <p>We received your message and will get back to you soon.</p>
            <form method="get" action="<?php echo e($_SERVER['PHP_SELF']); ?>">
                <button type="submit" class="button">Return form</button>
            </form>
        </div>
    <?php else: ?>

        <p>Please complete the form below and click <strong>Send</strong>.</p>

        <?php if (!empty($errors)): ?>
            <div class="error" role="alert">
                <p><strong>Please fix the errors below:</strong></p>
                <ul>
                <?php foreach ($errors as $err): ?>
                    <li><?php echo e($err); ?></li>
                <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?php echo e($_SERVER['PHP_SELF']); ?>" method="post" novalidate>
            <div class="field">
                <label for="name">Name</label>
                <input id="name" name="name" type="text" value="<?php echo e($values['name']); ?>">
            </div>

            <div class="field">
                <label for="email">Email</label>
                <input id="email" name="email" type="email" value="<?php echo e($values['email']); ?>">
            </div>
            <div class="field">
                <label for="phone">Phone</label>
                <input id="phone" name="phone" type="phone" value="<?php echo e($values['phone']); ?>">
            </div>

            <div class="field">
                <label for="message">Message</label>
                <textarea id="message" name="message" rows="6"><?php echo e($values['message']); ?></textarea>
            </div>

            <div class="submit">
                <button type="submit">Send</button>
            </div>
        </form>

    <?php endif; ?>

</body>
</html>