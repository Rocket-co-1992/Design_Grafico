<?php
class ControleEquipamentos {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function registrarManutencao($dados) {
        try {
            $this->db->beginTransaction();
            
            // Registra manutenção
            $sql = "INSERT INTO manutencoes_equipamento (
                        equipamento_id, tipo, descricao, custo, 
                        data_realizada, responsavel_id
                    ) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $dados['equipamento_id'],
                $dados['tipo'],
                $dados['descricao'],
                $dados['custo'],
                $dados['data_realizada'],
                $_SESSION['user_id']
            ]);
            
            // Atualiza última manutenção do equipamento
            $sql = "UPDATE equipamentos SET 
                    ultima_manutencao = ?, 
                    proxima_manutencao = DATE_ADD(?, INTERVAL ? DAY)
                    WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $dados['data_realizada'],
                $dados['data_realizada'],
                $dados['intervalo_dias'],
                $dados['equipamento_id']
            ]);
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    public function atualizarContador($equipamento_id, $contador) {
        $sql = "UPDATE equipamentos SET contador_impressoes = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$contador, $equipamento_id]);
    }
    
    public function verificarManutencoesPendentes() {
        $sql = "SELECT * FROM equipamentos 
                WHERE proxima_manutencao <= CURRENT_DATE
                AND status = 'ativo'";
        return $this->db->query($sql)->fetchAll();
    }
    
    public function getHistoricoManutencoes($equipamento_id) {
        $sql = "SELECT m.*, u.nome as responsavel_nome
                FROM manutencoes_equipamento m
                JOIN usuarios u ON m.responsavel_id = u.id
                WHERE m.equipamento_id = ?
                ORDER BY m.data_realizada DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$equipamento_id]);
        return $stmt->fetchAll();
    }
}
?>
