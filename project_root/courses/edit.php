<?php
require_once __DIR__ . '/../classes/Database.php';

$db = Database::getInstance();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    header('Location: index.php');
    exit;
}

$course = $db->fetch('SELECT * FROM courses WHERE id = ?', [$id]);
if (!$course) {
    header('Location: index.php');
    exit;
}

$title = $course['title'];
$description = $course['description'];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);

    if ($title === '') {
        $errors['title'] = 'Title is required.';
    } elseif (strlen($title) < 3) {
        $errors['title'] = 'Minimum 3 characters.';
    }

    if (empty($errors)) {
        try {
            $db->update('courses',
                ['title' => $title, 'description' => $description],
                'id = ?',
                [$id]
            );

            header('Location: index.php?updated=1');
            exit;

        } catch (Exception $e) {
            $errors['general'] = 'Update failed.';
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Course</title>
</head>
<body>

<h1>Edit Course</h1>

<form method="post">
    <div>
        <label>Title:</label><br>
        <input type="text" name="title" value="<?= htmlspecialchars($title) ?>">
        <?php if (!empty($errors['title'])): ?>
            <span style="color:red"><?= $errors['title'] ?></span>
        <?php endif; ?>
    </div>

    <div>
        <label>Description:</label><br>
        <textarea name="description"><?= htmlspecialchars($description) ?></textarea>
    </div>

    <button type="submit">Update</button>
    <a href="index.php">Cancel</a>
</form>

</body>
</html>
