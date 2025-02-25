<?php
require_once '../../config/config.php';
require_once '../../models/Carrinho.php';

$carrinho = new Carrinho();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['remover'])) {
        $carrinho->remover($_POST['index']);
    } elseif (isset($_POST['atualizar'])) {
        // Implementar atualização de quantidades
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Carrinho de Compras - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Carrinho de Compras</h1>
        
        <?php if (empty($carrinho->getItens())): ?>
            <div class="alert alert-info">
                Seu carrinho está vazio. <a href="produtos.php">Continue comprando</a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Produto</th>
                            <th>Opções</th>
                            <th>Preview</th>
                            <th>Quantidade</th>
                            <th>Preço</th>
                            <th>Total</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($carrinho->getItens() as $index => $item): ?>
                        <tr>
                            <td><?php echo $item['nome']; ?></td>
                            <td>
                                <?php if (!empty($item['opcoes'])): ?>
                                    <ul class="list-unstyled">
                                        <?php foreach ($item['opcoes'] as $opcao): ?>
                                            <li><?php echo $opcao['nome'] . ': ' . $opcao['valor']; ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($item['design']): ?>
                                    <img src="<?php echo $item['design']['preview']; ?>" 
                                         alt="Preview" style="max-width: 100px;">
                                <?php endif; ?>
                            </td>
                            <td>
                                <input type="number" name="quantidade" 
                                       value="<?php echo $item['quantidade']; ?>" 
                                       min="1" class="form-control" style="width: 80px;">
                            </td>
                            <td>R$ <?php echo number_format($item['preco_unitario'], 2, ',', '.'); ?></td>
                            <td>R$ <?php echo number_format($item['preco_total'], 2, ',', '.'); ?></td>
                            <td>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="index" value="<?php echo $index; ?>">
                                    <button type="submit" name="remover" class="btn btn-danger btn-sm">
                                        Remover
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" class="text-right"><strong>Total:</strong></td>
                            <td colspan="2">
                                <strong>R$ <?php echo number_format($carrinho->getTotal(), 2, ',', '.'); ?></strong>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <div class="row mt-4">
                <div class="col-md-6">
                    <a href="produtos.php" class="btn btn-secondary">
                        Continuar Comprando
                    </a>
                </div>
                <div class="col-md-6 text-right">
                    <a href="checkout.php" class="btn btn-primary btn-lg">
                        Finalizar Compra
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
