<?php
// students/index.php
// Hiển thị danh sách sinh viên, link sang thêm/sửa/xóa

require_once __DIR__ . '/../classes/Database.php';

$db = Database::getInstance();

// Lấy tất cả sinh viên, sắp xếp mới nhất lên trước
$students = $db->fetchAll('SELECT * FROM students ORDER BY created_at DESC');

// Đọc message đơn giản qua query string
$successMessage = '';
if (isset($_GET['success'])) {
    $successMessage = 'Thêm sinh viên thành công!';
} elseif (isset($_GET['updated'])) {
    $successMessage = 'Cập nhật sinh viên thành công!';
} elseif (isset($_GET['deleted'])) {
    $successMessage = 'Xóa sinh viên thành công!';
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý sinh viên</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background: #4CAF50; color: #fff; }
        .btn { padding: 4px 8px; text-decoration: none; border-radius: 3px; }
        .btn-add { background: #4CAF50; color: #fff; }
        .btn-edit { background: #2196F3; color: #fff; }
        .btn-delete { background: #f44336; color: #fff; }
    </style>
</head>
<body>
<h1>Quản lý sinh viên</h1>

<?php if ($successMessage): ?>
    <p style="color: green;"><?= htmlspecialchars($successMessage) ?></p>
<?php endif; ?>

<p>
    <a href="create.php" class="btn btn-add">+ Thêm sinh viên</a>
</p>

<table>
    <tr>
        <th>ID</th>
        <th>Họ tên</th>
        <th>Email</th>
        <th>Ngày tạo</th>
        <th>Hành động</th>
    </tr>

    <?php foreach ($students as $student): ?>
        <tr>
            <td><?= $student['id'] ?></td>
            <td><?= htmlspecialchars($student['name']) ?></td>
            <td><?= htmlspecialchars($student['email']) ?></td>
            <td><?= $student['created_at'] ?></td>
            <td>
                <a href="edit.php?id=<?= $student['id'] ?>" class="btn btn-edit">Sửa</a>
                <a href="delete.php?id=<?= $student['id'] ?>" class="btn btn-delete"
                   onclick="return confirm('Bạn chắc chắn muốn xóa?');">Xóa</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
