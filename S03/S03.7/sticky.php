<?php
// sticky.php
// A complex form demonstrating 'sticky' inputs: when validation fails
// the user's input is safely re-displayed in the form fields.

// Helper to safely echo values into HTML
function e($str) {
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

$errors = [];
$submitted = $_SERVER['REQUEST_METHOD'] === 'POST';

// Default values
$values = [
    'name' => '',
    'email' => '',
    'password' => '', // we'll NOT re-populate password field for security
    'gender' => '',
    'interests' => [],
    'country' => '',
    'bio' => '',
];

if ($submitted) {
    // Collect raw values (we'll sanitize when outputting)
    $values['name'] = trim($_POST['name'] ?? '');
    $values['email'] = trim($_POST['email'] ?? '');
    $values['password'] = $_POST['password'] ?? '';
    $values['gender'] = $_POST['gender'] ?? '';
    $values['interests'] = $_POST['interests'] ?? [];
    if (!is_array($values['interests'])) {
        // ensure array
        $values['interests'] = [$values['interests']];
    }
    $values['country'] = $_POST['country'] ?? '';
    $values['bio'] = trim($_POST['bio'] ?? '');

    // Validation
    if ($values['name'] === '') {
        $errors[] = 'Name is required.';
    }

    if ($values['email'] === '') {
        $errors[] = 'Email is required.';
    } elseif (!filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email is not a valid address.';
    }

    // Force an error if password is too short to demonstrate sticky behavior
    if ($values['password'] === '') {
        $errors[] = 'Password is required.';
    } elseif (strlen($values['password']) < 8) {
        $errors[] = 'Password too short. Must be at least 8 characters.'; // forced error
    }

    $validGenders = ['male', 'female', 'other'];
    if ($values['gender'] && !in_array($values['gender'], $validGenders, true)) {
        $errors[] = 'Invalid gender selection.';
    }

    $validCountries = ['us' => 'United States', 'ca' => 'Canada', 'uk' => 'United Kingdom', 'au' => 'Australia'];
    if ($values['country'] && !array_key_exists($values['country'], $validCountries)) {
        $errors[] = 'Invalid country selection.';
    }

    // Bio length check
    if (strlen($values['bio']) > 1000) {
        $errors[] = 'Bio is too long.';
    }

    // If no errors, pretend to process and show success message
    if (empty($errors)) {
        // In a real app you'd save to DB, etc. Here we show confirmation (but do not re-display password)
        $savedName = e($values['name']);
        $savedEmail = e($values['email']);
        // Clear password variable so it's not accidentally echoed
        $values['password'] = '';
        echo "<!doctype html>\n<html lang=\"en\">\n<head><meta charset=\"utf-8\"><title>Form Submitted</title></head>\n<body>\n<h1>✅ Form submitted successfully</h1>\n<p><strong>Name:</strong> {$savedName}</p>\n<p><strong>Email:</strong> {$savedEmail}</p>\n<p><a href=\"{$_SERVER['PHP_SELF']}\">Submit another response</a></p>\n</body>\n</html>";
        exit;
    }
}

?><!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Sticky Form Example</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 1rem; }
        .error { background:#ffe6e6; border:1px solid #ff4d4d; padding:1rem; margin-bottom:1rem; }
        .field { margin-bottom: .75rem; }
        label { display:block; margin-bottom:.25rem; font-weight:bold; }
    </style>
</head>
<body>
<h1>Sticky Form (Preserve Values on Validation Error)</h1>

<?php if (!empty($errors)): ?>
    <div class="error" role="alert">
        <strong>There were problems with your submission:</strong>
        <ul>
            <?php foreach ($errors as $err): ?>
                <li><?php echo e($err); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post" action="<?php echo e($_SERVER['PHP_SELF']); ?>">
    <div class="field">
        <label for="name">Name</label>
        <input id="name" name="name" type="text" value="<?php echo e($values['name']); ?>" required>
    </div>

    <div class="field">
        <label for="email">Email</label>
        <input id="email" name="email" type="email" value="<?php echo e($values['email']); ?>" required>
    </div>

    <div class="field">
        <label for="password">Password (minimum 8 chars)</label>
        <input id="password" name="password" type="password" value="">
        <small>For demo purposes the form will show an error if the password is shorter than 8 characters.</small>
    </div>

    <div class="field">
        <label>Gender</label>
        <label><input type="radio" name="gender" value="male" <?php echo ($values['gender']==='male') ? 'checked' : ''; ?>> Male</label>
        <label><input type="radio" name="gender" value="female" <?php echo ($values['gender']==='female') ? 'checked' : ''; ?>> Female</label>
        <label><input type="radio" name="gender" value="other" <?php echo ($values['gender']==='other') ? 'checked' : ''; ?>> Other</label>
    </div>

    <div class="field">
        <label>Interests</label>
        <?php $allInterests = ['programming'=>'Programming','music'=>'Music','sports'=>'Sports','reading'=>'Reading']; ?>
        <?php foreach ($allInterests as $key => $label): ?>
            <label><input type="checkbox" name="interests[]" value="<?php echo e($key); ?>" <?php echo in_array($key, $values['interests']) ? 'checked' : ''; ?>> <?php echo e($label); ?></label>
        <?php endforeach; ?>
    </div>

    <div class="field">
        <label for="country">Country</label>
        <select id="country" name="country">
            <option value="">-- Select --</option>
            <?php foreach ($validCountries as $code => $label): ?>
                <option value="<?php echo e($code); ?>" <?php echo ($values['country']===$code) ? 'selected' : ''; ?>><?php echo e($label); ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="field">
        <label for="bio">Bio</label>
        <textarea id="bio" name="bio" rows="5" cols="40"><?php echo e($values['bio']); ?></textarea>
    </div>

    <button type="submit">Submit</button>
</form>

<hr>
<p><em>Note:</em> For security, the password field is not re-populated. Other fields retain their values on validation failure and are safely output using <code>htmlspecialchars</code>.</p>

</body>
</html>