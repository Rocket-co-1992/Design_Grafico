<?php
require_once '../../config/config.php';
require_once '../../core/Auth.php';
require_once '../../models/Fidelidade.php';

$auth = new Auth();
$auth->requireAuth();

$fidelidade = new Fidelidade();
$programa = $fidelidade->getPontos($_SESSION['user_id']);
$historico = $fidelidade->getHistoricoPontos($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Programa de Fidelidade - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-6">
                <h1>Programa de Fidelidade</h1>
            </div>
            <div class="col-md-6 text-right">
                <h3>Seus Pontos: <span class="text-success"><?php echo $programa['pontos']; ?></span></h3>
                <p>Nível: <span class="badge badge-primary"><?php echo ucfirst($programa['nivel']); ?></span></p>
            </div>
        </div>
        
        <!-- Níveis e Benefícios -->
        <div class="card mb-4">
            <div class="card-header">
                <h4>Níveis e Benefícios</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="nivel-card <?php echo $programa['nivel'] == 'bronze' ? 'ativo' : ''; ?>">
                            <h5>Bronze</h5>
                            <p>0-999 pontos</p>
                            <ul>
                                <li>Pontos básicos em compras</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="nivel-card <?php echo $programa['nivel'] == 'prata' ? 'ativo' : ''; ?>">
                            <h5>Prata</h5>
                            <p>1.000-4.999 pontos</p>
                            <ul>
                                <li>1.5x pontos em compras</li>
                                <li>5% desconto</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="nivel-card <?php echo $programa['nivel'] == 'ouro' ? 'ativo' : ''; ?>">
                            <h5>Ouro</h5>
                            <p>5.000-9.999 pontos</p>
                            <ul>
                                <li>2x pontos em compras</li>
                                <li>10% desconto</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="nivel-card <?php echo $programa['nivel'] == 'diamante' ? 'ativo' : ''; ?>">
                            <h5>Diamante</h5>
                            <p>10.000+ pontos</p>
                            <ul>
                                <li>3x pontos em compras</li>
                                <li>15% desconto</li>
                                <li>Frete grátis</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Histórico de Pontos -->
        <div class="card">
            <div class="card-header">
                <h4>Histórico de Pontos</h4>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Tipo</th>
                            <th>Descrição</th>
                            <th>Pontos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($historico as $h): ?>
                            <tr>
                                <td><?php echo date('d/m/Y H:i', strtotime($h['created_at'])); ?></td>
                                <td><?php echo ucfirst($h['tipo']); ?></td>
                                <td><?php echo $h['descricao']; ?></td>
                                <td class="<?php echo $h['tipo'] == 'credito' ? 'text-success' : 'text-danger'; ?>">
                                    <?php echo $h['tipo'] == 'credito' ? '+' : '-'; ?><?php echo $h['pontos']; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
