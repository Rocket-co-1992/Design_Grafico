<?php
class Financeiro {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function registrarContaReceber($dados) {
        $sql = "INSERT INTO contas_receber (pedido_id, valor, data_vencimento, status) 
                VALUES (?, ?, ?, 'pendente')";
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute([$dados['pedido_id'], $dados['valor'], $dados['vencimento']])) {
            $this->registrarFluxoCaixa([
                'tipo' => 'receita',
                'valor' => $dados['valor'],
                'data_movimento' => $dados['vencimento'],
                'descricao' => 'Conta a receber - Pedido #' . $dados['pedido_id'],
                'referencia_id' => $this->db->lastInsertId(),
                'referencia_tipo' => 'conta_receber'
            ]);
            return true;
        }
        return false;
    }
    
    public function registrarContaPagar($dados) {
        $sql = "INSERT INTO contas_pagar (descricao, valor, data_vencimento, categoria, fornecedor_id) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        if ($stmt->execute([
            $dados['descricao'],
            $dados['valor'],
            $dados['vencimento'],
            $dados['categoria'],
            $dados['fornecedor_id']
        ])) {
            $this->registrarFluxoCaixa([
                'tipo' => 'despesa',
                'valor' => $dados['valor'],
                'data_movimento' => $dados['vencimento'],
                'descricao' => 'Conta a pagar - ' . $dados['descricao'],
                'referencia_id' => $this->db->lastInsertId(),
                'referencia_tipo' => 'conta_pagar'
            ]);
            return true;
        }
        return false;
    }
    
    private function registrarFluxoCaixa($dados) {
        $sql = "INSERT INTO fluxo_caixa (tipo, valor, data_movimento, descricao, referencia_id, referencia_tipo) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $dados['tipo'],
            $dados['valor'],
            $dados['data_movimento'],
            $dados['descricao'],
            $dados['referencia_id'],
            $dados['referencia_tipo']
        ]);
    }
    
    public function getFluxoCaixa($dataInicio, $dataFim) {
        $sql = "SELECT * FROM fluxo_caixa 
                WHERE data_movimento BETWEEN ? AND ? 
                ORDER BY data_movimento";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$dataInicio, $dataFim]);
        return $stmt->fetchAll();
    }
}
?>
