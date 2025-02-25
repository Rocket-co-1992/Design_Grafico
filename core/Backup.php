<?php
class Backup {
    private $db;
    private $backupPath;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->backupPath = dirname(__DIR__) . '/backups/';
        
        if (!file_exists($this->backupPath)) {
            mkdir($this->backupPath, 0777, true);
        }
    }
    
    public function gerarBackup() {
        $filename = $this->backupPath . 'backup_' . date('Y-m-d_H-i-s') . '.sql';
        
        $tables = $this->db->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
        $dump = '';
        
        foreach ($tables as $table) {
            $result = $this->db->query('SELECT * FROM ' . $table);
            $rows = $result->fetchAll(PDO::FETCH_ASSOC);
            
            $dump .= 'TRUNCATE TABLE ' . $table . ";\n";
            
            foreach ($rows as $row) {
                $dump .= $this->insertStatement($table, $row);
            }
        }
        
        file_put_contents($filename, $dump);
        return $filename;
    }
    
    private function insertStatement($table, $data) {
        $columns = implode(', ', array_keys($data));
        $values = implode("', '", array_map('addslashes', $data));
        return "INSERT INTO $table ($columns) VALUES ('$values');\n";
    }
    
    public function listarBackups() {
        $backups = glob($this->backupPath . '*.sql');
        return array_map(function($file) {
            return [
                'arquivo' => basename($file),
                'data' => date('d/m/Y H:i:s', filemtime($file)),
                'tamanho' => round(filesize($file) / 1024, 2) . ' KB'
            ];
        }, $backups);
    }
}
?>
