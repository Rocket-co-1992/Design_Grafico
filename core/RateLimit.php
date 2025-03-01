<?php
class RateLimit {
    private $db;
    private $maxRequests = 60;
    private $timeWindow = 60; // segundos
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function check($ip) {
        $this->limparAntigos();
        
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM rate_limits WHERE ip = ? AND timestamp > DATE_SUB(NOW(), INTERVAL ? SECOND)");
        $stmt->execute([$ip, $this->timeWindow]);
        $count = $stmt->fetchColumn();
        
        if ($count >= $this->maxRequests) {
            return false;
        }
        
        $stmt = $this->db->prepare("INSERT INTO rate_limits (ip, timestamp) VALUES (?, NOW())");
        $stmt->execute([$ip]);
        
        return true;
    }
    
    private function limparAntigos() {
        $stmt = $this->db->prepare("DELETE FROM rate_limits WHERE timestamp < DATE_SUB(NOW(), INTERVAL ? SECOND)");
        $stmt->execute([$this->timeWindow]);
    }
}
?>
