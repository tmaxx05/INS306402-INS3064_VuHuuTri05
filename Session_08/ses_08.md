

## 1. Mục tiêu \& bối cảnh

- Xây dựng web app quản lý đăng ký học với 3 bảng liên kết: `students`, `courses`, `enrollments` (quan hệ nhiều–nhiều).
- Áp dụng lại: PDO + prepared statements, lớp `Database`, error reporting, try–catch, logging đã học.[^2][^1]
- Sau buổi học, sinh viên tự tay code xong CRUD cho ít nhất 2 bảng và phần lớn bảng còn lại, đồng thời hiểu cách xử lý lỗi trong ứng dụng thực tế.[^1][^2]

***

## 2. Thiết kế cơ sở dữ liệu (3 bảng có quan hệ)

### 2.1 Mô hình bài toán

- `students`: lưu thông tin sinh viên (id, name, email, created_at).
- `courses`: lưu thông tin khóa học (id, title, description, created_at).
- `enrollments`: mỗi dòng là một sinh viên đăng ký một khóa học (student_id, course_id, enrolled_at), có khóa ngoại tới `students` và `courses`.[^2]

Quan hệ:

- 1 sinh viên – nhiều khóa học (qua `enrollments`).
- 1 khóa học – nhiều sinh viên (qua `enrollments`).


### 2.2 Câu lệnh SQL tạo database \& bảng

```sql
CREATE DATABASE IF NOT EXISTS school_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE school_db;

-- Bảng students (sinh viên)
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bảng courses (khóa học)
CREATE TABLE IF NOT EXISTS courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bảng enrollments (đăng ký học)
CREATE TABLE IF NOT EXISTS enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    enrolled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_enroll_student FOREIGN KEY (student_id)
        REFERENCES students(id) ON DELETE CASCADE,
    CONSTRAINT fk_enroll_course FOREIGN KEY (course_id)
        REFERENCES courses(id) ON DELETE CASCADE,
    CONSTRAINT uc_enroll UNIQUE (student_id, course_id)
);
```


***

## 3. Chuẩn bị project PHP

### 3.1 Gợi ý cấu trúc thư mục

```text
project_root/
  config/
    database.php
  classes/
    Database.php
  students/
    index.php
    create.php
    edit.php
    delete.php
  courses/
    index.php
    create.php
    edit.php
    delete.php
  enrollments/
    index.php
    create.php
    delete.php
  index.php        (trang chính, link tới các module)
```


### 3.2 File cấu hình kết nối (`config/database.php`)

```php
<?php
// config/database.php
// Chứa thông tin cấu hình DB, các file khác require để dùng

return [
    'host'     => 'localhost',
    'dbname'   => 'school_db',   // Tên DB đã tạo ở trên
    'username' => 'root',        // Tùy cấu hình XAMPP/MAMP
    'password' => '',            // Thường rỗng trên XAMPP
    'charset'  => 'utf8mb4',
];
```


***

## 4. Lớp Database dùng PDO + xử lý lỗi

Dựa trên mẫu lớp `Database` session 10 và cách xử lý lỗi/exception ở session 9, ta gộp lại thành một lớp có log lỗi, ném `Exception` tổng quát cho controller xử lý.[^1][^2]

