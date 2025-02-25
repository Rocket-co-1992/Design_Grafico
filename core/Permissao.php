<?php
class Permissao {
    private $db;
    private $usuario_id;
    
    public function __construct($usuario_id = null) {
        $this->db = Database::getInstance()->getConnection();
        $this->usuario_id = $usuario_id ?? $_SESSION['user_id'] ?? null;
    }
    
    public function verificarPermissao($permissao) {
        if ($_SESSION['user_level'] >= 2) return true; // Admin tem todas as permissões
        
        $sql = "SELECT COUNT(*) as tem_permissao 
                FROM usuario_permissoes up 
                JOIN permissoes p ON up.permissao_id = p.id 
                WHERE up.usuario_id = ? AND p.nome = ?";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$this->usuario_id, $permissao]);
        $resultado = $stmt->fetch();
        
        return $resultado['tem_permissao'] > 0;
    }
    
    public function atribuirPermissao($usuario_id, $permissao_id) {
        $sql = "INSERT IGNORE INTO usuario_permissoes (usuario_id, permissao_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$usuario_id, $permissao_id]);
    }
    
    public function revogarPermissao($usuario_id, $permissao_id) {
        $sql = "DELETE FROM usuario_permissoes WHERE usuario_id = ? AND permissao_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$usuario_id, $permissao_id]);
    }
    
    public function listarPermissoesUsuario($usuario_id) {
        $sql = "SELECT p.* 
                FROM permissoes p 
                JOIN usuario_permissoes up ON p.id = up.permissao_id 
                WHERE up.usuario_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$usuario_id]);
        return $stmt->fetchAll();
    }
}
?>
