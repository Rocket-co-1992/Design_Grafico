<?php
require_once '../../config/config.php';
require_once '../../models/Carrinho.php';

$carrinho = new Carrinho();

// Processar ações do carrinho
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['atualizar'])) {
        $carrinho->atualizarQuantidade($_POST['item_id'], $_POST['quantidade']);
    } elseif (isset($_POST['remover'])) {
        $carrinho->remover($_POST['item_id']);
    }
    header('Location: carrinho.php');
    exit;
}

$itens = $carrinho->listar();
$total = $carrinho->getTotal();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Carrinho - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Seu Carrinho</h1>
        
        <?php if (empty($itens)): ?>
            <div class="alert alert-info">
                Seu carrinho está vazio. <a href="produtos.php">Continuar Comprando</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Preço</th>
                            <th>Quantidade</th>
                            <th>Subtotal</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($itens as $index => $item): ?>
                        <tr>
                            <td><?php echo $item['nome']; ?></td>
                            <td>R$ <?php echo number_format($item['preco_unitario'], 2, ',', '.'); ?></td>
                            <td>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="item_id" value="<?php echo $index; ?>">
                                    <input type="number" name="quantidade" value="<?php echo $item['quantidade']; ?>" min="1" class="form-control" style="width: 80px;">
                                    <button type="submit" name="atualizar" class="btn btn-primary btn-sm">Atualizar</button>
                                </form>
                            </td>
                            <td>R$ <?php echo number_format($item['preco_total'], 2, ',', '.'); ?></td>
                            <td>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="item_id" value="<?php echo $index; ?>">
                                    <button type="submit" name="remover" class="btn btn-danger btn-sm">Remover</button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-right"><strong>Total:</strong></td>
                            <td colspan="2"><strong>R$ <?php echo number_format($total, 2, ',', '.'); ?></strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <div class="row mt-4">
                <div class="col-md-6">
                    <a href="produtos.php" class="btn btn-secondary">Continuar Comprando</a>
                </div>
                <div class="col-md-6 text-right">
                    <a href="checkout.php" class="btn btn-primary btn-lg">Finalizar Compra</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
