<?php
require_once '../../config/config.php';
require_once '../../core/Auth.php';
require_once '../../models/Relatorio.php';
require_once '../../models/Financeiro.php';
require_once '../../core/Auditoria.php';

$auth = new Auth();
$auth->requireAuth();

if ($_SESSION['user_level'] < 2) {
    header('Location: index.php');
    exit();
}

$relatorio = new Relatorio();
$financeiro = new Financeiro();
$auditoria = new Auditoria();

// Dados para o dashboard
$hoje = date('Y-m-d');
$mesAtual = date('Y-m');
$dataInicio = date('Y-m-01');
$dataFim = date('Y-m-t');

$totaisMes = $relatorio->lucratividade($dataInicio, $dataFim);
$produtividade = $relatorio->produtividadeProducao($dataInicio, $dataFim);
$ultimasAuditorias = $auditoria->listar(['limit' => 10]);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Administrativo - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <script src="../../assets/js/chart.js"></script>
</head>
<body>
    <div class="container">
        <h1>Dashboard Administrativo</h1>
        
        <!-- Indicadores Principais -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5 class="card-title">Receita do Mês</h5>
                        <h3>R$ <?php echo number_format($totaisMes[0]['receita'] ?? 0, 2, ',', '.'); ?></h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success">
                    <div class="card-body">
                        <h5 class="card-title">Lucro do Mês</h5>
                        <h3>R$ <?php echo number_format($totaisMes[0]['lucro'] ?? 0, 2, ',', '.'); ?></h3>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Gráficos e Tabelas -->
        <div class="row">
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Produtividade por Operador</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="graficoProducao"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Log de Auditoria -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>Últimas Atividades</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            <?php foreach ($ultimasAuditorias as $log): ?>
                                <div class="list-group-item">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1"><?php echo $log['acao']; ?></h6>
                                        <small><?php echo date('d/m H:i', strtotime($log['created_at'])); ?></small>
                                    </div>
                                    <p class="mb-1"><?php echo $log['usuario_nome']; ?> - <?php echo $log['tabela']; ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Inicialização dos gráficos
        const ctxProducao = document.getElementById('graficoProducao').getContext('2d');
        new Chart(ctxProducao, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_column($produtividade, 'operador')); ?>,
                datasets: [{
                    label: 'Etapas Concluídas',
                    data: <?php echo json_encode(array_column($produtividade, 'etapas_concluidas')); ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
