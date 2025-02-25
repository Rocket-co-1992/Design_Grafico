<?php
class Template {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function criar($dados) {
        $sql = "INSERT INTO produto_templates (produto_id, nome, arquivo, thumbnail, dimensoes, formato, categoria) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $dados['produto_id'],
            $dados['nome'],
            $dados['arquivo'],
            $dados['thumbnail'],
            $dados['dimensoes'],
            $dados['formato'],
            $dados['categoria']
        ]);
    }
    
    public function listarPorProduto($produto_id) {
        $sql = "SELECT * FROM produto_templates WHERE produto_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$produto_id]);
        return $stmt->fetchAll();
    }
    
    public function listarPorCategoria($categoria) {
        $sql = "SELECT * FROM produto_templates WHERE categoria = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$categoria]);
        return $stmt->fetchAll();
    }
    
    public function excluir($id) {
        // Primeiro remove os arquivos físicos
        $template = $this->buscarPorId($id);
        if ($template) {
            @unlink(dirname(__DIR__) . '/uploads/templates/' . $template['arquivo']);
            @unlink(dirname(__DIR__) . '/uploads/templates/thumbnails/' . $template['thumbnail']);
        }
        
        // Remove do banco
        $sql = "DELETE FROM produto_templates WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
    
    private function buscarPorId($id) {
        $sql = "SELECT * FROM produto_templates WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
?>
