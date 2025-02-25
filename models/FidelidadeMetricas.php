<?php
class FidelidadeMetricas {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function calcularROI($periodo = 30) {
        // Custo com recompensas resgatadas
        $sql = "SELECT SUM(r.valor) as custo_total
                FROM resgates res
                JOIN recompensas r ON res.recompensa_id = r.id
                WHERE res.data_resgate >= DATE_SUB(CURRENT_DATE, INTERVAL ? DAY)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$periodo]);
        $custos = $stmt->fetch()['custo_total'] ?? 0;
        
        // Receita de clientes fidelizados
        $sql = "SELECT SUM(p.valor_total) as receita
                FROM pedidos p
                JOIN programa_fidelidade pf ON p.cliente_id = pf.cliente_id
                WHERE p.created_at >= DATE_SUB(CURRENT_DATE, INTERVAL ? DAY)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$periodo]);
        $receita = $stmt->fetch()['receita'] ?? 0;
        
        // Cálculo do ROI
        $roi = $custos > 0 ? (($receita - $custos) / $custos) * 100 : 0;
        
        return [
            'custos' => $custos,
            'receita' => $receita,
            'roi' => $roi
        ];
    }
    
    public function getMetricasRetencao() {
        $sql = "SELECT 
                COUNT(DISTINCT CASE WHEN pedidos > 1 THEN cliente_id END) as clientes_retorno,
                COUNT(DISTINCT cliente_id) as total_clientes,
                AVG(pedidos) as media_pedidos,
                AVG(valor_total) as ticket_medio
                FROM (
                    SELECT 
                        c.id as cliente_id,
                        COUNT(p.id) as pedidos,
                        SUM(p.valor_total) as valor_total
                    FROM clientes c
                    LEFT JOIN pedidos p ON c.id = p.cliente_id
                    GROUP BY c.id
                ) as metricas";
        
        return $this->db->query($sql)->fetch();
    }
    
    public function getAnaliseEngajamento() {
        $sql = "SELECT 
                nivel,
                COUNT(*) as total_clientes,
                AVG(dias_ultimo_resgate) as media_dias_resgate,
                AVG(pontos) as media_pontos
                FROM (
                    SELECT 
                        pf.cliente_id,
                        pf.nivel,
                        pf.pontos,
                        DATEDIFF(CURRENT_DATE, MAX(r.data_resgate)) as dias_ultimo_resgate
                    FROM programa_fidelidade pf
                    LEFT JOIN resgates r ON pf.cliente_id = r.cliente_id
                    GROUP BY pf.cliente_id
                ) as analise
                GROUP BY nivel
                ORDER BY 
                FIELD(nivel, 'bronze', 'prata', 'ouro', 'diamante')";
        
        return $this->db->query($sql)->fetchAll();
    }
    
    public function identificarClientesRisco() {
        $sql = "SELECT 
                c.id,
                c.nome,
                c.email,
                MAX(p.created_at) as ultima_compra,
                DATEDIFF(CURRENT_DATE, MAX(p.created_at)) as dias_inativo,
                pf.nivel,
                pf.pontos
                FROM clientes c
                JOIN programa_fidelidade pf ON c.id = pf.cliente_id
                LEFT JOIN pedidos p ON c.id = p.cliente_id
                GROUP BY c.id
                HAVING dias_inativo > 90
                ORDER BY dias_inativo DESC";
        
        return $this->db->query($sql)->fetchAll();
    }
}
?>
