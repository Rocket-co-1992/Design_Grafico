<?php
require_once '../../config/config.php';
require_once '../../core/Auth.php';
require_once '../../models/ControleQualidade.php';

$auth = new Auth();
$auth->requireAuth();

$controle = new ControleQualidade();
$inspecao_id = $_GET['id'] ?? 0;
$inspecao = $controle->getInspecao($inspecao_id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controle->registrarResultado($inspecao_id, $_POST['resultados']);
    header('Location: inspecao_qualidade.php?id=' . $inspecao_id . '&success=1');
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Inspeção de Qualidade - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Inspeção de Qualidade - OP #<?php echo $inspecao['ordem_numero']; ?></h1>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                Resultados registrados com sucesso!
            </div>
        <?php endif; ?>
        
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <strong>Inspetor:</strong> <?php echo $inspecao['inspetor_nome']; ?>
                    </div>
                    <div class="col-md-4">
                        <strong>Data:</strong> <?php echo date('d/m/Y H:i', strtotime($inspecao['data_inspecao'])); ?>
                    </div>
                    <div class="col-md-4">
                        <strong>Status:</strong>
                        <span class="badge badge-<?php echo $inspecao['status'] == 'aprovado' ? 'success' : 'danger'; ?>">
                            <?php echo ucfirst($inspecao['status']); ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <form method="POST" id="formInspecao">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Verificação</th>
                            <th>Resultado</th>
                            <th>Observações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($inspecao['resultados'] as $item): ?>
                            <tr>
                                <td><?php echo $item['descricao']; ?></td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <input type="radio" 
                                               name="resultados[<?php echo $item['id']; ?>][conforme]" 
                                               value="1" 
                                               <?php echo $item['conforme'] ? 'checked' : ''; ?> 
                                               required>
                                        <label class="btn btn-outline-success">Conforme</label>
                                        
                                        <input type="radio" 
                                               name="resultados[<?php echo $item['id']; ?>][conforme]" 
                                               value="0" 
                                               <?php echo !$item['conforme'] ? 'checked' : ''; ?>>
                                        <label class="btn btn-outline-danger">Não Conforme</label>
                                    </div>
                                </td>
                                <td>
                                    <textarea name="resultados[<?php echo $item['id']; ?>][observacao]" 
                                              class="form-control"><?php echo $item['observacao']; ?></textarea>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Salvar Inspeção</button>
            </div>
        </form>
    </div>
</body>
</html>