```php
<?php
// classes/Database.php

class Database
{
    private static ?Database $instance = null; // Singleton: chỉ 1 instance
    private PDO $pdo;                          // Đối tượng PDO dùng nội bộ

    // Private constructor: chỉ được gọi từ getInstance()
    private function __construct()
    {
        $config = require __DIR__ . '/../config/database.php';

        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";

        try {
            $this->pdo = new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Lỗi -> Exception
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // fetch() trả mảng kết hợp
                PDO::ATTR_EMULATE_PREPARES   => false,                  // Prepared thật
            ]);
        } catch (PDOException $e) {
            // Ghi log chi tiết cho dev
            error_log('DB connection failed: ' . $e->getMessage());
            // Thông báo chung chung cho user
            throw new Exception('Không thể kết nối cơ sở dữ liệu, vui lòng thử lại sau.');
        }
    }

    // Hàm public duy nhất để lấy instance
    public static function getInstance(): Database
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Lấy PDO raw nếu cần
    public function getConnection(): PDO
    {
        return $this->pdo;
    }

    // Hàm helper chạy query có/không có param
    public function query(string $sql, array $params = []): PDOStatement
    {
        try {
            $stmt = $this->pdo->prepare($sql); // Chuẩn bị câu lệnh
            $stmt->execute($params);           // Truyền mảng giá trị vào
            return $stmt;
        } catch (PDOException $e) {
            // Log chi tiết + SQL
            error_log('DB query failed: ' . $e->getMessage() . ' | SQL: ' . $sql);
            // Ném ra Exception chung chung
            throw new Exception('Có lỗi khi thao tác với cơ sở dữ liệu.');
        }
    }

    // Lấy nhiều bản ghi
    public function fetchAll(string $sql, array $params = []): array
    {
        return $this->query($sql, $params)->fetchAll();
    }

    // Lấy 1 bản ghi hoặc false
    public function fetch(string $sql, array $params = []): array|false
    {
        return $this->query($sql, $params)->fetch();
    }

    // Thêm bản ghi, trả về id mới
    public function insert(string $table, array $data): string
    {
        $columns      = implode(', ', array_keys($data));           // "name, email"
        $placeholders = implode(', ', array_fill(0, count($data), '?')); // "?, ?"

        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";

        $this->query($sql, array_values($data));

        return $this->pdo->lastInsertId();
    }

    // Cập nhật bản ghi, trả về số dòng bị ảnh hưởng
    public function update(string $table, array $data, string $where, array $whereParams = []): int
    {
        // "name = ?, email = ?"
        $set = implode(' = ?, ', array_keys($data)) . ' = ?';

        $sql = "UPDATE {$table} SET {$set} WHERE {$where}";

        $params = array_merge(array_values($data), $whereParams);

        return $this->query($sql, $params)->rowCount();
    }

    // Xóa bản ghi
    public function delete(string $table, string $where, array $params = []): int
    {
        $sql = "DELETE FROM {$table} WHERE {$where}";

        return $this->query($sql, $params)->rowCount();
    }
}
```


***

## 5. Xây module CRUD cho bảng `students`

### 5.1 Danh sách sinh viên – `students/index.php`

```php
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
```


### 5.2 Thêm sinh viên – `students/create.php` (validate + try–catch)

```php
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
```


### 5.3 Sửa sinh viên – `students/edit.php`

```php
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
```


### 5.4 Xóa sinh viên – `students/delete.php`

```php
<?php
// students/delete.php
// Xóa sinh viên, đã có confirm() bên client

require_once __DIR__ . '/../classes/Database.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    header('Location: index.php');
    exit;
}

try {
    $db = Database::getInstance();
    $db->delete('students', 'id = ?', [$id]);
} catch (Exception $e) {
    // Có thể log thêm hoặc redirect với thông báo lỗi
}

header('Location: index.php?deleted=1');
exit;
```


***

## 6. Gợi ý CRUD cho `courses` (GV có thể rút gọn)

- Tạo module `courses` với 4 file tương tự `students`.
- Cột chính: `title`, `description`.
- Validate: `title` không rỗng, chiều dài tối thiểu 3 ký tự.
- Đây là bài tập nhỏ để sinh viên làm theo mẫu ngay trên lớp.

***

## 7. Quản lý `enrollments` (liên kết 2 bảng)

### 7.1 Danh sách đăng ký – `enrollments/index.php`

```php
<?php
// enrollments/index.php

require_once __DIR__ . '/../classes/Database.php';

$db = Database::getInstance();

$sql = 'SELECT e.id,
               s.name  AS student_name,
               s.email,
               c.title AS course_title,
               e.enrolled_at
        FROM enrollments e
        JOIN students s ON e.student_id = s.id
        JOIN courses  c ON e.course_id  = c.id
        ORDER BY e.enrolled_at DESC';

$enrollments = $db->fetchAll($sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách đăng ký học</title>
</head>
<body>
<h1>Danh sách đăng ký học</h1>

<p>
    <a href="create.php">+ Thêm đăng ký</a>
</p>

<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>Sinh viên</th>
        <th>Email</th>
        <th>Khóa học</th>
        <th>Thời gian đăng ký</th>
        <th>Hành động</th>
    </tr>

    <?php foreach ($enrollments as $enroll): ?>
        <tr>
            <td><?= $enroll['id'] ?></td>
            <td><?= htmlspecialchars($enroll['student_name']) ?></td>
            <td><?= htmlspecialchars($enroll['email']) ?></td>
            <td><?= htmlspecialchars($enroll['course_title']) ?></td>
            <td><?= $enroll['enrolled_at'] ?></td>
            <td>
                <a href="delete.php?id=<?= $enroll['id'] ?>"
                   onclick="return confirm('Hủy đăng ký này?');">Xóa</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

</body>
</html>
```


