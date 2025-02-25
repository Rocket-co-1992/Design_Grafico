<?php
require_once '../../config/config.php';
require_once '../../core/Auth.php';
require_once '../../models/Recompensa.php';
require_once '../../models/Fidelidade.php';

$auth = new Auth();
$auth->requireAuth();

$recompensa = new Recompensa();
$fidelidade = new Fidelidade();

$pontos = $fidelidade->getPontos($_SESSION['user_id']);
$recompensas = $recompensa->listarDisponiveis();
$resgates = $recompensa->getResgatesCliente($_SESSION['user_id']);

// Processar resgate
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resgatar'])) {
    try {
        $recompensa->resgatar($_SESSION['user_id'], $_POST['recompensa_id']);
        $mensagem = ['tipo' => 'success', 'texto' => 'Resgate realizado com sucesso!'];
        
        // Recarrega dados
        $pontos = $fidelidade->getPontos($_SESSION['user_id']);
        $resgates = $recompensa->getResgatesCliente($_SESSION['user_id']);
    } catch (Exception $e) {
        $mensagem = ['tipo' => 'danger', 'texto' => $e->getMessage()];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Recompensas - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-6">
                <h1>Recompensas Disponíveis</h1>
            </div>
            <div class="col-md-6 text-right">
                <h3>Seus Pontos: <span class="text-success"><?php echo $pontos['pontos']; ?></span></h3>
            </div>
        </div>
        
        <?php if (isset($mensagem)): ?>
            <div class="alert alert-<?php echo $mensagem['tipo']; ?>">
                <?php echo $mensagem['texto']; ?>
            </div>
        <?php endif; ?>
        
        <!-- Recompensas Disponíveis -->
        <div class="row mb-4">
            <?php foreach ($recompensas as $r): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $r['nome']; ?></h5>
                            <p class="card-text"><?php echo $r['descricao']; ?></p>
                            <div class="pontos-necessarios">
                                <?php echo $r['pontos_necessarios']; ?> pontos
                            </div>
                            
                            <?php if ($pontos['pontos'] >= $r['pontos_necessarios']): ?>
                                <form method="POST">
                                    <input type="hidden" name="recompensa_id" value="<?php echo $r['id']; ?>">
                                    <button type="submit" name="resgatar" class="btn btn-primary">
                                        Resgatar
                                    </button>
                                </form>