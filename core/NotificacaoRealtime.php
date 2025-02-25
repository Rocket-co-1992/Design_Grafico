<?php
class NotificacaoRealtime {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function enviar($usuario_id, $tipo, $titulo, $mensagem) {
        $sql = "INSERT INTO notificacoes_realtime (usuario_id, tipo, titulo, mensagem) 
                VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$usuario_id, $tipo, $titulo, $mensagem]);
    }
    
    public function buscarNovas($usuario_id) {
        $sql = "SELECT * FROM notificacoes_realtime 
                WHERE usuario_id = ? AND lida = FALSE 
                ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$usuario_id]);
        return $stmt->fetchAll();
    }
    
    public function marcarComoLida($id) {
        $sql = "UPDATE notificacoes_realtime SET lida = TRUE WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
}
?>
