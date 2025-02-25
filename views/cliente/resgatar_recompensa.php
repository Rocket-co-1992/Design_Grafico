<?php
require_once '../../config/config.php';
require_once '../../core/Auth.php';
require_once '../../controllers/RecompensaController.php';
require_once '../../models/Recompensa.php';
require_once '../../models/Fidelidade.php';

$auth = new Auth();
$auth->requireAuth();

$controller = new RecompensaController();
$recompensa = new Recompensa();
$fidelidade = new Fidelidade();

$recompensa_id = $_GET['id'] ?? 0;
$detalhes = $recompensa->buscarPorId($recompensa_id);
$pontos_cliente = $fidelidade->getPontos($_SESSION['user_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resultado = $controller->processarResgate([
        'cliente_id' => $_SESSION['user_id'],
        'recompensa_id' => $recompensa_id
    ]);
    
    if ($resultado['success']) {
        header('Location: recompensas.php?success=1');
        exit;
    }
    
    $erro = $resultado['message'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Resgatar Recompensa - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-8">
                <h1>Resgatar Recompensa</h1>
            </div>
            <div class="col-md-4 text-right">
                <h4>Seus Pontos: <span class="text-success"><?php echo $pontos_cliente['pontos']; ?></span></h4>
            </div>
        </div>

        <?php if (isset($erro)): ?>
            <div class="alert alert-danger">
                <?php echo $erro; ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?php echo $detalhes['nome']; ?></h5>
                <p class="card-text"><?php echo $detalhes['descricao']; ?></p>
                
                <div class="alert alert-info">
                    <strong>Pontos necessários:</strong> <?php echo $detalhes['pontos_necessarios']; ?> pontos
                </div>
                
                <?php if ($pontos_cliente['pontos'] >= $detalhes['pontos_necessarios']): ?>
                    <form method="POST" onsubmit="return confirm('Confirma o resgate desta recompensa?');">
                        <button type="submit" class="btn btn-primary btn-lg">
                            Confirmar Resgate
                        </button>
                    </form>
                <?php else: ?>
                    <div class="alert alert-warning">
                        Você não possui pontos suficientes para esta recompensa.
                        Faltam <?php echo $detalhes['pontos_necessarios'] - $pontos_cliente['pontos']; ?> pontos.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
