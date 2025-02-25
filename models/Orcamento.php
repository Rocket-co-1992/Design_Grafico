<?php
class Orcamento {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function criar($dados) {
        $this->db->beginTransaction();
        
        try {
            // Insere o cabeçalho do orçamento
            $sql = "INSERT INTO pedidos (cliente_id, status, valor_total) VALUES (?, 'orcamento', ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $dados['cliente_id'],
                $dados['valor_total']
            ]);
            
            $orcamento_id = $this->db->lastInsertId();
            
            // Insere os itens do orçamento
            foreach ($dados['itens'] as $item) {
                $sql = "INSERT INTO pedido_itens (pedido_id, produto_id, quantidade, valor_unitario) 
                        VALUES (?, ?, ?, ?)";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([
                    $orcamento_id,
                    $item['produto_id'],
                    $item['quantidade'],
                    $item['valor_unitario']
                ]);
            }
            
            $this->db->commit();
            return $orcamento_id;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    public function listar() {
        $sql = "SELECT p.*, c.nome as cliente_nome 
                FROM pedidos p 
                JOIN clientes c ON p.cliente_id = c.id 
                WHERE p.status = 'orcamento'
                ORDER BY p.created_at DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
}
?>
