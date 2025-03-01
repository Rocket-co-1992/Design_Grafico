<div class="catalogo-container">
    <div class="filtros-sidebar">
        <div class="card filtros-card">
            <h3>Filtros</h3>
            <form class="filtros-form">
                <div class="form-group">
                    <label>Categorias</label>
                    <div class="categorias-lista">
                        <?php foreach ($categorias as $cat): ?>
                        <label class="checkbox-container">
                            <input type="checkbox" name="categorias[]" value="<?= $cat['id'] ?>">
                            <span class="checkmark"></span>
                            <?= htmlspecialchars($cat['nome']) ?>
                            <span class="count">(<?= $cat['total'] ?>)</span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label>Faixa de Preço</label>
                    <div class="price-range">
                        <input type="range" id="precoRange" min="0" max="1000" step="10"
                               oninput="atualizarPreco(this.value)">
                        <div class="price-inputs">
                            <input type="number" id="precoMin" placeholder="Min" class="form-control">
                            <span>até</span>
                            <input type="number" id="precoMax" placeholder="Max" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Ordenar por</label>
                    <select name="ordenacao" class="form-control">
                        <option value="relevancia">Relevância</option>
                        <option value="preco_asc">Menor Preço</option>
                        <option value="preco_desc">Maior Preço</option>
                        <option value="mais_vendidos">Mais Vendidos</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Aplicar Filtros</button>
            </form>
        </div>
    </div>

    <div class="produtos-grid">
        <?php foreach ($produtos as $produto): ?>
        <div class="produto-card" data-categoria="<?= $produto['categoria_id'] ?>">
            <div class="produto-preview">
                <img src="<?= $produto['imagem'] ?>" alt="<?= htmlspecialchars($produto['nome']) ?>">
                <?php if ($produto['promocao']): ?>
                    <span class="badge badge-promocao">-<?= $produto['desconto'] ?>%</span>
                <?php endif; ?>
                <div class="produto-acoes">
                    <button class="btn-quickview" onclick="visualizarProduto(<?= $produto['id'] ?>)">
                        <i class="icon-eye"></i>
                    </button>
                    <button class="btn-favorite" onclick="toggleFavorito(<?= $produto['id'] ?>)">
                        <i class="icon-heart<?= $produto['favorito'] ? ' active' : '' ?>"></i>
                    </button>
                </div>
            </div>

            <div class="produto-info">
                <h3><?= htmlspecialchars($produto['nome']) ?></h3>
                <div class="produto-meta">
                    <span class="categoria"><?= htmlspecialchars($produto['categoria_nome']) ?></span>
                    <div class="rating">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i class="icon-star<?= $i <= $produto['rating'] ? ' active' : '' ?>"></i>
                        <?php endfor; ?>
                        <span class="reviews">(<?= $produto['total_reviews'] ?>)</span>
                    </div>
                </div>
                <div class="produto-preco">
                    <?php if ($produto['promocao']): ?>
                        <span class="preco-original">R$ <?= number_format($produto['preco_original'], 2, ',', '.') ?></span>
                    <?php endif; ?>
                    <span class="preco-atual">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></span>
                </div>
            </div>

            <div class="produto-footer">
                <button class="btn btn-primary" onclick="personalizarProduto(<?= $produto['id'] ?>)">
                    Personalizar
                </button>
                <button class="btn btn-outline" onclick="adicionarCarrinho(<?= $produto['id'] ?>)">
                    <i class="icon-cart"></i>
                </button>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
