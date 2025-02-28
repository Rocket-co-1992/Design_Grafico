<?php
class Categoria {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function listar() {
        $sql = "SELECT * FROM categorias WHERE ativo = TRUE ORDER BY nome";
        return $this->db->query($sql)->fetchAll();
    }
    
    public function criar($dados) {
        $sql = "INSERT INTO categorias (nome, descricao, slug) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $dados['nome'],
            $dados['descricao'],
            $this->gerarSlug($dados['nome'])
        ]);
        return $this->db->lastInsertId();
    }
    
    private function gerarSlug($texto) {
        $texto = strtolower($texto);
        $texto = preg_replace('/[^a-z0-9\s-]/', '', $texto);
        $texto = preg_replace('/[\s-]+/', '-', $texto);
        return trim($texto, '-');
    }
}
?>
