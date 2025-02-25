<?php
require_once '../../config/config.php';
require_once '../../core/Auth.php';
require_once '../../models/QualidadeEstatisticas.php';

$auth = new Auth();
$auth->requireAuth();

$qualidade = new QualidadeEstatisticas();
$periodo = $_GET['periodo'] ?? 30;
$indicadores = $qualidade->getIndicadoresGerais($periodo);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard de Qualidade - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <script src="../../assets/js/chart.js"></script>
</head>
<body>
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-8">
                <h1>Dashboard de Qualidade</h1>
            </div>
            <div class="col-md-4">
                <select class="form-control" onchange="this.form.submit()" name="periodo">
                    <option value="7" <?php echo $periodo == 7 ? 'selected' : ''; ?>>Última Semana</option>
                    <option value="30" <?php echo $periodo == 30 ? 'selected' : ''; ?>>Último Mês</option>
                    <option value="90" <?php echo $periodo == 90 ? 'selected' : ''; ?>>Último Trimestre</option>
                </select>
            </div>
        </div>

        <!-- KPIs Principais -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5>Taxa de Aprovação</h5>
                        <h2><?php echo number_format($indicadores['taxa_aprovacao']['taxa'], 1); ?>%</h2>
                        <small><?php echo $indicadores['taxa_aprovacao']['aprovados']; ?> de 
                               <?php echo $indicadores['taxa_aprovacao']['total']; ?> inspeções</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5>Tempo Médio Inspeção</h5>
                        <h2><?php echo number_format($indicadores['tempo_medio_inspecao'], 0); ?> min</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos -->
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Defeitos Mais Frequentes</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="defeitosChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Desempenho por Operador</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="operadoresChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabela de Defeitos -->
        <div class="card">
            <div class="card-header">
                <h5>Análise de Defeitos</h5>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Tipo de Defeito</th>
                            <th>Ocorrências</th>
                            <th>% do Total</th>
                            <th>Tendência</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($indicadores['defeitos_frequentes'] as $defeito): ?>
                            <tr>
                                <td><?php echo $defeito['descricao']; ?></td>
                                <td><?php echo $defeito['ocorrencias']; ?></td>
                                <td><?php echo number_format($defeito['percentual'], 1); ?>%</td>
                                <td>
                                    <span class="trend-indicator">
                                        <?php echo $defeito['ocorrencias'] > 5 ? '↑' : '↓'; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Inicialização dos gráficos
        new Chart(document.getElementById('defeitosChart'), {
            type: 'pareto',
            data: {
                labels: <?php echo json_encode(array_column($indicadores['defeitos_frequentes'], 'descricao')); ?>,
                datasets: [{
                    type: 'bar',
                    label: 'Ocorrências',
                    data: <?php echo json_encode(array_column($indicadores['defeitos_frequentes'], 'ocorrencias')); ?>
                }]
            }
        });

        new Chart(document.getElementById('operadoresChart'), {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_column($indicadores['desempenho_operadores'], 'operador')); ?>,
                datasets: [{
                    label: 'Taxa de Aprovação (%)',
                    data: <?php echo json_encode(array_column($indicadores['desempenho_operadores'], 'taxa_aprovacao')); ?>
                }]
            }
        });
    </script>
</body>
</html>
