<?php
class Campanha {
    private $db;
    private $email;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->email = new EmailService();
    }
    
    public function criarCampanha($dados) {
        $sql = "INSERT INTO campanhas (
            nome, tipo, conteudo, segmento, status, data_inicio, data_fim
        ) VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $dados['nome'],
            $dados['tipo'],
            $dados['conteudo'],
            $dados['segmento'],
            'agendada',
            $dados['data_inicio'],
            $dados['data_fim']
        ]);
    }
    
    public function executarCampanhaReativacao($cliente_id) {
        $cliente = $this->getClienteInfo($cliente_id);
        $template = $this->getTemplateReativacao($cliente['nivel']);
        
        // Gera cupom personalizado
        $cupom = $this->gerarCupomReativacao($cliente_id);
        
        // Envia email
        return $this->email->enviar(
            $cliente['email'],
            'Sentimos sua falta! ' . $template['assunto'],
            'campanha_reativacao',
            [
                'nome' => $cliente['nome'],
                'nivel' => $cliente['nivel'],
                'pontos' => $cliente['pontos'],
                'cupom' => $cupom,
                'desconto' => $template['desconto']
            ]
        );
    }
    
    private function gerarCupomReativacao($cliente_id) {
        $codigo = 'VOLTA' . strtoupper(uniqid());
        $cupom = new Cupom();
        
        $cupom->criar([
            'codigo' => $codigo,
            'tipo' => 'percentual',
            'valor' => 15,
            'data_inicio' => date('Y-m-d'),
            'data_fim' => date('Y-m-d', strtotime('+30 days')),
            'limite_usos' => 1,
            'cliente_id' => $cliente_id
        ]);
        
        return $codigo;
    }
    
    public function verificarClientesInativos() {
        $sql = "SELECT c.id, c.nome, c.email, pf.nivel, pf.pontos,
                DATEDIFF(CURRENT_DATE, MAX(p.created_at)) as dias_inativo
                FROM clientes c
                JOIN programa_fidelidade pf ON c.id = pf.cliente_id
                LEFT JOIN pedidos p ON c.id = p.cliente_id
                GROUP BY c.id
                HAVING dias_inativo >= 90
                AND NOT EXISTS (
                    SELECT 1 FROM campanhas_enviadas ce 
                    WHERE ce.cliente_id = c.id 
                    AND ce.tipo = 'reativacao'
                    AND ce.data_envio >= DATE_SUB(CURRENT_DATE, INTERVAL 30 DAY)
                )";
        
        return $this->db->query($sql)->fetchAll();
    }
    
    private function registrarEnvioCampanha($cliente_id, $tipo) {
        $sql = "INSERT INTO campanhas_enviadas (cliente_id, tipo) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$cliente_id, $tipo]);
    }
    
    private function getTemplateReativacao($nivel) {
        $templates = [
            'bronze' => ['desconto' => 10, 'assunto' => 'Volte com 10% de desconto'],
            'prata' => ['desconto' => 15, 'assunto' => 'Cliente especial: 15% de desconto'],
            'ouro' => ['desconto' => 20, 'assunto' => 'Cliente VIP: 20% de desconto'],
            'diamante' => ['desconto' => 25, 'assunto' => 'Cliente Premium: 25% de desconto']
        ];
        
        return $templates[$nivel] ?? $templates['bronze'];
    }
}
?>
