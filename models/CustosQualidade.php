<?php
class CustosQualidade {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function registrarCusto($dados) {
        try {
            $this->db->beginTransaction();
            
            $sql = "INSERT INTO custos_qualidade 
                    (tipo, descricao, valor, data_ocorrencia, ordem_producao_id, usuario_id) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $dados['tipo'],
                $dados['descricao'],
                $dados['valor'],
                $dados['data_ocorrencia'],
                $dados['ordem_producao_id'],
                $_SESSION['user_id']
            ]);
            
            $custo_id = $this->db->lastInsertId();
            
            // Se for uma falha, registra análise
            if (in_array($dados['tipo'], ['falha_interna', 'falha_externa'])) {
                $this->registrarAnaliseFalha($custo_id, $dados);
            }
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    private function registrarAnaliseFalha($custo_id, $dados) {
        $sql = "INSERT INTO analise_falhas 
                (tipo_falha, causa_raiz, acao_corretiva, status, responsavel_id, prazo_conclusao, custo_id) 
                VALUES (?, ?, ?, 'aberta', ?, ?, ?)";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $dados['tipo_falha'],
            $dados['causa_raiz'],
            $dados['acao_corretiva'],
            $dados['responsavel_id'],
            $dados['prazo_conclusao'],
            $custo_id
        ]);
    }
    
    public function getRelatorioConsolidado($periodo) {
        $sql = "SELECT 
                tipo,
                COUNT(*) as ocorrencias,
                SUM(valor) as valor_total,
                AVG(valor) as valor_medio
                FROM custos_qualidade
                WHERE data_ocorrencia >= DATE_SUB(CURRENT_DATE, INTERVAL ? DAY)
                GROUP BY tipo
                ORDER BY valor_total DESC";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$periodo]);
        return $stmt->fetchAll();
    }
    
    public function getIndicadoresCusto() {
        return [
            'distribuicao' => $this->getDistribuicaoCustos(),
            'tendencia' => $this->getTendenciaCustos(),
            'pareto_falhas' => $this->getParetoFalhas(),
            'custo_por_produto' => $this->getCustoPorProduto()
        ];
    }
}
?>
