<?php
// app/Controllers/ProductController.php
class ProductController {
    
    // Helper method to load views and pass data
    private function render($view, $data =[]) {
        extract($data); // Converts array keys into variables (e.g., ['products' => $list] becomes $products)
        
        // We use a layout file to avoid repeating HTML headers/footers
        ob_start(); // Start output buffering
        require "../app/Views/{$view}.php";
        $content = ob_get_clean(); // Get the view content

        require "../app/Views/layout.php"; // Inject content into layout
    }

    // [READ] List all products
    public function index() {
        $productModel = new ProductModel();
        $products = $productModel->all(); // Inherited from Model.php
        
        $this->render('products/index', ['products' => $products]);
    }

    // [CREATE] Show form
    public function create() {
        $this->render('products/create');
    }

    // [CREATE] Handle form submission
    public function store() {
        $productModel = new ProductModel();
        $data = [
            'name' => $_POST['name'] ?? '',
            'price' => $_POST['price'] ?? ''
        ];

        $productModel->save($data);
        
        // Redirect back to list
        header("Location: ?url=/products"); 
        exit;
    }

    //[UPDATE] Show edit form
    public function edit() {
        $id = $_GET['id'] ?? null;
        if (!$id) throw new Exception("Product ID required for editing.");

        $productModel = new ProductModel();
        $product = $productModel->find($id); // Inherited from Model.php

        if (!$product) throw new Exception("Product not found.");

        $this->render('products/edit', ['product' => $product]);
    }

    //[UPDATE] Handle form submission
    public function update() {
        $id = $_POST['id'] ?? null;
        if (!$id) throw new Exception("Product ID required for updating.");

        $productModel = new ProductModel();
        $data = [
            'name' => $_POST['name'] ?? '',
            'price' => $_POST['price'] ?? ''
        ];

        $productModel->update($id, $data);
        
        header("Location: ?url=/products");
        exit;
    }

    //[DELETE] Handle deletion
    public function delete() {
        $id = $_POST['id'] ?? null;
        if ($id) {
            $productModel = new ProductModel();
            $productModel->deleteById($id); // Inherited from Model.php
        }
        header("Location: ?url=/products");
        exit;
    }
}