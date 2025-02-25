<?php
require_once '../../config/config.php';
require_once '../../core/Auth.php';
require_once '../../models/ControleEquipamentos.php';

$auth = new Auth();
$auth->requireAuth();

$controle = new ControleEquipamentos();
$equipamentos = $controle->verificarManutencoesPendentes();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Alertas de Manutenção - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Alertas de Manutenção</h1>
        
        <?php if (!empty($equipamentos)): ?>
            <div class="alert alert-warning">
                Existem <?php echo count($equipamentos); ?> equipamentos com manutenção pendente!
            </div>
            
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Equipamento</th>
                            <th>Última Manutenção</th>
                            <th>Manutenção Prevista</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($equipamentos as $equip): ?>
                            <tr>
                                <td>
                                    <?php echo $equip['nome']; ?>
                                    <small class="text-muted d-block"><?php echo $equip['modelo']; ?></small>
                                </td>
                                <td><?php echo date('d/m/Y', strtotime($equip['ultima_manutencao'])); ?></td>
                                <td>
                                    <span class="text-danger">
                                        <?php echo date('d/m/Y', strtotime($equip['proxima_manutencao'])); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge badge-warning">Manutenção Pendente</span>
                                </td>
                                <td>
                                    <a href="registrar_manutencao.php?id=<?php echo $equip['id']; ?>" 
                                       class="btn btn-primary btn-sm">
                                        Registrar Manutenção
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-success">
                Não há equipamentos com manutenção pendente no momento.
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
