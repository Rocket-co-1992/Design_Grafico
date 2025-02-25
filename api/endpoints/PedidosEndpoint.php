<?php
class PedidosEndpoint {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function get($params) {
        if (empty($params)) {
            // Listar todos os pedidos
            $sql = "SELECT p.*, c.nome as cliente_nome 
                    FROM pedidos p 
                    JOIN clientes c ON p.cliente_id = c.id 
                    ORDER BY p.created_at DESC";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll();
        } else {
            // Buscar pedido específico
            $sql = "SELECT p.*, c.nome as cliente_nome 
                    FROM pedidos p 
                    JOIN clientes c ON p.cliente_id = c.id 
                    WHERE p.id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$params[0]]);
            return $stmt->fetch();
        }
    }
    
    public function post($dados) {
        try {
            $this->db->beginTransaction();
            
            // Inserir pedido
            $sql = "INSERT INTO pedidos (cliente_id, status, valor_total) 
                    VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $dados['cliente_id'],
                $dados['status'],
                $dados['valor_total']
            ]);
            
            $pedidoId = $this->db->lastInsertId();
            
            // Inserir itens do pedido
            foreach ($dados['itens'] as $item) {
                $sql = "INSERT INTO pedido_itens (pedido_id, produto_id, quantidade, valor_unitario) 
                        VALUES (?, ?, ?, ?)";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([
                    $pedidoId,
                    $item['produto_id'],
                    $item['quantidade'],
                    $item['valor_unitario']
                ]);
            }
            
            $this->db->commit();
            return ['id' => $pedidoId, 'mensagem' => 'Pedido criado com sucesso'];
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
?>
