<?php
class EmailService {
    private $mailer;
    
    public function __construct() {
        $this->mailer = new PHPMailer(true);
        $this->configurarSMTP();
    }
    
    private function configurarSMTP() {
        $this->mailer->isSMTP();
        $this->mailer->Host = SMTP_HOST;
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = SMTP_USER;
        $this->mailer->Password = SMTP_PASS;
        $this->mailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mailer->Port = SMTP_PORT;
        $this->mailer->setFrom(SMTP_USER, SITE_NAME);
        $this->mailer->isHTML(true);
    }
    
    public function enviar($destinatario, $assunto, $template, $dados) {
        try {
            $this->mailer->addAddress($destinatario);
            $this->mailer->Subject = $assunto;
            $this->mailer->Body = $this->renderizarTemplate($template, $dados);
            
            return $this->mailer->send();
        } catch (Exception $e) {
            error_log('Erro ao enviar email: ' . $e->getMessage());
            return false;
        }
    }
    
    private function renderizarTemplate($template, $dados) {
        ob_start();
        include dirname(__DIR__) . '/views/emails/' . $template . '.php';
        return ob_get_clean();
    }
}
?>
