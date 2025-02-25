<?php
require_once '../../../config/config.php';
require_once '../../../core/Auth.php';
require_once '../../../models/CampanhaAnalytics.php';
require_once '../../../models/Campanha.php';

$auth = new Auth();
$auth->requireAuth();

if ($_SESSION['user_level'] < 2) {
    header('Location: ../../dashboard/index.php');
    exit();
}

$analytics = new CampanhaAnalytics();
$campanha = new Campanha();

$campanha_id = $_GET['id'] ?? 0;
$dados_campanha = $campanha->buscarPorId($campanha_id);

if (!$dados_campanha) {
    header('Location: index.php');
    exit();
}

$resultados = $analytics->getResultadosCampanha($campanha_id);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Resultados da Campanha - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../../../assets/css/style.css">
    <script src="../../../assets/js/chart.js"></script>
</head>
<body>
    <div class="container">
        <h1>Resultados da Campanha: <?php echo $dados_campanha['nome']; ?></h1>
        
        <!-- Métricas Principais -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5>Taxa de Abertura</h5>
                        <h2><?php 
                            echo number_format(
                                ($resultados['metricas_gerais']['aberturas'] / 
                                 $resultados['metricas_gerais']['total_envios']) * 100,
                                1
                            ); 
                        ?>%</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5>Taxa de Cliques</h5>
                        <h2><?php 
                            echo number_format(
                                ($resultados['metricas_gerais']['cliques'] / 
                                 $resultados['metricas_gerais']['aberturas']) * 100,
                                1
                            ); 
                        ?>%</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5>Conversões</h5>
                        <h2><?php echo $resultados['conversoes']['total_pedidos']; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5>ROI</h5>
                        <h2><?php echo number_format($resultados['roi']['roi'], 1); ?>%</h2>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Gráfico de Engajamento -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Engajamento ao Longo do Tempo</h5>
            </div>
            <div class="card-body">
                <canvas id="engajamentoChart"></canvas>
            </div>
        </div>
    </div>
    
    <script>
        // Inicialização do gráfico
        const ctx = document.getElementById('engajamentoChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_column($resultados['engajamento'], 'data_abertura')); ?>,
                datasets: [{
                    label: 'Aberturas',
                    data: <?php echo json_encode(array_column($resultados['engajamento'], 'total_aberturas')); ?>
                }, {
                    label: 'Cliques',
                    data: <?php echo json_encode(array_column($resultados['engajamento'], 'total_cliques')); ?>
                }]
            }
        });
    </script>
</body>
</html>
