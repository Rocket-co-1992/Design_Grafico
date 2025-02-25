<?php
class OrcamentoGrafico {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function calcularOrcamento($dados) {
        $custoMaterial = $this->calcularCustoMaterial($dados);
        $custoImpressao = $this->calcularCustoImpressao($dados);
        $custoAcabamento = $this->calcularCustoAcabamento($dados['acabamentos']);
        
        $custoTotal = $custoMaterial + $custoImpressao + $custoAcabamento;
        $margemLucro = $this->getMargemLucro($dados['quantidade']);
        
        return [
            'custo_material' => $custoMaterial,
            'custo_impressao' => $custoImpressao,
            'custo_acabamento' => $custoAcabamento,
            'custo_total' => $custoTotal,
            'preco_venda' => $custoTotal * (1 + $margemLucro),
            'detalhamento' => [
                'material' => $this->getMaterialDetalhes($dados['material_id']),
                'acabamentos' => $this->getAcabamentosDetalhes($dados['acabamentos']),
                'tempo_producao' => $this->calcularTempoProducao($dados)
            ]
        ];
    }
    
    private function calcularCustoMaterial($dados) {
        $material = $this->getMaterialDetalhes($dados['material_id']);
        $areaTotal = $this->calcularAreaTotal($dados);
        $desperdicio = $this->calcularDesperdicio($dados['quantidade']);
        
        return ($areaTotal * (1 + $desperdicio)) * $material['preco_m2'];
    }
    
    private function calcularCustoImpressao($dados) {
        $equipamento = $this->getEquipamentoImpressao($dados['tipo_impressao']);
        $tempoImpressao = $this->calcularTempoImpressao($dados);
        
        return ($equipamento['custo_hora'] * $tempoImpressao) + $equipamento['custo_setup'];
    }
    
    private function calcularCustoAcabamento($acabamentos) {
        $custoTotal = 0;
        foreach ($acabamentos as $acabamento_id) {
            $acabamento = $this->getAcabamentoDetalhes($acabamento_id);
            $custoTotal += $acabamento['preco_base'] + ($acabamento['preco_unitario'] * $quantidade);
        }
        return $custoTotal;
    }
    
    private function calcularTempoProducao($dados) {
        $tempoImpressao = $this->calcularTempoImpressao($dados);
        $tempoAcabamento = $this->calcularTempoAcabamento($dados['acabamentos']);
        $tempoSetup = $this->getTempoSetup($dados['tipo_impressao']);
        
        return $tempoImpressao + $tempoAcabamento + $tempoSetup;
    }
    
    private function getMargemLucro($quantidade) {
        // Margem dinâmica baseada na quantidade
        if ($quantidade < 100) return 0.8; // 80%
        if ($quantidade < 500) return 0.6; // 60%
        if ($quantidade < 1000) return 0.5; // 50%
        return 0.4; // 40%
    }
    
    public function getMaterialDetalhes($material_id) {
        $sql = "SELECT m.*, f.largura, f.altura 
                FROM materiais_impressao m
                JOIN formatos_impressao f ON m.formato_id = f.id
                WHERE m.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$material_id]);
        return $stmt->fetch();
    }
    
    public function getAcabamentosDisponiveis() {
        return $this->db->query("SELECT * FROM acabamentos WHERE disponivel = TRUE")->fetchAll();
    }
    
    public function salvarOrcamento($dados) {
        try {
            $this->db->beginTransaction();
            
            // Salva o orçamento
            $sql = "INSERT INTO orcamentos (cliente_id, valor_total, validade) VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $dados['cliente_id'],
                $dados['valor_total'],
                date('Y-m-d', strtotime('+7 days'))
            ]);
            
            $orcamento_id = $this->db->lastInsertId();
            
            // Salva os detalhes
            $this->salvarDetalhesOrcamento($orcamento_id, $dados);
            
            $this->db->commit();
            return $orcamento_id;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
}
?>
