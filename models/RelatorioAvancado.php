<?php
class RelatorioAvancado {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function kpis($dataInicio, $dataFim) {
        return [
            'vendas' => $this->kpiVendas($dataInicio, $dataFim),
            'producao' => $this->kpiProducao($dataInicio, $dataFim),
            'financeiro' => $this->kpiFinanceiro($dataInicio, $dataFim)
        ];
    }
    
    private function kpiVendas($dataInicio, $dataFim) {
        $sql = "SELECT 
                COUNT(*) as total_pedidos,
                SUM(valor_total) as valor_total,
                AVG(valor_total) as ticket_medio,
                (SELECT COUNT(DISTINCT cliente_id) 
                 FROM pedidos 
                 WHERE created_at BETWEEN ? AND ?) as clientes_ativos
                FROM pedidos 
                WHERE created_at BETWEEN ? AND ?";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$dataInicio, $dataFim, $dataInicio, $dataFim]);
        return $stmt->fetch();
    }
    
    private function kpiProducao($dataInicio, $dataFim) {
        $sql = "SELECT 
                COUNT(*) as total_ordens,
                AVG(TIMESTAMPDIFF(HOUR, data_inicio, data_fim)) as tempo_medio_producao,
                SUM(CASE WHEN status = 'atrasado' THEN 1 ELSE 0 END) as ordens_atrasadas
                FROM ordem_producao
                WHERE data_inicio BETWEEN ? AND ?";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$dataInicio, $dataFim]);
        return $stmt->fetch();
    }
    
    private function kpiFinanceiro($dataInicio, $dataFim) {
        $sql = "SELECT 
                SUM(CASE WHEN tipo = 'receita' THEN valor ELSE 0 END) as receita_total,
                SUM(CASE WHEN tipo = 'despesa' THEN valor ELSE 0 END) as despesa_total,
                COUNT(DISTINCT CASE WHEN tipo = 'receita' THEN data_movimento END) as dias_com_receita
                FROM fluxo_caixa
                WHERE data_movimento BETWEEN ? AND ?";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$dataInicio, $dataFim]);
        return $stmt->fetch();
    }
    
    public function previsaoFaturamento() {
        $sql = "SELECT 
                DATE_FORMAT(data_vencimento, '%Y-%m') as mes,
                SUM(valor) as valor_previsto
                FROM contas_receber
                WHERE status = 'pendente'
                GROUP BY DATE_FORMAT(data_vencimento, '%Y-%m')
                ORDER BY mes
                LIMIT 6";
                
        return $this->db->query($sql)->fetchAll();
    }
}
?>
