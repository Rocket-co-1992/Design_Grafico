<?php
require_once '../../config/config.php';
require_once '../../core/Auth.php';
require_once '../../models/Produto.php';

$auth = new Auth();
$auth->requireAuth();

$produto = new Produto();
$produtos = $produto->listar();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Produtos - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Produtos</h1>
        <a href="criar.php" class="btn btn-primary">Novo Produto</a>
        
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Preço</th>
                    <th>Estoque</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($produtos as $p): ?>
                <tr>
                    <td><?php echo $p['id']; ?></td>
                    <td><?php echo $p['nome']; ?></td>
                    <td>R$ <?php echo number_format($p['preco'], 2, ',', '.'); ?></td>
                    <td><?php echo $p['estoque']; ?></td>
                    <td>
                        <a href="editar.php?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-info">Editar</a>
                        <a href="excluir.php?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-danger" 
                           onclick="return confirm('Confirma exclusão?')">Excluir</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
