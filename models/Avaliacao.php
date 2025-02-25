<?php
class Avaliacao {
    private $db;
    private $fidelidade;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->fidelidade = new Fidelidade();
    }
    
    public function avaliar($dados) {
        try {
            $this->db->beginTransaction();
            
            $sql = "INSERT INTO avaliacoes_pedido (pedido_id, cliente_id, nota, comentario) 
                    VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $dados['pedido_id'],
                $dados['cliente_id'],
                $dados['nota'],
                $dados['comentario']
            ]);
            
            // Adiciona pontos bônus por avaliação
            if ($dados['nota'] >= 4) {
                $this->fidelidade->adicionarPontos(
                    $dados['cliente_id'],
                    $dados['pedido_id'],
                    50 // Pontos bônus por avaliação positiva
                );
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    public function getAvaliacaoPedido($pedido_id) {
        $sql = "SELECT * FROM avaliacoes_pedido WHERE pedido_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$pedido_id]);
        return $stmt->fetch();
    }
    
    public function getMediaAvaliacoes() {
        $sql = "SELECT AVG(nota) as media, COUNT(*) as total 
                FROM avaliacoes_pedido";
        return $this->db->query($sql)->fetch();
    }
}
?>
