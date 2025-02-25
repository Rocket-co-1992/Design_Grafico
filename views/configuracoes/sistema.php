<?php
require_once '../../config/config.php';
require_once '../../core/Auth.php';
require_once '../../models/Configuracao.php';
require_once '../../core/Backup.php';
require_once '../../core/Logger.php';

$auth = new Auth();
$auth->requireAuth();

$config = new Configuracao();
$backup = new Backup();
$logger = new Logger();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['backup'])) {
        $backupFile = $backup->gerarBackup();
        $logger->registrar('backup', 'Backup gerado com sucesso', ['arquivo' => $backupFile]);
        $mensagem = "Backup gerado com sucesso!";
    } elseif (isset($_POST['configuracoes'])) {
        foreach ($_POST['config'] as $chave => $valor) {
            $config->salvar($chave, $valor);
        }
        $logger->registrar('config', 'Configurações atualizadas');
        $mensagem = "Configurações salvas com sucesso!";
    }
}

$configuracoes = $config->listarTodas();
$backups = $backup->listarBackups();
$logs = $logger->lerLogs();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Configurações do Sistema - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Configurações do Sistema</h1>
        
        <?php if (isset($mensagem)): ?>
            <div class="alert alert-success"><?php echo $mensagem; ?></div>
        <?php endif; ?>
        
        <!-- Configurações Gerais -->
        <div class="card mb-4">
            <div class="card-header">
                <h3>Configurações Gerais</h3>
            </div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="configuracoes" value="1">
                    <!-- Campos de configuração -->
                    <div class="mb-3">
                        <label>Nome da Empresa</label>
                        <input type="text" name="config[empresa_nome]" class="form-control" 
                               value="<?php echo $config->obter('empresa_nome'); ?>">
                    </div>
                    <div class="mb-3">
                        <label>Email para Notificações</label>
                        <input type="email" name="config[email_notificacao]" class="form-control"
                               value="<?php echo $config->obter('email_notificacao'); ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Salvar Configurações</button>
                </form>
            </div>
        </div>
        
        <!-- Backup do Sistema -->
        <div class="card mb-4">
            <div class="card-header">
                <h3>Backup do Sistema</h3>
            </div>
            <div class="card-body">
                <form method="POST">
                    <input type="hidden" name="backup" value="1">
                    <button type="submit" class="btn btn-warning">Gerar Novo Backup</button>
                </form>
                
                <table class="table mt-3">
                    <thead>
                        <tr>
                            <th>Arquivo</th>
                            <th>Data</th>
                            <th>Tamanho</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($backups as $backup): ?>
                            <tr>
                                <td><?php echo $backup['arquivo']; ?></td>
                                <td><?php echo $backup['data']; ?></td>
                                <td><?php echo $backup['tamanho']; ?></td>
                                <td>
                                    <a href="download_backup.php?file=<?php echo $backup['arquivo']; ?>" 
                                       class="btn btn-sm btn-info">Download</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Logs do Sistema -->
        <div class="card">
            <div class="card-header">
                <h3>Logs do Sistema</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Data/Hora</th>
                                <th>Tipo</th>
                                <th>Usuário</th>
                                <th>Mensagem</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($logs as $log): ?>
                                <tr>
                                    <td><?php echo $log['timestamp']; ?></td>
                                    <td><?php echo $log['tipo']; ?></td>
                                    <td><?php echo $log['usuario_id']; ?></td>
                                    <td><?php echo $log['mensagem']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
