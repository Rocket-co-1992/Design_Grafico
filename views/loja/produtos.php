<?php
require_once '../../config/config.php';
require_once '../../models/Produto.php';
require_once '../../models/Categoria.php';

$produto = new Produto();
$categoria = new Categoria();

$categorias = $categoria->listar();
$filtro_categoria = isset($_GET['categoria']) ? $_GET['categoria'] : null;
$ordem = isset($_GET['ordem']) ? $_GET['ordem'] : 'nome';

$produtos = $produto->listarLoja($filtro_categoria, $ordem);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Produtos - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <!-- Filtros -->
            <div class="col-md-3">
                <h3>Categorias</h3>
                <div class="list-group">
                    <a href="?ordem=<?php echo $ordem; ?>" 
                       class="list-group-item <?php echo !$filtro_categoria ? 'active' : ''; ?>">
                        Todas
                    </a>
                    <?php foreach($categorias as $cat): ?>
                        <a href="?categoria=<?php echo $cat['id']; ?>&ordem=<?php echo $ordem; ?>" 
                           class="list-group-item <?php echo $filtro_categoria == $cat['id'] ? 'active' : ''; ?>">
                            <?php echo $cat['nome']; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
                
                <h3 class="mt-4">Ordenar por</h3>
                <div class="list-group">
                    <a href="?categoria=<?php echo $filtro_categoria; ?>&ordem=nome" 
                       class="list-group-item <?php echo $ordem == 'nome' ? 'active' : ''; ?>">
                        Nome
                    </a>
                    <a href="?categoria=<?php echo $filtro_categoria; ?>&ordem=preco_asc" 
                       class="list-group-item <?php echo $ordem == 'preco_asc' ? 'active' : ''; ?>">
                        Menor Preço
                    </a>
                    <a href="?categoria=<?php echo $filtro_categoria; ?>&ordem=preco_desc" 
                       class="list-group-item <?php echo $ordem == 'preco_desc' ? 'active' : ''; ?>">
                        Maior Preço
                    </a>
                </div>
            </div>
            
            <!-- Lista de Produtos -->
            <div class="col-md-9">
                <div class="row">
                    <?php foreach($produtos as $p): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card h-100">
                                <?php if($p['imagem']): ?>
                                    <img src="../../uploads/produtos/<?php echo $p['imagem']; ?>" 
                                         class="card-img-top" alt="<?php echo $p['nome']; ?>">
                                <?php endif; ?>
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $p['nome']; ?></h5>
                                    <p class="card-text"><?php echo $p['descricao_curta']; ?></p>
                                    <div class="price-tag">
                                        R$ <?php echo number_format($p['preco'], 2, ',', '.'); ?>
                                    </div>
                                    <a href="produto.php?id=<?php echo $p['id']; ?>" 
                                       class="btn btn-primary">Ver Detalhes</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
