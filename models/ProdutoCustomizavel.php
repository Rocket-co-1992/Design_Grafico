<?php
class ProdutoCustomizavel extends Produto {
    public function getTemplates($produto_id) {
        $sql = "SELECT * FROM templates_produto WHERE produto_id = ? AND ativo = TRUE";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$produto_id]);
        return $stmt->fetchAll();
    }
    
    public function validarArquivo($arquivo) {
        $validacoes = [
            'resolucao_minima' => $this->validarResolucao($arquivo),
            'cores' => $this->validarCores($arquivo),
            'margens' => $this->validarMargens($arquivo),
            'fontes' => $this->validarFontes($arquivo)
        ];
        
        return array_filter($validacoes, function($v) { return !$v['valido']; });
    }
    
    public function salvarPersonalizacao($dados) {
        $this->db->beginTransaction();
        
        try {
            // Salva dados do design
            $sql = "INSERT INTO designs_produto 
                    (produto_id, cliente_id, dados_design, preview_url) 
                    VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $dados['produto_id'],
                $dados['cliente_id'],
                json_encode($dados['design']),
                $dados['preview']
            ]);
            
            $this->db->commit();
            return $this->db->lastInsertId();
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }
    
    private function validarResolucao($arquivo) {
        // Implementar validação de resolução mínima
        // ...
    }
    
    private function validarCores($arquivo) {
        // Implementar validação de cores (CMYK)
        // ...
    }
}
?>
