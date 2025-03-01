<div class="producao-painel">
    <div class="painel-header">
        <div class="status-tabs">
            <button class="tab-btn active" data-status="todos">Todos (<?= $totais['todos'] ?>)</button>
            <button class="tab-btn" data-status="aguardando">Aguardando (<?= $totais['aguardando'] ?>)</button>
            <button class="tab-btn" data-status="producao">Em Produção (<?= $totais['producao'] ?>)</button>
            <button class="tab-btn" data-status="parado">Parados (<?= $totais['parado'] ?>)</button>
            <button class="tab-btn" data-status="concluido">Concluídos (<?= $totais['concluido'] ?>)</button>
        </div>
        <div class="painel-actions">
            <select class="form-control" id="filtroOperador">
                <option value="">Todos Operadores</option>
                <?php foreach ($operadores as $op): ?>
                    <option value="<?= $op['id'] ?>"><?= htmlspecialchars($op['nome']) ?></option>
                <?php endforeach; ?>
            </select>
            <button class="btn btn-outline" onclick="atualizarPainel()">
                <i class="icon-refresh"></i> Atualizar
            </button>
        </div>
    </div>

    <div class="pedidos-grid">
        <?php foreach ($pedidos as $pedido): ?>
        <div class="pedido-card status-<?= $pedido['status'] ?>" data-operador="<?= $pedido['operador_id'] ?>">
            <div class="pedido-header">
                <div class="pedido-info">
                    <span class="pedido-numero">#<?= $pedido['numero'] ?></span>
                    <span class="pedido-cliente"><?= htmlspecialchars($pedido['cliente']) ?></span>
                </div>
                <div class="pedido-prazo <?= strtotime($pedido['prazo']) < time() ? 'atrasado' : '' ?>">
                    <i class="icon-clock"></i>
                    <?= date('d/m/Y', strtotime($pedido['prazo'])) ?>
                </div>
            </div>

            <div class="pedido-timeline">
                <?php foreach ($pedido['etapas'] as $etapa): ?>
                <div class="etapa-item <?= $etapa['status'] ?>">
                    <div class="etapa-status">
                        <div class="status-dot"></div>
                        <div class="status-line"></div>
                    </div>
                    <div class="etapa-info">
                        <span class="etapa-nome"><?= htmlspecialchars($etapa['nome']) ?></span>
                        <?php if ($etapa['inicio']): ?>
                            <span class="etapa-tempo">
                                <?= floor((time() - strtotime($etapa['inicio'])) / 60) ?> min
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="pedido-footer">
                <?php if ($pedido['operador']): ?>
                <div class="operador-info">
                    <img src="<?= $pedido['operador_avatar'] ?>" alt="Avatar" class="avatar-mini">
                    <span><?= htmlspecialchars($pedido['operador_nome']) ?></span>
                </div>
                <?php endif; ?>
                <div class="pedido-actions">
                    <button class="btn btn-sm" onclick="iniciarProducao(<?= $pedido['id'] ?>)">
                        Iniciar
                    </button>
                    <button class="btn btn-sm btn-outline" onclick="verDetalhes(<?= $pedido['id'] ?>)">
                        Detalhes
                    </button>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div id="detalhesModal" class="modal">
        <!-- Conteúdo carregado via AJAX -->
    </div>
</div>
