<?php
class CampanhaAnalytics {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getResultadosCampanha($campanha_id) {
        return [
            'metricas_gerais' => $this->getMetricasGerais($campanha_id),
            'conversoes' => $this->getConversoes($campanha_id),
            'engajamento' => $this->getEngajamento($campanha_id),
            'roi' => $this->calcularROI($campanha_id)
        ];
    }
    
    private function getMetricasGerais($campanha_id) {
        $sql = "SELECT 
                COUNT(*) as total_envios,
                SUM(CASE WHEN status = 'entregue' THEN 1 ELSE 0 END) as entregas,
                SUM(CASE WHEN status = 'aberto' THEN 1 ELSE 0 END) as aberturas,
                SUM(CASE WHEN status = 'clicado' THEN 1 ELSE 0 END) as cliques
                FROM campanhas_enviadas 
                WHERE campanha_id = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$campanha_id]);
        return $stmt->fetch();
    }
    
    private function getConversoes($campanha_id) {
        $sql = "SELECT COUNT(p.id) as total_pedidos, SUM(p.valor_total) as valor_total
                FROM pedidos p
                JOIN campanhas_enviadas ce ON p.cliente_id = ce.cliente_id
                WHERE ce.campanha_id = ?
                AND p.created_at BETWEEN ce.data_envio AND DATE_ADD(ce.data_envio, INTERVAL 7 DAY)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$campanha_id]);
        return $stmt->fetch();
    }
    
    private function getEngajamento($campanha_id) {
        $sql = "SELECT 
                cliente_id,
                MAX(CASE WHEN status = 'aberto' THEN data_envio END) as data_abertura,
                MAX(CASE WHEN status = 'clicado' THEN data_envio END) as data_clique
                FROM campanhas_enviadas
                WHERE campanha_id = ?
                GROUP BY cliente_id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$campanha_id]);
        return $stmt->fetchAll();
    }
    
    private function calcularROI($campanha_id) {
        $sql = "SELECT c.custo, SUM(p.valor_total) as receita
                FROM campanhas c
                LEFT JOIN campanhas_enviadas ce ON c.id = ce.campanha_id
                LEFT JOIN pedidos p ON ce.cliente_id = p.cliente_id
                WHERE c.id = ?
                AND p.created_at BETWEEN ce.data_envio AND DATE_ADD(ce.data_envio, INTERVAL 7 DAY)
                GROUP BY c.id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$campanha_id]);
        $resultado = $stmt->fetch();
        
        if ($resultado && $resultado['custo'] > 0) {
            return [
                'roi' => (($resultado['receita'] - $resultado['custo']) / $resultado['custo']) * 100,
                'custo' => $resultado['custo'],
                'receita' => $resultado['receita']
            ];
        }
        
        return ['roi' => 0, 'custo' => 0, 'receita' => 0];
    }
}
?>
