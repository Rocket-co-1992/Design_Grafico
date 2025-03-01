<div class="pedidos-container">
    <div class="pedidos-header">
        <div class="header-filtros">
            <div class="periodo-selector">
                <button class="btn btn-outline" data-periodo="hoje">Hoje</button>
                <button class="btn btn-outline active" data-periodo="semana">Esta Semana</button>
                <button class="btn btn-outline" data-periodo="mes">Este Mês</button>
                <div class="custom-period">
                    <input type="date" id="dataInicio" class="form-control">
                    <span>até</span>
                    <input type="date" id="dataFim" class="form-control">
                </div>
            </div>
            <div class="status-filtros">
                <select class="form-control" id="filtroStatus">
                    <option value="">Todos os Status</option>
                    <option value="aguardando">Aguardando</option>
                    <option value="aprovado">Aprovado</option>
                    <option value="producao">Em Produção</option>
                    <option value="concluido">Concluído</option>
                    <option value="cancelado">Cancelado</option>
                </select>
            </div>
        </div>
        <div class="header-acoes">
            <button class="btn btn-outline" onclick="exportarRelatorio()">
                <i class="icon-download"></i> Exportar
            </button>
            <button class="btn btn-primary" onclick="novoPedido()">
                <i class="icon-plus"></i> Novo Pedido
            </button>
        </div>
    </div>

    <div class="pedidos-grid">
        <?php foreach ($pedidos as $pedido): ?>
        <div class="pedido-card status-<?= $pedido['status'] ?>">
            <div class="pedido-header">
                <div class="pedido-info">
                    <span class="pedido-numero">#<?= $pedido['numero'] ?></span>
                    <span class="pedido-data"><?= date('d/m/Y H:i', strtotime($pedido['data'])) ?></span>
                </div>
                <div class="pedido-cliente">
                    <img src="<?= $pedido['cliente_avatar'] ?? '/assets/img/avatar-default.png' ?>" 
                         alt="Avatar" class="avatar-mini">
                    <span class="cliente-nome"><?= htmlspecialchars($pedido['cliente_nome']) ?></span>
                </div>
            </div>

            <div class="pedido-items">
                <?php foreach ($pedido['items'] as $item): ?>
                <div class="item-row">
                    <span class="item-nome"><?= htmlspecialchars($item['produto_nome']) ?></span>
                    <span class="item-qtd">x<?= $item['quantidade'] ?></span>
                    <span class="item-valor">R$ <?= number_format($item['valor_total'], 2, ',', '.') ?></span>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="pedido-footer">
                <div class="pedido-totais">
                    <div class="total-item">
                        <span>Subtotal:</span>
                        <span>R$ <?= number_format($pedido['subtotal'], 2, ',', '.') ?></span>
                    </div>
                    <?php if ($pedido['desconto']): ?>
                    <div class="total-item desconto">
                        <span>Desconto:</span>
                        <span>-R$ <?= number_format($pedido['desconto'], 2, ',', '.') ?></span>
                    </div>
                    <?php endif; ?>
                    <div class="total-final">
                        <span>Total:</span>
                        <span>R$ <?= number_format($pedido['total'], 2, ',', '.') ?></span>
                    </div>
                </div>
                <div class="pedido-acoes">
                    <button class="btn btn-sm" onclick="verDetalhes(<?= $pedido['id'] ?>)">
                        Ver Detalhes
                    </button>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline dropdown-trigger">
                            <i class="icon-more"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a href="#" onclick="editarPedido(<?= $pedido['id'] ?>)">Editar</a>
                            <a href="#" onclick="duplicarPedido(<?= $pedido['id'] ?>)">Duplicar</a>
                            <?php if ($pedido['status'] == 'aguardando'): ?>
                                <a href="#" onclick="cancelarPedido(<?= $pedido['id'] ?>)" class="text-danger">
                                    Cancelar
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
