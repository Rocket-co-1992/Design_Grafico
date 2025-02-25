<?php
class Cupom {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function validar($codigo, $valor_pedido) {
        $sql = "SELECT * FROM cupons 
                WHERE codigo = ? 
                AND (data_inicio IS NULL OR data_inicio <= CURDATE())
                AND (data_fim IS NULL OR data_fim >= CURDATE())
                AND (limite_usos IS NULL OR usos_realizados < limite_usos)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$codigo]);
        $cupom = $stmt->fetch();
        
        if (!$cupom) {
            return ['valido' => false, 'mensagem' => 'Cupom inválido ou expirado'];
        }
        
        if ($cupom['valor_minimo'] && $valor_pedido < $cupom['valor_minimo']) {
            return [
                'valido' => false, 
                'mensagem' => 'Valor mínimo do pedido não atingido'
            ];
        }
        
        return [
            'valido' => true,
            'tipo' => $cupom['tipo'],
            'valor' => $cupom['valor'],
            'cupom_id' => $cupom['id']
        ];
    }
    
    public function aplicarDesconto($cupom, $valor_pedido) {
        if ($cupom['tipo'] == 'percentual') {
            return $valor_pedido * ($cupom['valor'] / 100);
        }
        return min($cupom['valor'], $valor_pedido);
    }
    
    public function registrarUso($cupom_id) {
        $sql = "UPDATE cupons 
                SET usos_realizados = usos_realizados + 1 
                WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$cupom_id]);
    }
    
    public function criar($dados) {
        $sql = "INSERT INTO cupons (
                    codigo, tipo, valor, data_inicio, data_fim, 
                    limite_usos, valor_minimo
                ) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $dados['codigo'],
            $dados['tipo'],
            $dados['valor'],
            $dados['data_inicio'],
            $dados['data_fim'],
            $dados['limite_usos'],
            $dados['valor_minimo']
        ]);
    }
}
?>
