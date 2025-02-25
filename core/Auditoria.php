<?php
class Auditoria {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function registrar($acao, $tabela, $registro_id, $dados_anteriores = null, $dados_novos = null) {
        $sql = "INSERT INTO auditoria (usuario_id, acao, tabela, registro_id, dados_anteriores, dados_novos, ip) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
                
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $_SESSION['user_id'] ?? null,
            $acao,
            $tabela,
            $registro_id,
            json_encode($dados_anteriores),
            json_encode($dados_novos),
            $_SERVER['REMOTE_ADDR']
        ]);
    }
    
    public function listar($filtros = []) {
        $sql = "SELECT a.*, u.nome as usuario_nome 
                FROM auditoria a 
                LEFT JOIN usuarios u ON a.usuario_id = u.id 
                WHERE 1=1";
        $params = [];
        
        if (!empty($filtros['data_inicio'])) {
            $sql .= " AND DATE(a.created_at) >= ?";
            $params[] = $filtros['data_inicio'];
        }
        
        if (!empty($filtros['data_fim'])) {
            $sql .= " AND DATE(a.created_at) <= ?";
            $params[] = $filtros['data_fim'];
        }
        
        $sql .= " ORDER BY a.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
?>
