<?php
// app/Models/ProductModel.php
class ProductModel extends Model {
    
    // Define the table name for the parent class's all() and find() methods
    protected $table = 'products'; 

    // Implement the abstract method required by Model
    public function validate($data): bool {
        // Basic validation: Name and Price are required, Price must be numeric
        if (empty($data['name']) || empty($data['price'])) {
            return false;
        }
        if (!is_numeric($data['price'])) {
            return false;
        }
        return true;
    }

    // Specific logic for saving a product
    public function save($data) {
        if (!$this->validate($data)) {
            throw new Exception("Validation failed for Product data.");
        }

        $stmt = $this->db->prepare("INSERT INTO products (name, price) VALUES (?, ?)");
        return $stmt->execute([$data['name'], $data['price']]);
    }

    // Specific logic for updating a product
    public function update($id, $data) {
         if (!$this->validate($data)) {
            throw new Exception("Validation failed for Product data.");
        }

        $stmt = $this->db->prepare("UPDATE products SET name = ?, price = ? WHERE id = ?");
        return $stmt->execute([$data['name'], $data['price'], $id]);
    }
}