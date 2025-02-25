<?php
require_once '../../../config/config.php';
require_once '../../../core/Auth.php';
require_once '../../../models/FidelidadeMetricas.php';

$auth = new Auth();
$auth->requireAuth();

if ($_SESSION['user_level'] < 2) {
    header('Location: ../../dashboard/index.php');
    exit();
}

$metricas = new FidelidadeMetricas();
$periodo = $_GET['periodo'] ?? 30;

$roi = $metricas->calcularROI($periodo);
$retencao = $metricas->getMetricasRetencao();
$engajamento = $metricas->getAnaliseEngajamento();
$clientes_risco = $metricas->identificarClientesRisco();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Métricas Avançadas - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../../../assets/css/style.css">
    <script src="../../../assets/js/chart.js"></script>
</head>
<body>
    <div class="container">
        <h1>Métricas Avançadas do Programa de Fidelidade</h1>
        
        <!-- Filtro de Período -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="form-inline">
                    <label class="mr-2">Período de Análise:</label>
                    <select name="periodo" class="form-control mr-2" onchange="this.form.submit()">
                        <option value="30" <?php echo $periodo == 30 ? 'selected' : ''; ?>>Últimos 30 dias</option>
                        <option value="90" <?php echo $periodo == 90 ? 'selected' : ''; ?>>Últimos 90 dias</option>
                        <option value="180" <?php echo $periodo == 180 ? 'selected' : ''; ?>>Últimos 180 dias</option>
                        <option value="365" <?php echo $periodo == 365 ? 'selected' : ''; ?>>Último ano</option>
                    </select>
                </form>
            </div>
        </div>

        <!-- ROI e Métricas Principais -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>ROI do Programa</h5>
                    </div>
                    <div class="card-body">
                        <h3 class="<?php echo $roi['roi'] > 0 ? 'text-success' : 'text-danger'; ?>">
                            <?php echo number_format($roi['roi'], 2); ?>%
                        </h3>
                        <p>Receita: R$ <?php echo number_format($roi['receita'], 2, ',', '.'); ?></p>
                        <p>Custos: R$ <?php echo number_format($roi['custos'], 2, ',', '.'); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5>Métricas de Retenção</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3">
                                <h6>Taxa de Retenção</h6>
                                <h4><?php 
                                    echo number_format(
                                        ($retencao['clientes_retorno'] / $retencao['total_clientes']) * 100, 
                                        1
                                    ); 
                                ?>%</h4>
                            </div>
                            <div class="col-md-3">
                                <h6>Média de Pedidos</h6>
                                <h4><?php echo number_format($retencao['media_pedidos'], 1); ?></h4>
                            </div>
                            <div class="col-md-3">
                                <h6>Ticket Médio</h6>
                                <h4>R$ <?php echo number_format($retencao['ticket_medio'], 2, ',', '.'); ?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Clientes em Risco -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Clientes em Risco de Churn</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Email</th>
                            <th>Última Compra</th>
                            <th>Dias Inativo</th>
                            <th>Nível</th>
                            <th>Pontos</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clientes_risco as $cliente): ?>
                            <tr>
                                <td><?php echo $cliente['nome']; ?></td>
                                <td><?php echo $cliente['email']; ?></td>
                                <td><?php echo date('d/m/Y', strtotime($cliente['ultima_compra'])); ?></td>
                                <td><?php echo $cliente['dias_inativo']; ?></td>
                                <td><?php echo ucfirst($cliente['nivel']); ?></td>
                                <td><?php echo $cliente['pontos']; ?></td>
                                <td>
                                    <button class="btn btn-sm btn-primary" onclick="enviarCampanha(<?php echo $cliente['id']; ?>)">
                                        Enviar Campanha
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        function enviarCampanha(clienteId) {
            // Implementar envio de campanha de reativação
            alert('Implementar envio de campanha para cliente ' + clienteId);
        }
    </script>
</body>
</html>
