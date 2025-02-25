<?php
class Producao {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function criarOrdemProducao($pedidoId, $dados) {
        $sql = "INSERT INTO ordem_producao (pedido_id, prioridade, observacoes) 
                VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        
        if ($stmt->execute([$pedidoId, $dados['prioridade'], $dados['observacoes']])) {
            $ordemId = $this->db->lastInsertId();
            return $this->criarEtapas($ordemId, $dados['etapas']);
        }
        return false;
    }
    
    private function criarEtapas($ordemId, $etapas) {
        $sql = "INSERT INTO etapas_producao (ordem_id, nome, status, tempo_estimado, responsavel_id) 
                VALUES (?, ?, 'pendente', ?, ?)";
        $stmt = $this->db->prepare($sql);
        
        foreach ($etapas as $etapa) {
            $stmt->execute([
                $ordemId,
                $etapa['nome'],
                $etapa['tempo_estimado'],
                $etapa['responsavel_id']
            ]);
        }
        return true;
    }
    
    public function atualizarEtapa($etapaId, $status) {
        $sql = "UPDATE etapas_producao SET 
                status = ?,
                data_fim = CASE WHEN ? = 'finalizado' THEN NOW() ELSE NULL END
                WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$status, $status, $etapaId]);
    }
    
    public function listarOrdensProducao() {
        $sql = "SELECT op.*, p.id as pedido_numero, c.nome as cliente_nome 
                FROM ordem_producao op 
                JOIN pedidos p ON op.pedido_id = p.id 
                JOIN clientes c ON p.cliente_id = c.id 
                ORDER BY op.prioridade DESC, op.data_inicio ASC";
        return $this->db->query($sql)->fetchAll();
    }
}
?>
