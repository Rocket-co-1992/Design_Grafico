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
    <div class="kanban-container">
        <header class="kanban-header">
            <h1>Gestão de Pedidos</h1>
            <div class="header-actions">
                <button onclick="filtrarCards()" class="btn btn-outline-primary">
                    <i class="fas fa-filter"></i> Filtrar
                </button>
                <button onclick="abrirConfiguracoesQuadro()" class="btn btn-outline-secondary">
                    <i class="fas fa-cog"></i>
                </button>
            </div>
        </header>

        <div class="kanban-board" id="kanbanBoard">
            <?php foreach ($quadro['colunas'] as $coluna): ?>
                <div class="kanban-column" data-id="<?php echo $coluna['id']; ?>">
                    <div class="column-header" style="background-color: <?php echo $coluna['cor']; ?>">
                        <h3><?php echo $coluna['nome']; ?></h3>
                        <span class="card-count"><?php echo count($coluna['cartoes']); ?></span>
                    </div>
                    
                    <div class="cards-container">
                        <?php foreach ($coluna['cartoes'] as $cartao): ?>
                            <div class="kanban-card" 
                                 data-id="<?php echo $cartao['id']; ?>"
                                 onclick="abrirDetalhesCartao(<?php echo $cartao['id']; ?>)">
                                
                                <div class="card-header">
                                    <span class="prioridade prioridade-<?php echo $cartao['prioridade']; ?>"></span>
                                    <h4><?php echo $cartao['titulo']; ?></h4>
                                </div>
                                
                                <div class="card-content">
                                    <p><?php echo substr($cartao['descricao'], 0, 100); ?>...</p>
                                    
                                    <?php if (!empty($cartao['checklist'])): ?>
                                        <div class="checklist-progress">
                                            <div class="progress-bar" style="width: <?php 
                                                echo $cartao['checklist']['progresso']; 
                                            ?>%"></div>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($cartao['etiquetas'])): ?>
                                        <div class="tags">
                                            <?php foreach ($cartao['etiquetas'] as $etiqueta): ?>
                                                <span class="tag" style="background-color: <?php 
                                                    echo $etiqueta['cor']; 
                                                ?>"><?php echo $etiqueta['nome']; ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="card-footer">
                                    <?php if ($cartao['prazo']): ?>
                                        <span class="prazo <?php 
                                            echo strtotime($cartao['prazo']) < time() ? 'atrasado' : ''; 
                                        ?>">
                                            <i class="far fa-clock"></i> 
                                            <?php echo date('d/m', strtotime($cartao['prazo'])); ?>
                                        </span>
                                    <?php endif; ?>
                                    
                                    <?php if ($cartao['responsavel_nome']): ?>
                                        <span class="responsavel">
                                            <img src="<?php echo $cartao['responsavel_avatar']; ?>" 
                                                 alt="<?php echo $cartao['responsavel_nome']; ?>"
                                                 class="avatar-mini">
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

    <!-- Modal de Detalhes do Cartão -->
    <div class="modal fade" id="cardDetailsModal"></div>

    <script src="../../../assets/js/kanban.js"></script>
</body>
</html>
