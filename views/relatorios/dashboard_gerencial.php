<?php
require_once '../../config/config.php';
require_once '../../core/Auth.php';
require_once '../../models/RelatorioAvancado.php';

$auth = new Auth();
$auth->requireAuth();

// Verificar permissão
if ($_SESSION['user_level'] < 2) {
    header('Location: ../dashboard/index.php');
    exit();
}

$relatorio = new RelatorioAvancado();

$dataInicio = $_GET['data_inicio'] ?? date('Y-m-01');
$dataFim = $_GET['data_fim'] ?? date('Y-m-t');

$kpis = $relatorio->kpis($dataInicio, $dataFim);
$previsaoFaturamento = $relatorio->previsaoFaturamento();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Gerencial - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <script src="../../assets/js/chart.js"></script>
</head>
<body>
    <div class="container">
        <h1>Dashboard Gerencial</h1>
        
        <!-- Filtros -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row">
                    <div class="col-md-4">
                        <input type="date" name="data_inicio" value="<?php echo $dataInicio; ?>" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <input type="date" name="data_fim" value="<?php echo $dataFim; ?>" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">Atualizar</button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- KPIs -->
        <div class="row mb-4">
            <!-- Vendas -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Vendas</h5>
                    </div>
                    <div class="card-body">
                        <p>Pedidos: <?php echo $kpis['vendas']['total_pedidos']; ?></p>
                        <p>Valor Total: R$ <?php echo number_format($kpis['vendas']['valor_total'], 2, ',', '.'); ?></p>
                        <p>Ticket Médio: R$ <?php echo number_format($kpis['vendas']['ticket_medio'], 2, ',', '.'); ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Produção -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Produção</h5>
                    </div>
                    <div class="card-body">
                        <p>Ordens: <?php echo $kpis['producao']['total_ordens']; ?></p>
                        <p>Tempo Médio: <?php echo round($kpis['producao']['tempo_medio_producao'], 1); ?>h</p>
                        <p>Atrasadas: <?php echo $kpis['producao']['ordens_atrasadas']; ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Financeiro -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Financeiro</h5>
                    </div>
                    <div class="card-body">
                        <p>Receita: R$ <?php echo number_format($kpis['financeiro']['receita_total'], 2, ',', '.'); ?></p>
                        <p>Despesa: R$ <?php echo number_format($kpis['financeiro']['despesa_total'], 2, ',', '.'); ?></p>
                        <p>Resultado: R$ <?php echo number_format($kpis['financeiro']['receita_total'] - $kpis['financeiro']['despesa_total'], 2, ',', '.'); ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Gráfico de Previsão -->
        <div class="card">
            <div class="card-header">
                <h5>Previsão de Faturamento</h5>
            </div>
            <div class="card-body">
                <canvas id="graficoPrevisao"></canvas>
            </div>
        </div>
    </div>
    
    <script>
        // Inicialização do gráfico de previsão
        const ctxPrevisao = document.getElementById('graficoPrevisao').getContext('2d');
        new Chart(ctxPrevisao, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_column($previsaoFaturamento, 'mes')); ?>,
                datasets: [{
                    label: 'Previsão de Faturamento',
                    data: <?php echo json_encode(array_column($previsaoFaturamento, 'valor_previsto')); ?>,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            }
        });
    </script>
</body>
</html>
