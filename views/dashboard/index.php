<?php
require_once '../../config/config.php';
require_once '../../core/Auth.php';
require_once '../../models/Financeiro.php';
require_once '../../models/Producao.php';
require_once '../../models/Pedido.php';

$auth = new Auth();
$auth->requireAuth();

$financeiro = new Financeiro();
$producao = new Producao();
$pedido = new Pedido();

// Obtém estatísticas
$hoje = date('Y-m-d');
$mesAtual = date('Y-m');

$pedidosHoje = $pedido->contarPedidosPorPeriodo($hoje, $hoje);
$pedidosMes = $pedido->contarPedidosPorPeriodo($mesAtual.'-01', $mesAtual.'-31');
$producoesAtivas = $producao->contarOrdensPorStatus('em_producao');
$faturamentoMes = $financeiro->getFaturamentoPorPeriodo($mesAtual.'-01', $mesAtual.'-31');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Dashboard</h1>
        
        <div class="row">
            <!-- Card Pedidos -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Pedidos Hoje</h5>
                        <h2><?php echo $pedidosHoje; ?></h2>
                        <p>Total do mês: <?php echo $pedidosMes; ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Card Produção -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Em Produção</h5>
                        <h2><?php echo $producoesAtivas; ?></h2>
                    </div>
                </div>
            </div>
            
            <!-- Card Faturamento -->
            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Faturamento Mês</h5>
                        <h2>R$ <?php echo number_format($faturamentoMes, 2, ',', '.'); ?></h2>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Gráfico de Pedidos -->
        <div class="row mt-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Evolução de Pedidos</h5>
                        <canvas id="graficoVendas"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="../../assets/js/chart.js"></script>
    <script>
        // Implementação dos gráficos aqui
    </script>
</body>
</html>
