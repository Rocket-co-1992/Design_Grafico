<?php
class Cliente {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function criar($dados) {
        $sql = "INSERT INTO clientes (nome, email, telefone, endereco, cnpj) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $dados['nome'],
            $dados['email'],
            $dados['telefone'],
            $dados['endereco'],
            $dados['cnpj']
        ]);
    }
    
    public function listar() {
        $sql = "SELECT * FROM clientes ORDER BY nome";
        return $this->db->query($sql)->fetchAll();
    }
    
    public function buscarPorId($id) {
        $sql = "SELECT * FROM clientes WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function atualizar($id, $dados) {
        $sql = "UPDATE clientes SET nome = ?, email = ?, telefone = ?, endereco = ?, cnpj = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $dados['nome'],
            $dados['email'],
            $dados['telefone'],
            $dados['endereco'],
            $dados['cnpj'],
            $id
        ]);
    }
}
?>
