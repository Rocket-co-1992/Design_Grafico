<?php
require_once '../../config/config.php';
require_once '../../core/Auth.php';
require_once '../../models/Atendimento.php';

$auth = new Auth();
$auth->requireAuth();

$atendimento = new Atendimento();

// Verificar ID do chamado
if (!isset($_GET['id'])) {
    header('Location: atendimento.php');
    exit;
}

// Carregar dados do chamado
$chamado_id = $_GET['id'];
$chamado = $atendimento->getChamado($chamado_id);

// Verificar se o chamado pertence ao cliente
if ($chamado['cliente_id'] != $_SESSION['user_id']) {
    header('Location: atendimento.php');
    exit;
}

// Processar nova resposta
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $atendimento->responderChamado(
            $chamado_id,
            $_POST['resposta'],
            $_SESSION['user_id']
        );
        $mensagem = ['tipo' => 'success', 'texto' => 'Resposta enviada com sucesso!'];
    } catch (Exception $e) {
        $mensagem = ['tipo' => 'danger', 'texto' => $e->getMessage()];
    }
}

// Carregar respostas
$respostas = $atendimento->getRespostas($chamado_id);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Chamado #<?php echo $chamado_id; ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-8">
                <h1>Chamado #<?php echo str_pad($chamado_id, 6, '0', STR_PAD_LEFT); ?></h1>
            </div>
            <div class="col-md-4 text-right">
                <a href="atendimento.php" class="btn btn-secondary">Voltar</a>
            </div>
        </div>

        <?php if (isset($mensagem)): ?>
            <div class="alert alert-<?php echo $mensagem['tipo']; ?>">
                <?php echo $mensagem['texto']; ?>
            </div>
        <?php endif; ?>

        <!-- Detalhes do Chamado -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><?php echo $chamado['assunto']; ?></h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Status:</strong> 
                            <span class="badge badge-<?php 
                                echo $chamado['status'] == 'aberto' ? 'warning' : 
                                    ($chamado['status'] == 'respondido' ? 'info' : 'success'); 
                            ?>">
                                <?php echo ucfirst($chamado['status']); ?>
                            </span>
                        </p>
                        <p><strong>Data de Abertura:</strong> 
                            <?php echo date('d/m/Y H:i', strtotime($chamado['created_at'])); ?>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Prioridade:</strong>
                            <?php echo ucfirst($chamado['prioridade']); ?>
                        </p>
                    </div>
                </div>
                <hr>
                <div class="chamado-descricao">
                    <?php echo nl2br(htmlspecialchars($chamado['descricao'])); ?>
                </div>
            </div>
        </div>

        <!-- Histórico de Respostas -->
        <div class="mt-4">
            <h3>Histórico de Respostas</h3>
            <?php foreach ($respostas as $resp): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="resposta-header">
                            <strong><?php echo $resp['usuario_nome']; ?></strong>
                            <small class="text-muted">
                                <?php echo date('d/m/Y H:i', strtotime($resp['created_at'])); ?>
                            </small>
                        </div>
                        <div class="resposta-conteudo mt-2">
                            <?php echo nl2br(htmlspecialchars($resp['resposta'])); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Nova Resposta -->
        <?php if ($chamado['status'] != 'fechado'): ?>
            <div class="card mt-4">
                <div class="card-body">
                    <h4>Adicionar Resposta</h4>
                    <form method="POST">
                        <div class="form-group">
                            <textarea name="resposta" class="form-control" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Enviar Resposta</button>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
