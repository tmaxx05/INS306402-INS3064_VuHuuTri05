<?php
require_once 'Database.php';

// Get the PDO connection using Singleton pattern
$db = Database::getInstance()->getConnection();

// SQL query to get top 3 customers by total spending
$sql = "SELECT u.name, u.email, SUM(o.total_amount) AS total_spent
        FROM users u
        JOIN orders o ON u.id = o.user_id
        GROUP BY u.id, u.name, u.email
        ORDER BY total_spent DESC
        LIMIT 3";

// Prepare the SQL statement (prevents SQL Injection)
$stmt = $db->prepare($sql);

// Execute the query
$stmt->execute();

// Fetch all results as associative array
$results = $stmt->fetchAll();
?>

<!-- HTML Table to display data -->
<table border="1">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Total Spent</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($results as $row): ?>
        <tr>
            <!-- Display customer name -->
            <td><?= $row['name'] ?></td>

            <!-- Display customer email -->
            <td><?= $row['email'] ?></td>

            <!-- Display total amount spent -->
            <td><?= $row['total_spent'] ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
