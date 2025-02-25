<?php
class Configuracao {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function salvar($chave, $valor) {
        $sql = "INSERT INTO configuracoes (chave, valor) 
                VALUES (?, ?) 
                ON DUPLICATE KEY UPDATE valor = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$chave, $valor, $valor]);
    }
    
    public function obter($chave) {
        $sql = "SELECT valor FROM configuracoes WHERE chave = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$chave]);
        $resultado = $stmt->fetch();
        return $resultado ? $resultado['valor'] : null;
    }
    
    public function listarTodas() {
        $sql = "SELECT * FROM configuracoes ORDER BY chave";
        return $this->db->query($sql)->fetchAll();
    }
}
?>
