<?php
class Notificacao {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function criar($dados) {
        $sql = "INSERT INTO notificacoes (usuario_id, titulo, mensagem, tipo) 
                VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $dados['usuario_id'],
            $dados['titulo'],
            $dados['mensagem'],
            $dados['tipo']
        ]);
    }
    
    public function marcarComoLida($id) {
        $sql = "UPDATE notificacoes SET lida = TRUE WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
    
    public function getNotificacoesUsuario($usuarioId) {
        $sql = "SELECT * FROM notificacoes 
                WHERE usuario_id = ? AND lida = FALSE 
                ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$usuarioId]);
        return $stmt->fetchAll();
    }
}
?>
