<?php
// app/Models/Model.php
abstract class Model {
    protected $db;
    protected $table; // Child classes must define this

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    // Shared code: Fetch all records from the table
    public function all() {
        if (empty($this->table)) {
            throw new Exception("Table name not set in Model.");
        }
        $stmt = $this->db->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll();
    }

    // Shared code: Find a single record by ID
    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    // Shared code: Delete a record by ID
    public function deleteById($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Abstract method: Forces all child classes to implement their own validation logic
    abstract public function validate($data): bool;
}