<?php
class Carrinho {
    private $db;
    private $itens = [];
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->inicializar();
    }
    
    private function inicializar() {
        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }
        $this->itens = &$_SESSION['carrinho'];
    }
    
    public function adicionar($produto_id, $quantidade, $opcoes = [], $design = null) {
        $produto = $this->getProduto($produto_id);
        if (!$produto) return false;
        
        $preco_total = $produto['preco'];
        foreach ($opcoes as $opcao) {
            $preco_total += $this->getPrecoOpcao($opcao);
        }
        
        $item = [
            'produto_id' => $produto_id,
            'nome' => $produto['nome'],
            'quantidade' => $quantidade,
            'preco_unitario' => $preco_total,
            'preco_total' => $preco_total * $quantidade,
            'opcoes' => $opcoes,
            'design' => $design
        ];
        
        $this->itens[] = $item;
        return true;
    }
    
    private function getProduto($id) {
        $sql = "SELECT * FROM produtos WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    private function getPrecoOpcao($opcao_id) {
        $sql = "SELECT preco_adicional FROM produto_opcoes_valores WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$opcao_id]);
        $resultado = $stmt->fetch();
        return $resultado ? $resultado['preco_adicional'] : 0;
    }
    
    public function remover($index) {
        if (isset($this->itens[$index])) {
            unset($this->itens[$index]);
            $this->itens = array_values($this->itens);
            return true;
        }
        return false;
    }
    
    public function getTotal() {
        return array_reduce($this->itens, function($total, $item) {
            return $total + $item['preco_total'];
        }, 0);
    }
    
    public function getItens() {
        return $this->itens;
    }
    
    public function limpar() {
        $this->itens = [];
        unset($_SESSION['carrinho']);
    }
}
?>
