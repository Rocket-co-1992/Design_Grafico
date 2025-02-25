<?php
require_once '../../config/config.php';
require_once '../../core/Auth.php';
require_once '../../models/Relatorio.php';

$auth = new Auth();
$auth->requireAuth();

$relatorio = new Relatorio();

if (isset($_GET['tipo']) && isset($_GET['data_inicio']) && isset($_GET['data_fim'])) {
    $tipo = $_GET['tipo'];
    $dataInicio = $_GET['data_inicio'];
    $dataFim = $_GET['data_fim'];
    
    switch ($tipo) {
        case 'vendas':
            $dados = $relatorio->vendaPorPeriodo($dataInicio, $dataFim);
            break;
        case 'produtos':
            $dados = $relatorio->produtosVendidos($dataInicio, $dataFim);
            break;
    }
    
    if (isset($_GET['export']) && $_GET['export'] == 'csv') {
        $relatorio->exportarCSV($dados, "relatorio_{$tipo}_{$dataInicio}_{$dataFim}.csv");
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Relatórios - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Relatórios</h1>
        
        <form method="GET" class="mb-4">
            <div class="row">
                <div class="col">
                    <select name="tipo" class="form-control" required>
                        <option value="vendas">Vendas por Período</option>
                        <option value="produtos">Produtos Vendidos</option>
                    </select>
                </div>
                <div class="col">
                    <input type="date" name="data_inicio" class="form-control" required>
                </div>
                <div class="col">
                    <input type="date" name="data_fim" class="form-control" required>
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-primary">Gerar</button>
                    <?php if (isset($dados)): ?>
                    <button type="submit" name="export" value="csv" class="btn btn-success">
                        Exportar CSV
                    </button>
                    <?php endif; ?>
                </div>
            </div>
        </form>
        
        <?php if (isset($dados)): ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <?php foreach (array_keys($dados[0]) as $coluna): ?>
                                <th><?php echo ucfirst(str_replace('_', ' ', $coluna)); ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dados as $linha): ?>
                            <tr>
                                <?php foreach ($linha as $valor): ?>
                                    <td><?php echo $valor; ?></td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
