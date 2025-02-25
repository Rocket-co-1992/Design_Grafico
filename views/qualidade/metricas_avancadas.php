<?php
require_once '../../config/config.php';
require_once '../../core/Auth.php';
require_once '../../models/QualidadeEstatisticas.php';

$auth = new Auth();
$auth->requireAuth();

$estatisticas = new QualidadeEstatisticas();
$periodo = $_GET['periodo'] ?? 30;
$metricas = $estatisticas->getMetricasAvancadas($periodo);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Métricas Avançadas - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <script src="../../assets/js/chart.js"></script>
    <script src="../../assets/js/d3.min.js"></script>
</head>
<body>
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col">
                <h1>Métricas Avançadas de Qualidade</h1>
            </div>
            <div class="col">
                <select class="form-control" onchange="this.form.submit()" name="periodo">
                    <option value="7" <?php echo $periodo == 7 ? 'selected' : ''; ?>>7 dias</option>
                    <option value="30" <?php echo $periodo == 30 ? 'selected' : ''; ?>>30 dias</option>
                    <option value="90" <?php echo $periodo == 90 ? 'selected' : ''; ?>>90 dias</option>
                </select>
            </div>
        </div>

        <div class="row">
            <!-- Gráfico de Tendências -->
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Tendência de Qualidade</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="tendenciaChart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Análise de Pareto -->
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Análise de Pareto - Defeitos</h5>
                    </div>
                    <div class="card-body">
                        <div id="paretoChart"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Mapa de Calor -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Mapa de Calor - Ocorrências por Processo</h5>
                    </div>
                    <div class="card-body">
                        <div id="heatmapChart"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Inicialização dos gráficos avançados
        const ctx = document.getElementById('tendenciaChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($metricas['tendencia']['labels']); ?>,
                datasets: [{
                    label: 'Índice de Qualidade',
                    data: <?php echo json_encode($metricas['tendencia']['dados']); ?>,
                    borderColor: '#007bff',
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: false,
                        suggestedMin: 80
                    }
                }
            }
        });

        // Inicialização do gráfico de Pareto com D3.js
        const paretoData = <?php echo json_encode($metricas['pareto']); ?>;
        initParetoChart(paretoData);

        // Inicialização do mapa de calor
        const heatmapData = <?php echo json_encode($metricas['heatmap']); ?>;
        initHeatmap(heatmapData);
    </script>

    <script src="../../assets/js/metricas-qualidade.js"></script>
</body>
</html>
