<?php
require_once '../../config/config.php';
require_once '../../models/Orcamento.php';
require_once '../../models/Produto.php';

$orcamento = new Orcamento();
$produto = new Produto();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $resultado = $orcamento->calcularPreco($_POST);
        $preco = $resultado['preco'];
        $prazo = $resultado['prazo'];
    } catch (Exception $e) {
        $erro = $e->getMessage();
    }
}

$materiais = $produto->listarMateriais();
$acabamentos = $produto->listarAcabamentos();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Orçamento Online - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Orçamento Online</h1>
        
        <?php if (isset($erro)): ?>
            <div class="alert alert-danger"><?php echo $erro; ?></div>
        <?php endif; ?>
        
        <form method="POST" id="formOrcamento" class="needs-validation" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6">
                    <h3>Especificações do Trabalho</h3>
                    
                    <div class="form-group">
                        <label>Tipo de Trabalho</label>
                        <select name="tipo" class="form-control" required>
                            <option value="">Selecione...</option>
                            <option value="flyer">Flyer/Panfleto</option>
                            <option value="cartao">Cartão de Visita</option>
                            <option value="banner">Banner</option>
                            <option value="outros">Outros</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Material</label>
                        <select name="material_id" class="form-control" required>
                            <?php foreach ($materiais as $material): ?>
                                <option value="<?php echo $material['id']; ?>">
                                    <?php echo $material['nome']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Dimensões</label>
                        <div class="row">
                            <div class="col">
                                <input type="number" name="largura" class="form-control" 
                                       placeholder="Largura (mm)" required>
                            </div>
                            <div class="col">
                                <input type="number" name="altura" class="form-control" 
                                       placeholder="Altura (mm)" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Quantidade</label>
                        <input type="number" name="quantidade" class="form-control" required>
                    </div>
                </div>

                <div class="col-md-6">
                    <h3>Acabamentos</h3>
                    <?php foreach ($acabamentos as $acabamento): ?>
                        <div class="form-check mb-2">
                            <input type="checkbox" name="acabamentos[]" 
                                   value="<?php echo $acabamento['id']; ?>" 
                                   class="form-check-input">
                            <label class="form-check-label">
                                <?php echo $acabamento['nome']; ?>
                                <?php if ($acabamento['preco_base'] > 0): ?>
                                    (+R$ <?php echo number_format($acabamento['preco_base'], 2, ',', '.'); ?>)
                                <?php endif; ?>
                            </label>
                        </div>
                    <?php endforeach; ?>

                    <h3 class="mt-4">Arquivo</h3>
                    <div class="form-group">
                        <input type="file" name="arquivo" class="form-control-file" 
                               accept=".pdf,.ai,.psd,.jpg,.png">
                        <small class="form-text text-muted">
                            Formatos aceitos: PDF, AI, PSD, JPG, PNG
                        </small>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary btn-lg mt-4">
                Calcular Orçamento
            </button>
        </form>

        <?php if (isset($preco)): ?>
            <div class="card mt-4">
                <div class="card-body">
                    <h3>Resultado do Orçamento</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Valor Total</h4>
                            <h2 class="text-success">
                                R$ <?php echo number_format($preco, 2, ',', '.'); ?>
                            </h2>
                        </div>
                        <div class="col-md-6">
                            <h4>Prazo de Produção</h4>
                            <p class="lead"><?php echo $prazo; ?> dias úteis</p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button class="btn btn-success btn-lg" onclick="prosseguirPedido()">
                            Prosseguir com o Pedido
                        </button>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script>
    function prosseguirPedido() {
        document.getElementById('formOrcamento').action = 'finalizar_pedido.php';
        document.getElementById('formOrcamento').submit();
    }
    </script>
</body>
</html>
