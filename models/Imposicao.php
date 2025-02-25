<?php
class Imposicao {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function calcularAproveitamento($dados) {
        $formato_trabalho = [
            'largura' => $dados['largura'],
            'altura' => $dados['altura'],
            'sangria' => $dados['sangria'] ?? 0
        ];
        
        $formato_papel = $this->getFormatoPapel($dados['material_id']);
        
        // Calcula dimensões com sangria
        $largura_total = $formato_trabalho['largura'] + (2 * $formato_trabalho['sangria']);
        $altura_total = $formato_trabalho['altura'] + (2 * $formato_trabalho['sangria']);
        
        // Calcula aproveitamentos possíveis
        $aproveitamentos = [
            $this->calcularPosicoes($largura_total, $altura_total, $formato_papel),
            $this->calcularPosicoes($altura_total, $largura_total, $formato_papel)
        ];
        
        // Retorna o melhor aproveitamento
        $melhor = max($aproveitamentos, function($a) {
            return $a['total_pecas'];
        });
        
        return [
            'formato_trabalho' => $formato_trabalho,
            'formato_papel' => $formato_papel,
            'melhor_aproveitamento' => $melhor,
            'desperdicio_percentual' => $this->calcularDesperdicio($melhor, $formato_papel),
            'visualizacao' => $this->gerarVisualizacao($melhor, $formato_trabalho, $formato_papel)
        ];
    }
    
    private function calcularPosicoes($largura, $altura, $formato_papel) {
        $pecas_largura = floor($formato_papel['largura'] / $largura);
        $pecas_altura = floor($formato_papel['altura'] / $altura);
        
        return [
            'orientacao' => 'normal',
            'pecas_largura' => $pecas_largura,
            'pecas_altura' => $pecas_altura,
            'total_pecas' => $pecas_largura * $pecas_altura,
            'area_util' => $largura * $altura * ($pecas_largura * $pecas_altura),
            'area_total' => $formato_papel['largura'] * $formato_papel['altura']
        ];
    }
    
    private function calcularDesperdicio($aproveitamento, $formato_papel) {
        $area_total = $formato_papel['largura'] * $formato_papel['altura'];
        $area_util = $aproveitamento['area_util'];
        
        return (($area_total - $area_util) / $area_total) * 100;
    }
    
    public function gerarVisualizacao($aproveitamento, $formato_trabalho, $formato_papel) {
        // Gera representação visual do aproveitamento em SVG
        $svg = '<svg width="500" height="300" viewBox="0 0 ' . 
               $formato_papel['largura'] . ' ' . $formato_papel['altura'] . '">';
        
        // Desenha folha
        $svg .= '<rect width="100%" height="100%" fill="#f0f0f0" stroke="#000"/>';
        
        // Desenha peças
        for ($i = 0; $i < $aproveitamento['pecas_largura']; $i++) {
            for ($j = 0; $j < $aproveitamento['pecas_altura']; $j++) {
                $x = $i * $formato_trabalho['largura'];
                $y = $j * $formato_trabalho['altura'];
                
                $svg .= '<rect x="' . $x . '" y="' . $y . '" ' .
                        'width="' . $formato_trabalho['largura'] . '" ' .
                        'height="' . $formato_trabalho['altura'] . '" ' .
                        'fill="none" stroke="#ff0000" stroke-width="0.5"/>';
            }
        }
        
        $svg .= '</svg>';
        return $svg;
    }
    
    private function getFormatoPapel($material_id) {
        $sql = "SELECT f.* 
                FROM formatos_impressao f
                JOIN materiais_impressao m ON m.formato_id = f.id
                WHERE m.id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$material_id]);
        return $stmt->fetch();
    }
}
?>
