<?php
require_once __DIR__ . '/../controllers/ApiController.php';

$apiController = new ApiController();

header('Content-Type: application/json');

$requestMethod = $_SERVER['REQUEST_METHOD'];

switch ($requestMethod) {
    case 'GET':
        $apiController->getData();
        break;
    case 'POST':
        $apiController->postData();
        break;
    default:
        http_response_code(405);
        echo json_encode(['message' => 'Método não permitido']);
        break;
}