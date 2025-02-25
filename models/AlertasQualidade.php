<?php
class AlertasQualidade {
    private $db;
    private $notificacao;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->notificacao = new NotificacaoRealtime();
    }
    
    public function verificarAlertas() {
        $alertas = array_merge(
            $this->verificarDesviosQualidade(),
            $this->verificarMetasEmRisco(),
            $this->verificarFalhasRecorrentes(),
            $this->verificarCustosExcessivos()
        );
        
        foreach ($alertas as $alerta) {
            $this->notificarResponsaveis($alerta);
        }
        
        return $alertas;
    }
    
    private function verificarDesviosQualidade() {
        $sql = "SELECT 
                op.id as ordem_id,
                COUNT(CASE WHEN ri.conforme = 0 THEN 1 END) as falhas,
                p.nome as produto,
                c.nome as cliente
                FROM ordem_producao op
                JOIN inspecoes_qualidade iq ON op.id = iq.ordem_producao_id
                JOIN resultados_inspecao ri ON iq.id = ri.inspecao_id
                JOIN pedidos pe ON op.pedido_id = pe.id
                JOIN produtos p ON pe.produto_id = p.id
                JOIN clientes c ON pe.cliente_id = c.id
                WHERE op.status != 'concluido'
                GROUP BY op.id
                HAVING falhas > 3";
        
        return $this->db->query($sql)->fetchAll();
    }
    
    private function verificarMetasEmRisco() {
        $metas = new MetasQualidade();
        $resultados = $metas->avaliarDesempenho();
        
        return array_filter($resultados, function($meta) {
            return $meta['atingimento'] < 80;
        });
    }
    
    private function verificarFalhasRecorrentes() {
        $sql = "SELECT 
                tipo_falha,
                COUNT(*) as ocorrencias,
                GROUP_CONCAT(DISTINCT ordem_producao_id) as ordens
                FROM analise_falhas af
                JOIN custos_qualidade cq ON af.custo_id = cq.id
                WHERE af.data_conclusao IS NULL
                AND cq.data_ocorrencia >= DATE_SUB(CURRENT_DATE, INTERVAL 30 DAY)
                GROUP BY tipo_falha
                HAVING ocorrencias >= 3";
        
        return $this->db->query($sql)->fetchAll();
    }
    
    private function notificarResponsaveis($alerta) {
        // Identifica responsáveis baseado no tipo de alerta
        $responsaveis = $this->getResponsaveisAlerta($alerta);
        
        foreach ($responsaveis as $responsavel) {
            $this->notificacao->enviar(
                $responsavel['id'],
                'alerta_qualidade',
                'Alerta de Qualidade: ' . $alerta['tipo'],
                $this->formatarMensagemAlerta($alerta)
            );
            
            // Se for crítico, envia email também
            if ($this->isAlertaCritico($alerta)) {
                $email = new EmailService();
                $email->enviar(
                    $responsavel['email'],
                    'Alerta Crítico de Qualidade',
                    'alertas/qualidade_critico',
                    ['alerta' => $alerta]
                );
            }
        }
    }
    
    private function isAlertaCritico($alerta) {
        // Define critérios para alertas críticos
        if (isset($alerta['falhas']) && $alerta['falhas'] > 5) return true;
        if (isset($alerta['atingimento']) && $alerta['atingimento'] < 50) return true;
        if (isset($alerta['ocorrencias']) && $alerta['ocorrencias'] > 5) return true;
        return false;
    }
    
    public function getAlertas($periodo = 7) {
        $sql = "SELECT * FROM alertas_qualidade 
                WHERE data_geracao >= DATE_SUB(CURRENT_DATE, INTERVAL ? DAY)
                ORDER BY criticidade DESC, data_geracao DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$periodo]);
        return $stmt->fetchAll();
    }
}
?>
