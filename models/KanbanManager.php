<?php
class KanbanManager {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function criarCartaoPedido($pedido_id) {
        try {
            $this->db->beginTransaction();
            
            // Busca informações do pedido
            $pedido = $this->getPedidoInfo($pedido_id);
            
            // Cria cartão na primeira coluna
            $sql = "INSERT INTO kanban_cartoes (
                pedido_id, coluna_id, titulo, descricao, prioridade, posicao
            ) VALUES (?, 
                (SELECT id FROM kanban_colunas ORDER BY ordem LIMIT 1),
                ?, ?, 'media',
                (SELECT COALESCE(MAX(posicao), 0) + 1 FROM kanban_cartoes)
            )";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $pedido_id,
                "Pedido #{$pedido_id} - {$pedido['cliente_nome']}",
                "Novo pedido: {$pedido['descricao']}\nValor: R$ {$pedido['valor_total']}"
            ]);
            
            $cartao_id = $this->db->lastInsertId();
            
            // Adiciona checklist padrão
            $this->criarChecklistPadrao($cartao_id);
            
            $this->db->commit();
            return $cartao_id;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    private function criarChecklistPadrao($cartao_id) {
        $itens = [
            'Verificar arquivos recebidos',
            'Validar especificações',
            'Confirmar prazo de entrega',
            'Enviar para produção',
            'Controle de qualidade',
            'Preparar entrega'
        ];
        
        $sql = "INSERT INTO kanban_checklist (cartao_id, item, ordem) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        
        foreach ($itens as $ordem => $item) {
            $stmt->execute([$cartao_id, $item, $ordem + 1]);
        }
    }
    
    public function moverCartao($cartao_id, $coluna_id, $posicao) {
        $sql = "UPDATE kanban_cartoes 
                SET coluna_id = ?, posicao = ?, 
                    updated_at = CURRENT_TIMESTAMP 
                WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$coluna_id, $posicao, $cartao_id]);
    }
    
    public function getQuadro() {
        $sql = "SELECT 
                c.*, 
                u.nome as responsavel_nome,
                p.status as pedido_status,
                GROUP_CONCAT(e.nome) as etiquetas
                FROM kanban_cartoes c
                LEFT JOIN usuarios u ON c.responsavel_id = u.id
                LEFT JOIN pedidos p ON c.pedido_id = p.id
                LEFT JOIN kanban_cartao_etiquetas ce ON c.id = ce.cartao_id
                LEFT JOIN kanban_etiquetas e ON ce.etiqueta_id = e.id
                GROUP BY c.id
                ORDER BY c.coluna_id, c.posicao";
                
        return $this->db->query($sql)->fetchAll();
    }
    
    public function getCartaoDetalhes($cartao_id) {
        // Busca detalhes do cartão
        $sql = "SELECT c.*, 
                u.nome as responsavel_nome,
                p.status as pedido_status,
                p.valor_total,
                cl.nome as cliente_nome
                FROM kanban_cartoes c
                LEFT JOIN usuarios u ON c.responsavel_id = u.id
                LEFT JOIN pedidos p ON c.pedido_id = p.id
                LEFT JOIN clientes cl ON p.cliente_id = cl.id
                WHERE c.id = ?";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$cartao_id]);
        $cartao = $stmt->fetch();
        
        if ($cartao) {
            // Adiciona checklist
            $cartao['checklist'] = $this->getChecklist($cartao_id);
            // Adiciona comentários
            $cartao['comentarios'] = $this->getComentarios($cartao_id);
        }
        
        return $cartao;
    }
}
?>
