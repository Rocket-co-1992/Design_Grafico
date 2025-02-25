<?php
class EscalonamentoAlertas {
    private $db;
    private $notificacao;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->notificacao = new NotificacaoRealtime();
    }
    
    public function processarEscalonamentos() {
        $alertas = $this->getAlertasPendentes();
        
        foreach ($alertas as $alerta) {
            $nivel = $this->determinarNivelEscalonamento($alerta);
            $responsaveis = $this->getResponsaveisNivel($nivel);
            
            foreach ($responsaveis as $responsavel) {
                $this->notificarResponsavel($responsavel, $alerta);
            }
            
            $this->atualizarStatusAlerta($alerta['id'], $nivel);
        }
    }
    
    private function determinarNivelEscalonamento($alerta) {
        $tempoDecorrido = time() - strtotime($alerta['data_geracao']);
        $horasDecorridas = $tempoDecorrido / 3600;
        
        if ($horasDecorridas > 24) return 'diretoria';
        if ($horasDecorridas > 8) return 'gerencia';
        if ($horasDecorridas > 4) return 'supervisor';
        return 'operador';
    }
    
    private function notificarResponsavel($responsavel, $alerta) {
        $mensagem = $this->gerarMensagemEscalonamento($alerta);
        
        // Notifica em tempo real
        $this->notificacao->enviar(
            $responsavel['id'],
            'escalonamento',
            'Alerta Escalado: ' . $alerta['tipo'],
            $mensagem
        );
        
        // Envia email urgente
        $email = new EmailService();
        $email->enviarUrgente(
            $responsavel['email'],
            'Escalonamento de Alerta - Ação Necessária',
            'escalonamento/alerta',
            ['alerta' => $alerta, 'responsavel' => $responsavel]
        );
    }
    
    private function atualizarStatusAlerta($alertaId, $nivel) {
        $sql = "UPDATE alertas_qualidade 
                SET nivel_escalonamento = ?, 
                    ultima_atualizacao = NOW() 
                WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$nivel, $alertaId]);
    }
    
    public function getEstatisticasEscalonamento() {
        $sql = "SELECT 
                nivel_escalonamento,
                COUNT(*) as total,
                AVG(TIMESTAMPDIFF(HOUR, data_geracao, ultima_atualizacao)) as tempo_medio
                FROM alertas_qualidade
                WHERE data_geracao >= DATE_SUB(CURRENT_DATE, INTERVAL 30 DAY)
                GROUP BY nivel_escalonamento";
        
        return $this->db->query($sql)->fetchAll();
    }
}
?>
