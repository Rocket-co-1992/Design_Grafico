<?php
require_once '../../config/config.php';
require_once '../../core/Auth.php';
require_once '../../models/Financeiro.php';

$auth = new Auth();
$auth->requireAuth();

$financeiro = new Financeiro();

$dataInicio = isset($_GET['data_inicio']) ? $_GET['data_inicio'] : date('Y-m-01');
$dataFim = isset($_GET['data_fim']) ? $_GET['data_fim'] : date('Y-m-t');

$movimentos = $financeiro->getFluxoCaixa($dataInicio, $dataFim);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Fluxo de Caixa - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Fluxo de Caixa</h1>
        
        <form method="GET" class="mb-3">
            <div class="row">
                <div class="col">
                    <input type="date" name="data_inicio" value="<?php echo $dataInicio; ?>" class="form-control">
                </div>
                <div class="col">
                    <input type="date" name="data_fim" value="<?php echo $dataFim; ?>" class="form-control">
                </div>
                <div class="col">
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </div>
            </div>
        </form>

        <table class="table">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Tipo</th>
                    <th>Descrição</th>
                    <th>Valor</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($movimentos as $movimento): ?>
                <tr>
                    <td><?php echo date('d/m/Y', strtotime($movimento['data_movimento'])); ?></td>
                    <td><?php echo ucfirst($movimento['tipo']); ?></td>
                    <td><?php echo $movimento['descricao']; ?></td>
                    <td class="<?php echo $movimento['tipo'] == 'receita' ? 'text-success' : 'text-danger'; ?>">
                        R$ <?php echo number_format($movimento['valor'], 2, ',', '.'); ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
