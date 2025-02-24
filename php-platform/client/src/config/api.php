<?php
// Configurações da API
define('API_BASE_URL', 'http://localhost/php-platform/root/api/v1/');
define('API_TIMEOUT', 30);

// Função para obter a URL completa de um endpoint
function getApiUrl($endpoint) {
    return API_BASE_URL . $endpoint;
}
?>