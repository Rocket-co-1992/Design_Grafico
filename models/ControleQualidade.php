<?php
class ControleQualidade {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function criarInspecao($ordem_producao_id) {
        try {
            $this->db->beginTransaction();
            
            // Identifica o tipo de produto
            $tipo_produto = $this->getTipoProdutoOrdem($ordem_producao_id);
            
            // Busca checklist apropriado
            $checklist = $this->getChecklistPorTipo($tipo_produto);
            
            // Cria inspeção
            $sql = "INSERT INTO inspecoes_qualidade (
                        ordem_producao_id, checklist_id, inspetor_id, status
                    ) VALUES (?, ?, ?, 'pendente')";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $ordem_producao_id,
                $checklist['id'],
                $_SESSION['user_id']
            ]);
            
            $inspecao_id = $this->db->lastInsertId();
            
            // Cria itens de verificação
            $this->criarItensVerificacao($inspecao_id, $checklist['id']);
            
            $this->db->commit();
            return $inspecao_id;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    public function registrarResultado($inspecao_id, $resultados) {
        try {
            $this->db->beginTransaction();
            
            foreach ($resultados as $item_id => $resultado) {
                $sql = "INSERT INTO resultados_inspecao (
                            inspecao_id, item_checklist_id, conforme, observacao
                        ) VALUES (?, ?, ?, ?)";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([
                    $inspecao_id,
                    $item_id,
                    $resultado['conforme'],
                    $resultado['observacao']
                ]);
            }
            
            // Atualiza status da inspeção
            $this->atualizarStatusInspecao($inspecao_id);
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    private function atualizarStatusInspecao($inspecao_id) {
        // Verifica se todos os itens estão conformes
        $sql = "SELECT COUNT(*) as total, 
                SUM(CASE WHEN conforme = 1 THEN 1 ELSE 0 END) as conformes
                FROM resultados_inspecao 
                WHERE inspecao_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$inspecao_id]);
        $resultado = $stmt->fetch();
        
        $status = ($resultado['total'] == $resultado['conformes']) ? 'aprovado' : 'reprovado';
        
        $sql = "UPDATE inspecoes_qualidade SET status = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$status, $inspecao_id]);
    }
    
    public function getInspecao($inspecao_id) {
        $sql = "SELECT iq.*, u.nome as inspetor_nome, op.id as ordem_numero
                FROM inspecoes_qualidade iq
                JOIN usuarios u ON iq.inspetor_id = u.id
                JOIN ordem_producao op ON iq.ordem_producao_id = op.id
                WHERE iq.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$inspecao_id]);
        
        $inspecao = $stmt->fetch();
        if ($inspecao) {
            $inspecao['resultados'] = $this->getResultadosInspecao($inspecao_id);
        }
        
        return $inspecao;
    }
    
    private function getResultadosInspecao($inspecao_id) {
        $sql = "SELECT ri.*, ic.descricao
                FROM resultados_inspecao ri
                JOIN itens_checklist ic ON ri.item_checklist_id = ic.id
                WHERE ri.inspecao_id = ?
                ORDER BY ic.ordem";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$inspecao_id]);
        return $stmt->fetchAll();
    }
}
?>
