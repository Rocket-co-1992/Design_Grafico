<?php
class Produto {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function criar($dados) {
        $sql = "INSERT INTO produtos (nome, descricao, preco, estoque) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $dados['nome'],
            $dados['descricao'],
            $dados['preco'],
            $dados['estoque']
        ]);
    }
    
    public function listar() {
        $sql = "SELECT * FROM produtos ORDER BY nome";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    public function buscarPorId($id) {
        $sql = "SELECT * FROM produtos WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function atualizar($id, $dados) {
        $sql = "UPDATE produtos SET nome = ?, descricao = ?, preco = ?, estoque = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $dados['nome'],
            $dados['descricao'],
            $dados['preco'],
            $dados['estoque'],
            $id
        ]);
    }
}
?>
