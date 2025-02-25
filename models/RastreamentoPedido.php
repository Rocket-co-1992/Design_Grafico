<?php
class RastreamentoPedido {
    private $db;
    private $notificacao;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->notificacao = new NotificacaoRealtime();
    }
    
    public function atualizarStatus($pedido_id, $status, $descricao) {
        try {
            $this->db->beginTransaction();
            
            // Atualiza status do pedido
            $sql = "UPDATE pedidos SET status = ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$status, $pedido_id]);
            
            // Registra no rastreamento
            $sql = "INSERT INTO rastreamento_pedido (pedido_id, status, descricao) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$pedido_id, $status, $descricao]);
            
            // Notifica o cliente
            $this->notificarCliente($pedido_id, $status, $descricao);
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    public function getHistorico($pedido_id) {
        $sql = "SELECT * FROM rastreamento_pedido 
                WHERE pedido_id = ? 
                ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$pedido_id]);
        return $stmt->fetchAll();
    }
    
    private function notificarCliente($pedido_id, $status, $descricao) {
        $sql = "SELECT c.id, c.email, c.nome, p.id as pedido_numero 
                FROM pedidos p 
                JOIN clientes c ON p.cliente_id = c.id 
                WHERE p.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$pedido_id]);
        $cliente = $stmt->fetch();
        
        if ($cliente) {
            // Envia notificação em tempo real
            $this->notificacao->enviar(
                $cliente['id'],
                'pedido_status',
                "Atualização do Pedido #{$cliente['pedido_numero']}",
                "Seu pedido está: {$status}"
            );
            
            // Envia email
            $email = new EmailService();
            $email->enviar(
                $cliente['email'],
                "Atualização do Pedido #{$cliente['pedido_numero']}",
                "status_pedido",
                [
                    'nome' => $cliente['nome'],
                    'pedido' => $cliente['pedido_numero'],
                    'status' => $status,
                    'descricao' => $descricao
                ]
            );
        }
    }
}
?>
