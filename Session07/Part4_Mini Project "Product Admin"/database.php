<?php
class Database {
    // Store the single instance of the Database class (Singleton)
    private static $instance = null;

    // Store the PDO connection object
    private $connection;

    // Private constructor prevents creating object from outside
    private function __construct() {
        // DSN (Data Source Name) contains database connection info
        $dsn = "mysql:host=localhost;dbname=ecommerce_db;charset=utf8mb4";

        // PDO options to configure behavior
        $options = [
            // Enable exception mode for error handling
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,

            // Set default fetch mode to associative array
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,

            // Disable emulated prepared statements for better security
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        
        try {
            // Create PDO connection to the database
            // Change 'root' and '' if your database has different credentials
            $this->connection = new PDO($dsn, 'root', '', $options);

        } catch (PDOException $e) {
            // Stop the program and show error if connection fails
            die("Database connection failed: " . $e->getMessage());
        }
    }

    // Static method to get the single instance of the class
    public static function getInstance() {
        // Create a new instance if it does not exist
        if (self::$instance == null) {
            self::$instance = new Database();
        }

        // Return the existing instance
        return self::$instance;
    }

    // Return the PDO connection for use in other files
    public function getConnection() {
        return $this->connection;
    }
}
?>
