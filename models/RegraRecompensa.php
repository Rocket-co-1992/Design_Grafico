<?php
class RegraRecompensa {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function verificarElegibilidade($cliente_id, $recompensa_id) {
        $regras = $this->getRegras($recompensa_id);
        $cliente = $this->getClienteInfo($cliente_id);
        
        foreach ($regras as $regra) {
            if (!$this->validarRegra($regra, $cliente)) {
                return [
                    'elegivel' => false,
                    'motivo' => $this->getMotivoRejeicao($regra)
                ];
            }
        }
        
        return ['elegivel' => true];
    }
    
    private function validarRegra($regra, $cliente) {
        switch ($regra['tipo']) {
            case 'minimo_compras':
                return $cliente['total_compras'] >= $regra['valor'];
            
            case 'dias_membro':
                $dias = (time() - strtotime($cliente['created_at'])) / (60 * 60 * 24);
                return $dias >= $regra['valor'];
                
            case 'nivel_minimo':
                return $this->compararNiveis($cliente['nivel'], $regra['valor']);
        }
        return false;
    }
    
    private function getMotivoRejeicao($regra) {
        $mensagens = [
            'minimo_compras' => "Necessário mínimo de R$ {$regra['valor']} em compras",
            'dias_membro' => "Necessário ser membro por {$regra['valor']} dias",
            'nivel_minimo' => "Necessário ter nível {$regra['valor']} ou superior"
        ];
        return $mensagens[$regra['tipo']] ?? 'Requisitos não atendidos';
    }
}
