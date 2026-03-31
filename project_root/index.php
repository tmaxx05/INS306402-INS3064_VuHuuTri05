<?php
require_once 'classes/Database.php';

$db = Database::getInstance();

// Get statistics
$totalStudents = $db->fetch("SELECT COUNT(*) AS total FROM students")['total'];
$totalCourses = $db->fetch("SELECT COUNT(*) AS total FROM courses")['total'];
$totalEnrollments = $db->fetch("SELECT COUNT(*) AS total FROM enrollments")['total'];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>

    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">

    <h1 class="mb-4">🎓 School Management Dashboard</h1>

    <div class="row">

        <!-- Students -->
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5>Total Students</h5>
                    <h2><?= $totalStudents ?></h2>
                </div>
            </div>
        </div>

        <!-- Courses -->
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5>Total Courses</h5>
                    <h2><?= $totalCourses ?></h2>
                </div>
            </div>
        </div>

        <!-- Enrollments -->
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5>Total Enrollments</h5>
                    <h2><?= $totalEnrollments ?></h2>
                </div>
            </div>
        </div>

    </div>

    <hr>

    <h3>Manage System</h3>

    <a href="students/index.php" class="btn btn-primary">Students</a>
    <a href="courses/index.php" class="btn btn-success">Courses</a>
    <a href="enrollments/index.php" class="btn btn-warning">Enrollments</a>
    <a href="logout.php" class="btn btn-danger float-end">Logout</a>

</div>

</body>
</html>
