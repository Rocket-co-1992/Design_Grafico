<?php
class QualidadeEstatisticas {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getIndicadoresGerais($periodo = 30) {
        $dataInicio = date('Y-m-d', strtotime("-$periodo days"));
        
        return [
            'taxa_aprovacao' => $this->calcularTaxaAprovacao($dataInicio),
            'defeitos_frequentes' => $this->getDefeitosFrequentes($dataInicio),
            'desempenho_operadores' => $this->getDesempenhoOperadores($dataInicio),
            'tempo_medio_inspecao' => $this->calcularTempoMedioInspecao($dataInicio)
        ];
    }
    
    private function calcularTaxaAprovacao($dataInicio) {
        $sql = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'aprovado' THEN 1 ELSE 0 END) as aprovados
                FROM inspecoes_qualidade
                WHERE data_inspecao >= ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$dataInicio]);
        $resultado = $stmt->fetch();
        
        return [
            'taxa' => $resultado['total'] > 0 ? 
                     ($resultado['aprovados'] / $resultado['total']) * 100 : 0,
            'total' => $resultado['total'],
            'aprovados' => $resultado['aprovados']
        ];
    }
    
    private function getDefeitosFrequentes($dataInicio) {
        $sql = "SELECT 
                ic.descricao,
                COUNT(*) as ocorrencias,
                (COUNT(*) * 100.0 / (
                    SELECT COUNT(*) 
                    FROM resultados_inspecao ri2
                    JOIN inspecoes_qualidade iq2 ON ri2.inspecao_id = iq2.id
                    WHERE iq2.data_inspecao >= ?
                )) as percentual
                FROM resultados_inspecao ri
                JOIN inspecoes_qualidade iq ON ri.inspecao_id = iq.id
                JOIN itens_checklist ic ON ri.item_checklist_id = ic.id
                WHERE ri.conforme = 0
                AND iq.data_inspecao >= ?
                GROUP BY ic.id, ic.descricao
                ORDER BY ocorrencias DESC
                LIMIT 5";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$dataInicio, $dataInicio]);
        return $stmt->fetchAll();
    }
    
    private function getDesempenhoOperadores($dataInicio) {
        $sql = "SELECT 
                u.nome as operador,
                COUNT(DISTINCT iq.id) as total_inspecoes,
                AVG(CASE WHEN iq.status = 'aprovado' THEN 1 ELSE 0 END) * 100 as taxa_aprovacao
                FROM inspecoes_qualidade iq
                JOIN usuarios u ON iq.inspetor_id = u.id
                WHERE iq.data_inspecao >= ?
                GROUP BY u.id, u.nome
                ORDER BY taxa_aprovacao DESC";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$dataInicio]);
        return $stmt->fetchAll();
    }
    
    private function calcularTempoMedioInspecao($dataInicio) {
        $sql = "SELECT AVG(
                    TIMESTAMPDIFF(MINUTE, 
                    data_inspecao, 
                    (SELECT MIN(ri.created_at) 
                     FROM resultados_inspecao ri 
                     WHERE ri.inspecao_id = iq.id))
                ) as tempo_medio
                FROM inspecoes_qualidade iq
                WHERE data_inspecao >= ?";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$dataInicio]);
        return $stmt->fetch()['tempo_medio'];
    }
    
    public function gerarRelatorioQualidade($dataInicio, $dataFim) {
        return [
            'indicadores' => $this->getIndicadoresGerais(30),
            'analise_pareto' => $this->getAnalisePareto($dataInicio, $dataFim),
            'tendencias' => $this->getTendenciasQualidade($dataInicio, $dataFim),
            'custo_retrabalho' => $this->getCustoRetrabalho($dataInicio, $dataFim)
        ];
    }
}
?>
