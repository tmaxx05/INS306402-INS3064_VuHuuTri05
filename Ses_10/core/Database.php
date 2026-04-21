<?php
// core/Database.php
class Database {
    private $pdo;

    public function __construct() {
        $config = require '../config/database.php';
        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8mb4";
        
        try {
            $this->pdo = new PDO($dsn, $config['user'], $config['pass'],[
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            die("Database Connection Error: " . $e->getMessage());
        }
    }

    public function getConnection() {
        return $this->pdo;
    }
}