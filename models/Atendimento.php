<?php
class Atendimento {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function criarChamado($dados) {
        $sql = "INSERT INTO chamados (cliente_id, assunto, descricao, prioridade, status) 
                VALUES (?, ?, ?, ?, 'aberto')";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $dados['cliente_id'],
            $dados['assunto'],
            $dados['descricao'],
            $dados['prioridade']
        ]);
    }
    
    public function listarChamados($filtros = []) {
        $where = "WHERE 1=1";
        $params = [];
        
        if (!empty($filtros['status'])) {
            $where .= " AND status = ?";
            $params[] = $filtros['status'];
        }
        
        if (!empty($filtros['cliente_id'])) {
            $where .= " AND cliente_id = ?";
            $params[] = $filtros['cliente_id'];
        }
        
        $sql = "SELECT c.*, cli.nome as cliente_nome 
                FROM chamados c
                JOIN clientes cli ON c.cliente_id = cli.id
                $where
                ORDER BY c.created_at DESC";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function responderChamado($chamado_id, $resposta, $usuario_id) {
        try {
            $this->db->beginTransaction();
            
            $sql = "INSERT INTO chamado_respostas (chamado_id, usuario_id, resposta) 
                    VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$chamado_id, $usuario_id, $resposta]);
            
            $sql = "UPDATE chamados SET status = 'respondido' WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$chamado_id]);
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    public function fecharChamado($chamado_id) {
        $sql = "UPDATE chamados SET status = 'fechado' WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$chamado_id]);
    }
    
    public function getRespostas($chamado_id) {
        $sql = "SELECT r.*, u.nome as usuario_nome 
                FROM chamado_respostas r
                LEFT JOIN usuarios u ON r.usuario_id = u.id
                WHERE r.chamado_id = ?
                ORDER BY r.created_at";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$chamado_id]);
        return $stmt->fetchAll();
    }
    
    public function getChamado($id) {
        $sql = "SELECT c.*, cli.nome as cliente_nome 
                FROM chamados c
                JOIN clientes cli ON c.cliente_id = cli.id
                WHERE c.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
?>
