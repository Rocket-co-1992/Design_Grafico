<?php
require_once '../../config/config.php';
require_once '../../models/Produto.php';
require_once '../../models/Carrinho.php';

$produto = new Produto();
$carrinho = new Carrinho();

$id = isset($_GET['id']) ? $_GET['id'] : 0;
$p = $produto->buscarPorId($id);

if (!$p) {
    header('Location: produtos.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['adicionar'])) {
    $quantidade = $_POST['quantidade'];
    $opcoes = isset($_POST['opcoes']) ? $_POST['opcoes'] : [];
    
    if ($carrinho->adicionar($id, $quantidade, $opcoes)) {
        $mensagem = ['tipo' => 'success', 'texto' => 'Produto adicionado ao carrinho!'];
    } else {
        $mensagem = ['tipo' => 'danger', 'texto' => 'Erro ao adicionar produto'];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $p['nome']; ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="container">
        <?php if (isset($mensagem)): ?>
            <div class="alert alert-<?php echo $mensagem['tipo']; ?>">
                <?php echo $mensagem['texto']; ?>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-6">
                <?php if($p['imagem']): ?>
                    <img src="../../uploads/produtos/<?php echo $p['imagem']; ?>" 
                         class="img-fluid" alt="<?php echo $p['nome']; ?>">
                <?php endif; ?>
                
                <!-- Online Designer Integration -->
                <?php if($p['permite_personalizar']): ?>
                    <div class="mt-3">
                        <button class="btn btn-success btn-lg" onclick="abrirDesigner(<?php echo $p['id']; ?>)">
                            Personalizar Design Online
                        </button>
                    </div>
                    <div id="designer-preview" class="mt-3"></div>
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <h1><?php echo $p['nome']; ?></h1>
                <div class="price-tag mb-4">
                    R$ <?php echo number_format($p['preco'], 2, ',', '.'); ?>
                </div>
                <div class="description mb-4">
                    <?php echo $p['descricao']; ?>
                </div>
                
                <form method="POST">
                    <div class="form-group">
                        <label>Quantidade:</label>
                        <input type="number" name="quantidade" value="1" min="1" 
                               class="form-control" style="width: 100px">
                    </div>
                    
                    <?php if($p['opcoes']): ?>
                        <?php foreach($p['opcoes'] as $opcao): ?>
                            <div class="form-group">
                                <label><?php echo $opcao['nome']; ?>:</label>
                                <select name="opcoes[<?php echo $opcao['id']; ?>]" class="form-control">
                                    <?php foreach($opcao['valores'] as $valor): ?>
                                        <option value="<?php echo $valor['id']; ?>">
                                            <?php echo $valor['valor']; ?>
                                            <?php if($valor['preco_adicional'] > 0): ?>
                                                (+ R$ <?php echo number_format($valor['preco_adicional'], 2, ',', '.'); ?>)
                                            <?php endif; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    
                    <button type="submit" name="adicionar" class="btn btn-primary btn-lg">
                        Adicionar ao Carrinho
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Online Designer Scripts -->
    <script src="../../assets/js/fabric.min.js"></script>
    <script src="../../assets/js/designer.js"></script>
    <script>
        function abrirDesigner(produtoId) {
            const designer = new OnlineDesigner({
                container: 'designer-preview',
                produtoId: produtoId,
                dimensoes: <?php echo json_encode($p['dimensoes']); ?>,
                templates: <?php echo json_encode($p['templates'] ?? []); ?>,
                onSave: function(design) {
                    document.getElementById('design_data').value = JSON.stringify(design);
                }
            });
        }
    </script>
</body>
</html>
