<?php
require_once '../../config/config.php';
require_once '../../core/Auth.php';
require_once '../../models/AlertasQualidade.php';

$auth = new Auth();
$auth->requireAuth();

$alerta_id = $_GET['id'] ?? 0;
$alertas = new AlertasQualidade();
$alerta = $alertas->getAlerta($alerta_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resultado = $alertas->tratarAlerta($alerta_id, $_POST);
    if ($resultado['success']) {
        header('Location: monitoramento.php?success=1');
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tratar Alerta - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Tratar Alerta #<?php echo $alerta_id; ?></h1>

        <div class="card mb-4">
            <div class="card-header">
                <h5>Detalhes do Alerta</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Tipo:</strong> <?php echo $alerta['tipo']; ?></p>
                        <p><strong>Data:</strong> <?php echo date('d/m/Y H:i', strtotime($alerta['data_geracao'])); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Status:</strong> <?php echo ucfirst($alerta['status']); ?></p>
                        <p><strong>Nível:</strong> <?php echo ucfirst($alerta['nivel_escalonamento']); ?></p>
                    </div>
                </div>
                <div class="alert alert-warning mt-3">
                    <?php echo $alerta['descricao']; ?>
                </div>
            </div>
        </div>

        <form method="POST" class="card">
            <div class="card-header">
                <h5>Análise e Tratamento</h5>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label>Causa Raiz</label>
                    <textarea name="causa_raiz" class="form-control" rows="3" required></textarea>
                </div>

                <div class="form-group">
                    <label>Ação Imediata</label>
                    <textarea name="acao_imediata" class="form-control" rows="3" required></textarea>
                </div>

                <div class="form-group">
                    <label>Ação Corretiva</label>
                    <textarea name="acao_corretiva" class="form-control" rows="3" required></textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Responsável</label>
                            <select name="responsavel_id" class="form-control" required>
                                <?php foreach ($alertas->getResponsaveis() as $resp): ?>
                                    <option value="<?php echo $resp['id']; ?>">
                                        <?php echo $resp['nome']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Prazo para Conclusão</label>
                            <input type="date" name="prazo_conclusao" class="form-control" required>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Registrar Tratamento</button>
            </div>
        </form>
    </div>
</body>
</html>
