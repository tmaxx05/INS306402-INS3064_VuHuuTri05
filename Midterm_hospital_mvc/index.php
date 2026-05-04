<?php
require_once 'config/Database.php';
require_once 'controllers/PatientController.php';

$database = new Database();
$db = $database->getConnection();

$controller = new PatientController($db);
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

switch ($action) {
    case 'create':
        $controller->create();
        break;
    case 'store':
        $controller->store();
        break;
    case 'edit':
        $controller->edit($_GET['id']);
        break;
    case 'update':
        $controller->update();
        break;
    case 'delete':
        $controller->delete($_GET['id']);
        break;
    case 'index':
    default:
        $controller->index();
        break;
}
?>