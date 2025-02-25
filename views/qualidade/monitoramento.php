<?php
require_once '../../config/config.php';
require_once '../../core/Auth.php';
require_once '../../models/AlertasQualidade.php';
require_once '../../models/EscalonamentoAlertas.php';

$auth = new Auth();
$auth->requireAuth();

$alertas = new AlertasQualidade();
$escalonamento = new EscalonamentoAlertas();

$alertasAtivos = $alertas->getAlertas(1); // Últimas 24h
$estatisticas = $escalonamento->getEstatisticasEscalonamento();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Monitoramento em Tempo Real - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <script src="../../assets/js/chart.js"></script>
    <script src="../../assets/js/socketio.js"></script>
</head>
<body>
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col">
                <h1>Monitoramento em Tempo Real</h1>
            </div>
            <div class="col text-right">
                <div id="status-conexao" class="badge badge-success">
                    Conectado
                </div>
            </div>
        </div>

        <!-- Indicadores em Tempo Real -->
        <div class="row">
            <div class="col-md-3">
                <div class="card bg-warning">
                    <div class="card-body">
                        <h5>Alertas Ativos</h5>
                        <h2 id="total-alertas"><?php echo count($alertasAtivos); ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger">
                    <div class="card-body">
                        <h5>Escalonados</h5>
                        <h2 id="total-escalonados">
                            <?php echo count(array_filter($alertasAtivos, function($a) {
                                return $a['nivel_escalonamento'] != 'operador';
                            })); ?>
                        </h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista de Alertas em Tempo Real -->
        <div class="card mt-4">
            <div class="card-header">
                <h5>Alertas Ativos</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="tabela-alertas">
                        <thead>
                            <tr>
                                <th>Hora</th>
                                <th>Tipo</th>
                                <th>Descrição</th>
                                <th>Nível</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($alertasAtivos as $alerta): ?>
                                <tr id="alerta-<?php echo $alerta['id']; ?>"
                                    class="alerta-row <?php echo $alerta['criticidade']; ?>">
                                    <td><?php echo date('H:i', strtotime($alerta['data_geracao'])); ?></td>
                                    <td><?php echo $alerta['tipo']; ?></td>
                                    <td><?php echo $alerta['descricao']; ?></td>
                                    <td><?php echo ucfirst($alerta['nivel_escalonamento']); ?></td>
                                    <td><?php echo $alerta['status']; ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary"
                                                onclick="tratarAlerta(<?php echo $alerta['id']; ?>)">
                                            Tratar
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Conexão WebSocket para atualizações em tempo real
        const socket = io('<?php echo SOCKET_URL; ?>');
        
        socket.on('novo_alerta', function(alerta) {
            adicionarAlertaTabela(alerta);
            atualizarContadores();
        });
        
        socket.on('atualizacao_alerta', function(alerta) {
            atualizarAlertaTabela(alerta);
        });
        
        function tratarAlerta(id) {
            // Implementar tratamento do alerta
        }
        
        // Atualização automática a cada 30 segundos
        setInterval(function() {
            fetch('api/alertas/ativos')
                .then(res => res.json())
                .then(data => {
                    atualizarDados(data);
                });
        }, 30000);
    </script>
</body>
</html>
