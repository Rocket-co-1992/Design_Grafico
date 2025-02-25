<?php
class MetasQualidade {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function criarMeta($dados) {
        $sql = "INSERT INTO metas_qualidade 
                (indicador, meta, periodo, data_inicio, data_fim, observacoes) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $dados['indicador'],
            $dados['meta'],
            $dados['periodo'],
            $dados['data_inicio'],
            $dados['data_fim'],
            $dados['observacoes']
        ]);
    }
    
    public function avaliarDesempenho() {
        $metas = $this->getMetasAtivas();
        $resultados = [];
        
        foreach ($metas as $meta) {
            $valorReal = $this->getValorIndicador($meta['indicador'], $meta['data_inicio'], $meta['data_fim']);
            $atingimento = ($valorReal / $meta['meta']) * 100;
            
            $resultados[] = [
                'indicador' => $meta['indicador'],
                'meta' => $meta['meta'],
                'realizado' => $valorReal,
                'atingimento' => $atingimento,
                'status' => $this->getStatusAtingimento($atingimento)
            ];
        }
        
        return $resultados;
    }
    
    private function getMetasAtivas() {
        $sql = "SELECT * FROM metas_qualidade 
                WHERE data_fim >= CURRENT_DATE 
                ORDER BY data_fim";
        return $this->db->query($sql)->fetchAll();
    }
    
    private function getValorIndicador($indicador, $dataInicio, $dataFim) {
        switch ($indicador) {
            case 'taxa_aprovacao':
                return $this->calcularTaxaAprovacao($dataInicio, $dataFim);
            case 'tempo_medio_inspecao':
                return $this->calcularTempoMedioInspecao($dataInicio, $dataFim);
            // Adicionar outros indicadores conforme necessário
            default:
                return 0;
        }
    }
    
    private function getStatusAtingimento($percentual) {
        if ($percentual >= 100) return 'atingida';
        if ($percentual >= 80) return 'parcial';
        return 'nao_atingida';
    }
}
?>
