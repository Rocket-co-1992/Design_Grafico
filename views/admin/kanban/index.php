<?php
require_once '../../../config/config.php';
require_once '../../../core/Auth.php';
require_once '../../../models/KanbanManager.php';

$auth = new Auth();
$auth->requireAuth();

if ($_SESSION['user_level'] < 2) {
    header('Location: ../../dashboard/index.php');
    exit();
}

$kanban = new KanbanManager();
$quadro = $kanban->getQuadro();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Kanban - Gestão de Pedidos - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="../../../assets/css/style.css">
    <link rel="stylesheet" href="../../../assets/css/kanban.css">
    <script src="../../../assets/js/dragula.min.js"></script>
</head>
<body>
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col">
                <h1>Gestão de Pedidos - Kanban</h1>
            </div>
            <div class="col text-right">
                <button class="btn btn-primary" onclick="abrirFiltros()">
                    <i class="fas fa-filter"></i> Filtros
                </button>
            </div>
        </div>

        <div class="kanban-board">
            <?php foreach ($quadro['colunas'] as $coluna): ?>
                <div class="kanban-column" data-id="<?php echo $coluna['id']; ?>">
                    <div class="column-header" style="background: <?php echo $coluna['cor']; ?>">
                        <h5><?php echo $coluna['nome']; ?></h5>
                        <span class="badge badge-light">
                            <?php echo count($coluna['cartoes']); ?>
                            <?php if ($coluna['limite_cartoes']): ?>
                                /<?php echo $coluna['limite_cartoes']; ?>
                            <?php endif; ?>
                        </span>
                    </div>
                    
                    <div class="kanban-cards" id="coluna-<?php echo $coluna['id']; ?>">
                        <?php foreach ($coluna['cartoes'] as $cartao): ?>
                            <div class="kanban-card" data-id="<?php echo $cartao['id']; ?>">
                                <div class="card-header">
                                    <span class="prioridade prioridade-<?php echo $cartao['prioridade']; ?>"></span>
                                    <?php echo $cartao['titulo']; ?>
                                </div>
                                
                                <div class="card-body">
                                    <p><?php echo substr($cartao['descricao'], 0, 100); ?>...</p>
                                    
                                    <?php if ($cartao['etiquetas']): ?>
                                        <div class="etiquetas">
                                            <?php foreach (explode(',', $cartao['etiquetas']) as $etiqueta): ?>
                                                <span class="etiqueta"><?php echo $etiqueta; ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="card-footer">
                                    <?php if ($cartao['responsavel_nome']): ?>
                                        <span class="responsavel">
                                            <?php echo $cartao['responsavel_nome']; ?>
                                        </span>
                                    <?php endif; ?>
                                    
                                    <?php if ($cartao['prazo']): ?>
                                        <span class="prazo <?php echo strtotime($cartao['prazo']) < time() ? 'atrasado' : ''; ?>">
                                            <?php echo date('d/m', strtotime($cartao['prazo'])); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="../../../assets/js/kanban.js"></script>
</body>
</html>
