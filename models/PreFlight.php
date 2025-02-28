<?php
class PreFlight {
    private $arquivo;
    private $info;
    
    public function __construct($arquivo) {
        $this->arquivo = $arquivo;
        $this->info = [
            'status' => 'pendente',
            'erros' => [],
            'avisos' => [],
            'detalhes' => []
        ];
    }
    
    public function verificar() {
        try {
            $this->verificarExtensao();
            $this->verificarDimensoes();
            $this->verificarResolucao();
            $this->verificarCores();
            
            if (empty($this->info['erros'])) {
                $this->info['status'] = 'aprovado';
            }
            
        } catch (Exception $e) {
            $this->info['status'] = 'erro';
            $this->info['erros'][] = $e->getMessage();
        }
        
        return $this->info;
    }
    
    private function verificarExtensao() {
        $extensao = strtolower(pathinfo($this->arquivo['name'], PATHINFO_EXTENSION));
        $permitidas = ['pdf', 'ai', 'psd', 'jpg', 'png'];
        
        if (!in_array($extensao, $permitidas)) {
            throw new Exception('Formato de arquivo não suportado');
        }
        
        $this->info['detalhes']['formato'] = $extensao;
    }
    
    private function verificarDimensoes() {
        if (!isset($this->info['detalhes']['formato'])) {
            throw new Exception('Formato do arquivo não foi validado');
        }
        
        if (in_array($this->info['detalhes']['formato'], ['jpg', 'png'])) {
            $imagem = @getimagesize($this->arquivo['tmp_name']);
            
            if ($imagem === false) {
                throw new Exception('Não foi possível ler as dimensões da imagem');
            }
            
            if ($imagem[0] < 1000 || $imagem[1] < 1000) {
                $this->info['avisos'][] = 'Resolução da imagem pode ser baixa para impressão';
            }
            
            $this->info['detalhes']['dimensoes'] = [
                'largura' => $imagem[0],
                'altura' => $imagem[1]
            ];
        }
    }
    
    private function verificarResolucao() {
        if (in_array($this->info['detalhes']['formato'], ['jpg', 'png'])) {
            $dpi = $this->calcularDPI();
            
            if ($dpi < 300) {
                $this->info['avisos'][] = 'DPI abaixo do recomendado para impressão (300 DPI)';
            }
            
            $this->info['detalhes']['dpi'] = $dpi;
        }
    }
    
    private function verificarCores() {
        if (!isset($this->info['detalhes']['formato'])) {
            return;
        }
        
        if (in_array($this->info['detalhes']['formato'], ['jpg', 'png'])) {
            $conteudo = @file_get_contents($this->arquivo['tmp_name']);
            if ($conteudo === false) {
                throw new Exception('Não foi possível ler o arquivo');
            }
            
            $imagem = @imagecreatefromstring($conteudo);
            if ($imagem === false) {
                throw new Exception('Formato de imagem inválido');
            }
            
            $espaco_cores = imagecolorstotal($imagem);
            
            if ($espaco_cores === 0) {
                $this->info['detalhes']['cores'] = 'RGB/CMYK';
            } else {
                $this->info['detalhes']['cores'] = 'Indexado';
                $this->info['avisos'][] = 'Imagem com cores indexadas pode ter qualidade reduzida';
            }
            
            imagedestroy($imagem);
        }
    }
    
    private function calcularDPI() {
        $imagem = getimagesize($this->arquivo['tmp_name']);
        return round($imagem[0] / 8.27); // Assume tamanho A4 em polegadas
    }
}
?>
