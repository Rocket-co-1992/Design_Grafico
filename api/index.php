<?php
header('Content-Type: application/json');
require_once '../config/config.php';
require_once '../core/Database.php';
require_once '../core/Auth.php';

// Tratamento de CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    exit(0);
}

// Obter o endpoint e método
$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));
$endpoint = array_shift($request);
$method = $_SERVER['REQUEST_METHOD'];

// Autenticação via token
function checkToken() {
    $headers = apache_request_headers();
    if (!isset($headers['Authorization'])) {
        return false;
    }
    $token = str_replace('Bearer ', '', $headers['Authorization']);
    // Implementar validação do token
    return true;
}

// Roteamento da API
try {
    switch ($endpoint) {
        case 'pedidos':
            require_once 'endpoints/PedidosEndpoint.php';
            $api = new PedidosEndpoint();
            break;
            
        case 'clientes':
            require_once 'endpoints/ClientesEndpoint.php';
            $api = new ClientesEndpoint();
            break;
            
        default:
            http_response_code(404);
            echo json_encode(['erro' => 'Endpoint não encontrado']);
            exit;
    }
    
    if (!checkToken() && $endpoint != 'auth') {
        http_response_code(401);
        echo json_encode(['erro' => 'Não autorizado']);
        exit;
    }
    
    // Executar método correspondente
    switch ($method) {
        case 'GET':
            $response = $api->get($request);
            break;
        case 'POST':
            $response = $api->post(json_decode(file_get_contents('php://input'), true));
            break;
        case 'PUT':
            $response = $api->put($request, json_decode(file_get_contents('php://input'), true));
            break;
        case 'DELETE':
            $response = $api->delete($request);
            break;
        default:
            http_response_code(405);
            $response = ['erro' => 'Método não permitido'];
    }
    
    echo json_encode($response);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['erro' => $e->getMessage()]);
}
?>
