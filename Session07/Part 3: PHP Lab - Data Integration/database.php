<?php
class Database {
    // Holds the single instance of the class (Singleton pattern)
    private static $instance = null;

    // PDO connection object
    private $connection;

    // Private constructor prevents direct object creation
    private function __construct() {
        // Data Source Name (DSN) defines connection info
        $dsn = "mysql:host=localhost;dbname=ecommerce_db;charset=utf8mb4";

        // PDO options to configure behavior
        $options = [
            // Throw exceptions on errors (better debugging)
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,

            // Fetch results as associative arrays by default
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,

            // Disable emulated prepared statements (use real ones for security)
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        
        try {
            // Create a new PDO connection
            // Replace 'root' and '' with your actual database credentials if needed
            $this->connection = new PDO($dsn, 'root', '', $options);

        } catch (PDOException $e) {
            // Stop execution if connection fails and show error message
            die("Database connection failed: " . $e->getMessage());
        }
    }

    // Static method to get the single instance of Database
    public static function getInstance() {
        // Create instance if it doesn't exist yet
        if (self::$instance == null) {
            self::$instance = new Database();
        }

        // Return the existing instance
        return self::$instance;
    }

    // Public method to access the PDO connection
    public function getConnection() {
        return $this->connection;
    }
}
?>
