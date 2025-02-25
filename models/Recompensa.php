<?php
class Recompensa {
    private $db;
    private $fidelidade;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->fidelidade = new Fidelidade();
    }
    
    public function listarDisponiveis() {
        $sql = "SELECT * FROM recompensas 
                WHERE ativo = TRUE 
                AND (quantidade_disponivel IS NULL OR quantidade_disponivel > 0)
                ORDER BY pontos_necessarios";
        return $this->db->query($sql)->fetchAll();
    }
    
    public function resgatar($cliente_id, $recompensa_id) {
        try {
            $this->db->beginTransaction();
            
            // Verifica pontos disponíveis
            $pontos = $this->fidelidade->getPontos($cliente_id);
            $recompensa = $this->buscarPorId($recompensa_id);
            
            if (!$recompensa || !$pontos || $pontos['pontos'] < $recompensa['pontos_necessarios']) {
                throw new Exception('Pontos insuficientes');
            }
            
            // Registra resgate
            $sql = "INSERT INTO resgates (cliente_id, recompensa_id, pontos_usados) 
                    VALUES (?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $cliente_id,
                $recompensa_id,
                $recompensa['pontos_necessarios']
            ]);
            
            // Debita pontos
            $this->fidelidade->debitarPontos(
                $cliente_id,
                $recompensa['pontos_necessarios'],
                'Resgate de recompensa: ' . $recompensa['nome']
            );
            
            // Atualiza quantidade disponível
            if ($recompensa['quantidade_disponivel'] !== null) {
                $sql = "UPDATE recompensas 
                        SET quantidade_disponivel = quantidade_disponivel - 1 
                        WHERE id = ?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$recompensa_id]);
            }
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    private function buscarPorId($id) {
        $sql = "SELECT * FROM recompensas WHERE id = ? AND ativo = TRUE";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function getResgatesCliente($cliente_id) {
        $sql = "SELECT r.*, rec.nome, rec.descricao, rec.tipo, rec.valor
                FROM resgates r
                JOIN recompensas rec ON r.recompensa_id = rec.id
                WHERE r.cliente_id = ?
                ORDER BY r.data_resgate DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$cliente_id]);
        return $stmt->fetchAll();
    }

    public function getUltimosResgates($limite = 10) {
        $sql = "SELECT r.*, 
                c.nome as cliente_nome,
                rec.nome as recompensa_nome
                FROM resgates r
                JOIN clientes c ON r.cliente_id = c.id
                JOIN recompensas rec ON r.recompensa_id = rec.id
                ORDER BY r.data_resgate DESC
                LIMIT ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$limite]);
        return $stmt->fetchAll();
    }
    
    public function getRelatorioResgates($dataInicio, $dataFim) {
        $sql = "SELECT 
                DATE(r.data_resgate) as data,
                COUNT(*) as total_resgates,
                SUM(r.pontos_usados) as pontos_total,
                rec.nome as recompensa
                FROM resgates r
                JOIN recompensas rec ON r.recompensa_id = rec.id
                WHERE r.data_resgate BETWEEN ? AND ?
                GROUP BY DATE(r.data_resgate), rec.id
                ORDER BY data";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$dataInicio, $dataFim]);
        return $stmt->fetchAll();
    }
}
?>
