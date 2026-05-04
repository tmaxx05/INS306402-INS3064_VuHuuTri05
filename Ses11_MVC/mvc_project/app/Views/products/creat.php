<!-- app/Views/products/create.php -->
<h2>Add Product</h2>
<form action="?url=/products/store" method="POST">
    <label>Name:</label><br>
    <input type="text" name="name" required><br><br>
    
    <label>Price:</label><br>
    <input type="number" step="0.01" name="price" required><br><br>
    
    <button type="submit" class="btn">Save</button>
    <a href="?url=/products" class="btn">Cancel</a>
</form>