<?php
require_once __DIR__ . '/../classes/Database.php';

$id = (int)($_GET['id'] ?? 0);

if ($id > 0) {
    try {
        $db = Database::getInstance();
        $db->delete('courses', 'id = ?', [$id]);
    } catch (Exception $e) {
        // Optional: log error
    }
}

header('Location: index.php?deleted=1');
exit;
