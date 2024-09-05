<?php

require_once 'db.php';
require_once 'EmixDSL.php';

$dsn = "mysql:host=localhost;dbname=emilie";
$username = "root";
$password = "";
$options = [];

try {
    $conn = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données: " . $e->getMessage());
}

$crud = new EmixDSL($conn);

header('Content-Type: application/json');
$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

switch ($method) {
    case 'POST':
        $items = $input['items'];
        $result = $crud->create($items);
        echo json_encode(['success' => $result]);
        break;
    case 'GET':
        $result = $crud->read();
        echo json_encode($result);
        break;
    case 'PUT':
        $id = $_GET['id'];
        $item = $input['item'];
        $result = $crud->update($id, $item);
        echo json_encode(['success' => $result]);
        break;
    case 'DELETE':
        $id = $_GET['id'];
        $result = $crud->delete($id);
        echo json_encode(['success' => $result]);
        break;
    default:
        echo json_encode(['error' => 'Invalid request method']);
        break;
}