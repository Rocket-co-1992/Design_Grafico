<?php
require_once '../../config/config.php';
require_once '../../core/Auth.php';
require_once '../../models/Atendimento.php';

$auth = new Auth();
$auth->requireAuth();

$atendimento = new Atendimento();

// Processar novo chamado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = [
        'cliente_id' => $_SESSION['user_id'],
        'assunto' => $_POST['assunto'],
        'descricao' => $_POST['descricao'],
        'prioridade' => $_POST['prioridade']
    ];
    
    try {
        if ($atendimento->criarChamado($dados)) {
            $mensagem = ['tipo' => 'success', 'texto' => 'Chamado criado com sucesso!'];
        }
    } catch (Exception $e) {
        $mensagem = ['tipo' => 'danger', 'texto' => $e->getMessage()];
    }
}

// Listar chamados do cliente
$chamados = $atendimento->listarChamados(['cliente_id' => $_SESSION['user_id']]);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Atendimento - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-8">
                <h1>Meus Chamados</h1>
            </div>
            <div class="col-md-4 text-right">
                <button class="btn btn-primary" data-toggle="modal" data-target="#novoChamado">
                    Abrir Novo Chamado
                </button>
            </div>
        </div>
        
        <?php if (isset($mensagem)): ?>
            <div class="alert alert-<?php echo $mensagem['tipo']; ?>">
                <?php echo $mensagem['texto']; ?>
            </div>
        <?php endif; ?>
        
        <!-- Lista de Chamados -->
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Protocolo</th>
                        <th>Assunto</th>
                        <th>Status</th>
                        <th>Data</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($chamados as $chamado): ?>
                        <tr>
                            <td>#<?php echo str_pad($chamado['id'], 6, '0', STR_PAD_LEFT); ?></td>
                            <td><?php echo $chamado['assunto']; ?></td>
                            <td>
                                <span class="badge badge-<?php 
                                    echo $chamado['status'] == 'aberto' ? 'warning' : 
                                        ($chamado['status'] == 'respondido' ? 'info' : 'success'); 
                                ?>">
                                    <?php echo ucfirst($chamado['status']); ?>
                                </span>
                            </td>
                            <td><?php echo date('d/m/Y H:i', strtotime($chamado['created_at'])); ?></td>
                            <td>
                                <a href="ver_chamado.php?id=<?php echo $chamado['id']; ?>" 
                                   class="btn btn-sm btn-info">
                                    Ver Detalhes
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Modal Novo Chamado -->
    <div class="modal fade" id="novoChamado">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Novo Chamado</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Assunto</label>
                            <input type="text" name="assunto" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Descrição</label>
                            <textarea name="descricao" class="form-control" rows="5" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Prioridade</label>
                            <select name="prioridade" class="form-control">
                                <option value="baixa">Baixa</option>
                                <option value="media">Média</option>
                                <option value="alta">Alta</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Enviar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
