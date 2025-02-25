<?php
class RecompensaController {
    private $recompensa;
    private $notificacao;
    private $email;
    
    public function __construct() {
        $this->recompensa = new Recompensa();
        $this->notificacao = new NotificacaoRealtime();
        $this->email = new EmailService();
    }
    
    public function processarResgate($dados) {
        try {
            $resgate = $this->recompensa->resgatar($dados['cliente_id'], $dados['recompensa_id']);
            
            if ($resgate) {
                // Envia notificação
                $this->notificacao->enviar(
                    $dados['cliente_id'],
                    'resgate',
                    'Resgate de Recompensa Confirmado',
                    'Seu resgate foi processado com sucesso!'
                );
                
                // Envia email de confirmação
                $cliente = $this->getClienteInfo($dados['cliente_id']);
                $recompensa = $this->recompensa->buscarPorId($dados['recompensa_id']);
                
                $this->email->enviar(
                    $cliente['email'],
                    'Confirmação de Resgate - ' . SITE_NAME,
                    'recompensa_resgate',
                    [
                        'nome' => $cliente['nome'],
                        'recompensa' => $recompensa['nome'],
                        'pontos' => $recompensa['pontos_necessarios']
                    ]
                );
                
                return ['success' => true, 'message' => 'Resgate realizado com sucesso!'];
            }
            
            return ['success' => false, 'message' => 'Erro ao processar resgate'];
            
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    private function getClienteInfo($cliente_id) {
        $cliente = new Cliente();
        return $cliente->buscarPorId($cliente_id);
    }
}
?>
