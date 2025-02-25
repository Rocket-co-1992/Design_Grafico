<?php
class Checkout {
    private $db;
    private $carrinho;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->carrinho = new Carrinho();
    }
    
    public function finalizarPedido($dados) {
        try {
            $this->db->beginTransaction();
            
            // Criar pedido
            $pedido_id = $this->criarPedido($dados['cliente_id']);
            
            // Salvar itens do pedido
            $this->salvarItensPedido($pedido_id);
            
            // Salvar endereço de entrega
            $this->salvarEnderecoEntrega($pedido_id, $dados['endereco']);
            
            // Processar pagamento
            $pagamento = $this->processarPagamento($pedido_id, $dados['pagamento']);
            
            if ($pagamento['success']) {
                $this->db->commit();
                $this->carrinho->limpar();
                return [
                    'success' => true,
                    'pedido_id' => $pedido_id,
                    'pagamento' => $pagamento
                ];
            } else {
                $this->db->rollBack();
                return [
                    'success' => false,
                    'error' => $pagamento['error']
                ];
            }
            
        } catch (Exception $e) {
            $this->db->rollBack();
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    private function criarPedido($cliente_id) {
        $sql = "INSERT INTO pedidos (cliente_id, status, valor_total) VALUES (?, 'aguardando_pagamento', ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$cliente_id, $this->carrinho->getTotal()]);
        return $this->db->lastInsertId();
    }
    
    private function salvarItensPedido($pedido_id) {
        $sql = "INSERT INTO pedido_itens (pedido_id, produto_id, quantidade, valor_unitario) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        
        foreach ($this->carrinho->getItens() as $item) {
            $stmt->execute([
                $pedido_id,
                $item['produto_id'],
                $item['quantidade'],
                $item['preco_unitario']
            ]);
            
            // Se tiver design personalizado, salvar
            if (!empty($item['design'])) {
                $this->salvarDesignPedido($pedido_id, $item['produto_id'], $item['design']);
            }
        }
    }
    
    private function processarPagamento($pedido_id, $dados_pagamento) {
        // Integração com gateway de pagamento
        $gateway = new PaymentGateway();
        
        $pagamento = $gateway->processar([
            'valor' => $this->carrinho->getTotal(),
            'tipo' => $dados_pagamento['tipo'],
            'parcelas' => $dados_pagamento['parcelas'] ?? 1,
            'cartao' => $dados_pagamento['cartao'] ?? null
        ]);
        
        if ($pagamento['success']) {
            $sql = "INSERT INTO pagamentos (pedido_id, valor, status, gateway, gateway_reference, tipo_pagamento, parcelas) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $pedido_id,
                $this->carrinho->getTotal(),
                'aprovado',
                'gateway_nome',
                $pagamento['reference'],
                $dados_pagamento['tipo'],
                $dados_pagamento['parcelas'] ?? 1
            ]);
        }
        
        return $pagamento;
    }
    
    private function salvarEnderecoEntrega($pedido_id, $endereco) {
        $sql = "INSERT INTO endereco_entrega 
                (pedido_id, cep, logradouro, numero, complemento, bairro, cidade, estado) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $pedido_id,
            $endereco['cep'],
            $endereco['logradouro'],
            $endereco['numero'],
            $endereco['complemento'],
            $endereco['bairro'],
            $endereco['cidade'],
            $endereco['estado']
        ]);
    }
}
?>
