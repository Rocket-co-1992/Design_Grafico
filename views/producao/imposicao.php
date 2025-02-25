<?php
require_once '../../config/config.php';
require_once '../../core/Auth.php';
require_once '../../models/Imposicao.php';

$auth = new Auth();
$auth->requireAuth();

$imposicao = new Imposicao();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resultado = $imposicao->calcularAproveitamento($_POST);
}

$materiais = $imposicao->getMateriais();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cálculo de Imposição - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Cálculo de Imposição</h1>
        
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <form method="POST">
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
                                <label>Largura do Trabalho (mm)</label>
                                <input type="number" name="largura" class="form-control" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Altura do Trabalho (mm)</label>
                                <input type="number" name="altura" class="form-control" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Sangria (mm)</label>
                                <input type="number" name="sangria" class="form-control" value="3">
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Calcular</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <?php if (isset($resultado)): ?>
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5>Resultado do Aproveitamento</h5>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col">
                                    <strong>Total de Peças:</strong>
                                    <?php echo $resultado['melhor_aproveitamento']['total_pecas']; ?>
                                </div>
                                <div class="col">
                                    <strong>Desperdício:</strong>
                                    <?php echo number_format($resultado['desperdicio_percentual'], 1); ?>%
                                </div>
                            </div>
                            
                            <div class="visualizacao-imposicao">
                                <?php echo $resultado['visualizacao']; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
