<?php
class Relatorio {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function vendaPorPeriodo($dataInicio, $dataFim) {
        $sql = "SELECT 
                DATE(p.created_at) as data,
                COUNT(*) as total_pedidos,
                SUM(p.valor_total) as valor_total,
                c.nome as cliente_nome
                FROM pedidos p
                JOIN clientes c ON p.cliente_id = c.id
                WHERE DATE(p.created_at) BETWEEN ? AND ?
                GROUP BY DATE(p.created_at), c.nome
                ORDER BY data";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$dataInicio, $dataFim]);
        return $stmt->fetchAll();
    }
    
    public function produtosVendidos($dataInicio, $dataFim) {
        $sql = "SELECT 
                p.nome as produto,
                SUM(pi.quantidade) as quantidade_total,
                SUM(pi.quantidade * pi.valor_unitario) as valor_total
                FROM pedido_itens pi
                JOIN produtos p ON pi.produto_id = p.id
                JOIN pedidos ped ON pi.pedido_id = ped.id
                WHERE DATE(ped.created_at) BETWEEN ? AND ?
                GROUP BY p.id, p.nome
                ORDER BY quantidade_total DESC";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$dataInicio, $dataFim]);
        return $stmt->fetchAll();
    }
    
    public function clientesAtivos($dataInicio, $dataFim) {
        $sql = "SELECT 
                c.nome,
                c.email,
                COUNT(p.id) as total_pedidos,
                SUM(p.valor_total) as valor_total,
                MAX(p.created_at) as ultima_compra
                FROM clientes c
                LEFT JOIN pedidos p ON c.id = p.cliente_id
                WHERE p.created_at BETWEEN ? AND ?
                GROUP BY c.id, c.nome, c.email
                ORDER BY total_pedidos DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$dataInicio, $dataFim]);
        return $stmt->fetchAll();
    }
    
    public function produtividadeProducao($dataInicio, $dataFim) {
        $sql = "SELECT 
                u.nome as operador,
                COUNT(ep.id) as total_etapas,
                AVG(ep.tempo_real) as tempo_medio,
                SUM(CASE WHEN ep.status = 'finalizado' THEN 1 ELSE 0 END) as etapas_concluidas
                FROM etapas_producao ep
                JOIN usuarios u ON ep.responsavel_id = u.id
                WHERE ep.data_inicio BETWEEN ? AND ?
                GROUP BY u.id, u.nome
                ORDER BY etapas_concluidas DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$dataInicio, $dataFim]);
        return $stmt->fetchAll();
    }
    
    public function lucratividade($dataInicio, $dataFim) {
        $sql = "SELECT 
                DATE_FORMAT(p.created_at, '%Y-%m') as mes,
                SUM(p.valor_total) as receita,
                SUM(cp.valor) as despesas,
                SUM(p.valor_total) - SUM(cp.valor) as lucro
                FROM pedidos p
                LEFT JOIN contas_pagar cp ON DATE_FORMAT(p.created_at, '%Y-%m') = DATE_FORMAT(cp.data_vencimento, '%Y-%m')
                WHERE p.created_at BETWEEN ? AND ?
                GROUP BY DATE_FORMAT(p.created_at, '%Y-%m')
                ORDER BY mes";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$dataInicio, $dataFim]);
        return $stmt->fetchAll();
    }
    
    public function exportarCSV($dados, $nomeArquivo) {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename='.$nomeArquivo);
        
        $output = fopen('php://output', 'w');
        fputcsv($output, array_keys($dados[0]));
        
        foreach ($dados as $linha) {
            fputcsv($output, $linha);
        }
        
        fclose($output);
    }
}
