<?php
// students/edit.php
// Sửa thông tin sinh viên theo id, có validate & check email trùng

require_once __DIR__ . '/../classes/Database.php';

$db = Database::getInstance();

// Lấy id từ query string
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    header('Location: index.php');
    exit;
}

$errors = [];

// Lấy sinh viên hiện tại
try {
    $student = $db->fetch('SELECT * FROM students WHERE id = ?', [$id]);
    if (!$student) {
        header('Location: index.php');
        exit;
    }
} catch (Exception $e) {
    die('Không lấy được dữ liệu sinh viên.');
}

$name  = $student['name'];
$email = $student['email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name  = trim($_POST['name']  ?? '');
    $email = trim($_POST['email'] ?? '');

    if ($name === '') {
        $errors['name'] = 'Vui lòng nhập họ tên.';
    }

    if ($email === '') {
        $errors['email'] = 'Vui lòng nhập email.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email không hợp lệ.';
    }

    if (empty($errors)) {
        try {
            // Email trùng nhưng không phải bản ghi hiện tại
            $existing = $db->fetch(
                'SELECT id FROM students WHERE email = ? AND id <> ?',
                [$email, $id]
            );

            if ($existing) {
                $errors['email'] = 'Email đã thuộc về sinh viên khác.';
            } else {
                $db->update('students', [
                    'name'  => $name,
                    'email' => $email,
                ], 'id = ?', [$id]);

                header('Location: index.php?updated=1');
                exit;
            }
        } catch (Exception $e) {
            $errors['general'] = 'Có lỗi khi cập nhật, vui lòng thử lại.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa sinh viên</title>
</head>
<body>
<h1>Sửa sinh viên</h1>

<?php if (!empty($errors['general'])): ?>
    <p style="color: red;"><?= htmlspecialchars($errors['general']) ?></p>
<?php endif; ?>

<form method="post">
    <div>
        <label>Họ tên:</label><br>
        <input type="text" name="name" value="<?= htmlspecialchars($name) ?>">
        <?php if (!empty($errors['name'])): ?>
            <span style="color: red;"><?= htmlspecialchars($errors['name']) ?></span>
        <?php endif; ?>
    </div>

    <div>
        <label>Email:</label><br>
        <input type="email" name="email" value="<?= htmlspecialchars($email) ?>">
        <?php if (!empty($errors['email'])): ?>
            <span style="color: red;"><?= htmlspecialchars($errors['email']) ?></span>
        <?php endif; ?>
    </div>

    <button type="submit">Cập nhật</button>
    <a href="index.php">Hủy</a>
</form>

</body>
</html>
