<?php
require_once '../../../config/config.php';
require_once '../../../models/KanbanManager.php';

$cartao_id = $_GET['id'] ?? 0;
$kanban = new KanbanManager();
$cartao = $kanban->getCartaoDetalhes($cartao_id);

if (!$cartao) {
    echo "Cartão não encontrado";
    exit;
}
?>

<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">
                <span class="badge badge-<?php echo $cartao['prioridade']; ?>">
                    <?php echo ucfirst($cartao['prioridade']); ?>
                </span>
                <?php echo $cartao['titulo']; ?>
            </h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <div class="modal-body">
            <div class="row">
                <div class="col-md-8">
                    <!-- Descrição -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <h6>Descrição</h6>
                            <p><?php echo nl2br($cartao['descricao']); ?></p>
                        </div>
                    </div>

                    <!-- Checklist -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <h6 class="d-flex justify-content-between">
                                Checklist
                                <span class="text-muted">
                                    <?php echo $cartao['checklist']['concluidos']; ?>/<?php echo count($cartao['checklist']['itens']); ?>
                                </span>
                            </h6>
                            
                            <div class="progress mb-3">
                                <div class="progress-bar" style="width: <?php echo $cartao['checklist']['progresso']; ?>%"></div>
                            </div>
                            
                            <?php foreach ($cartao['checklist']['itens'] as $item): ?>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" 
                                           class="custom-control-input" 
                                           id="check-<?php echo $item['id']; ?>"
                                           <?php echo $item['concluido'] ? 'checked' : ''; ?>
                                           onchange="marcarItem(this, <?php echo $item['id']; ?>)">
                                    <label class="custom-control-label" for="check-<?php echo $item['id']; ?>">
                                        <?php echo $item['item']; ?>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Comentários -->
                    <div class="card">
                        <div class="card-body">
                            <h6>Comentários</h6>
                            <div class="comentarios-lista">
                                <?php foreach ($cartao['comentarios'] as $comentario): ?>
                                    <div class="comentario mb-2">
                                        <div class="d-flex">
                                            <img src="<?php echo $comentario['usuario_avatar']; ?>" 
                                                 class="avatar-mini mr-2">
                                            <div>
                                                <strong><?php echo $comentario['usuario_nome']; ?></strong>
                                                <small class="text-muted ml-2">
                                                    <?php echo date('d/m/Y H:i', strtotime($comentario['created_at'])); ?>
                                                </small>
                                                <p class="mb-0"><?php echo $comentario['comentario']; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <form onsubmit="adicionarComentario(event, <?php echo $cartao_id; ?>)">
                                <div class="input-group mt-3">
                                    <input type="text" class="form-control" placeholder="Adicionar comentário...">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-primary">Enviar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <!-- Informações do Pedido -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <h6>Informações do Pedido</h6>
                            <p><strong>Cliente:</strong> <?php echo $cartao['cliente_nome']; ?></p>
                            <p><strong>Valor:</strong> R$ <?php echo number_format($cartao['valor_total'], 2, ',', '.'); ?></p>
                            <p><strong>Status:</strong> <?php echo ucfirst($cartao['pedido_status']); ?></p>
                        </div>
                    </div>

                    <!-- Responsável e Prazo -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <h6>Responsável</h6>
                            <select class="form-control mb-3" onchange="atualizarResponsavel(this.value, <?php echo $cartao_id; ?>)">
                                <option value="">Selecionar responsável</option>
                                <?php foreach ($kanban->getUsuarios() as $usuario): ?>
                                    <option value="<?php echo $usuario['id']; ?>"
                                            <?php echo $usuario['id'] == $cartao['responsavel_id'] ? 'selected' : ''; ?>>
                                        <?php echo $usuario['nome']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <h6>Prazo</h6>
                            <input type="date" 
                                   class="form-control" 
                                   value="<?php echo $cartao['prazo']; ?>"
                                   onchange="atualizarPrazo(this.value, <?php echo $cartao_id; ?>)">
                        </div>
                    </div>

                    <!-- Etiquetas -->
                    <div class="card">
                        <div class="card-body">
                            <h6>Etiquetas</h6>
                            <div class="etiquetas-lista">
                                <?php foreach ($cartao['etiquetas'] as $etiqueta): ?>
                                    <span class="badge" 
                                          style="background-color: <?php echo $etiqueta['cor']; ?>">
                                        <?php echo $etiqueta['nome']; ?>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
