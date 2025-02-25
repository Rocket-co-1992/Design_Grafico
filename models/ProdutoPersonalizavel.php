<?php
class ProdutoPersonalizavel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getDetalhes($id) {
        $sql = "SELECT p.*, GROUP_CONCAT(DISTINCT pt.id) as templates,
                GROUP_CONCAT(DISTINCT po.id) as opcoes
                FROM produtos p 
                LEFT JOIN produto_templates pt ON p.id = pt.produto_id
                LEFT JOIN produto_opcoes po ON p.id = po.produto_id
                WHERE p.id = ?
                GROUP BY p.id";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $produto = $stmt->fetch();
        
        if ($produto) {
            $produto['templates'] = $this->getTemplates($id);
            $produto['opcoes'] = $this->getOpcoes($id);
        }
        
        return $produto;
    }
    
    public function getTemplates($produto_id) {
        $sql = "SELECT * FROM produto_templates WHERE produto_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$produto_id]);
        return $stmt->fetchAll();
    }
    
    public function getOpcoes($produto_id) {
        $sql = "SELECT po.*, GROUP_CONCAT(pov.id,':|:',pov.valor,':|:',pov.preco_adicional) as valores
                FROM produto_opcoes po
                LEFT JOIN produto_opcoes_valores pov ON po.id = pov.opcao_id
                WHERE po.produto_id = ?
                GROUP BY po.id
                ORDER BY po.ordem";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$produto_id]);
        $opcoes = $stmt->fetchAll();
        
        // Formatar valores das opções
        foreach ($opcoes as &$opcao) {
            $valores = [];
            $valoresRaw = explode(',', $opcao['valores']);
            foreach ($valoresRaw as $valor) {
                list($id, $nome, $preco) = explode(':|:', $valor);
                $valores[] = [
                    'id' => $id,
                    'valor' => $nome,
                    'preco_adicional' => $preco
                ];
            }
            $opcao['valores'] = $valores;
        }
        
        return $opcoes;
    }
    
    public function salvarDesign($dados) {
        $sql = "INSERT INTO designs_salvos (cliente_id, produto_id, nome, dados_design, preview_url) 
                VALUES (?, ?, ?, ?, ?)";
                
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $dados['cliente_id'],
            $dados['produto_id'],
            $dados['nome'],
            json_encode($dados['design']),
            $dados['preview']
        ]);
    }
}
?>
