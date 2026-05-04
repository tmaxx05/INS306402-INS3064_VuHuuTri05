<!-- app/Views/products/edit.php -->
<h2>Edit Product</h2>
<form action="?url=/products/update" method="POST">
    <input type="hidden" name="id" value="<?= $product['id'] ?>">
    
    <label>Name:</label><br>
    <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required><br><br>
    
    <label>Price:</label><br>
    <input type="number" step="0.01" name="price" value="<?= htmlspecialchars($product['price']) ?>" required><br><br>
    
    <button type="submit" class="btn">Update</button>
    <a href="?url=/products" class="btn">Cancel</a>
</form>