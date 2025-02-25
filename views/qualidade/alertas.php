<?php
require_once '../../config/config.php';
require_once '../../core/Auth.php';
require_once '../../models/AlertasQualidade.php';

$auth = new Auth();
$auth->requireAuth();

$alertas = new AlertasQualidade();
$listaAlertas = $alertas->getAlertas(7);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Alertas de Qualidade - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-6">
                <h1>Alertas de Qualidade</h1>
            </div>
            <div class="col-md-6 text-right">
                <button class="btn btn-primary" onclick="verificarAlertas()">
                    Verificar Novos Alertas
                </button>
            </div>
        </div>

        <!-- Alertas Ativos -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>Alertas Ativos</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tipo</th>
                                <th>Descrição</th>
                                <th>Criticidade</th>
                                <th>Data</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($listaAlertas as $alerta): ?>
                                <tr class="alert-row <?php echo $alerta['criticidade']; ?>">
                                    <td><?php echo $alerta['tipo']; ?></td>
                                    <td><?php echo $alerta['descricao']; ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo $alerta['criticidade']; ?>">
                                            <?php echo ucfirst($alerta['criticidade']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($alerta['data_geracao'])); ?></td>
                                    <td><?php echo ucfirst($alerta['status']); ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-info" 
                                                onclick="verDetalhes(<?php echo $alerta['id']; ?>)">
                                            Detalhes
                                        </button>
                                        <?php if ($alerta['status'] == 'pendente'): ?>
                                            <button class="btn btn-sm btn-success" 
                                                    onclick="marcarResolvido(<?php echo $alerta['id']; ?>)">
                                                Resolver
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Detalhes -->
    <div class="modal fade" id="modalDetalhes">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalhes do Alerta</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <!-- Conteúdo carregado via AJAX -->
                </div>
            </div>
        </div>
    </div>

    <script src="../../assets/js/alertas-qualidade.js"></script>
</body>
</html>
