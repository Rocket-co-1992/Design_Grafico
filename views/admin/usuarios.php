<?php
require_once '../../config/config.php';
require_once '../../core/Auth.php';
require_once '../../models/Usuario.php';

$auth = new Auth();
$auth->requireAuth();

if ($_SESSION['user_level'] < 2) {
    header('Location: ' . BASE_URL . '/dashboard.php');
    exit();
}

$usuario = new Usuario();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'criar':
                $usuario->criar($_POST);
                break;
            case 'atualizar':
                $usuario->atualizar($_POST['id'], $_POST);
                break;
            case 'excluir':
                $usuario->excluir($_POST['id']);
                break;
        }
    }
}

$usuarios = $usuario->listarTodos();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Administração de Usuários - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Administração de Usuários</h1>
        
        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#novoUsuario">
            Novo Usuário
        </button>
        
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Nível</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $u): ?>
                <tr>
                    <td><?php echo $u['id']; ?></td>
                    <td><?php echo $u['nome']; ?></td>
                    <td><?php echo $u['email']; ?></td>
                    <td><?php echo $u['nivel']; ?></td>
                    <td>
                        <button class="btn btn-sm btn-info" onclick="editarUsuario(<?php echo htmlspecialchars(json_encode($u)); ?>)">
                            Editar
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="excluirUsuario(<?php echo $u['id']; ?>)">
                            Excluir
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Modal de Novo Usuário -->
    <div class="modal fade" id="novoUsuario">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <input type="hidden" name="action" value="criar">
                    <div class="modal-header">
                        <h5 class="modal-title">Novo Usuário</h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nome</label>
                            <input type="text" name="nome" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Senha</label>
                            <input type="password" name="senha" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Nível de Acesso</label>
                            <select name="nivel" class="form-control">
                                <option value="1">Usuário</option>
                                <option value="2">Administrador</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Salvar</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
