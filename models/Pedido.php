<?php
class Pedido {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function criar($dados) {
        if (empty($dados['cliente_id']) || empty($dados['itens'])) {
            throw new Exception('Dados do pedido incompletos');
        }
        
        try {
            $this->db->beginTransaction();
            
            // Validar cliente
            $cliente = $this->validarCliente($dados['cliente_id']);
            if (!$cliente) {
                throw new Exception('Cliente não encontrado');
            }
            
            // Validar itens
            foreach ($dados['itens'] as $item) {
                if (!$this->validarProduto($item['produto_id'])) {
                    throw new Exception('Produto inválido');
                }
            }
            
            $sql = "INSERT INTO pedidos (cliente_id, valor_total, status) VALUES (?, ?, 'aguardando')";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$dados['cliente_id'], $dados['valor_total']]);
            
            $pedido_id = $this->db->lastInsertId();
            
            foreach ($dados['itens'] as $item) {
                $sql = "INSERT INTO pedido_itens (pedido_id, produto_id, quantidade, valor_unitario)
                        VALUES (?, ?, ?, ?)";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([
                    $pedido_id,
                    $item['produto_id'],
                    $item['quantidade'],
                    $item['valor_unitario']
                ]);
            }
            
            $this->db->commit();
            return $pedido_id;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    public function atualizarStatus($pedido_id, $status) {
        $sql = "UPDATE pedidos SET status = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$status, $pedido_id]);
    }
    
    public function getDetalhes($pedido_id) {
        $sql = "SELECT p.*, c.nome as cliente_nome, c.email as cliente_email,
                COUNT(i.id) as total_itens, SUM(i.quantidade) as quantidade_total
                FROM pedidos p
                JOIN clientes c ON p.cliente_id = c.id
                JOIN pedido_itens i ON p.id = i.pedido_id
                WHERE p.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$pedido_id]);
        return $stmt->fetch();
    }
    
    public function contarPedidosPorPeriodo($dataInicio, $dataFim) {
        $sql = "SELECT COUNT(*) as total FROM pedidos 
                WHERE DATE(created_at) BETWEEN ? AND ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$dataInicio, $dataFim]);
        $resultado = $stmt->fetch();
        return $resultado['total'];
    }
    
    public function getEstatisticasPorPeriodo($dataInicio, $dataFim) {
        $sql = "SELECT 
                    DATE(created_at) as data,
                    COUNT(*) as total_pedidos,
                    SUM(valor_total) as valor_total
                FROM pedidos 
                WHERE DATE(created_at) BETWEEN ? AND ?
                GROUP BY DATE(created_at)
                ORDER BY data";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$dataInicio, $dataFim]);
        return $stmt->fetchAll();
    }
    
    private function validarCliente($cliente_id) {
        $sql = "SELECT id FROM clientes WHERE id = ? AND ativo = TRUE";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$cliente_id]);
        return $stmt->fetch();
    }
    
    private function validarProduto($produto_id) {
        $sql = "SELECT id FROM produtos WHERE id = ? AND ativo = TRUE";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$produto_id]);
        return $stmt->fetch();
    }
}
?>
