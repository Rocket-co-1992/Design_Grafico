<div class="fornecedores-container">
    <div class="page-header">
        <h2>Fornecedores</h2>
        <div class="header-actions">
            <button class="btn btn-primary" onclick="novoFornecedor()">Novo Fornecedor</button>
            <button class="btn btn-outline" onclick="exportarLista()">Exportar</button>
        </div>
    </div>

    <div class="filtros-card card">
        <form class="filtros-form">
            <div class="filtros-grid">
                <div class="form-group">
                    <label>Buscar</label>
                    <input type="text" class="form-control" placeholder="Nome, CNPJ ou produto">
                </div>
                <div class="form-group">
                    <label>Categoria</label>
                    <select class="form-control">
                        <option value="">Todas</option>
                        <option value="materiais">Materiais</option>
                        <option value="equipamentos">Equipamentos</option>
                        <option value="servicos">Serviços</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select class="form-control">
                        <option value="">Todos</option>
                        <option value="ativo">Ativo</option>
                        <option value="inativo">Inativo</option>
                    </select>
                </div>
            </div>
        </form>
    </div>

    <div class="fornecedores-grid">
        <?php foreach ($fornecedores as $forn): ?>
        <div class="fornecedor-card card">
            <div class="card-header">
                <div class="fornecedor-info">
                    <h3><?= htmlspecialchars($forn['nome']) ?></h3>
                    <span class="cnpj"><?= htmlspecialchars($forn['cnpj']) ?></span>
                </div>
                <span class="status-badge status-<?= $forn['status'] ?>">
                    <?= ucfirst($forn['status']) ?>
                </span>
            </div>

            <div class="card-content">
                <div class="info-row">
                    <span class="label">Categoria:</span>
                    <span class="valor"><?= htmlspecialchars($forn['categoria']) ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Produtos:</span>
                    <span class="valor"><?= htmlspecialchars($forn['produtos']) ?></span>
                </div>
                <div class="info-row">
                    <span class="label">Último Pedido:</span>
                    <span class="valor"><?= $forn['ultimo_pedido'] ? date('d/m/Y', strtotime($forn['ultimo_pedido'])) : '-' ?></span>
                </div>

                <div class="historico-pagamentos">
                    <div class="titulo-linha">
                        <h4>Histórico de Pagamentos</h4>
                        <span class="media-prazo <?= $forn['media_prazo'] <= 0 ? 'positivo' : 'negativo' ?>">
                            <?= $forn['media_prazo'] ?> dias
                        </span>
                    </div>
                    <div class="mini-grafico">
                        <!-- Implementar gráfico de barras miniatura -->
                    </div>
                </div>
            </div>

            <div class="card-actions">
                <button class="btn btn-sm" onclick="verFornecedor(<?= $forn['id'] ?>)">
                    Detalhes
                </button>
                <button class="btn btn-sm btn-outline" onclick="novoPedido(<?= $forn['id'] ?>)">
                    Novo Pedido
                </button>
                <button class="btn btn-icon" onclick="toggleFavorito(<?= $forn['id'] ?>)">
                    <i class="icon-star<?= $forn['favorito'] ? ' active' : '' ?>"></i>
                </button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
