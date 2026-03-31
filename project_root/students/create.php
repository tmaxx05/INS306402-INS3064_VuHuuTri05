<?php
// students/create.php
// Form thêm sinh viên mới, có validate & xử lý lỗi DB

require_once __DIR__ . '/../classes/Database.php';

$errors = [];
$name   = '';
$email  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Lấy dữ liệu từ form
    $name  = trim($_POST['name']  ?? '');
    $email = trim($_POST['email'] ?? '');

    // 1. Validate phía server
    if ($name === '') {
        $errors['name'] = 'Vui lòng nhập họ tên.';
    }

    if ($email === '') {
        $errors['email'] = 'Vui lòng nhập email.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email không hợp lệ.';
    }

    // 2. Nếu không có lỗi validate thì xử lý DB
    if (empty($errors)) {
        try {
            $db = Database::getInstance();

            // Kiểm tra email đã tồn tại chưa
            $existing = $db->fetch('SELECT id FROM students WHERE email = ?', [$email]);

            if ($existing) {
                $errors['email'] = 'Email đã tồn tại.';
            } else {
                // Thêm bản ghi mới
                $db->insert('students', [
                    'name'  => $name,
                    'email' => $email,
                ]);

                // Redirect về danh sách với thông báo success
                header('Location: index.php?success=1');
                exit;
            }
        } catch (Exception $e) {
            // Không show message nhạy cảm, chỉ báo lỗi chung
            $errors['general'] = 'Có lỗi xảy ra, vui lòng thử lại sau.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm sinh viên</title>
</head>
<body>
<h1>Thêm sinh viên mới</h1>

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

    <button type="submit">Lưu</button>
    <a href="index.php">Hủy</a>
</form>

</body>
</html>
