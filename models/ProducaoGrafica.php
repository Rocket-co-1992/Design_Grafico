<?php
class ProducaoGrafica {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function criarOrdemProducao($pedido_id) {
        try {
            $this->db->beginTransaction();
            
            // Cria ordem de produção
            $sql = "INSERT INTO ordem_producao (pedido_id, status) VALUES (?, 'aguardando')";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$pedido_id]);
            
            $ordem_id = $this->db->lastInsertId();
            
            // Cria etapas da produção
            $this->criarEtapasProducao($ordem_id, $pedido_id);
            
            $this->db->commit();
            return $ordem_id;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    private function criarEtapasProducao($ordem_id, $pedido_id) {
        // Busca acabamentos do pedido
        $acabamentos = $this->getAcabamentosPedido($pedido_id);
        
        // Etapa de pré-impressão
        $this->criarEtapa($ordem_id, 'pre_impressao', 'Preparação de Arquivos', 1);
        
        // Etapa de impressão
        $this->criarEtapa($ordem_id, 'impressao', 'Impressão', 2);
        
        // Etapas de acabamento
        $ordem = 3;
        foreach ($acabamentos as $acabamento) {
            $this->criarEtapa($ordem_id, 'acabamento', $acabamento['nome'], $ordem++);
        }
        
        // Etapa de controle de qualidade
        $this->criarEtapa($ordem_id, 'qualidade', 'Controle de Qualidade', $ordem++);
        
        // Etapa de embalagem
        $this->criarEtapa($ordem_id, 'embalagem', 'Embalagem', $ordem);
    }
    
    private function criarEtapa($ordem_id, $tipo, $nome, $ordem) {
        $sql = "INSERT INTO etapas_producao (ordem_id, tipo, nome, ordem, status) 
                VALUES (?, ?, ?, ?, 'pendente')";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$ordem_id, $tipo, $nome, $ordem]);
    }
    
    public function atualizarStatusEtapa($etapa_id, $status, $observacoes = null) {
        $sql = "UPDATE etapas_producao 
                SET status = ?, 
                    observacoes = ?,
                    data_conclusao = CASE WHEN ? = 'concluido' THEN NOW() ELSE NULL END
                WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$status, $observacoes, $status, $etapa_id]);
    }
    
    public function getOrdemProducao($ordem_id) {
        $sql = "SELECT op.*, p.id as pedido_numero, c.nome as cliente_nome
                FROM ordem_producao op
                JOIN pedidos p ON op.pedido_id = p.id
                JOIN clientes c ON p.cliente_id = c.id
                WHERE op.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$ordem_id]);
        
        $ordem = $stmt->fetch();
        if ($ordem) {
            $ordem['etapas'] = $this->getEtapasOrdem($ordem_id);
        }
        
        return $ordem;
    }
}
?>
