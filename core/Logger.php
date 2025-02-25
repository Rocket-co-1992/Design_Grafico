<?php
class Logger {
    private $logPath;
    
    public function __construct() {
        $this->logPath = dirname(__DIR__) . '/logs/';
        
        if (!file_exists($this->logPath)) {
            mkdir($this->logPath, 0777, true);
        }
    }
    
    public function registrar($tipo, $mensagem, $dados = []) {
        $log = [
            'timestamp' => date('Y-m-d H:i:s'),
            'tipo' => $tipo,
            'usuario_id' => $_SESSION['user_id'] ?? 'sistema',
            'mensagem' => $mensagem,
            'dados' => $dados,
            'ip' => $_SERVER['REMOTE_ADDR']
        ];
        
        $filename = $this->logPath . date('Y-m-d') . '.log';
        $logStr = json_encode($log) . "\n";
        
        file_put_contents($filename, $logStr, FILE_APPEND);
    }
    
    public function lerLogs($data = null) {
        $data = $data ?? date('Y-m-d');
        $filename = $this->logPath . $data . '.log';
        
        if (!file_exists($filename)) {
            return [];
        }
        
        $logs = array_map(function($line) {
            return json_decode($line, true);
        }, file($filename));
        
        return array_filter($logs);
    }
}
?>
