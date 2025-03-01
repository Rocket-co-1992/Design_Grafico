<?php
class Auth {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function login($email, $senha) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = ? AND ativo = 1");
        $stmt->execute([filter_var($email, FILTER_SANITIZE_EMAIL)]);
        $usuario = $stmt->fetch();
        
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            // Gerar token CSRF
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $_SESSION['user_id'] = $usuario['id'];
            $_SESSION['user_name'] = htmlspecialchars($usuario['nome']);
            $_SESSION['user_level'] = (int)$usuario['nivel'];
            $_SESSION['last_activity'] = time();
            
            // Registrar login
            $this->logLogin($usuario['id']);
            return true;
        }
        return false;
    }
    
    private function logLogin($user_id) {
        $stmt = $this->db->prepare("INSERT INTO login_logs (usuario_id, ip, data) VALUES (?, ?, NOW())");
        $stmt->execute([$user_id, $_SERVER['REMOTE_ADDR']]);
    }
    
    public function checkSession() {
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > SESSION_TIME)) {
            $this->logout();
            return false;
        }
        $_SESSION['last_activity'] = time();
        return true;
    }
    
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    public function logout() {
        session_destroy();
    }
    
    public function requireAuth() {
        if (!$this->isLoggedIn()) {
            header('Location: ' . BASE_URL . '/login.php');
            exit();
        }
    }
}
?>
