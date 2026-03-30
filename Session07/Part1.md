# 1. JOIN Distinction:
INNER JOIN: Only returns rows with matches in both tables.

LEFT JOIN: Returns all rows from the left table, even if there’s no match in the right table (those unmatched columns will be NULL).
# 2. Aggregation Logic:
WHERE: Filters rows before aggregation.

HAVING: Filters groups after aggregation (e.g., only show categories with SUM(sales) > 1000).
# 3. PDO Definition & Advantages:
PDO stands for PHP Data Objects.
Advantage 1: It supports multiple database systems (MySQL, PostgreSQL, SQLite, etc.), meaning you can switch databases without rewriting all your code.
Advantage 2: It uses a consistent, object-oriented API and natively supports Prepared Statements, making it highly secure against SQL injection.
# 4. Security (Prepared Statements):
Prepared Statements separate the SQL query structure from the user-provided data. The database engine compiles the SQL template first. Then, user inputs are sent separately as literal values (not executable code). This guarantees that an attacker cannot alter the query's underlying logic via SQL Injection.
# 5. Execution Flow:
The database engine evaluates these clauses in this specific order: 1. WHERE (filters raw rows) -> 2. GROUP BY (aggregates the filtered rows) -> 3. HAVING (filters the grouped results).
