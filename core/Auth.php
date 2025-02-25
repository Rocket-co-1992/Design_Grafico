<?php
class Auth {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function login($email, $senha) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();
        
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            $_SESSION['user_id'] = $usuario['id'];
            $_SESSION['user_name'] = $usuario['nome'];
            $_SESSION['user_level'] = $usuario['nivel'];
            return true;
        }
        return false;
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
