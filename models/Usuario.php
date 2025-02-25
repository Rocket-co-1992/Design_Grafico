<?php
class Usuario {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function criar($dados) {
        $sql = "INSERT INTO usuarios (nome, email, senha, nivel) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $dados['nome'],
            $dados['email'],
            password_hash($dados['senha'], PASSWORD_DEFAULT),
            $dados['nivel']
        ]);
    }
    
    public function atualizar($id, $dados) {
        $campos = [];
        $valores = [];
        
        if (!empty($dados['nome'])) {
            $campos[] = "nome = ?";
            $valores[] = $dados['nome'];
        }
        
        if (!empty($dados['email'])) {
            $campos[] = "email = ?";
            $valores[] = $dados['email'];
        }
        
        if (!empty($dados['senha'])) {
            $campos[] = "senha = ?";
            $valores[] = password_hash($dados['senha'], PASSWORD_DEFAULT);
        }
        
        if (isset($dados['nivel'])) {
            $campos[] = "nivel = ?";
            $valores[] = $dados['nivel'];
        }
        
        if (empty($campos)) {
            return false;
        }
        
        $valores[] = $id;
        $sql = "UPDATE usuarios SET " . implode(", ", $campos) . " WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($valores);
    }
    
    public function excluir($id) {
        $sql = "DELETE FROM usuarios WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
    
    public function listarTodos() {
        $sql = "SELECT id, nome, email, nivel, created_at FROM usuarios ORDER BY nome";
        return $this->db->query($sql)->fetchAll();
    }
    
    public function buscarPorId($id) {
        $sql = "SELECT id, nome, email, nivel, created_at FROM usuarios WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
?>
