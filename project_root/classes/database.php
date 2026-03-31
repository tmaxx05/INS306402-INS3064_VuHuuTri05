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
