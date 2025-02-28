<?php
class Fidelidade {
    private $db;
    private $niveis = [
        'bronze' => ['min' => 0, 'multiplicador' => 1],
        'prata' => ['min' => 1000, 'multiplicador' => 1.5],
        'ouro' => ['min' => 5000, 'multiplicador' => 2],
        'diamante' => ['min' => 10000, 'multiplicador' => 3]
    ];
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function adicionarPontos($cliente_id, $pedido_id, $valor_pedido) {
        try {
            $this->db->beginTransaction();
            
            // Calcula pontos (1 ponto a cada R$ 1,00)
            $pontos_base = floor($valor_pedido);
            $multiplicador = $this->getMultiplicadorNivel($cliente_id);
            $pontos = floor($pontos_base * $multiplicador);
            
            // Registra histórico
            $sql = "INSERT INTO historico_pontos (cliente_id, pedido_id, pontos, tipo, descricao) 
                    VALUES (?, ?, ?, 'credito', 'Pontos do pedido #')";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$cliente_id, $pedido_id, $pontos]);
            
            // Atualiza pontos do cliente
            $this->atualizarPontos($cliente_id);
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    private function getMultiplicadorNivel($cliente_id) {
        $nivel = $this->getNivelCliente($cliente_id);
        return $this->niveis[$nivel]['multiplicador'];
    }
    
    private function getNivelCliente($cliente_id) {
        $sql = "SELECT nivel FROM programa_fidelidade WHERE cliente_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$cliente_id]);
        $resultado = $stmt->fetch();
        return $resultado ? $resultado['nivel'] : 'bronze';
    }
    
    private function atualizarPontos($cliente_id) {
        // Calcula total de pontos
        $sql = "SELECT SUM(pontos) as total 
                FROM historico_pontos 
                WHERE cliente_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$cliente_id]);
        $total = $stmt->fetch()['total'] ?? 0;
        
        // Determina novo nível
        $novo_nivel = 'bronze';
        foreach ($this->niveis as $nivel => $config) {
            if ($total >= $config['min']) {
                $novo_nivel = $nivel;
            }
        }
        
        // Atualiza ou cria registro
        $sql = "INSERT INTO programa_fidelidade (cliente_id, pontos, nivel) 
                VALUES (?, ?, ?) 
                ON DUPLICATE KEY UPDATE 
                pontos = ?, nivel = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$cliente_id, $total, $novo_nivel, $total, $novo_nivel]);
    }
    
    public function getPontos($cliente_id) {
        $sql = "SELECT 
                COALESCE(SUM(CASE WHEN tipo = 'credito' THEN pontos ELSE -pontos END), 0) as pontos
                FROM historico_pontos 
                WHERE cliente_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$cliente_id]);
        return $stmt->fetch();
    }
    
    public function getHistoricoPontos($cliente_id) {
        $sql = "SELECT * FROM historico_pontos 
                WHERE cliente_id = ? 
                ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$cliente_id]);
        return $stmt->fetchAll();
    }

    public function getEstatisticas() {
        // Total de clientes no programa
        $sql = "SELECT COUNT(*) as total FROM programa_fidelidade";
        $total_clientes = $this->db->query($sql)->fetch()['total'];
        
        // Total de pontos ativos
        $sql = "SELECT SUM(pontos) as total FROM programa_fidelidade";
        $pontos_ativos = $this->db->query($sql)->fetch()['total'];
        
        // Resgates no mês atual
        $sql = "SELECT COUNT(*) as total FROM resgates 
                WHERE MONTH(data_resgate) = MONTH(CURRENT_DATE())
                AND YEAR(data_resgate) = YEAR(CURRENT_DATE())";
        $resgates_mes = $this->db->query($sql)->fetch()['total'];
        
        // Distribuição de níveis
        $sql = "SELECT nivel, COUNT(*) as total 
                FROM programa_fidelidade 
                GROUP BY nivel";
        $niveis = $this->db->query($sql)->fetchAll();
        
        $distribuicao = array_column($niveis, 'total', 'nivel');
        
        return [
            'total_clientes' => $total_clientes,
            'pontos_ativos' => $pontos_ativos,
            'resgates_mes' => $resgates_mes,
            'distribuicao_niveis' => [
                $distribuicao['bronze'] ?? 0,
                $distribuicao['prata'] ?? 0,
                $distribuicao['ouro'] ?? 0,
                $distribuicao['diamante'] ?? 0
            ]
        ];
    }
    
    public function relatorioEngajamento($periodo = 30) {
        $sql = "SELECT 
                c.nome as cliente,
                pf.pontos,
                pf.nivel,
                COUNT(r.id) as total_resgates,
                MAX(r.data_resgate) as ultimo_resgate
                FROM programa_fidelidade pf
                JOIN clientes c ON pf.cliente_id = c.id
                LEFT JOIN resgates r ON pf.cliente_id = r.cliente_id
                WHERE r.data_resgate >= DATE_SUB(CURRENT_DATE, INTERVAL ? DAY)
                OR r.data_resgate IS NULL
                GROUP BY pf.cliente_id
                ORDER BY pf.pontos DESC";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$periodo]);
        return $stmt->fetchAll();
    }

    public function creditarPontos($cliente_id, $pontos, $motivo) {
        return $this->registrarMovimentacao($cliente_id, $pontos, 'credito', $motivo);
    }
    
    public function debitarPontos($cliente_id, $pontos, $motivo) {
        $saldo = $this->getPontos($cliente_id);
        if ($saldo['pontos'] < $pontos) {
            throw new Exception('Saldo de pontos insuficiente');
        }
        return $this->registrarMovimentacao($cliente_id, $pontos, 'debito', $motivo);
    }
    
    private function registrarMovimentacao($cliente_id, $pontos, $tipo, $motivo) {
        $sql = "INSERT INTO historico_pontos 
                (cliente_id, pontos, tipo, motivo) 
                VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $cliente_id,
            $pontos,
            $tipo,
            $motivo
        ]);
    }

    public function getHistorico($cliente_id) {
        $sql = "SELECT * FROM historico_pontos 
                WHERE cliente_id = ? 
                ORDER BY data_movimento DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$cliente_id]);
        return $stmt->fetchAll();
    }
    
    public function calcularNivel($pontos) {
        $niveis = [
            'Bronze' => 0,
            'Prata' => 1000,
            'Ouro' => 5000,
            'Platina' => 10000,
            'Diamante' => 20000
        ];
        
        $nivel_atual = 'Bronze';
        foreach ($niveis as $nivel => $pontos_necessarios) {
            if ($pontos >= $pontos_necessarios) {
                $nivel_atual = $nivel;
            } else {
                break;
            }
        }
        
        return $nivel_atual;
    }
}
?>
