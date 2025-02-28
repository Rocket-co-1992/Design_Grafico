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

    public function calcularPreco($dados) {
        // Validar dados mínimos
        $this->validarDados($dados);
        
        // Cálculo base (material + área)
        $preco_base = $this->calcularPrecoBase($dados);
        
        // Adicionar acabamentos
        $preco_acabamentos = $this->calcularAcabamentos($dados['acabamentos'] ?? []);
        
        // Aplicar desconto por quantidade
        $desconto = $this->calcularDesconto($dados['quantidade']);
        
        // Cálculo final
        $preco_final = ($preco_base + $preco_acabamentos) * $dados['quantidade'];
        $preco_final = $preco_final * (1 - $desconto);
        
        // Calcular prazo
        $prazo = $this->calcularPrazo($dados);
        
        return [
            'preco' => $preco_final,
            'prazo' => $prazo,
            'detalhamento' => [
                'preco_base' => $preco_base,
                'acabamentos' => $preco_acabamentos,
                'desconto' => $desconto
            ]
        ];
    }
    
    private function validarDados($dados) {
        $campos_obrigatorios = ['tipo', 'material_id', 'largura', 'altura', 'quantidade'];
        foreach ($campos_obrigatorios as $campo) {
            if (empty($dados[$campo])) {
                throw new Exception("Campo {$campo} é obrigatório");
            }
        }
        
        if ($dados['quantidade'] < 1) {
            throw new Exception("Quantidade mínima inválida");
        }
    }
    
    private function calcularPrecoBase($dados) {
        // Buscar preço do material
        $sql = "SELECT preco_m2 FROM materiais_impressao WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$dados['material_id']]);
        $material = $stmt->fetch();
        
        if (!$material) {
            throw new Exception("Material não encontrado");
        }
        
        // Calcular área em m²
        $area = ($dados['largura'] * $dados['altura']) / 1000000;
        
        return $area * $material['preco_m2'];
    }
    
    private function calcularAcabamentos($acabamentos) {
        if (empty($acabamentos)) {
            return 0;
        }
        
        $sql = "SELECT preco_base FROM acabamentos WHERE id IN (" . 
               str_repeat('?,', count($acabamentos)-1) . '?)';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($acabamentos);
        
        $total = 0;
        while ($row = $stmt->fetch()) {
            $total += $row['preco_base'];
        }
        
        return $total;
    }
    
    private function calcularDesconto($quantidade) {
        // Tabela progressiva de descontos
        $tabela_descontos = [
            1000 => 0.05,
            5000 => 0.10,
            10000 => 0.15,
            50000 => 0.20
        ];
        
        $desconto = 0;
        foreach ($tabela_descontos as $qtd => $desc) {
            if ($quantidade >= $qtd) {
                $desconto = $desc;
            }
        }
        
        return $desconto;
    }
    
    private function calcularPrazo($dados) {
        // Prazo base por tipo de trabalho
        $prazos_base = [
            'flyer' => 2,
            'cartao' => 3,
            'banner' => 1,
            'outros' => 5
        ];
        
        $prazo = $prazos_base[$dados['tipo']] ?? 5;
        
        // Adicionar prazo por acabamentos
        if (!empty($dados['acabamentos'])) {
            $prazo += count($dados['acabamentos']);
        }
        
        // Adicionar prazo por quantidade
        if ($dados['quantidade'] > 5000) {
            $prazo += 2;
        } elseif ($dados['quantidade'] > 1000) {
            $prazo += 1;
        }
        
        return $prazo;
    }
}
?>
