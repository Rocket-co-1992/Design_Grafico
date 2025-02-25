<?php
require_once '../../config/config.php';
require_once '../../core/Auth.php';
require_once '../../models/Pedido.php';
require_once '../../models/RastreamentoPedido.php';

$auth = new Auth();
$auth->requireAuth();

$pedido = new Pedido();
$rastreamento = new RastreamentoPedido();

$pedidos = $pedido->listarPorCliente($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Meus Pedidos - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Meus Pedidos</h1>
        
        <?php if (empty($pedidos)): ?>
            <div class="alert alert-info">
                Você ainda não tem pedidos.
            </div>
        <?php else: ?>
            <?php foreach ($pedidos as $ped): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <div class="row">
                            <div class="col">
                                <h5>Pedido #<?php echo $ped['id']; ?></h5>
                                <small><?php echo date('d/m/Y H:i', strtotime($ped['created_at'])); ?></small>
                            </div>
                            <div class="col text-right">
                                <span class="badge badge-<?php echo $ped['status'] == 'concluido' ? 'success' : 'primary'; ?>">
                                    <?php echo ucfirst($ped['status']); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Detalhes do Pedido -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h6>Itens do Pedido</h6>
                                <ul class="list-unstyled">
                                    <?php foreach ($ped['itens'] as $item): ?>
                                        <li>
                                            <?php echo $item['quantidade']; ?>x 
                                            <?php echo $item['nome']; ?> - 
                                            R$ <?php echo number_format($item['valor_total'], 2, ',', '.'); ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6>Valor Total</h6>
                                <h3>R$ <?php echo number_format($ped['valor_total'], 2, ',', '.'); ?></h3>
                            </div>
                        </div>
                        
                        <!-- Timeline de Rastreamento -->
                        <h6>Status do Pedido</h6>
                        <div class="timeline">
                            <?php foreach ($rastreamento->getHistorico($ped['id']) as $status): ?>
                                <div class="timeline-item">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <h6><?php echo ucfirst($status['status']); ?></h6>
                                        <p><?php echo $status['descricao']; ?></p>
                                        <small><?php echo date('d/m/Y H:i', strtotime($status['created_at'])); ?></small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>
