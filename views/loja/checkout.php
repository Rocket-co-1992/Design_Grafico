<?php
require_once '../../config/config.php';
require_once '../../models/Carrinho.php';
require_once '../../models/Checkout.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?redirect=checkout.php');
    exit;
}

$carrinho = new Carrinho();
$checkout = new Checkout();

if (empty($carrinho->getItens())) {
    header('Location: carrinho.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resultado = $checkout->finalizarPedido([
        'cliente_id' => $_SESSION['user_id'],
        'endereco' => $_POST['endereco'],
        'pagamento' => $_POST['pagamento']
    ]);
    
    if ($resultado['success']) {
        header('Location: pedido-confirmado.php?id=' . $resultado['pedido_id']);
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Finalizar Compra</h1>
        
        <form method="POST" id="checkoutForm">
            <!-- Resumo do Pedido -->
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3>Resumo do Pedido</h3>
                        </div>
                        <div class="card-body">
                            <?php foreach ($carrinho->getItens() as $item): ?>
                                <div class="produto-item">
                                    <h4><?php echo $item['nome']; ?></h4>
                                    <p>Quantidade: <?php echo $item['quantidade']; ?></p>
                                    <p>R$ <?php echo number_format($item['preco_total'], 2, ',', '.'); ?></p>
                                </div>
                            <?php endforeach; ?>
                            
                            <hr>
                            <h4>Total: R$ <?php echo number_format($carrinho->getTotal(), 2, ',', '.'); ?></h4>
                        </div>
                    </div>
                </div>
                
                <!-- Endereço de Entrega -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3>Endereço de Entrega</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>CEP</label>
                                <input type="text" name="endereco[cep]" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Logradouro</label>
                                <input type="text" name="endereco[logradouro]" class="form-control" required>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label>Número</label>
                                        <input type="text" name="endereco[numero]" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label>Complemento</label>
                                        <input type="text" name="endereco[complemento]" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Bairro</label>
                                <input type="text" name="endereco[bairro]" class="form-control" required>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label>Cidade</label>
                                        <input type="text" name="endereco[cidade]" class="form-control" required>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label>Estado</label>
                                        <select name="endereco[estado]" class="form-control" required>
                                            <option value="">Selecione...</option>
                                            <!-- Lista de estados -->
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Pagamento -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h3>Pagamento</h3>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Forma de Pagamento</label>
                                <select name="pagamento[tipo]" class="form-control" required>
                                    <option value="">Selecione...</option>
                                    <option value="cartao">Cartão de Crédito</option>
                                    <option value="boleto">Boleto Bancário</option>
                                    <option value="pix">PIX</option>
                                </select>
                            </div>
                            
                            <div id="cartao-campos" style="display: none;">
                                <!-- Campos do cartão serão mostrados via JavaScript -->
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-lg btn-block mt-4">
                                Finalizar Compra
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    
    <script src="../../assets/js/checkout.js"></script>
</body>
</html>
