<?php
// Simple secure avatar upload handler
// Usage: GET -> form, POST -> handle upload

$maxSize = 2 * 1024 * 1024; // 2MB
$allowedMimes = [
    'image/jpeg' => '.jpg',
    'image/png' => '.png',
];
$uploadDir = __DIR__ . '/uploads';

function respond($message) {
    echo '<p>' . htmlspecialchars($message) . '</p>';
    echo '<p><a href="' . htmlspecialchars($_SERVER['PHP_SELF']) . '">Quay lại ↑ Tải ảnh khác</a></p>';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['avatar'])) {
        respond('No file uploaded.');
        exit;
    }

    $file = $_FILES['avatar'];

    // Check upload errors
    if ($file['error'] !== UPLOAD_ERR_OK) {
        $err = $file['error'];
        $msg = 'Upload error';
        switch ($err) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                $msg = 'File is too large.'; break;
            case UPLOAD_ERR_PARTIAL:
                $msg = 'File was only partially uploaded.'; break;
            case UPLOAD_ERR_NO_FILE:
                $msg = 'No file sent.'; break;
            default:
                $msg = 'Unknown upload error.'; break;
        }
        respond($msg);
        exit;
    }

    // Size check
    if ($file['size'] > $maxSize) {
        respond('File exceeds maximum allowed size of 2MB.');
        exit;
    }

    // Ensure it's an actual image (prevents fake Content-Type)
    $imageInfo = @getimagesize($file['tmp_name']);
    if ($imageInfo === false) {
        respond('Uploaded file is not a valid image.');
        exit;
    }

    // MIME type check using finfo
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!array_key_exists($mime, $allowedMimes)) {
        respond('Only JPEG and PNG images are allowed.');
        exit;
    }

    // Create uploads dir if missing
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            respond('Failed to create upload directory.');
            exit;
        }
    }

    // Generate unique file name
    try {
        $random = bin2hex(random_bytes(16));
    } catch (Exception $e) {
        $random = uniqid('', true);
    }
    $ext = $allowedMimes[$mime];
    $newName = $random . $ext;
    $destination = $uploadDir . DIRECTORY_SEPARATOR . $newName;

    // Move uploaded file
    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        respond('Failed to move uploaded file.');
        exit;
    }

    // Set safe permissions
    @chmod($destination, 0644);

    // Success message (do not reveal internal paths)
    $publicPath = 'uploads/' . $newName;
    echo '<p>Upload successful ✅</p>';
    echo '<p>File stored as: ' . htmlspecialchars($newName) . '</p>';
    echo '<p>Preview:</p>';
    echo '<img src="' . htmlspecialchars($publicPath) . '" alt="avatar" style="max-width:200px; height:auto;">';
    echo '<p><a href="' . htmlspecialchars($_SERVER['PHP_SELF']) . '">Upload another image</a></p>';
    exit;
}

// HTML form (GET)
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Upload Avatar</title>
</head>
<body>
<h2>Profile Avatar Upload</h2>
<form method="post" action="" enctype="multipart/form-data">
    <label for="avatar">Choose an avatar (JPEG or PNG, max 2MB):</label><br>
    <input type="file" name="avatar" id="avatar" accept="image/jpeg,image/png" required><br><br>
    <button type="submit">Upload</button>
</form>
</body>
</html>