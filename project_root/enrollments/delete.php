<?php
// enrollments/delete.php

require_once __DIR__ . '/../classes/Database.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
if ($id <= 0) {
    header('Location: index.php');
    exit;
}

try {
    $db = Database::getInstance();
    $db->delete('enrollments', 'id = ?', [$id]);
} catch (Exception $e) {
    // Log hoặc hiển thị message chung nếu cần
}

header('Location: index.php?deleted=1');
exit;
