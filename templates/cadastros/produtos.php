<div class="produtos-cadastro">
    <div class="page-header">
        <h2>Cadastro de Produtos</h2>
        <div class="header-actions">
            <button class="btn btn-primary" onclick="novoProduto()">Novo Produto</button>
            <button class="btn btn-outline" onclick="importarProdutos()">Importar</button>
        </div>
    </div>

    <div class="produtos-grid">
        <div class="filtros-sidebar card">
            <h3>Filtros</h3>
            <form class="filtros-form">
                <div class="form-group">
                    <label>Categoria</label>
                    <select class="form-control" name="categoria">
                        <option value="">Todas</option>
                        <?php foreach ($categorias as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <div class="toggle-group">
                        <label class="toggle">
                            <input type="checkbox" name="status[]" value="ativo" checked>
                            <span>Ativos</span>
                        </label>
                        <label class="toggle">
                            <input type="checkbox" name="status[]" value="inativo">
                            <span>Inativos</span>
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label>Preço</label>
                    <div class="range-input">
                        <input type="number" name="preco_min" placeholder="Mín" class="form-control">
                        <span>até</span>
                        <input type="number" name="preco_max" placeholder="Máx" class="form-control">
                    </div>
                </div>

                <button type="submit" class="btn btn-secondary">Aplicar Filtros</button>
            </form>
        </div>

        <div class="produtos-lista">
            <?php foreach ($produtos as $produto): ?>
            <div class="produto-card card">
                <div class="produto-preview">
                    <img src="<?= $produto['imagem'] ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
                    <span class="produto-status status-<?= $produto['status'] ?>">
                        <?= ucfirst($produto['status']) ?>
                    </span>
                </div>

                <div class="produto-info">
                    <h3><?= htmlspecialchars($produto['nome']) ?></h3>
                    <div class="produto-meta">
                        <span class="categoria"><?= htmlspecialchars($produto['categoria']) ?></span>
                        <span class="codigo">Cód: <?= $produto['codigo'] ?></span>
                    </div>
                    <div class="produto-preco">
                        <span class="valor">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></span>
                        <?php if ($produto['promocao']): ?>
                        <span class="promocao">Em promoção</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="produto-acoes">
                    <button class="btn btn-sm btn-icon" onclick="editarProduto(<?= $produto['id'] ?>)">
                        <i class="icon-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-icon" onclick="duplicarProduto(<?= $produto['id'] ?>)">
                        <i class="icon-copy"></i>
                    </button>
                    <button class="btn btn-sm btn-icon" onclick="excluirProduto(<?= $produto['id'] ?>)">
                        <i class="icon-trash"></i>
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="paginacao-container">
        <?php if ($total_paginas > 1): ?>
            <?php for ($i = 1; $i <= $total_paginas; $i++): ?>
                <a href="?pagina=<?= $i ?>" class="btn btn-outline <?= $pagina_atual == $i ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        <?php endif; ?>
    </div>
</div>
