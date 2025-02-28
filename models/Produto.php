<?php
class Produto {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function criar($dados) {
        $sql = "INSERT INTO produtos (nome, descricao, preco, estoque) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $dados['nome'],
            $dados['descricao'],
            $dados['preco'],
            $dados['estoque']
        ]);
    }
    
    public function listar() {
        $sql = "SELECT * FROM produtos ORDER BY nome";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
    
    public function listarLoja($categoria_id = null, $ordem = 'nome') {
        $where = "WHERE p.ativo = TRUE";
        $params = [];
        
        if ($categoria_id) {
            $where .= " AND p.categoria_id = ?";
            $params[] = $categoria_id;
        }
        
        $orderBy = match($ordem) {
            'preco_asc' => 'p.preco ASC',
            'preco_desc' => 'p.preco DESC',
            default => 'p.nome ASC'
        };
        
        $sql = "SELECT p.*, c.nome as categoria_nome
                FROM produtos p
                LEFT JOIN categorias c ON p.categoria_id = c.id
                $where
                ORDER BY $orderBy";
                
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    public function buscarPorId($id) {
        $sql = "SELECT p.*, c.nome as categoria_nome 
                FROM produtos p
                LEFT JOIN categorias c ON p.categoria_id = c.id
                WHERE p.id = ? AND p.ativo = TRUE";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        
        $produto = $stmt->fetch();
        if ($produto) {
            $produto['opcoes'] = $this->getOpcoes($id);
            $produto['imagens'] = $this->getImagens($id);
            $produto['templates'] = $this->getTemplates($id);
        }
        return $produto;
    }
    
    private function getOpcoes($produto_id) {
        $sql = "SELECT o.*, v.id as valor_id, v.valor, v.preco_adicional 
                FROM produto_opcoes o
                JOIN produto_opcao_valores v ON o.id = v.opcao_id
                WHERE o.produto_id = ?
                ORDER BY o.ordem, v.ordem";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$produto_id]);
        
        $opcoes = [];
        while ($row = $stmt->fetch()) {
            $opcao_id = $row['id'];
            if (!isset($opcoes[$opcao_id])) {
                $opcoes[$opcao_id] = [
                    'id' => $opcao_id,
                    'nome' => $row['nome'],
                    'tipo' => $row['tipo'],
                    'valores' => []
                ];
            }
            $opcoes[$opcao_id]['valores'][] = [
                'id' => $row['valor_id'],
                'valor' => $row['valor'],
                'preco_adicional' => $row['preco_adicional']
            ];
        }
        return array_values($opcoes);
    }
    
    public function getPrecoOpcao($opcao_id, $valor_id) {
        $sql = "SELECT preco_adicional 
                FROM produto_opcoes_valores 
                WHERE opcao_id = ? AND id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$opcao_id, $valor_id]);
        $resultado = $stmt->fetch();
        return $resultado ? $resultado['preco_adicional'] : 0;
    }
    
    private function getImagens($produto_id) {
        $sql = "SELECT * FROM produto_imagens 
                WHERE produto_id = ? 
                ORDER BY ordem";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$produto_id]);
        return $stmt->fetchAll();
    }
    
    private function getTemplates($produto_id) {
        $sql = "SELECT * FROM produto_templates 
                WHERE produto_id = ? AND ativo = TRUE
                ORDER BY ordem";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$produto_id]);
        return $stmt->fetchAll();
    }
    
    public function listarMateriais() {
        $sql = "SELECT * FROM materiais_impressao WHERE ativo = TRUE ORDER BY nome";
        return $this->db->query($sql)->fetchAll();
    }
    
    public function listarAcabamentos() {
        $sql = "SELECT * FROM acabamentos WHERE ativo = TRUE ORDER BY nome";
        return $this->db->query($sql)->fetchAll();
    }
    
    public function atualizar($id, $dados) {
        $sql = "UPDATE produtos SET nome = ?, descricao = ?, preco = ?, estoque = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $dados['nome'],
            $dados['descricao'],
            $dados['preco'],
            $dados['estoque'],
            $id
        ]);
    }
}
?>
