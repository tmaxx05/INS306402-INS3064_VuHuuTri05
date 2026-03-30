# 2.1 Task 1: Product Catalog with Categories

```sql

SELECT p.name AS product_name, c.category_name 
FROM products p
LEFT JOIN categories c ON p.category_id = c.id;

```

# 2.2 Task 2: Revenue Analysis by Category

```sql

SELECT c.category_name, SUM(oi.quantity * oi.unit_price) AS total_revenue
FROM order_items oi
JOIN products p ON oi.product_id = p.id
LEFT JOIN categories c ON p.category_id = c.id
GROUP BY c.id, c.category_name;

```

# 2.3 Task 3: VIP Customers

```sql

SELECT u.name, u.email, SUM(o.total_amount) AS total_spent
FROM users u
JOIN orders o ON u.id = o.user_id
GROUP BY u.id, u.name, u.email
ORDER BY total_spent DESC
LIMIT 3;

```
