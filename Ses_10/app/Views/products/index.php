<!-- app/Views/products/index.php -->
<h2>Product List</h2>
<a href="?url=/products/create" class="btn">Add New Product</a>

<table>
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Price</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($products as $p): ?>
    <tr>
        <td><?= $p['id'] ?></td>
        <td><?= htmlspecialchars($p['name']) ?></td>
        <td>$<?= htmlspecialchars($p['price']) ?></td>
        <td>
            <a href="?url=/products/edit&id=<?= $p['id'] ?>" class="btn">Edit</a>
            
            <!-- Delete should be a POST request for safety, not a simple link -->
            <form action="?url=/products/delete" method="POST" style="display:inline;">
                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>