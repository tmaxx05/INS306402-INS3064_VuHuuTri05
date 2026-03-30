<?php
require_once 'Database.php';

// Get database connection using Singleton pattern
$db = Database::getInstance()->getConnection();

// Fetch all categories to populate dropdown filter
$stmtCat = $db->query("SELECT id, category_name FROM categories");
$categories = $stmtCat->fetchAll();

// Capture user input safely (GET request)
$search_name = $_GET['search_name'] ?? '';
$search_category = $_GET['search_category'] ?? '';

// Base SQL query with LEFT JOIN
// LEFT JOIN ensures products without categories are still displayed
$sql = "SELECT p.id, p.name, p.price, c.category_name, p.stock 
        FROM products p 
        LEFT JOIN categories c ON p.category_id = c.id 
        WHERE 1=1"; // 1=1 makes it easier to append conditions dynamically

$params = [];

// Apply name filter using LIKE and prepared statement
if (!empty($search_name)) {
    $sql .= " AND p.name LIKE :name";
    $params[':name'] = '%' . $search_name . '%'; // wildcard search
}

// Apply category filter using exact match
if (!empty($search_category)) {
    $sql .= " AND p.category_id = :category_id";
    $params[':category_id'] = $search_category;
}

// Prepare and execute the query securely
$stmt = $db->prepare($sql);
$stmt->execute($params);

// Fetch all matching products
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Admin Dashboard</title>
    <style>
        /* Basic page styling */
        body { font-family: Arial, sans-serif; margin: 20px; }

        /* Filter form styling */
        .filter-form { 
            background: #f4f4f4; 
            padding: 15px; 
            margin-bottom: 20px; 
            border-radius: 5px; 
        }

        /* Table styling */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background: #ddd; }

        /* Highlight low stock products (requirement) */
        .low-stock { 
            background-color: #ffcccc; 
            color: #990000; 
            font-weight: bold; 
        }
    </style>
</head>
<body>

    <h2>Product Administration</h2>

    <!-- Filter Form -->
    <div class="filter-form">
        <form method="GET" action="index.php">
            
            <!-- Search by product name -->
            <label for="search_name">Search Name:</label>
            <input type="text" name="search_name" id="search_name"
                   value="<?= htmlspecialchars($search_name) ?>">

            <!-- Filter by category -->
            <label for="search_category">Category:</label>
            <select name="search_category" id="search_category">
                <option value="">-- All Categories --</option>

                <!-- Populate dropdown dynamically -->
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" 
                        <?= ($search_category == $cat['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['category_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <!-- Submit filter -->
            <button type="submit">Filter Products</button>

            <!-- Reset filters -->
            <a href="index.php">
                <button type="button">Reset</button>
            </a>
        </form>
    </div>

    <!-- Product Data Table -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Product Name</th>
                <th>Price</th>
                <th>Category</th>
                <th>Stock Level</th>
            </tr>
        </thead>
        <tbody>

            <?php if (count($products) > 0): ?>
                <?php foreach ($products as $product): ?>

                    <!-- Apply "low-stock" class if stock < 10 -->
                    <tr class="<?= ($product['stock'] < 10) ? 'low-stock' : '' ?>">

                        <!-- Display product ID -->
                        <td><?= htmlspecialchars($product['id']) ?></td>

                        <!-- Display product name safely -->
                        <td><?= htmlspecialchars($product['name']) ?></td>

                        <!-- Format price to 2 decimal places -->
                        <td>$<?= number_format($product['price'], 2) ?></td>

                        <!-- Show category or fallback text -->
                        <td><?= htmlspecialchars($product['category_name'] ?? 'Uncategorized') ?></td>

                        <!-- Display stock -->
                        <td><?= htmlspecialchars($product['stock']) ?></td>
                    </tr>

                <?php endforeach; ?>

            <?php else: ?>
                <!-- Show message if no data found -->
                <tr>
                    <td colspan="5" style="text-align:center;">
                        No products found matching your criteria.
                    </td>
                </tr>
            <?php endif; ?>

        </tbody>
    </table>

</body>
</html>
