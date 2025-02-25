<?php
class PrazoEntrega {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function calcularPrazo($pedido_id) {
        $pedido = $this->getPedidoDetalhes($pedido_id);
        $cargaAtual = $this->getOcupacaoProducao();
        
        // Calcula tempo total de produção
        $tempoTotal = $this->calcularTempoProducao($pedido);
        
        // Verifica disponibilidade dos equipamentos
        $disponibilidade = $this->verificarDisponibilidadeEquipamentos($pedido);
        
        // Calcula data prevista considerando carga atual
        $dataPrevista = $this->calcularDataPrevista($tempoTotal, $cargaAtual);
        
        return [
            'tempo_producao' => $tempoTotal,
            'data_prevista' => $dataPrevista,
            'disponibilidade_equipamentos' => $disponibilidade,
            'detalhamento' => [
                'pre_impressao' => $this->calcularTempoPreparo($pedido),
                'impressao' => $this->calcularTempoImpressao($pedido),
                'acabamento' => $this->calcularTempoAcabamento($pedido)
            ]
        ];
    }
    
    private function calcularTempoProducao($pedido) {
        $tempoPreparo = $this->calcularTempoPreparo($pedido);
        $tempoImpressao = $this->calcularTempoImpressao($pedido);
        $tempoAcabamento = $this->calcularTempoAcabamento($pedido);
        
        return $tempoPreparo + $tempoImpressao + $tempoAcabamento;
    }
    
    private function getOcupacaoProducao() {
        $sql = "SELECT 
                DATE(ep.data_inicio) as data,
                SUM(COALESCE(ep.tempo_estimado, 0)) as tempo_ocupado
                FROM etapas_producao ep
                WHERE ep.status != 'concluido'
                AND ep.data_inicio >= CURRENT_DATE
                GROUP BY DATE(ep.data_inicio)
                ORDER BY data";
        
        return $this->db->query($sql)->fetchAll(PDO::FETCH_KEY_PAIR);
    }
    
    private function calcularDataPrevista($tempoTotal, $cargaAtual) {
        $dataAtual = new DateTime();
        $horasPorDia = 8; // Jornada de trabalho
        $diasNecessarios = ceil($tempoTotal / $horasPorDia);
        
        while ($diasNecessarios > 0) {
            $dataAtual->modify('+1 weekday');
            $dataStr = $dataAtual->format('Y-m-d');
            
            // Verifica carga do dia
            $cargaDia = $cargaAtual[$dataStr] ?? 0;
            if ($cargaDia < $horasPorDia) {
                $diasNecessarios--;
            }
        }
        
        return $dataAtual->format('Y-m-d');
    }
    
    private function verificarDisponibilidadeEquipamentos($pedido) {
        $sql = "SELECT e.*, 
                (SELECT COUNT(*) FROM etapas_producao ep 
                 WHERE ep.equipamento_id = e.id 
                 AND ep.status = 'em_andamento') as em_uso
                FROM equipamentos e
                WHERE e.tipo = ?";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$pedido['tipo_impressao']]);
        return $stmt->fetchAll();
    }
}
?>
