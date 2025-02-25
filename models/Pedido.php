<?php
class Pedido {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function contarPedidosPorPeriodo($dataInicio, $dataFim) {
        $sql = "SELECT COUNT(*) as total FROM pedidos 
                WHERE DATE(created_at) BETWEEN ? AND ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$dataInicio, $dataFim]);
        $resultado = $stmt->fetch();
        return $resultado['total'];
    }
    
    public function getEstatisticasPorPeriodo($dataInicio, $dataFim) {
        $sql = "SELECT 
                    DATE(created_at) as data,
                    COUNT(*) as total_pedidos,
                    SUM(valor_total) as valor_total
                FROM pedidos 
                WHERE DATE(created_at) BETWEEN ? AND ?
                GROUP BY DATE(created_at)
                ORDER BY data";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$dataInicio, $dataFim]);
        return $stmt->fetchAll();
    }
}
?>
