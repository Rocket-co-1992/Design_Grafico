<?php
require_once '../../../config/config.php';
require_once '../../../core/Auth.php';
require_once '../../../models/Fidelidade.php';
require_once '../../../models/Recompensa.php';

$auth = new Auth();
$auth->requireAuth();

if ($_SESSION['user_level'] < 2) {
    header('Location: ../../dashboard/index.php');
    exit();
}

$fidelidade = new Fidelidade();
$recompensa = new Recompensa();

$estatisticas = $fidelidade->getEstatisticas();
$recompensas_ativas = $recompensa->listarDisponiveis();
$ultimos_resgates = $recompensa->getUltimosResgates(10);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Fidelidade - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../../../assets/css/style.css">
    <script src="../../../assets/js/chart.js"></script>
</head>
<body>
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-6">
                <h1>Dashboard do Programa de Fidelidade</h1>
            </div>
            <div class="col-md-6 text-right">
                <a href="recompensas/criar.php" class="btn btn-primary">Nova Recompensa</a>
            </div>
        </div>

        <!-- Cards de Estatísticas -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5>Total de Clientes</h5>
                        <h2><?php echo $estatisticas['total_clientes']; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5>Pontos Ativos</h5>
                        <h2><?php echo number_format($estatisticas['pontos_ativos'], 0, ',', '.'); ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h5>Resgates no Mês</h5>
                        <h2><?php echo $estatisticas['resgates_mes']; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h5>Recompensas Ativas</h5>
                        <h2><?php echo count($recompensas_ativas); ?></h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos e Tabelas -->
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Distribuição de Níveis</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="niveisChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Últimos Resgates</h5>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Cliente</th>
                                    <th>Recompensa</th>
                                    <th>Pontos</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($ultimos_resgates as $resgate): ?>
                                    <tr>
                                        <td><?php echo date('d/m/Y', strtotime($resgate['data_resgate'])); ?></td>
                                        <td><?php echo $resgate['cliente_nome']; ?></td>
                                        <td><?php echo $resgate['recompensa_nome']; ?></td>
                                        <td><?php echo $resgate['pontos_usados']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Inicialização dos gráficos
        const ctxNiveis = document.getElementById('niveisChart').getContext('2d');
        new Chart(ctxNiveis, {
            type: 'pie',
            data: {
                labels: ['Bronze', 'Prata', 'Ouro', 'Diamante'],
                datasets: [{
                    data: <?php echo json_encode($estatisticas['distribuicao_niveis']); ?>,
                    backgroundColor: ['#cd7f32', '#c0c0c0', '#ffd700', '#b9f2ff']
                }]
            }
        });
    </script>
</body>
</html>
