<?php
require_once __DIR__ . '/../classes/Database.php';

$db = Database::getInstance();
$courses = $db->fetchAll('SELECT * FROM courses ORDER BY created_at DESC');

$message = '';
if (isset($_GET['success'])) $message = 'Course added successfully!';
if (isset($_GET['updated'])) $message = 'Course updated successfully!';
if (isset($_GET['deleted'])) $message = 'Course deleted successfully!';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Courses</title>
</head>
<body>

<h1>Course Management</h1>

<?php if ($message): ?>
    <p style="color: green;"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<a href="create.php">+ Add Course</a>

<table border="1" cellpadding="8">
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Description</th>
        <th>Created</th>
        <th>Action</th>
    </tr>

    <?php foreach ($courses as $course): ?>
        <tr>
            <td><?= $course['id'] ?></td>
            <td><?= htmlspecialchars($course['title']) ?></td>
            <td><?= htmlspecialchars($course['description']) ?></td>
            <td><?= $course['created_at'] ?></td>
            <td>
                <a href="edit.php?id=<?= $course['id'] ?>">Edit</a>
                <a href="delete.php?id=<?= $course['id'] ?>"
                   onclick="return confirm('Delete this course?')">Delete</a>
            </td>
        </tr>
    <?php endforeach; ?>

</table>

</body>
</html>
