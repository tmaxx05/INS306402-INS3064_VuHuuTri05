<?php
// enrollments/create.php
// Chọn 1 sinh viên + 1 khóa học để tạo bản ghi enrollments

require_once __DIR__ . '/../classes/Database.php';

$db = Database::getInstance();

// Lấy danh sách sinh viên & khóa học cho dropdown
$students = $db->fetchAll('SELECT id, name FROM students ORDER BY name');
$courses  = $db->fetchAll('SELECT id, title FROM courses ORDER BY title');

$errors     = [];
$student_id = 0;
$course_id  = 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = (int) ($_POST['student_id'] ?? 0);
    $course_id  = (int) ($_POST['course_id']  ?? 0);

    if ($student_id <= 0) {
        $errors['student_id'] = 'Vui lòng chọn sinh viên.';
    }

    if ($course_id <= 0) {
        $errors['course_id'] = 'Vui lòng chọn khóa học.';
    }

    if (empty($errors)) {
        try {
            // Kiểm tra trùng đăng ký
            $exists = $db->fetch(
                'SELECT id FROM enrollments WHERE student_id = ? AND course_id = ?',
                [$student_id, $course_id]
            );

            if ($exists) {
                $errors['general'] = 'Sinh viên này đã đăng ký khóa học này.';
            } else {
                $db->insert('enrollments', [
                    'student_id' => $student_id,
                    'course_id'  => $course_id,
                ]);

                header('Location: index.php?success=1');
                exit;
            }
        } catch (Exception $e) {
            $errors['general'] = 'Có lỗi xảy ra, vui lòng thử lại sau.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm đăng ký học</title>
</head>
<body>
<h1>Thêm đăng ký học</h1>

<?php if (!empty($errors['general'])): ?>
    <p style="color: red;"><?= htmlspecialchars($errors['general']) ?></p>
<?php endif; ?>

<form method="post">
    <div>
        <label>Sinh viên:</label><br>
        <select name="student_id">
            <option value="0">-- Chọn sinh viên --</option>
            <?php foreach ($students as $s): ?>
                <option value="<?= $s['id'] ?>"
                    <?= ($s['id'] == $student_id) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($s['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php if (!empty($errors['student_id'])): ?>
            <span style="color: red;"><?= htmlspecialchars($errors['student_id']) ?></span>
        <?php endif; ?>
    </div>

    <div>
        <label>Khóa học:</label><br>
        <select name="course_id">
            <option value="0">-- Chọn khóa học --</option>
            <?php foreach ($courses as $c): ?>
                <option value="<?= $c['id'] ?>"
                    <?= ($c['id'] == $course_id) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['title']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?php if (!empty($errors['course_id'])): ?>
            <span style="color: red;"><?= htmlspecialchars($errors['course_id']) ?></span>
        <?php endif; ?>
    </div>

    <button type="submit">Lưu đăng ký</button>
    <a href="index.php">Hủy</a>
</form>

</body>
</html>
