<div class="kanban-header">
    <h2>Kanban de Produção</h2>
    <div class="kanban-actions">
        <button class="btn btn-primary" id="addCartao">+ Novo Cartão</button>
        <select class="form-control" id="filtroStatus">
            <option value="">Todos os Status</option>
            <option value="pendente">Pendente</option>
            <option value="producao">Em Produção</option>
            <option value="qualidade">Controle de Qualidade</option>
            <option value="concluido">Concluído</option>
        </select>
    </div>
</div>

<div class="kanban-board">
    <?php foreach ($colunas as $coluna): ?>
    <div class="kanban-coluna" data-id="<?= $coluna['id'] ?>">
        <div class="coluna-header">
            <h3><?= htmlspecialchars($coluna['nome']) ?></h3>
            <span class="contador"><?= count($coluna['cartoes']) ?></span>
        </div>
        
        <div class="cartoes-container">
            <?php foreach ($coluna['cartoes'] as $cartao): ?>
            <div class="cartao" data-id="<?= $cartao['id'] ?>">
                <div class="cartao-header">
                    <span class="pedido-num">#<?= $cartao['pedido_id'] ?></span>
                    <span class="prioridade prioridade-<?= $cartao['prioridade'] ?>"></span>
                </div>
                <h4><?= htmlspecialchars($cartao['titulo']) ?></h4>
                <div class="cartao-meta">
                    <span class="cliente"><?= htmlspecialchars($cartao['cliente']) ?></span>
                    <span class="prazo"><?= date('d/m', strtotime($cartao['prazo'])) ?></span>
                </div>
                <div class="cartao-footer">
                    <div class="etiquetas">
                        <?php foreach ($cartao['etiquetas'] as $etiqueta): ?>
                        <span class="etiqueta" style="background-color: <?= $etiqueta['cor'] ?>">
                            <?= htmlspecialchars($etiqueta['nome']) ?>
                        </span>
                        <?php endforeach; ?>
                    </div>
                    <img src="<?= $cartao['responsavel_avatar'] ?>" class="avatar-mini" title="<?= htmlspecialchars($cartao['responsavel_nome']) ?>">
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>
