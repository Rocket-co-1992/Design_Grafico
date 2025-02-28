<?php
require_once '../../config/config.php';
require_once '../../models/ProdutoCustomizavel.php';

$produto = new ProdutoCustomizavel();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $arquivo = $_FILES['design'];
    $erros = $produto->validarArquivo($arquivo);
    
    if (empty($erros)) {
        // Processa upload
        $design_id = $produto->salvarPersonalizacao([
            'produto_id' => $_POST['produto_id'],
            'cliente_id' => $_SESSION['user_id'],
            'design' => $arquivo,
            'preview' => gerarPreview($arquivo)
        ]);
        
        $mensagem = ['tipo' => 'success', 'texto' => 'Design enviado com sucesso!'];
    } else {
        $mensagem = ['tipo' => 'danger', 'texto' => 'Existem problemas com seu arquivo.'];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload de Design - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Upload de Design</h1>
        
        <?php if (isset($mensagem)): ?>
            <div class="alert alert-<?php echo $mensagem['tipo']; ?>">
                <?php echo $mensagem['texto']; ?>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($erros)): ?>
            <div class="alert alert-warning">
                <h4>Atenção aos seguintes pontos:</h4>
                <ul>
                    <?php foreach ($erros as $erro): ?>
                        <li><?php echo $erro['mensagem']; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        
        <div class="card">
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="produto_id" value="<?php echo $_GET['produto_id']; ?>">
                    
                    <div class="form-group">
                        <label>Arquivo do Design (PDF, AI ou EPS)</label>
                        <input type="file" name="design" class="form-control" accept=".pdf,.ai,.eps" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Observações</label>
                        <textarea name="observacoes" class="form-control" rows="3"></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Enviar Design</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