### 7.2 Thêm đăng ký – `enrollments/create.php`

```php
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
```


### 7.3 Xóa đăng ký – `enrollments/delete.php`

```php
<?php
// enrollments/delete.php

require_once __DIR__ . '/../classes/Database.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    header('Location: index.php');
    exit;
}

try {
    $db = Database::getInstance();
    $db->delete('enrollments', 'id = ?', [$id]);
} catch (Exception $e) {
    // Log hoặc hiển thị message chung nếu cần
}

header('Location: index.php?deleted=1');
exit;
```


***

## 8. Gợi ý phân bổ 3 giờ trên lớp

- 0–20 phút: Ôn lại error types, error_reporting, try–catch, custom exception, logging (Session 09) và PDO + CRUD cơ bản (Session 10).[^2][^1]
- 20–40 phút: Thiết kế CSDL, viết lệnh tạo 3 bảng, giải thích khóa ngoại \& unique.
- 40–70 phút: Giải thích chi tiết `Database.php`, line-by-line, nhấn mạnh xử lý lỗi trong constructor \& query.
- 70–110 phút: Live coding `students/index.php` + `students/create.php` (GV code, SV gõ theo, đặt câu hỏi).
- 110–150 phút: SV tự làm `edit.php`, `delete.php` cho `students` + bắt đầu module `courses`; GV hỗ trợ, sửa lỗi trực tiếp.

**Bài tập nhỏ ngay trên lớp:**

- Thêm cột `phone` cho bảng `students` và bổ sung vào form create/edit.
- Thêm thông báo success/error rõ ràng trên `students/index.php`.
- Cố tình gây lỗi (email trùng, tắt MySQL) để xem hệ thống log và thông báo ra sao (mở file log).

***

## 9. Bài tập về nhà (1 tuần – chuẩn bị giữa kì)

1. Hoàn thiện CRUD cho `courses` (list, create, edit, delete) với validate `title` không rỗng, dài ≥ 3 ký tự.
2. Hoàn thiện module `enrollments`, bổ sung:
    - Lọc theo `course_id` trên trang danh sách (chọn 1 khóa học để chỉ xem SV của khóa đó).
    - Thêm phân trang (10 bản ghi/trang).
3. Tạo `ValidationException` riêng cho validate form (dựa trên ví dụ custom exception ở Session 09) và áp dụng cho `students` + `courses`.[^1]
4. Cấu hình `error_reporting`, `display_errors`, `log_errors` cho 2 môi trường: dev (hiện lỗi đầy đủ) và production (ẩn lỗi, chỉ log file).[^1]
5. Tự luyện: tạo thêm module CRUD mới (vd: `teachers` hoặc `classrooms`) từ đầu đến cuối trong 45 phút để mô phỏng bài giữa kì:
    - Tạo bảng có khóa chính, vài cột đơn giản.
    - Viết nhanh 4 file: `index.php`, `create.php`, `edit.php`, `delete.php` với validate \& try–catch.

Nếu bạn muốn, tôi có thể tiếp tục tách riêng từng section thành slide/bài thực hành chi tiết cho giờ lên lớp, hoặc bộ đề luyện tập giữa kì dựa đúng trên cấu trúc này.
<span style="display:none">[^10][^11][^12][^13][^14][^15][^16][^17][^18][^19][^20][^21][^3][^4][^5][^6][^7][^8][^9]</span>

<div align="center">⁂</div>

[^1]: session_09_error_handling-2.md

[^2]: session_10_php_mysql.md

[^3]: session_15_jquery_intro.md

[^4]: session_14_security_methods.md

[^5]: session_13_cookies_sessions.md

[^6]: session_12_web_app_development.md

[^7]: session_11_programming_techniques.md

[^8]: session_10_php_mysql.md

[^9]: session_09_error_handling.md

[^10]: session_07_advanced_sql.md

[^11]: session_06_database_design.md

[^12]: session_05_intro_sql.md

[^13]: session_04_intro_mysql.md

[^14]: session_03_dynamic_websites.md

[^15]: session_02_programming_php.md

[^16]: session_01_intro_php.md

[^17]: SYLLABUS.md

[^18]: session_08_review_midterm.md

[^19]: README.md

[^20]: 00_installation_guide.md

[^21]: 00_course_overview.md

