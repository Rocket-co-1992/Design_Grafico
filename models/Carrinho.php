<?php
class Carrinho {
    private $db;
    private $sessao_key = 'carrinho';
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        if (!isset($_SESSION[$this->sessao_key])) {
            $_SESSION[$this->sessao_key] = [];
        }
    }
    
    public function adicionar($produto_id, $quantidade, $opcoes = []) {
        $produto = $this->getProduto($produto_id);
        if (!$produto) return false;
        
        $preco = $this->calcularPreco($produto, $opcoes);
        $key = $this->gerarKey($produto_id, $opcoes);
        
        if (isset($_SESSION[$this->sessao_key][$key])) {
            $_SESSION[$this->sessao_key][$key]['quantidade'] += $quantidade;
        } else {
            $_SESSION[$this->sessao_key][$key] = [
                'produto_id' => $produto_id,
                'nome' => $produto['nome'],
                'preco_unitario' => $preco,
                'quantidade' => $quantidade,
                'opcoes' => $opcoes
            ];
        }
        
        $this->atualizarPrecoTotal($key);
        return true;
    }
    
    public function remover($key) {
        if (isset($_SESSION[$this->sessao_key][$key])) {
            unset($_SESSION[$this->sessao_key][$key]);
            return true;
        }
        return false;
    }
    
    public function atualizarQuantidade($key, $quantidade) {
        if (isset($_SESSION[$this->sessao_key][$key])) {
            $_SESSION[$this->sessao_key][$key]['quantidade'] = max(1, $quantidade);
            $this->atualizarPrecoTotal($key);
            return true;
        }
        return false;
    }
    
    public function listar() {
        return $_SESSION[$this->sessao_key];
    }
    
    public function getTotal() {
        $total = 0;
        foreach ($_SESSION[$this->sessao_key] as $item) {
            $total += $item['preco_total'];
        }
        return $total;
    }
    
    private function getProduto($id) {
        $sql = "SELECT * FROM produtos WHERE id = ? AND ativo = TRUE";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    private function calcularPreco($produto, $opcoes) {
        $preco = $produto['preco'];
        
        if (!empty($opcoes)) {
            $sql = "SELECT preco_adicional 
                    FROM produto_opcao_valores 
                    WHERE id IN (" . implode(',', array_values($opcoes)) . ")";
            $adicionais = $this->db->query($sql)->fetchAll();
            
            foreach ($adicionais as $adicional) {
                $preco += $adicional['preco_adicional'];
            }
        }
        
        return $preco;
    }
    
    private function gerarKey($produto_id, $opcoes) {
        return $produto_id . '_' . md5(serialize($opcoes));
    }
    
    private function atualizarPrecoTotal($key) {
        $item = $_SESSION[$this->sessao_key][$key];
        $_SESSION[$this->sessao_key][$key]['preco_total'] = 
            $item['preco_unitario'] * $item['quantidade'];
    }
    
    public function limpar() {
        $_SESSION[$this->sessao_key] = [];
    }
}
?>
