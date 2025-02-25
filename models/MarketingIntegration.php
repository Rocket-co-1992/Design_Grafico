<?php
class MarketingIntegration {
    private $config;
    
    public function __construct() {
        $this->config = new Configuracao();
    }
    
    public function enviarMailchimp($dados) {
        $apiKey = $this->config->obter('mailchimp_api_key');
        $listId = $this->config->obter('mailchimp_list_id');
        
        // Implementar integração com Mailchimp
        $endpoint = "https://" . substr($apiKey,strpos($apiKey,'-')+1) . 
                   ".api.mailchimp.com/3.0/lists/" . $listId . "/members";
                   
        return $this->fazerRequisicao($endpoint, 'POST', [
            'email_address' => $dados['email'],
            'status' => 'subscribed',
            'merge_fields' => [
                'FNAME' => $dados['nome'],
                'NIVEL' => $dados['nivel']
            ]
        ], $apiKey);
    }
    
    public function enviarWhatsApp($dados) {
        $accessToken = $this->config->obter('whatsapp_token');
        $phoneNumber = $this->config->obter('whatsapp_phone');
        
        // Implementar integração com WhatsApp Business API
        $endpoint = "https://graph.facebook.com/v12.0/" . $phoneNumber . "/messages";
        
        return $this->fazerRequisicao($endpoint, 'POST', [
            'messaging_product' => 'whatsapp',
            'to' => $dados['telefone'],
            'type' => 'template',
            'template' => [
                'name' => $dados['template'],
                'language' => ['code' => 'pt_BR'],
                'components' => [
                    ['type' => 'body', 'parameters' => $dados['parametros']]
                ]
            ]
        ], $accessToken);
    }
    
    public function enviarSMS($dados) {
        $token = $this->config->obter('sms_token');
        
        // Implementar integração com gateway SMS
        $endpoint = "https://api.sms.com.br/send";
        
        return $this->fazerRequisicao($endpoint, 'POST', [
            'to' => $dados['telefone'],
            'message' => $dados['mensagem']
        ], $token);
    }
    
    private function fazerRequisicao($url, $metodo, $dados, $token) {
        $ch = curl_init();
        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $metodo);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($dados));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        curl_close($ch);
        
        return [
            'success' => $httpCode >= 200 && $httpCode < 300,
            'response' => json_decode($response, true),
            'code' => $httpCode
        ];
    }
}
?>
