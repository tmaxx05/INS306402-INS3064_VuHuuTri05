<!-- app/Views/layout.php -->
<!DOCTYPE html>
<html>
<head>
    <title>MVC Store</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
        .btn { padding: 5px 10px; text-decoration: none; border: 1px solid #ccc; background: #eee; color: #333; }
        .btn-danger { color: red; }
    </style>
</head>
<body>
    <h1>My MVC Store</h1>
    <!-- The $content variable comes from the Controller's render() method -->
    <?= $content ?> 
</body>
</html>